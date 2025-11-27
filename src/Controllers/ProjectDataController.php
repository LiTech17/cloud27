<?php
// src/Controllers/ProjectDataController.php

namespace Controllers;

use Models\ProjectDataModel;
use Models\ProjectModel;
use Core\Upload;

class ProjectDataController extends BaseController
{
    private ProjectDataModel $model;
    private ProjectModel $projectModel;
    private Upload $uploader;

    private const FILE_FIELDS = ['business_profile', 'brand_guidelines'];
    private const PER_PAGE = 10;

    public function __construct()
    {
        $this->model = new ProjectDataModel();
        $this->projectModel = new ProjectModel();

        $this->uploader = new Upload([
            'target_dir' => 'uploads/project_files/',
            'max_size' => 10 * 1024 * 1024,
            'allowed_mime_types' => [
                'image/png', 'image/jpeg', 'image/jpg',
                'application/pdf', 'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ],
            'allowed_extensions' => ['png', 'jpg', 'jpeg', 'pdf', 'doc', 'docx'],
            'sanitize_filename' => true
        ]);
    }

    // ================================================================
    // CLIENT PROJECT DATA (NEW STANDARDIZED METHODS)
    // ================================================================

    /**
     * Show client project-data dashboard (list all their project data)
     */
    public function index(): void
    {
        $this->requireClient();
        $clientId = $_SESSION['user_id'];

        $projects = $this->model->getForClient($clientId);

        $this->render('client/projectData/index', [
            'pageTitle' => 'My Project Data',
            'projects'  => $projects
        ]);
    }

    /**
     * Show the onboarding form for first-time project data completion
     */
    public function createForm(int $projectId): void
    {
        $this->requireClient();

        $projectData = $this->getProjectDataWithOwnership($projectId);

        if ($projectData) {
            // Check if it's a draft and show appropriate message
            if (($projectData['is_draft'] ?? 0) === 1) {
                $_SESSION['message'] = 'Resuming your draft. You can continue where you left off.';
            } else {
                $_SESSION['message'] = 'Project data found. You can review and update your information.';
            }
            
            // Redirect to edit if data exists (even if draft)
            header('Location: ' . BASE_PATH . "/client/project-data/{$projectId}/edit");
            exit;
        }

        $this->render('client/projectData/create', [
            'pageTitle'     => 'Complete Project Details',
            'projectId'     => $projectId,
            'projectData'   => $_SESSION['old_input'] ?? [], // Use old input on error
            'errors'        => $_SESSION['errors'] ?? [],
            'fieldOptions'  => $this->getFieldOptions(),
            'validationRules' => $this->model::getValidationRules()
        ]);

        unset($_SESSION['errors'], $_SESSION['old_input']);
    }

    /**
     * Show existing project data (view-only)
     */
    public function show(int $projectId): void
    {
        $this->requireClient();

        $projectData = $this->getProjectDataWithOwnership($projectId);

        if (!$projectData) {
            http_response_code(403);
            $this->render('403');
            exit;
        }

        $this->render('client/projectData/show', [
            'pageTitle'     => 'Project Data Summary',
            'projectData'   => $projectData,
            'fieldOptions'  => $this->getFieldOptions()
        ]);
    }

    /**
     * Show the edit form for project data
     */
    public function editForm(int $projectId): void
    {
        $this->requireClient();

        $projectData = $this->getProjectDataWithOwnership($projectId);

        if (!$projectData) {
            $_SESSION['error'] = 'Access denied or project not found.';
            header('Location: ' . BASE_PATH . '/client/project-data');
            exit;
        }
        
        // Merge project data with old input on error
        $data = $_SESSION['old_input'] ?? $projectData;

        $this->render('client/projectData/edit', [
            'pageTitle'     => 'Edit Project Data',
            'projectData'   => $data,
            'projectId'     => $projectId,
            'errors'        => $_SESSION['errors'] ?? [],
            'fieldOptions'  => $this->getFieldOptions(),
            'validationRules' => $this->model::getValidationRules()
        ]);

        unset($_SESSION['errors'], $_SESSION['old_input']);
    }

    // ================================================================
    // SAVE HANDLING (Shared between client + admin)
    // ================================================================

    public function store(int $projectId): void
    {
        $this->processSave($projectId, 'create');
    }

    public function update(int $projectId): void
    {
        $this->processSave($projectId, 'edit');
    }

    /**
     * Centralized save handler for create/update with draft support
     * @param int $projectId
     * @param string $mode 'create' or 'edit'
     */
    private function processSave(int $projectId, string $mode): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectWithError('Invalid request method.');
        }

