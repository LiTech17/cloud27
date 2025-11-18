<?php
// config/routes.php

/**
 * Defines the application's routing map, keyed by HTTP Method.
 * [URI] => [Controller_Class, Method_To_Call, Auth_Required_Boolean]
 */
$routes = [
    'GET' => [
        // --- Public Front-end Routes ---
        '/'               => ['Controllers\HomeController', 'index', false],
        '/about'          => ['Controllers\HomeController', 'about', false],
        '/services'       => ['Controllers\HomeController', 'services', false],
        '/contact'        => ['Controllers\ContactController', 'showForm', false],
        
        // --- Admin GET Routes ---
        '/login'          => ['Controllers\AdminController', 'showLogin', false], // Show login form
        '/logout'         => ['Controllers\AdminController', 'logout', false], // Logout action
        '/admin/dashboard'=> ['Controllers\AdminController', 'dashboard', true], // Protected route
    ],

    'POST' => [
        // --- Front-end POST Route ---
        '/contact'        => ['Controllers\ContactController', 'submitForm', false],
        
        // --- Admin POST Route ---
        '/login'          => ['Controllers\AdminController', 'processLogin', false], // Process login form submission
    ]
];