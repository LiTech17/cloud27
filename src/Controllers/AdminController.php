<?php
// src/Controllers/AdminController.php

namespace Controllers;

// --- REQUIRED DEPENDENCIES ---
use Models\UserModel;
use Models\ServiceModel;
// --- --------------------- ---

class AdminController extends BaseController {

    // ------------------------------------------------------------------------
    // 1. AUTHENTICATION METHODS (Place the missing ones here)
    // ------------------------------------------------------------------------

    /**
     * Display the login form view.
     */
    public function showLogin(): void {
        $this->render('login', ['title' => 'Admin Login']);
    }

    /**
     * Process the login form submission.
     */
    public function processLogin(): void {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $userModel = new UserModel();

        if ($userModel->authenticate($username, $password)) {
            // Successful login, set session variables
            $_SESSION['is_logged_in'] = true;
            $_SESSION['username'] = $username;
            header('Location: ' . BASE_PATH . '/admin/dashboard');
            exit;
        } else {
            // Failed login
            $_SESSION['error'] = 'Invalid username or password.';
            header('Location: ' . BASE_PATH . '/login');
            exit;
        }
    }

    /**
     * Handles user logout by destroying the session.
     */
    public function logout(): void {
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
    // 2. CRUD METHODS (The complete methods you provided)
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
        // Uses $_GET since we link to it as /admin/services/edit?id=X
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
        // Uses $_POST for form submission data
        $id = $_POST['id'] ?? null;
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');

        // Basic validation
        if (empty($title) || empty($description)) {
            $_SESSION['error'] = 'Title and Description are required.';
            
            // Redirect back to the form, preserving the ID if editing
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
        } else {
            // CREATE
            $success = $serviceModel->createService($data);
            $message = $success ? 'Service added successfully!' : 'Failed to add new service.';
        }

        // Set session message and redirect to the service list
        $_SESSION['message'] = $message;
        header('Location: ' . BASE_PATH . '/admin/services');
        exit;
    }

    /**
     * 4. Delete a service (DELETE POST).
     */
    public function deleteService(): void {
        $id = $_POST['id'] ?? null; // Gets ID from hidden input in the form

        if (!$id) {
            $_SESSION['message'] = 'Error: No service ID provided for deletion.';
        } else {
            $serviceModel = new ServiceModel();
            if ($serviceModel->deleteService((int)$id)) {
                $_SESSION['message'] = 'Service deleted successfully!';
            } else {
                $_SESSION['message'] = 'Error: Failed to delete service.';
            }
        }

        header('Location: ' . BASE_PATH . '/admin/services');
        exit;
    }
}