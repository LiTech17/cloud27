<?php
// config/routes.php

/**
 * Defines the application's routing map, keyed by HTTP Method.
 * Format: [URI] => [Controller_Class, Method_To_Call, Auth_Required_Boolean]
 */
$routes = [
    'GET' => [
        // --- Public Front-end Routes ---
        '/'                => ['Controllers\HomeController', 'index', false],
        '/about'           => ['Controllers\HomeController', 'about', false],
        '/services'        => ['Controllers\HomeController', 'services', false],
        '/contact'         => ['Controllers\ContactController', 'showForm', false],
        
        // --- Admin GET Routes ---
        '/login'           => ['Controllers\AdminController', 'showLogin', false],      // Show login form
        '/logout'          => ['Controllers\AdminController', 'logout', false],         // Logout action
        '/admin/dashboard' => ['Controllers\AdminController', 'dashboard', true],       // Protected route
        
        // --- CRUD GET Routes (Protected) ---
        '/admin/services'        => ['Controllers\AdminController', 'listServices', true],    // List all services
        '/admin/services/add'    => ['Controllers\AdminController', 'showEditForm', true],    // Show Add form
        '/admin/services/edit'   => ['Controllers\AdminController', 'showEditForm', true],    // Show Edit form (?id=X)
    ],

    'POST' => [
        // --- Front-end POST Route ---
        '/contact'         => ['Controllers\ContactController', 'submitForm', false],
        
        // --- Admin POST Routes ---
        '/login'           => ['Controllers\AdminController', 'processLogin', false],   // Process login form submission
        
        // --- CRUD POST Routes (Protected) ---
        '/admin/services/save'   => ['Controllers\AdminController', 'saveService', true],     // Handles both ADD and EDIT submission
        '/admin/services/delete' => ['Controllers\AdminController', 'deleteService', true],   // Handles deletion
    ]
];