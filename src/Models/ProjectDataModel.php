<?php

namespace Models;

class ProjectDataModel extends BaseModel
{
    protected string $table = 'project_data';

    // Constants for validation
    public const BUDGET_RANGES = ['low', 'medium', 'high', 'custom'];
    public const TIMELINES = ['urgent', 'standard', 'flexible'];

    /**
     * Get project data by project ID
     */
    public function getByProjectId(int $projectId): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE project_id = :project_id LIMIT 1";
        $result = $this->query($sql, [':project_id' => $projectId])[0] ?? null;

        return $result ? $this->decodeJsonFields($result) : null;
    }

    /**
     * Get project data with project info
     */
    public function getWithProject(int $projectId): ?array
    {
        $sql = "SELECT pd.*, p.title as project_title, p.client_name, p.status
                FROM {$this->table} pd
                JOIN projects p ON pd.project_id = p.id
                WHERE pd.project_id = :project_id
                LIMIT 1";

        $result = $this->query($sql, [':project_id' => $projectId])[0] ?? null;

        return $result ? $this->decodeJsonFields($result) : null;
    }

    /**
     * Get all project data for a specific client
     */
    public function getForClient(int $clientId): array
    {
        $sql = "SELECT pd.*, p.title as project_title, p.status
                FROM {$this->table} pd
                JOIN projects p ON pd.project_id = p.id
                WHERE p.client_id = :client_id
                ORDER BY pd.created_at DESC";

        $results = $this->query($sql, [':client_id' => $clientId]);
        return array_map([$this, 'decodeJsonFields'], $results);
    }

    /**
     * Get draft projects for a client
     */
    public function getDraftsForClient(int $clientId): array
    {
        $sql = "SELECT pd.*, p.title as project_title, p.status
                FROM {$this->table} pd
                JOIN projects p ON pd.project_id = p.id
                WHERE p.client_id = :client_id AND pd.is_draft = 1
                ORDER BY pd.last_saved_at DESC";

        $results = $this->query($sql, [':client_id' => $clientId]);
        return array_map([$this, 'decodeJsonFields'], $results);
    }

    /**
     * Get completed projects for a client
     */
    public function getCompletedForClient(int $clientId): array
    {
        $sql = "SELECT pd.*, p.title as project_title, p.status
                FROM {$this->table} pd
                JOIN projects p ON pd.project_id = p.id
                WHERE p.client_id = :client_id AND pd.is_draft = 0
                ORDER BY pd.submitted_at DESC";

        $results = $this->query($sql, [':client_id' => $clientId]);
        return array_map([$this, 'decodeJsonFields'], $results);
    }

    /**
     * Create project data
     */
    public function create(int $projectId, array $data): bool
    {
        if ($this->exists($projectId)) {
            throw new \Exception("Project data already exists for project ID: {$projectId}");
        }

        $data['project_id'] = $projectId;
        $data = $this->prepareData($data);

        return $this->insert($data);
    }

    /**
     * Update project data
     */
    public function updateByProjectId(int $projectId, array $data): bool
    {
        if (!$this->exists($projectId)) {
            throw new \Exception("Project data not found for project ID: {$projectId}");
        }

        $data = $this->prepareData($data);
        $setClauses = [];
        $params = [':project_id' => $projectId];

        foreach ($data as $col => $val) {
            $setClauses[] = "{$col} = :{$col}";
            $params[":{$col}"] = $val;
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $setClauses) . " WHERE project_id = :project_id";

        return $this->execute($sql, $params);
    }

    /**
     * Upsert: create or update with proper error handling
     */
    public function save(int $projectId, array $data): bool
    {
        try {
            return $this->exists($projectId)
                ? $this->updateByProjectId($projectId, $data)
                : $this->create($projectId, $data);
        } catch (\PDOException $e) {
            error_log("Database error in ProjectDataModel::save for project {$projectId}: " . $e->getMessage());
            throw new \Exception('Failed to save project data. Please try again.');
        }
    }

    /**
     * Delete data
     */
    public function deleteByProjectId(int $projectId): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE project_id = :project_id";
        return $this->execute($sql, [':project_id' => $projectId]);
    }

    /**
     * Check if exists
     */
    public function exists(int $projectId): bool
    {
        return $this->getByProjectId($projectId) !== null;
    }

    /**
     * Check if project data is a draft
     */
    public function isDraft(int $projectId): bool
    {
        $data = $this->getByProjectId($projectId);
        return $data && ($data['is_draft'] ?? 0) === 1;
    }

    /**
     * Mark project data as completed (not draft)
     */
    public function markAsCompleted(int $projectId): bool
    {
        return $this->updateByProjectId($projectId, [
            'is_draft' => 0,
            'submitted_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Validate input with improved array field handling and draft support
     */
    public function validate(array $data, bool $isDraft = false): array
    {
        $errors = [];

        // For drafts, only validate basic required fields
        if ($isDraft) {
            // Minimal validation for drafts
            if (!empty($data['contact_email']) && !filter_var($data['contact_email'], FILTER_VALIDATE_EMAIL)) {
                $errors['contact_email'] = 'Please enter a valid email address.';
            }
            
            // File upload validation (if files are provided in draft)
            // Note: File validation happens in controller, this is just for format checks
            
            return $errors;
        }

        // Full validation for final submission
        $requiredFields = [
            'company_name' => 'Company name is required.',
            'contact_name' => 'Contact name is required.',
            'contact_email' => 'Contact email is required.'
        ];

        foreach ($requiredFields as $field => $message) {
            if (empty(trim($data[$field] ?? ''))) {
                $errors[$field] = $message;
            }
        }

        // Email validation
        if (!empty($data['contact_email']) && !filter_var($data['contact_email'], FILTER_VALIDATE_EMAIL)) {
            $errors['contact_email'] = 'Please enter a valid email address.';
        }

        // Array fields - improved validation
        $arrayFields = [
            'goals_objectives' => 'Goals and objectives are required.',
            'target_audience' => 'Target audience information is required.',
            'pages_needed' => 'Please specify the pages needed.',
            'special_features' => 'Special features requirements are needed.',
            'design_preferences' => 'Design preferences are required.'
        ];

        foreach ($arrayFields as $field => $message) {
            $value = $data[$field] ?? '';
            
            if (empty($value)) {
                $errors[$field] = $message;
            } elseif (is_array($value)) {
                // Check if array has any non-empty values
                $filtered = array_filter($value, function($item) {
                    return !empty(trim($item));
                });
                
                if (empty($filtered)) {
                    $errors[$field] = $message;
                }
            } elseif (is_string($value) && empty(trim($value))) {
                $errors[$field] = $message;
            }
        }

        // Text field validations
        $textFields = [
            'products_services' => 1000,
            'mission_statement' => 500,
            'unique_selling_point' => 500,
            'additional_notes' => 2000
        ];

        foreach ($textFields as $field => $maxLength) {
            if (!empty($data[$field]) && strlen($data[$field]) > $maxLength) {
                $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . " must be less than {$maxLength} characters.";
            }
        }

        // Budget & timeline validation
        if (empty($data['budget_range']) || !in_array($data['budget_range'], self::BUDGET_RANGES)) {
            $errors['budget_range'] = 'Please select a valid budget range.';
        }

        if (empty($data['timeline']) || !in_array($data['timeline'], self::TIMELINES)) {
            $errors['timeline'] = 'Please select a valid timeline.';
        }

        // CMS and support field validation
        $choiceFields = ['cms_needed', 'content_existing', 'ongoing_support'];
        foreach ($choiceFields as $field) {
            if (!empty($data[$field]) && !in_array($data[$field], ['yes', 'no', 'not_sure'])) {
                $errors[$field] = 'Please select a valid option.';
            }
        }

        return $errors;
    }

    /**
     * Calculate form completion progress
     */
    public function calculateProgress(array $data): int
    {
        $totalFields = [
            'company_name', 'contact_name', 'contact_email', 'products_services',
            'mission_statement', 'unique_selling_point', 'competitors',
            'goals_objectives', 'target_audience', 'pages_needed', 'special_features',
            'design_preferences', 'cms_needed', 'technical_requirements', 'seo_requirements',
            'content_existing', 'ongoing_support', 'additional_notes', 'budget_range',
            'timeline'
        ];
        
        $completed = 0;
        foreach ($totalFields as $field) {
            $value = $data[$field] ?? '';
            
            if (!empty($value)) {
                if (is_array($value)) {
                    // For array fields, check if any value is set
                    $filtered = array_filter($value, function($item) {
                        return !empty(trim($item));
                    });
                    if (!empty($filtered)) {
                        $completed++;
                    }
                } elseif (!empty(trim($value))) {
                    $completed++;
                }
            }
        }
        
        return min(100, round(($completed / count($totalFields)) * 100));
    }

    /**
     * Sanitize input data
     */
    public function sanitizeInput(array $data): array
    {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = array_map(function($item) {
                    return htmlspecialchars(trim($item), ENT_QUOTES, 'UTF-8');
                }, $value);
            } else {
                $sanitized[$key] = htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
            }
        }
        
        return $sanitized;
    }

    /**
     * Prepare for insertion/update with improved handling and draft support
     */
    private function prepareData(array $data): array
    {
        $prepared = [];

        $jsonFields = [
            'products_services',
            'mission_statement',
            'competitors',
            'unique_selling_point',
            'goals_objectives',
            'target_audience',
            'pages_needed',
            'special_features',
            'design_preferences',
            'technical_requirements',
            'seo_requirements'
        ];

        foreach ($data as $key => $value) {
            if (in_array($key, $jsonFields)) {
                // Handle both array and string values for JSON fields
                if (is_array($value)) {
                    $prepared[$key] = json_encode(array_values($value)); // Reindex array
                } elseif (is_string($value) && !empty(trim($value))) {
                    // If it's a string, create a single-item array
                    $prepared[$key] = json_encode([trim($value)]);
                } else {
                    $prepared[$key] = null;
                }
            } elseif ($value === '' || $value === []) {
                $prepared[$key] = null;
            } else {
                $prepared[$key] = $value;
            }
        }

        // Set timestamps if not provided
        if (!isset($prepared['created_at']) && !$this->exists($data['project_id'] ?? 0)) {
            $prepared['created_at'] = date('Y-m-d H:i:s');
        }
        
        if (!isset($prepared['updated_at'])) {
            $prepared['updated_at'] = date('Y-m-d H:i:s');
        }

        return $prepared;
    }

    /**
     * Decode JSON fields with improved error handling
     */
    private function decodeJsonFields(array $result): array
    {
        $jsonFields = [
            'products_services',
            'mission_statement',
            'competitors',
            'unique_selling_point',
            'goals_objectives',
            'target_audience',
            'pages_needed',
            'special_features',
            'design_preferences',
            'technical_requirements',
            'seo_requirements'
        ];

        foreach ($jsonFields as $field) {
            if (isset($result[$field]) && is_string($result[$field])) {
                if ($result[$field] === '') {
                    $result[$field] = [];
                } else {
                    $decoded = json_decode($result[$field], true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $result[$field] = $decoded;
                    } else {
                        // If JSON decoding fails, treat as single value array
                        $result[$field] = [$result[$field]];
                    }
                }
            } elseif (!isset($result[$field])) {
                $result[$field] = [];
            }
        }

        return $result;
    }

    /**
     * Get field options with additional metadata
     */
    public static function getFieldOptions(): array
    {
        return [
            'budget_ranges' => [
                'low' => 'R5,000 - R15,000',
                'medium' => 'R15,000 - R30,000',
                'high' => 'R30,000 - R50,000',
                'custom' => 'Custom Budget'
            ],
            'timelines' => [
                'urgent' => '2–3 Weeks',
                'standard' => '4–6 Weeks',
                'flexible' => '2+ Months'
            ],
            'cms_options' => [
                'yes' => 'Yes',
                'no' => 'No',
                'not_sure' => 'Not Sure'
            ],
            'support_options' => [
                'yes' => 'Yes',
                'no' => 'No',
                'not_sure' => 'Not Sure'
            ],
            'content_options' => [
                'yes' => 'Yes, I have content',
                'no' => 'No, I need help creating content',
                'some' => 'I have some content'
            ]
        ];
    }

    /**
     * Get validation rules for frontend
     */
    public static function getValidationRules(): array
    {
        return [
            'required' => ['company_name', 'contact_name', 'contact_email'],
            'array_required' => ['goals_objectives', 'target_audience', 'pages_needed', 'special_features', 'design_preferences'],
            'max_lengths' => [
                'company_name' => 150,
                'contact_name' => 100,
                'contact_email' => 150,
                'products_services' => 1000,
                'mission_statement' => 500,
                'unique_selling_point' => 500
            ]
        ];
    }

    // Pagination methods
    public function getAllPaginated(int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT pd.*, p.title as project_title, p.client_name, p.status
                FROM {$this->table} pd
                JOIN projects p ON pd.project_id = p.id
                ORDER BY pd.id DESC
                LIMIT :offset, :limit";

        $results = $this->query($sql, [
            ':offset' => $offset,
            ':limit' => $perPage
        ]);

        return array_map([$this, 'decodeJsonFields'], $results);
    }

    public function countAll(): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->query($sql)[0] ?? ['total' => 0];
        return (int)$result['total'];
    }

    public function search(string $query, int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT pd.*, p.title as project_title, p.client_name, p.status
                FROM {$this->table} pd
                JOIN projects p ON pd.project_id = p.id
                WHERE p.title LIKE :q OR p.client_name LIKE :q OR pd.company_name LIKE :q
                ORDER BY pd.id DESC
                LIMIT :offset, :limit";

        $results = $this->query($sql, [
            ':q' => "%$query%",
            ':offset' => $offset,
            ':limit' => $perPage
        ]);

        return array_map([$this, 'decodeJsonFields'], $results);
    }

    public function countSearch(string $query): int
    {
        $sql = "SELECT COUNT(*) as total
                FROM {$this->table} pd
                JOIN projects p ON pd.project_id = p.id
                WHERE p.title LIKE :q OR p.client_name LIKE :q OR pd.company_name LIKE :q";

        $result = $this->query($sql, [':q' => "%$query%"])[0] ?? ['total' => 0];
        return (int)$result['total'];
    }

    /**
     * Get recent project data
     */
    public function getRecent(int $limit = 5): array
    {
        $sql = "SELECT pd.*, p.title as project_title, p.client_name, p.status
                FROM {$this->table} pd
                JOIN projects p ON pd.project_id = p.id
                ORDER BY pd.created_at DESC
                LIMIT :limit";

        $results = $this->query($sql, [':limit' => $limit]);
        return array_map([$this, 'decodeJsonFields'], $results);
    }

    /**
     * Get statistics for dashboard
     */
    public function getStats(): array
    {
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN is_draft = 1 THEN 1 ELSE 0 END) as drafts,
                SUM(CASE WHEN is_draft = 0 THEN 1 ELSE 0 END) as completed
                FROM {$this->table}";

        $result = $this->query($sql)[0] ?? ['total' => 0, 'drafts' => 0, 'completed' => 0];
        
        return [
            'total' => (int)$result['total'],
            'drafts' => (int)$result['drafts'],
            'completed' => (int)$result['completed']
        ];
    }
}