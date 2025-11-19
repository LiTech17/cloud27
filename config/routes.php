<?php
// config/routes.php

/**
 * Defines the application's routing map, keyed by HTTP Method.
 * Format: [URI] => [Controller_Class, Method_To_Call, Middleware_Required_String]
 */

$routes = [
    'GET' => [

        // --- Public Front-end Routes ---
        '/'                 => ['Controllers\HomeController', 'index', ''],
        '/about'            => ['Controllers\AboutController', 'index', ''],
        '/services'         => ['Controllers\HomeController', 'services', ''],
        '/contact'          => ['Controllers\ContactController', 'showForm', ''],
        '/packages'         => ['Controllers\AdminPackageController', 'showPackages', ''],

        // --- Authentication ---
        '/login'            => ['Controllers\AdminController', 'showLogin', ''],
        '/logout'           => ['Controllers\AdminController', 'logout', ''],

        // --- Admin GET Routes (Protected - is_admin=1) ---
        '/admin/dashboard'  => ['Controllers\AdminController', 'dashboard', 'Auth'],

        // ========================================
        // ABOUT MANAGEMENT (ADMIN)
        // ========================================

        // About dashboard
        '/admin/about'          => ['Controllers\AboutController', 'adminIndex', 'Auth'],

        // Edit About content
        '/admin/about/edit'     => ['Controllers\AboutController', 'edit', 'Auth'],

        // Team Member Management
        '/admin/about/team/add'     => ['Controllers\AboutController', 'addTeamMember', 'Auth'],
        '/admin/about/team/edit'    => ['Controllers\AboutController', 'editTeamMember', 'Auth'],

        // ========================================
        // SERVICES (ADMIN)
        // ========================================
        '/admin/services'       => ['Controllers\AdminController', 'listServices', 'Auth'],
        '/admin/services/add'   => ['Controllers\AdminController', 'showEditForm', 'Auth'],
        '/admin/services/edit'  => ['Controllers\AdminController', 'showEditForm', 'Auth'],

        // ========================================
        // PACKAGES (ADMIN)
        // ========================================
        '/admin/packages'       => ['Controllers\AdminPackageController', 'listPackages', 'Auth'],
        '/admin/packages/add'   => ['Controllers\AdminPackageController', 'showEditForm', 'Auth'],
        '/admin/packages/edit'  => ['Controllers\AdminPackageController', 'showEditForm', 'Auth'],

        // ========================================
        // USERS (ADMIN)
        // ========================================
        '/admin/users'          => ['Controllers\AdminController', 'listUsers', 'Auth'],
        '/admin/users/add'      => ['Controllers\AdminController', 'showUserEditForm', 'Auth'],
        '/admin/users/edit'     => ['Controllers\AdminController', 'showUserEditForm', 'Auth'],

        // --- Client GET Routes (Protected - is_admin=0) ---
        '/client/dashboard'     => ['Controllers\ClientController', 'index', 'Auth'],
        '/client'               => ['Controllers\ClientController', 'index', 'Auth'],
    ],

    'POST' => [

        // --- Front-end POST Route ---
        '/contact'                  => ['Controllers\ContactController', 'submitForm', ''],

        // --- Auth POST Route ---
        '/login'                    => ['Controllers\AdminController', 'processLogin', ''],

        // ==============================
        // SERVICES POST (Admin)
        // ==============================
        '/admin/services/save'      => ['Controllers\AdminController', 'saveService', 'Auth'],
        '/admin/services/delete'    => ['Controllers\AdminController', 'deleteService', 'Auth'],

        // ==============================
        // PACKAGES POST (Admin)
        // ==============================
        '/admin/packages/save'      => ['Controllers\AdminPackageController', 'savePackage', 'Auth'],
        '/admin/packages/delete'    => ['Controllers\AdminPackageController', 'deletePackage', 'Auth'],

        // ==============================
        // USERS POST (Admin)
        // ==============================
        '/admin/users/save'         => ['Controllers\AdminController', 'saveUser', 'Auth'],
        '/admin/users/delete'       => ['Controllers\AdminController', 'deleteUser', 'Auth'],

        // ==============================
        // ABOUT POST (Admin)
        // ==============================

        // Save About content
        '/admin/about/save'         => ['Controllers\AboutController', 'save', 'Auth'],

        // TEAM MEMBERS POST
        '/admin/about/team/save'    => ['Controllers\AboutController', 'saveTeamMember', 'Auth'],
        '/admin/about/team/delete'  => ['Controllers\AboutController', 'deleteTeamMember', 'Auth'],
    ]
];

return $routes;
