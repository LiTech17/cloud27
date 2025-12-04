<?php
// src/Models/ProjectModel.php

namespace Models;

/**
 * ProjectModel handles all database operations for projects
 * Supports both client and admin project management
 */
class ProjectModel extends BaseModel
{
    /** @var string The database table for projects */
    protected string $table = 'projects';

    /** @var array Columns allowed for mass assignment */
    // NOTE: Changed to protected visibility for consistency/inheritance if needed.
    protected $allowedFields = [ 
        'client_id',
        'title',
        'description',
        'package_name',
        'status',
        'budget',
        'start_date',
        'due_date',
        // --- NEW FIELD ADDED ---
        'uploaded_logo_path' // For storing the path to the client's logo
    ];

    /** @var array Valid project statuses */
    private $validStatuses = [
        'New',
        'In Progress',
        'On Hold',
        'Awaiting Client',
        'Review/QA',
        'Completed',
        'Canceled'
    ];

    // ========================================================================
    // READ OPERATIONS
    // (methods remain unchanged)
    // ========================================================================

    /**
     * Get a single project by ID
     * * @param int $id Project ID
     * @return array|null Project data or null if not found
     */
    public function getProjectById(int $id): ?array
    {
        return $this->findOne($id);
    }

    /**
     * Get all projects for a specific client
     * * @param int $clientId Client ID
     * @return array Array of projects
     */
    public function getAllByClient(int $clientId): array
    {
        $sql = "SELECT * FROM {$this->table} 
                 WHERE client_id = :client_id 
                 ORDER BY updated_at DESC";
        
        return $this->query($sql, [':client_id' => $clientId]);
    }

    /**
     * Get all projects with optional filters (admin view)
     * * @param array $filters Optional filters (client_id, status, package_name)
     * @return array Array of projects
     */
    public function getAllProjects(array $filters = []): array
    {
        $sql = "SELECT p.*, u.username as client_name, u.email as client_email 
                 FROM {$this->table} p 
                 LEFT JOIN users u ON p.client_id = u.id 
                 WHERE 1=1";
        
        $params = [];
        
        // Apply filters
        if (!empty($filters['client_id'])) {
            $sql .= " AND p.client_id = :client_id";
            $params[':client_id'] = $filters['client_id'];
        }
        
        if (!empty($filters['status'])) {
            $sql .= " AND p.status = :status";
            $params[':status'] = $filters['status'];
        }
        
        if (!empty($filters['package_name'])) {
            $sql .= " AND p.package_name = :package_name";
            $params[':package_name'] = $filters['package_name'];
        }
        
        $sql .= " ORDER BY p.created_at DESC";
        
        return $this->query($sql, $params);
    }

    /**
     * Get project statistics
     * * @param int|null $clientId Optional client ID to filter stats
     * @return array Statistics array
     */
    public function getProjectStats(?int $clientId = null): array
    {
        $where = $clientId ? "WHERE client_id = :client_id" : "";
        $params = $clientId ? [':client_id' => $clientId] : [];
        
        $sql = "SELECT 
                        COUNT(*) as total,
                        SUM(CASE WHEN status = 'New' THEN 1 ELSE 0 END) as new,
                        SUM(CASE WHEN status = 'In Progress' THEN 1 ELSE 0 END) as in_progress,
                        SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) as completed,
                        SUM(budget) as total_budget
                    FROM {$this->table} 
                    {$where}";
        