        // Check ownership (admins are allowed)
        if (!($_SESSION['is_admin'] ?? false)) {
            $projectDataCheck = $this->getProjectDataWithOwnership($projectId);
            if (!$projectDataCheck && $mode === 'edit') {
                 // Deny if editing non-existent or unowned data in client mode
                 $this->redirectWithError('Access denied.');
            }
        }

        $isDraft = isset($_POST['is_draft']) && $_POST['is_draft'] === '1';
        $data = $this->model->sanitizeInput($_POST);
        $data['project_id'] = $projectId;
        $data['client_id'] = $_SESSION['user_id'];
        
        $existingData = [];
        if ($mode === 'edit' || $this->model->getByProjectId($projectId)) {
            $existingData = $this->model->getByProjectId($projectId) ?? [];
        }

        $errors = [];
        if (!$isDraft) {
            // Only validate required fields on FINAL SUBMISSION ($isDraft is false)
            $errors = $this->model->validate($data);
        }

        $uploadedFiles = [];

        // Handle file uploads with rollback capability
        foreach (self::FILE_FIELDS as $field) {
            if (!empty($_FILES[$field]) && $_FILES[$field]['error'] !== UPLOAD_ERR_NO_FILE) {
                // Case 1: New file uploaded
                $uploadResult = $this->uploader->uploadFile($_FILES[$field]);
                if ($uploadResult['success']) {
                    $uploadedFiles[$field] = $uploadResult['filename'];
                    $data[$field] = $uploadResult['filename'];
                } else {
                    // File upload error
                    if (!$isDraft) {
                        $errors[$field] = $uploadResult['error'];
                    }
                }
            } elseif (isset($existingData[$field]) && !empty($existingData[$field])) {
                // Case 2: Keep existing file if no new upload
                $data[$field] = $existingData[$field];
            } else {
                // Case 3: No upload and no existing file, explicitly set to null
                $data[$field] = null;
            }
        }

        // --- Standard Validation Failure Handling ---
        if (!$isDraft && !empty($errors)) {
            // Clean up any uploaded files if validation fails
            foreach ($uploadedFiles as $filename) {
                try {
                    $this->uploader->deleteFile($filename);
                } catch (\Exception $e) {
                    error_log("Failed to clean up file after validation error: " . $e->getMessage());
                }
            }

            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $data; // Preserve input
            
            // FIXED: Always redirect to edit form, never to create form
            header("Location: " . BASE_PATH . "/client/project-data/{$projectId}/edit");
            exit;
        }

        // --- Database Save and Transaction-like File Management ---
        try {
            // Set draft/completion status and timestamps
            if ($isDraft) {
                $data['is_draft'] = 1;
                $data['last_saved_at'] = date('Y-m-d H:i:s');
                // Ensure submitted_at is not overwritten if it exists
                if (isset($existingData['submitted_at'])) {
                    $data['submitted_at'] = $existingData['submitted_at'];
                } else {
                    $data['submitted_at'] = null;
                }
            } else {
                $data['is_draft'] = 0; // Mark as complete (Final Submission)
                $data['submitted_at'] = date('Y-m-d H:i:s');
                $data['last_saved_at'] = date('Y-m-d H:i:s');
            }
            
            $this->model->save($projectId, $data);
            
            // Delete old files after successful database save (only if a new file replaced it)
            if ($mode === 'edit') {
                foreach (self::FILE_FIELDS as $field) {
                    if (isset($uploadedFiles[$field]) && !empty($existingData[$field]) && $uploadedFiles[$field] !== $existingData[$field]) {
                        try {
                            $this->uploader->deleteFile($existingData[$field]);
                        } catch (\Exception $e) {
                            error_log("Failed to delete old file: " . $e->getMessage());
                        }
                    }
                }
            }
            
            if ($isDraft) {
                $_SESSION['message'] = 'Draft saved successfully. You can continue later.';
            } else {
                $_SESSION['message'] = 'Project data submitted successfully.';
            }
            
        } catch (\Exception $e) {
            // Rollback: delete uploaded files on database failure
            foreach ($uploadedFiles as $filename) {
                try {
                    $this->uploader->deleteFile($filename);
                } catch (\Exception $deleteError) {
                    error_log("Failed to delete file during rollback: " . $deleteError->getMessage());
                }
            }
            
            error_log("ProjectData save error for project {$projectId}: " . $e->getMessage());
            
            // Error handling
            $_SESSION['errors'] = ['general' => 'Failed to save project data. Please try again.'];
            $_SESSION['old_input'] = $data;
            // FIXED: Always redirect to edit form on error
            header("Location: " . BASE_PATH . "/client/project-data/{$projectId}/edit");
            exit;
        }

