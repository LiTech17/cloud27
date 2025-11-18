<?php
// src/Controllers/AdminController.php

namespace Controllers;

use Models\UserModel;

class AdminController extends BaseController {

    // --- 1. DISPLAY LOGIN FORM ---
    public function showLogin(): void {
        // Only show the login page if the user is not already logged in
        if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
            header('Location: ' . BASE_PATH . '/admin/dashboard');
            exit;
        }

        $this->render('login', ['title' => 'Admin Login']);
    }

    // --- 2. PROCESS LOGIN FORM (POST) ---
    public function processLogin(): void {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $error = '';

        if (empty($username) || empty($password)) {
            $error = 'Both fields are required.';
        } else {
            $userModel = new UserModel();
            $user = $userModel->findByUsername($username);

            // Check if user exists and password is correct
            if ($user && password_verify($password, $user['password'])) {
                
                // Success: Start session, set flags, and redirect
                $_SESSION['is_logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                header('Location: ' . BASE_PATH . '/admin/dashboard');
                exit;

            } else {
                $error = 'Invalid username or password.';
            }
        }
        
        // Render login view again with error message if authentication fails
        $this->render('login', [
            'title' => 'Admin Login',
            'error' => $error,
            'username_value' => htmlspecialchars($username) // Keep username populated
        ]);
    }

    // --- 3. LOGOUT ---
    public function logout(): void {
        // Clear all session variables
        $_SESSION = [];
        
        // Destroy the session
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        
        // Redirect to login page
        header('Location: ' . BASE_PATH . '/login');
        exit;
    }

    // --- 4. ADMIN DASHBOARD (Protected) ---
    public function dashboard(): void {
        // Protection check will be added in Step 5.5
        $this->render('admin/dashboard', ['title' => 'Admin Dashboard']);
    }
}