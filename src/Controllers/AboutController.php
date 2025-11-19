<?php
// src/Controllers/AboutController.php

namespace Controllers;

use Models\AboutModel;
use Core\Upload;

/**
 * AboutController handles all about page related requests.
 * Manages both public-facing about page and admin about management.
 */
class AboutController extends BaseController {
    
    private $model;
    private $uploader;
    
    public function __construct() {
        $this->model = new AboutModel();
        
        // Configure upload handler for about page images
        $this->uploader = new Upload([
            'target_dir' => 'uploads/about/',
            'max_size' => 5242880, // 5MB
            'allowed_mime_types' => ['image/png', 'image/jpeg', 'image/jpg', 'image/webp'],
            'allowed_extensions' => ['png', 'jpg', 'jpeg', 'webp']
        ]);
    }
    
    /**
     * Display public about page
     */
    public function index(): void {
        // Get about content
        $content = $this->model->getContent();
        
        // Get team members
        $teamMembers = $this->model->getTeamMembers();
        
        // Initialize content if not exists
        if (!$content) {
            $this->model->initializeContent();
            $content = $this->model->getContent();
        }
        
        $this->render('about', [
            'pageTitle' => 'About Us | Cloud27',
            'content' => $content,
            'teamMembers' => $teamMembers
        ]);
    }
    
    /**
     * Display admin about management page
     */
    public function adminIndex(): void {
        $this->requireAdmin();
        
        $content = $this->model->getContent();
        $teamMembers = $this->model->getTeamMembers();
        
        // Initialize if not exists
        if (!$content) {
            $this->model->initializeContent();
            $content = $this->model->getContent();
        }
        
        $this->render('admin/about', [
            'pageTitle' => 'Manage About Page | Admin',
            'content' => $content,
            'teamMembers' => $teamMembers
        ]);
    }
    
    /**
     * Display edit about content form
     */
    public function edit(): void {
        $this->requireAdmin();
        
        $content = $this->model->getContent();
        
        if (!$content) {
            $this->model->initializeContent();
            $content = $this->model->getContent();
        }
        
        $this->render('admin/edit_about', [
            'pageTitle' => 'Edit About Content | Admin',
            'content' => $content,
            'error' => $_SESSION['error'] ?? null
        ]);
        
        unset($_SESSION['error']);
    }
    
