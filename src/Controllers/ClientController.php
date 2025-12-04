<?php
// src/Controllers/ClientController.php

// Keep the namespace consistent with AdminController
namespace Controllers; 

// BaseController is likely in the same namespace (Controllers) and handles rendering
class ClientController extends BaseController 
{
    /**
     * Display the Client Dashboard.
     */
    /**
     * Display the Client Dashboard.
     */
    /**
     * Display the User Profile.
     */
    public function profile()
    {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: ' . BASE_PATH . '/login');
            exit;
        }

        $userModel = new \Models\UserModel();
        $user = $userModel->getUserById($userId);

        $this->render('client/profile', [
            'title' => 'My Profile',
            'user' => $user
        ]);
    }

    /**
     * Handle Password Update.
     */
    public function updatePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_PATH . '/profile');
            exit;
        }

        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: ' . BASE_PATH . '/login');
            exit;
        }

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Basic Validation
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            header('Location: ' . BASE_PATH . '/profile?error=' . urlencode('All fields are required'));
            exit;
        }

        if ($newPassword !== $confirmPassword) {
            header('Location: ' . BASE_PATH . '/profile?error=' . urlencode('New passwords do not match'));
            exit;
        }

        if (strlen($newPassword) < 8) {
            header('Location: ' . BASE_PATH . '/profile?error=' . urlencode('Password must be at least 8 characters'));
            exit;
        }

        $userModel = new \Models\UserModel();
        $user = $userModel->getUserById($userId);

        if (!$user || !password_verify($currentPassword, $user['password'])) {
            header('Location: ' . BASE_PATH . '/profile?error=' . urlencode('Incorrect current password'));
            exit;
        }

        // Update Password
        $success = $userModel->updateUser($userId, ['password' => $newPassword]);

        if ($success) {
            header('Location: ' . BASE_PATH . '/profile?success=1');
        } else {
            header('Location: ' . BASE_PATH . '/profile?error=' . urlencode('Failed to update password'));
        }
        exit;
    }

    /**
     * Display the Client Dashboard.
     */
    public function index()
    {
        // Safety check for Admin access (Router handles primary check)
        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
            header('Location: ' . BASE_PATH . '/admin/dashboard');
            exit;
        }

        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: ' . BASE_PATH . '/login');
            exit;
        }

        $projectModel = new \Models\ProjectModel();
        
        // Fetch real data
        $projects = $projectModel->getAllByClient($userId);
        $stats = $projectModel->getProjectStats($userId);

        // Use the inherited render method from BaseController
        $this->render('client/dashboard', [
            'title' => 'Client Dashboard',
            'projects' => $projects,
            'stats' => $stats
        ]);
    }
}