        $result = $this->query($sql, $params);
        return $result[0] ?? [];
    }

    /**
     * Search projects by keyword
     * * @param string $keyword Search keyword
     * @param int|null $clientId Optional client ID filter
     * @return array Array of matching projects
     */
    public function searchProjects(string $keyword, ?int $clientId = null): array
    {
        $sql = "SELECT * FROM {$this->table} 
                 WHERE (title LIKE :keyword OR description LIKE :keyword)";
        
        $params = [':keyword' => "%{$keyword}%"];
        
        if ($clientId) {
            $sql .= " AND client_id = :client_id";
            $params[':client_id'] = $clientId;
        }
        
        $sql .= " ORDER BY updated_at DESC";
        
        return $this->query($sql, $params);
    }

    // ========================================================================
    // WRITE OPERATIONS
    // ========================================================================

    /**
     * Create a new project record and returns the ID of the newly inserted row.
     * * NOTE: This method is used by the GetStartedController to save the project 
     * before saving its related onboarding details.
     * * @param array $data Project data (must contain client_id, title, package_name)
     * @return int|false The new project ID on success, or false on failure.
     */
    public function createProjectAndReturnId(array $data): int|false
    {
        $filtered = $this->filterAllowed($data);
        
        // Validate data
        $errors = $this->validate($filtered);
        if (!empty($errors)) {
            error_log("Project creation failed (Validation): " . implode(', ', $errors));
            // Log the errors internally but don't expose them in the return type
            return false;
        }
        
        // Set default values if missing
        if (!isset($filtered['status'])) {
            $filtered['status'] = 'New';
        }
        
        if (!isset($filtered['budget'])) {
            $filtered['budget'] = 0.00;
        }
        
        // The BaseModel::insert method typically returns a boolean (success/fail).
        // To get the ID, we now use the new $this->getLastInsertId() method.
        try {
            $success = $this->insert($filtered); 

            if ($success) {
                // *** FIX APPLIED HERE ***
                // Replaced (int)$this->db->getLastInsertId() with the protected BaseModel method.
                return $this->getLastInsertId(); 
            }
        } catch (\Throwable $e) {
            error_log("DB ERROR: Failed to create project: " . $e->getMessage());
            return false;
        }

        return false;
    }


    /**
     * Create a new project (legacy/simple method)
     * * NOTE: This method is functionally redundant after adding createProjectAndReturnId
     * if the latter is used consistently, but kept here for backward compatibility.
     * * @param array $data Project data
     * @return bool Success status
     */
    public function createProject(array $data): bool
    {
        // We can just call the new method and cast the result
        return (bool)$this->createProjectAndReturnId($data);
    }

    /**
     * Update an existing project
     * * @param int $id Project ID
     * @param array $data Updated data
     * @return bool Success status
     */
    public function updateProject(int $id, array $data): bool
    {
        $filtered = $this->filterAllowed($data);
        
        // Don't validate client_id on update if not provided
        if (!isset($filtered['client_id'])) {
            $project = $this->getProjectById($id);
            if ($project) {
                $filtered['client_id'] = $project['client_id'];
            }
        }
        
        return $this->update($id, $filtered);
    }

    /**
     * Update project status
     * * @param int $id Project ID
     * @param string $status New status
     * @return bool Success status
     */
    public function updateStatus(int $id, string $status): bool
    {
        if (!in_array($status, $this->validStatuses)) {
            error_log("Invalid status: {$status}");
            return false;
        }
        
        return $this->update($id, ['status' => $status]);
    }

    /**
     * Delete a project
     * * @param int $id Project ID
     * @return bool Success status
     */
    public function deleteProject(int $id): bool
    {
        return $this->delete($id);
    }

    // ========================================================================
    // VALIDATION
    // (methods remain unchanged)
    // ========================================================================

    /**
     * Validate project input data
     * * @param array $data Data to validate
     * @return array Array of error messages (empty if valid)
     */
    public function validate(array $data): array
    {
        $errors = [];

        // Client ID validation
        if (empty($data['client_id'])) {
            $errors[] = 'Client ID is required.';
        } elseif (!is_numeric($data['client_id']) || $data['client_id'] < 1) {
            $errors[] = 'Client ID must be a valid number.';
        }

        // Title validation
        if (empty($data['title'])) {
            $errors[] = 'Project title is required.';
        } elseif (strlen(trim($data['title'])) < 5) {
            $errors[] = 'Project title must be at least 5 characters.';
        } elseif (strlen(trim($data['title'])) > 255) {
            $errors[] = 'Project title cannot exceed 255 characters.';
        }

        // Package name validation
        if (empty($data['package_name'])) {
            $errors[] = 'A service package must be selected.';
        }

        // Status validation
        if (isset($data['status']) && !in_array($data['status'], $this->validStatuses)) {
            $errors[] = 'Invalid project status.';
        }

        // Budget validation
        if (isset($data['budget'])) {
            if (!is_numeric($data['budget'])) {
                $errors[] = 'Budget must be a valid number.';
            } elseif ($data['budget'] < 0) {
                $errors[] = 'Budget cannot be negative.';
            }
        }

        // Date validation
        if (!empty($data['start_date']) && !$this->isValidDate($data['start_date'])) {
            $errors[] = 'Start date must be a valid date (YYYY-MM-DD).';
        }

        if (!empty($data['due_date']) && !$this->isValidDate($data['due_date'])) {
            $errors[] = 'Due date must be a valid date (YYYY-MM-DD).';
        }

        // Check if due date is after start date
        if (!empty($data['start_date']) && !empty($data['due_date'])) {
            if (strtotime($data['due_date']) < strtotime($data['start_date'])) {
                $errors[] = 'Due date cannot be before start date.';
            }
        }

        return $errors;
    }

    /**
     * Validate date format
     * * @param string $date Date string
     * @return bool True if valid
     */
    private function isValidDate(string $date): bool
    {
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    /**
     * Filter input data to allowed fields only
     * * @param array $data Input data
     * @return array Filtered data
     */
    private function filterAllowed(array $data): array
    {
        return array_intersect_key($data, array_flip($this->allowedFields));
    }

    // ========================================================================
    // UTILITY METHODS
    // (methods remain unchanged)
    // ========================================================================

    /**
     * Get all valid project statuses
     * * @return array Array of valid statuses
     */
    public function getValidStatuses(): array
    {
        return $this->validStatuses;
    }

    /**
     * Get status badge color class
     * * @param string $status Project status
     * @return string CSS class name
     */
    public function getStatusColor(string $status): string
    {
        $colors = [
            'New' => 'badge-info',
            'In Progress' => 'badge-primary',
            'On Hold' => 'badge-warning',
            'Awaiting Client' => 'badge-warning',
            'Review/QA' => 'badge-info',
            'Completed' => 'badge-success',
            'Canceled' => 'badge-error'
        ];
        
        return $colors[$status] ?? 'badge-outline';
    }

    /**
     * Get available packages from database
     * * @return array Array of package names
     */
    public function getAvailablePackages(): array
    {
        $sql = "SELECT DISTINCT title FROM pricing_packages ORDER BY title ASC";
        $results = $this->query($sql);
        
        return array_column($results, 'title');
    }
}