        // Clear old input and redirect after success
        unset($_SESSION['old_input']);
        
        // FIXED: Simplified redirect logic - always use edit for drafts, show for final
        if ($isDraft) {
            // Redirect back to the edit form for drafts
            header("Location: " . BASE_PATH . "/client/project-data/{$projectId}/edit");
        } else {
            // Redirect to show page for final submission
            header('Location: ' . BASE_PATH . "/client/project-data/{$projectId}");
        }
        exit;
    }

    // ================================================================
    // EXISTING METHODS (keep as is)
    // ================================================================

    /**
     * Handle file deletion for specific fields
     */
    public function deleteFile(int $projectId): void
    {
        $this->requireClient();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectWithError('Invalid request method.');
        }

        $field = $_POST['field'] ?? '';
        if (!in_array($field, self::FILE_FIELDS)) {
            $this->redirectWithError('Invalid file field.');
        }

        $projectData = $this->getProjectDataWithOwnership($projectId);
        if (!$projectData) {
            $this->redirectWithError('Access denied or project not found.');
        }

        if (!empty($projectData[$field])) {
            try {
                $this->uploader->deleteFile($projectData[$field]);
                
                // Update database to remove file reference
                $this->model->updateByProjectId($projectId, [$field => null]);
                
                $_SESSION['message'] = 'File deleted successfully.';
            } catch (\Exception $e) {
                error_log("Failed to delete file: " . $e->getMessage());
                $_SESSION['error'] = 'Failed to delete file.';
            }
        }

        header('Location: ' . BASE_PATH . "/client/project-data/{$projectId}/edit");
        exit;
    }

    /**
     * Download project file
     */
    public function downloadFile(int $projectId, string $field): void
    {
        if (!in_array($field, self::FILE_FIELDS)) {
            http_response_code(400);
            exit('Invalid file field.');
        }

        // Check ownership
        if (!($_SESSION['is_admin'] ?? false)) {
            $projectData = $this->getProjectDataWithOwnership($projectId);
            if (!$projectData) {
                http_response_code(403);
                exit('Access denied.');
            }
        } else {
            $projectData = $this->model->getByProjectId($projectId);
            if (!$projectData) {
                http_response_code(404);
                exit('Project data not found.');
            }
        }

        if (empty($projectData[$field])) {
            http_response_code(404);
            exit('File not found.');
        }

        try {
            $filePath = $this->uploader->getFilePath($projectData[$field]);
            if (!file_exists($filePath)) {
                http_response_code(404);
                exit('File not found on server.');
            }

            $filename = $projectData[$field];
            $filetype = mime_content_type($filePath);
            $filesize = filesize($filePath);

            header("Content-Type: $filetype");
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-Length: $filesize");
            header("Cache-Control: private");
            header("Pragma: no-cache");
            header("Expires: 0");

            readfile($filePath);
            exit;

        } catch (\Exception $e) {
            error_log("File download error: " . $e->getMessage());
            http_response_code(500);
            exit('Error downloading file.');
        }
    }

    // ================================================================
    // ADMIN METHODS
    // ================================================================

    public function adminView(int $projectId): void
    {
        $this->requireAdmin();

        $projectData = $this->model->getWithProject($projectId);

        if (!$projectData) {
            $_SESSION['error'] = 'Project data not found.';
            header('Location: ' . BASE_PATH . '/admin/projects');
            exit;
        }

        $this->render('admin/project_data', [
            'pageTitle'     => 'Project Data: ' . ($projectData['project_title'] ?? 'Unknown'),
            'projectData'   => $projectData,
            'fieldOptions'  => $this->getFieldOptions()
        ]);
    }

    public function adminIndex(): void
    {
        $this->requireAdmin();

        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = self::PER_PAGE;

        $projects = $this->model->getAllPaginated($page, $perPage);
        $total = $this->model->countAll();
        $totalPages = max(1, ceil($total / $perPage));

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $this->render('admin/projects', [
            'pageTitle'     => 'All Projects | Admin',
            'projects'      => $projects,
            'currentPage'   => $page,
            'totalPages'    => $totalPages,
            'fieldOptions'  => $this->getFieldOptions()
        ]);
    }

    public function search(): void
    {
        $this->requireAdmin();

        $query = trim($_GET['q'] ?? '');
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = self::PER_PAGE;

        if (empty($query)) {
            header('Location: ' . BASE_PATH . '/admin/projects');
            exit;
        }

        $projects = $this->model->search($query, $page, $perPage);
        $total = $this->model->countSearch($query);
        $totalPages = max(1, ceil($total / $perPage));

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $this->render('admin/projects', [
            'pageTitle'   => "Search: {$query} | Admin",
            'projects'    => $projects,
            'currentPage' => $page,
            'totalPages'  => $totalPages,
            'searchQuery' => $query,
            'fieldOptions'=> $this->getFieldOptions()
        ]);
    }

    public function delete(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectWithError('Invalid request method.');
        }

        $projectId = (int)($_POST['project_id'] ?? 0);

        if (!$projectId) {
            $this->redirectWithError('Invalid project ID.');
        }

        $projectData = $this->model->getByProjectId($projectId);

        if (!$projectData) {
            $_SESSION['error'] = 'Project data not found.';
            header('Location: ' . BASE_PATH . '/admin/projects');
            exit;
        }

        // Delete associated files
        foreach (self::FILE_FIELDS as $field) {
            if (!empty($projectData[$field])) {
                try {
                    $this->uploader->deleteFile($projectData[$field]);
                } catch (\Exception $e) {
                    error_log("Failed to delete file: " . $e->getMessage());
                }
            }
        }

        if ($this->model->deleteByProjectId($projectId)) {
            $_SESSION['message'] = 'Project data deleted successfully.';
        } else {
            $_SESSION['error'] = 'Failed to delete project data.';
        }

        header('Location: ' . BASE_PATH . '/admin/projects');
        exit;
    }

    // ================================================================
    // HELPERS
    // ================================================================

    private function getProjectDataWithOwnership(int $projectId): ?array
    {
        $clientId = $_SESSION['user_id'] ?? null;
        if (!$clientId) return null;

        $project = $this->projectModel->getProjectById($projectId);

        if (!$project || $project['client_id'] != $clientId) {
            return null;
        }

        return $this->model->getByProjectId($projectId);
    }

    public function getFieldOptions(): array
    {
        return $this->model::getFieldOptions();
    }

    private function requireAdmin(): void
    {
        if (!($_SESSION['is_logged_in'] ?? false)) {
            header('Location: ' . BASE_PATH . '/login');
            exit;
        }

        if (!($_SESSION['is_admin'] ?? false)) {
            $_SESSION['error'] = 'Admin access only.';
            header('Location: ' . BASE_PATH . '/admin/dashboard');
            exit;
        }
    }

    private function requireClient(): void
    {
        if (!($_SESSION['is_logged_in'] ?? false)) {
            header('Location: ' . BASE_PATH . '/login');
            exit;
        }

        if ($_SESSION['is_admin'] ?? false) {
            $_SESSION['error'] = 'Client access only.';
            header('Location: ' . BASE_PATH . '/admin/dashboard');
            exit;
        }
    }

    private function redirectWithError(string $message): void
    {
        $_SESSION['error'] = $message;

        $redirect = ($_SESSION['is_admin'] ?? false)
            ? BASE_PATH . '/admin/projects'
            : BASE_PATH . '/client/project-data';

        header('Location: ' . $redirect);
        exit;
    }
}