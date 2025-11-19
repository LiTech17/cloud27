<?php
// src/Controllers/AdminController.php

namespace Controllers;

// --- REQUIRED DEPENDENCIES ---
use Models\UserModel;
use Models\ServiceModel;
// \Logger is assumed to be in the global namespace (src/Logger.php)
// --- --------------------- ---

class AdminController extends BaseController {

    // ------------------------------------------------------------------------
    // 1. AUTHENTICATION METHODS (Updated for Role Awareness and Logging)
    // ------------------------------------------------------------------------

    /**
     * Display the login form view.
     */
    public function showLogin(): void {
        $this->render('login', ['title' => 'Admin Login']);
    }

    /**
     * Process the login form submission (Updated for Admin/Client RBAC and Logging).
     */
    public function processLogin(): void {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $userModel = new UserModel();
        $user = $userModel->authenticate($username, $password); // Must return user array with 'is_admin'
        
        if ($user) {
            // Successful login, set session variables
            $_SESSION['is_logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            // Store the flag directly
            $_SESSION['is_admin'] = (bool)$user['is_admin']; 
            
            // Determine role string for clarity in Router/Views
            $role = $_SESSION['is_admin'] ? 'admin' : 'client';
            $_SESSION['role'] = $role;

            // === LOGGING: Successful Login ===
            \Logger::auth("Successful login for user: {$username} (ID: {$user['id']}). Role: {$role}.");
            
            // Redirect based on the flag
            if ($_SESSION['is_admin']) {
                // Admin (is_admin = 1) goes to the Admin dashboard
                header('Location: ' . BASE_PATH . '/admin/dashboard');
            } else {
                // Client (is_admin = 0) goes to the Client dashboard
                header('Location: ' . BASE_PATH . '/client/dashboard');
            }
            exit;
        } else {
            // Failed login
            // === LOGGING: Failed Login ===
            \Logger::auth("Failed login attempt for username: {$username}.");

            $_SESSION['error'] = 'Invalid username or password.';
            header('Location: ' . BASE_PATH . '/login');
            exit;
        }
    }

    /**
     * Handles user logout by destroying the session.
     */
    public function logout(): void {
        
        // === LOGGING: Logout Event ===
        $username = $_SESSION['username'] ?? 'Unknown User';
        $userId = $_SESSION['user_id'] ?? 'N/A';
        \Logger::auth("User logged out: {$username} (ID: {$userId}).");
        
        // Destroy the current session
        session_unset();
        session_destroy();

        // Redirect the user to the homepage
        header('Location: ' . BASE_PATH . '/');
        exit;
    }

    public function dashboard(): void {
        $this->render('admin/dashboard', ['title' => 'Admin Dashboard']);
    }

    // ------------------------------------------------------------------------
    // 2. SERVICE CRUD METHODS (Updated for Logging)
    // ------------------------------------------------------------------------

    /**
     * 1. Display the list of all services (READ).
     */
    public function listServices(): void {
        $serviceModel = new ServiceModel();
        $services = $serviceModel->getAllServices();

        $this->render('admin/services', [
            'title' => 'Manage Services',
            'services' => $services
        ]);
    }

    /**
     * 2. Display the form for adding a new service or editing an existing one (CREATE/UPDATE).
     */
    public function showEditForm(): void {
        $service = null;
        $id = $_GET['id'] ?? null; 
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);

        if ($id) {
            $serviceModel = new ServiceModel();
            $service = $serviceModel->getServiceById((int)$id);

            if (!$service) {
                $_SESSION['message'] = 'Service not found.';
                header('Location: ' . BASE_PATH . '/admin/services');
                exit;
            }
        }

        $this->render('admin/edit_service', [
            'title' => ($id ? 'Edit' : 'Add') . ' Service',
            'service' => $service,
            'error' => $error
        ]);
    }

    /**
     * 3. Process the form submission to save (add or edit) a service (CREATE/UPDATE POST).
     */
    public function saveService(): void {
        $id = $_POST['id'] ?? null;
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if (empty($title) || empty($description)) {
            $_SESSION['error'] = 'Title and Description are required.';
            $redirect = BASE_PATH . '/admin/services/add';
            if ($id) {
                $redirect = BASE_PATH . '/admin/services/edit?id=' . $id;
            }
            header('Location: ' . $redirect);
            exit;
        }

        $serviceModel = new ServiceModel();
        $data = ['title' => $title, 'description' => $description];
        $success = false;
        $message = 'Something went wrong.';

        if ($id) {
            // UPDATE
            $success = $serviceModel->updateService((int)$id, $data);
            $message = $success ? 'Service updated successfully!' : 'Failed to update service.';

            if ($success) {
                // === LOGGING: Service Update ===
                \Logger::admin("Updated service ID {$id}. Title: '{$title}'.");
            }
        } else {
            // CREATE
            $success = $serviceModel->createService($data);
            $message = $success ? 'Service added successfully!' : 'Failed to add new service.';
            
            if ($success) {
                // === LOGGING: Service Creation ===
                \Logger::admin("Created new service. Title: '{$title}'.");
            }
        }

        $_SESSION['message'] = $message;
        header('Location: ' . BASE_PATH . '/admin/services');
        exit;
    }