    /**
     * Save about content
     */
    public function save(): void {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_PATH . '/admin/about');
            exit;
        }
        
        // Sanitize and validate input
        $data = [
            'company_name' => trim($_POST['company_name'] ?? ''),
            'tagline' => trim($_POST['tagline'] ?? ''),
            'mission_statement' => trim($_POST['mission_statement'] ?? ''),
            'about_text' => trim($_POST['about_text'] ?? ''),
            'founded_year' => trim($_POST['founded_year'] ?? date('Y')),
            'employee_count' => trim($_POST['employee_count'] ?? ''),
            'office_location' => trim($_POST['office_location'] ?? '')
        ];
        
        // Validate required fields
        if (empty($data['company_name']) || empty($data['about_text'])) {
            $_SESSION['error'] = 'Company name and about text are required.';
            header('Location: ' . BASE_PATH . '/admin/about/edit');
            exit;
        }
        
        // Handle hero image upload
        if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $result = $this->uploader->uploadFile($_FILES['hero_image']);
            
            if ($result['success']) {
                // Delete old image if exists
                $oldContent = $this->model->getContent();
                if ($oldContent && $oldContent['hero_image']) {
                    $this->uploader->deleteFile($oldContent['hero_image']);
                }
                
                $data['hero_image'] = $result['filename'];
            } else {
                $_SESSION['error'] = 'Hero image upload failed: ' . $result['error'];
                header('Location: ' . BASE_PATH . '/admin/about/edit');
                exit;
            }
        }
        
        // Handle company image upload
        if (isset($_FILES['company_image']) && $_FILES['company_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $result = $this->uploader->uploadFile($_FILES['company_image']);
            
            if ($result['success']) {
                // Delete old image if exists
                $oldContent = $this->model->getContent();
                if ($oldContent && $oldContent['company_image']) {
                    $this->uploader->deleteFile($oldContent['company_image']);
                }
                
                $data['company_image'] = $result['filename'];
            } else {
                $_SESSION['error'] = 'Company image upload failed: ' . $result['error'];
                header('Location: ' . BASE_PATH . '/admin/about/edit');
                exit;
            }
        }
        
        // Update content
        if ($this->model->updateContent($data)) {
            $_SESSION['message'] = 'About content updated successfully!';
            header('Location: ' . BASE_PATH . '/admin/about');
        } else {
            $_SESSION['error'] = 'Failed to update about content.';
            header('Location: ' . BASE_PATH . '/admin/about/edit');
        }
        
        exit;
    }
    
    /**
     * Add team member form
     */
    public function addTeamMember(): void {
        $this->requireAdmin();
        
        $this->render('admin/edit_team_member', [
            'pageTitle' => 'Add Team Member | Admin',
            'member' => null,
            'error' => $_SESSION['error'] ?? null
        ]);
        
        unset($_SESSION['error']);
    }
    
    /**
     * Edit team member form
     */
    public function editTeamMember(): void {
        $this->requireAdmin();
        
        $id = (int)($_GET['id'] ?? 0);
        $member = $this->model->getTeamMember($id);
        
        if (!$member) {
            $_SESSION['error'] = 'Team member not found.';
            header('Location: ' . BASE_PATH . '/admin/about');
            exit;
        }
        
        $this->render('admin/edit_team_member', [
            'pageTitle' => 'Edit Team Member | Admin',
            'member' => $member,
            'error' => $_SESSION['error'] ?? null
        ]);
        
        unset($_SESSION['error']);
    }
    
    /**
     * Save team member
     */
    public function saveTeamMember(): void {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_PATH . '/admin/about');
            exit;
        }
        
        $id = (int)($_POST['id'] ?? 0);
        $isEdit = $id > 0;
        
        // Sanitize input
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'position' => trim($_POST['position'] ?? ''),
            'bio' => trim($_POST['bio'] ?? ''),
            'display_order' => (int)($_POST['display_order'] ?? 0)
        ];
        
        // Validate
        if (empty($data['name']) || empty($data['position'])) {
            $_SESSION['error'] = 'Name and position are required.';
            $redirect = $isEdit ? '/admin/about/team/edit?id=' . $id : '/admin/about/team/add';
            header('Location: ' . BASE_PATH . $redirect);
            exit;
        }
        
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $result = $this->uploader->uploadFile($_FILES['image']);
            
            if ($result['success']) {
                // Delete old image if editing
                if ($isEdit) {
                    $oldMember = $this->model->getTeamMember($id);
                    if ($oldMember && $oldMember['image_path']) {
                        $this->uploader->deleteFile($oldMember['image_path']);
                    }
                }
                
                $data['image_path'] = $result['filename'];
            } else {
                $_SESSION['error'] = 'Image upload failed: ' . $result['error'];
                $redirect = $isEdit ? '/admin/about/team/edit?id=' . $id : '/admin/about/team/add';
                header('Location: ' . BASE_PATH . $redirect);
                exit;
            }
        }
        
        // Save or update
        if ($isEdit) {
            $success = $this->model->updateTeamMember($id, $data);
            $message = 'Team member updated successfully!';
        } else {
            $success = $this->model->addTeamMember($data);
            $message = 'Team member added successfully!';
        }
        
        if ($success) {
            $_SESSION['message'] = $message;
        } else {
            $_SESSION['error'] = 'Failed to save team member.';
        }
        
        header('Location: ' . BASE_PATH . '/admin/about');
        exit;
    }
    
    /**
     * Delete team member
     */
    public function deleteTeamMember(): void {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_PATH . '/admin/about');
            exit;
        }
        
        $id = (int)($_POST['id'] ?? 0);
        
        // Get member to delete image
        $member = $this->model->getTeamMember($id);
        
        if ($member && $this->model->deleteTeamMember($id)) {
            // Delete image if exists
            if ($member['image_path']) {
                $this->uploader->deleteFile($member['image_path']);
            }
            
            $_SESSION['message'] = 'Team member deleted successfully!';
        } else {
            $_SESSION['error'] = 'Failed to delete team member.';
        }
        
        header('Location: ' . BASE_PATH . '/admin/about');
        exit;
    }
    
    /**
     * Require admin authentication
     */
    private function requireAdmin(): void {
        if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
            header('Location: ' . BASE_PATH . '/login');
            exit;
        }
        
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            $_SESSION['error'] = 'Access denied. Admin privileges required.';
            header('Location: ' . BASE_PATH . '/admin/dashboard');
            exit;
        }
    }
}