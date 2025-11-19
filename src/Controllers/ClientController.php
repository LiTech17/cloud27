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
    public function index()
    {
        // Safety check for Admin access (Router handles primary check)
        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
            header('Location: ' . BASE_PATH . '/admin/dashboard');
            exit;
        }

        // Use the inherited render method from BaseController (like AdminController)
        // This prevents the "Class 'View' not found" error.
        $this->render('client/dashboard', [
            'title' => 'Client Dashboard'
        ]);
    }
}