    /**
     * 4. Delete a service (DELETE POST).
     */
    public function deleteService(): void {
        $id = $_POST['id'] ?? null; 

        if (!$id) {
            $_SESSION['message'] = 'Error: No service ID provided for deletion.';
        } else {
            $serviceModel = new ServiceModel();
            if ($serviceModel->deleteService((int)$id)) {
                $_SESSION['message'] = 'Service deleted successfully!';
                // === LOGGING: Service Deletion ===
                \Logger::admin("Deleted service ID {$id}.");
            } else {
                $_SESSION['message'] = 'Error: Failed to delete service.';
            }
        }

        header('Location: ' . BASE_PATH . '/admin/services');
        exit;
    }

    // ------------------------------------------------------------------------
    // 3. USER MANAGEMENT CRUD METHODS (Updated for Logging)
    // ------------------------------------------------------------------------

    /**
     * 1. Display the list of all users (READ).
     */
    public function listUsers(): void {
        // NOTE: Only Admins (is_admin=1) should access this route, enforced by Router
        $userModel = new UserModel();
        $users = $userModel->getAllUsers();

        $this->render('admin/users', [
            'title' => 'Manage User Accounts',
            'users' => $users
        ]);
    }

    /**
     * 2. Display the form for adding a new user or editing an existing one (CREATE/UPDATE).
     */
    public function showUserEditForm(): void {
        $user = null;
        $id = $_GET['id'] ?? null; 
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);

        if ($id) {
            $userModel = new UserModel();
            $user = $userModel->getUserById((int)$id);

            if (!$user) {
                $_SESSION['message'] = 'User not found.';
                header('Location: ' . BASE_PATH . '/admin/users');
                exit;
            }
        }

        $this->render('admin/edit_user', [
            'title' => ($id ? 'Edit' : 'Add') . ' User',
            'user' => $user,
            'error' => $error
        ]);
    }

    /**
     * 3. Process the form submission to save (add or edit) a user (CREATE/UPDATE POST).
     */
    public function saveUser(): void {
        $id = $_POST['id'] ?? null;
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $isAdmin = (int)($_POST['is_admin'] ?? 0); // Must be 0 or 1

        // Basic validation
        if (empty($username) || empty($email) || (empty($password) && !$id)) {
            $_SESSION['error'] = 'Username, Email, and Password (for new users) are required.';
            $redirect = BASE_PATH . '/admin/users/add';
            if ($id) {
                $redirect = BASE_PATH . '/admin/users/edit?id=' . $id;
            }
            header('Location: ' . $redirect);
            exit;
        }

        $userModel = new UserModel();
        $data = ['username' => $username, 'email' => $email, 'is_admin' => $isAdmin];

        if (!empty($password)) {
            // Hash the password for storage
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $success = false;
        $message = 'Something went wrong.';
        $role_string = $isAdmin ? 'Admin' : 'Client';

        if ($id) {
            // UPDATE
            $success = $userModel->updateUser((int)$id, $data);
            $message = $success ? 'User updated successfully!' : 'Failed to update user.';
            
            if ($success) {
                // === LOGGING: User Update ===
                \Logger::admin("Updated user ID {$id} (Username: {$username}). Role set to {$role_string}.");
            }
        } else {
            // CREATE
            $success = $userModel->createUser($data);
            $message = $success ? 'User added successfully!' : 'Failed to add new user.';
            
            if ($success) {
                // === LOGGING: User Creation ===
                \Logger::admin("Created new user: {$username}. Role: {$role_string}.");
            }
        }

        $_SESSION['message'] = $message;
        header('Location: ' . BASE_PATH . '/admin/users');
        exit;
    }

    /**
     * 4. Delete a user (DELETE POST).
     */
    public function deleteUser(): void {
        $id = $_POST['id'] ?? null; 

        // Cannot delete yourself
        if ($id == ($_SESSION['user_id'] ?? null)) {
            $_SESSION['message'] = 'Error: Cannot delete your own account.';
        } elseif (!$id) {
            $_SESSION['message'] = 'Error: No user ID provided for deletion.';
        } else {
            $userModel = new UserModel();
            if ($userModel->deleteUser((int)$id)) {
                $_SESSION['message'] = 'User deleted successfully!';
                // === LOGGING: User Deletion ===
                \Logger::admin("Deleted user ID {$id}.");
            } else {
                $_SESSION['message'] = 'Error: Failed to delete user.';
            }
        }

        header('Location: ' . BASE_PATH . '/admin/users');
        exit;
    }
}