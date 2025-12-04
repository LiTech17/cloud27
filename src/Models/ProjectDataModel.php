<?php
// src/Models/ProjectDataModel.php

namespace Models;

/**
 * ProjectDataModel handles the "Business Profile" or "Project Data" 
 * that is iteratively built by the client and admin.
 */
class ProjectDataModel extends BaseModel
{
    protected string $table = 'project_data';

    // Allowed fields for mass assignment
    protected array $allowedFields = [
        'project_id',
        'client_id',
        'business_name',
        'industry',
        'about_business',
        'target_audience',
        'contact_email',
        'contact_phone',
        'brand_colors',
        'business_profile', // File path
        'brand_guidelines', // File path
        'is_draft',
        'submitted_at',
        'last_saved_at'
    ];

    /**
     * Get project data by Project ID
     */
    public function getByProjectId(int $projectId): ?array
    {
        return $this->findBy('project_id', $projectId);
    }

    /**
     * Get project data with project details (joined)
     */
    public function getWithProject(int $projectId): ?array
    {
        $sql = "SELECT pd.*, p.title as project_title, p.status as project_status 
                FROM {$this->table} pd
                JOIN projects p ON pd.project_id = p.id
                WHERE pd.project_id = :project_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['project_id' => $projectId]);
        
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Get all project data for a specific client
     */
    public function getForClient(int $clientId): array
    {
        $sql = "SELECT pd.*, p.title as project_title 
                FROM {$this->table} pd
                JOIN projects p ON pd.project_id = p.id
                WHERE pd.client_id = :client_id
                ORDER BY pd.last_saved_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['client_id' => $clientId]);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get all project data paginated (for Admin)
     */
    public function getAllPaginated(int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT pd.*, p.title as project_title, u.username as client_name
                FROM {$this->table} pd
                JOIN projects p ON pd.project_id = p.id
                LEFT JOIN users u ON pd.client_id = u.id
                ORDER BY pd.last_saved_at DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Count all records
     */
    public function countAll(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM {$this->table}");
        return (int)$stmt->fetchColumn();
    }

    /**
     * Search project data
     */
    public function search(string $query, int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        $searchTerm = "%{$query}%";
        
        $sql = "SELECT pd.*, p.title as project_title, u.username as client_name
                FROM {$this->table} pd
                JOIN projects p ON pd.project_id = p.id
                LEFT JOIN users u ON pd.client_id = u.id
                WHERE pd.business_name LIKE :q1 
                   OR p.title LIKE :q2
                   OR u.username LIKE :q3
                ORDER BY pd.last_saved_at DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':q1', $searchTerm);
        $stmt->bindValue(':q2', $searchTerm);
        $stmt->bindValue(':q3', $searchTerm);
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function countSearch(string $query): int
    {
        $searchTerm = "%{$query}%";
        $sql = "SELECT COUNT(*)
                FROM {$this->table} pd
                JOIN projects p ON pd.project_id = p.id
                LEFT JOIN users u ON pd.client_id = u.id
                WHERE pd.business_name LIKE :q1 
                   OR p.title LIKE :q2
                   OR u.username LIKE :q3";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['q1' => $searchTerm, 'q2' => $searchTerm, 'q3' => $searchTerm]);
        
        return (int)$stmt->fetchColumn();
    }

    /**
     * Save (Create or Update)
     */
    public function save(int $projectId, array $data): bool
    {
        // Check if exists
        $exists = $this->getByProjectId($projectId);
        
        if ($exists) {
            return $this->updateByProjectId($projectId, $data);
        } else {
            // Insert
            $data['project_id'] = $projectId;
            return $this->insert($data);
        }
    }

    /**
     * Update by Project ID
     */
    public function updateByProjectId(int $projectId, array $data): bool
    {
        // Filter data to allowed fields
        $data = array_intersect_key($data, array_flip($this->allowedFields));
        
        if (empty($data)) {
            return false;
        }

        $setParts = [];
        $params = ['project_id' => $projectId];
        
        foreach ($data as $key => $value) {
            $setParts[] = "{$key} = :{$key}";
            $params[$key] = $value;
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $setParts) . " WHERE project_id = :project_id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Delete by Project ID
     */
    public function deleteByProjectId(int $projectId): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE project_id = :project_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['project_id' => $projectId]);
    }

    /**
     * Sanitize Input
     */
    public function sanitizeInput(array $input): array
    {
        $sanitized = [];
        foreach ($this->allowedFields as $field) {
            if (isset($input[$field])) {
                $sanitized[$field] = trim(strip_tags($input[$field]));
            }
        }
        return $sanitized;
    }

    /**
     * Validate Data
     */
    public function validate(array $data): array
    {
        $errors = [];
        $rules = self::getValidationRules();

        foreach ($rules as $field => $rule) {
            if ($rule['required'] && empty($data[$field])) {
                $errors[$field] = $rule['label'] . ' is required.';
            }
            // Add more validation as needed (email, etc.)
        }

        return $errors;
    }

    /**
     * Get Validation Rules (Static for easy access)
     */
    public static function getValidationRules(): array
    {
        return [
            'business_name' => ['required' => true, 'label' => 'Business Name'],
            'contact_email' => ['required' => true, 'label' => 'Contact Email'],
            // Add others as needed
        ];
    }

    /**
     * Get Field Options (for dropdowns, etc.)
     */
    public static function getFieldOptions(): array
    {
        return [
            'industries' => [
                'Technology', 'Health', 'Finance', 'Education', 'Retail', 'Construction', 'Other'
            ]
        ];
    }
}
