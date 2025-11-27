<?php
// config/routes.php

$routes = [

    // ========================================================================
    // GET ROUTES
    // ========================================================================
    'GET' => [

        // --- Public Front-end Routes ---
        '/'                 => ['Controllers\HomeController', 'index', ''],
        '/about'            => ['Controllers\AboutController', 'index', ''],
        '/services'         => ['Controllers\HomeController', 'services', ''],
        '/contact'          => ['Controllers\ContactController', 'showForm', ''],
        '/packages'         => ['Controllers\AdminPackageController', 'showPackages', ''],
        '/get-started'      => ['Controllers\GetStartedController', 'index', ''],

        // --- Authentication ---
        '/login'            => ['Controllers\AdminController', 'showLogin', ''],
        '/logout'           => ['Controllers\AdminController', 'logout', ''],

        // ====================================================================
        // ADMIN (is_admin = 1)
        // ====================================================================
        '/admin/dashboard'  => ['Controllers\AdminController', 'dashboard', 'Auth'],

        // ---------------------- PROJECT MANAGEMENT (ADMIN) --------------------
        '/admin/projects'               => ['Controllers\AdminProjectController', 'index', 'Auth'],
        '/admin/projects/create'        => ['Controllers\AdminProjectController', 'create', 'Auth'],
        '/admin/projects/edit/{id}'     => ['Controllers\AdminProjectController', 'edit', 'Auth'],
        '/admin/projects/{id}'          => ['Controllers\AdminProjectController', 'show', 'Auth'],

        // ---------------------- PROJECT DATA MANAGEMENT (ADMIN) ---------------
        '/admin/projects/data/{projectId}' => ['Controllers\ProjectDataController', 'adminView', 'Auth'],
        '/admin/projects/all-data'         => ['Controllers\ProjectDataController', 'adminIndex', 'Auth'],
        '/admin/projects/search'           => ['Controllers\ProjectDataController', 'search', 'Auth'],

        // ---------------------- ABOUT (ADMIN) ---------------------------------
        '/admin/about'                  => ['Controllers\AboutController', 'adminIndex', 'Auth'],
        '/admin/about/edit'             => ['Controllers\AboutController', 'edit', 'Auth'],
        '/admin/about/team/add'         => ['Controllers\AboutController', 'addTeamMember', 'Auth'],
        '/admin/about/team/edit'        => ['Controllers\AboutController', 'editTeamMember', 'Auth'],

        // ---------------------- SERVICES (ADMIN) ------------------------------
        '/admin/services'           => ['Controllers\AdminController', 'listServices', 'Auth'],
        '/admin/services/add'       => ['Controllers\AdminController', 'showEditForm', 'Auth'],
        '/admin/services/edit'      => ['Controllers\AdminController', 'showEditForm', 'Auth'],

        // ---------------------- PACKAGES (ADMIN) ------------------------------
        '/admin/packages'           => ['Controllers\AdminPackageController', 'listPackages', 'Auth'],
        '/admin/packages/add'       => ['Controllers\AdminPackageController', 'showEditForm', 'Auth'],
        '/admin/packages/edit'      => ['Controllers\AdminPackageController', 'showEditForm', 'Auth'],

        // ---------------------- USERS (ADMIN) ---------------------------------
        '/admin/users'              => ['Controllers\AdminController', 'listUsers', 'Auth'],
        '/admin/users/add'          => ['Controllers\AdminController', 'showUserEditForm', 'Auth'],
        '/admin/users/edit'         => ['Controllers\AdminController', 'showUserEditForm', 'Auth'],

        // ====================================================================
        // CLIENT ROUTES (is_admin = 0)
        // ====================================================================

        '/client/dashboard'         => ['Controllers\ClientController', 'index', 'Auth'],
        '/client'                   => ['Controllers\ClientController', 'index', 'Auth'],

        // ---------------------- PROJECT MANAGEMENT (CLIENT) -------------------
        '/client/projects'          => ['Controllers\ProjectController', 'index', 'Auth'],
        '/client/projects/create'   => ['Controllers\ProjectController', 'create', 'Auth'],
        '/client/projects/edit/{id}' => ['Controllers\ProjectController', 'edit', 'Auth'],
        '/client/projects/{id}'     => ['Controllers\ProjectController', 'show', 'Auth'],

        // ====================================================================
        // PROJECT DATA (CLIENT) — NEW CLEAN ROUTES
        // Folder: views/client/projectData/
        // ====================================================================

        '/client/project-data'                      => ['Controllers\ProjectDataController', 'index', 'Auth'],
        '/client/project-data/create/{projectId}'   => ['Controllers\ProjectDataController', 'createForm', 'Auth'],
        '/client/project-data/{projectId}'          => ['Controllers\ProjectDataController', 'show', 'Auth'],
        '/client/project-data/{projectId}/edit'     => ['Controllers\ProjectDataController', 'editForm', 'Auth'],

        // OLD ROUTES REMOVED:
        // /project/onboarding/{projectId}
        // /project/edit/{projectId}
        // /project/data/{projectId}
    ],


    // ========================================================================
    // POST ROUTES
    // ========================================================================
    'POST' => [

        // --- Front-End Contact Form ---
        '/contact'                      => ['Controllers\ContactController', 'submitForm', ''],

        // --- Get-Started Form Submission ---
        // 🚨 FIX: Updated route name to match client-side JS URL (onboarding-submit)
        '/onboarding-submit'            => ['Controllers\GetStartedController', 'submit', ''],

        // --- Authentication ---
        '/login'                        => ['Controllers\AdminController', 'processLogin', ''],

        // ---------------------- PROJECT MANAGEMENT (CLIENT POST) --------------
        '/client/projects/store'        => ['Controllers\ProjectController', 'store', 'Auth'],
        '/client/projects/update/{id}'  => ['Controllers\ProjectController', 'update', 'Auth'],
        '/client/projects/destroy/{id}' => ['Controllers\ProjectController', 'destroy', 'Auth'],

        // ====================================================================
        // PROJECT DATA (CLIENT POST)
        // ====================================================================
        '/client/project-data/store/{projectId}'   => ['Controllers\ProjectDataController', 'store', 'Auth'],
        '/client/project-data/update/{projectId}'  => ['Controllers\ProjectDataController', 'update', 'Auth'],

        // ---------------------- PROJECT MANAGEMENT (ADMIN POST) ---------------
        '/admin/projects/store'         => ['Controllers\AdminProjectController', 'store', 'Auth'],
        '/admin/projects/update/{id}'   => ['Controllers\AdminProjectController', 'update', 'Auth'],
        '/admin/projects/destroy/{id}'  => ['Controllers\AdminProjectController', 'destroy', 'Auth'],

        // ---------------------- PROJECT DATA (ADMIN POST) ---------------------
        '/admin/projects/data/save'     => ['Controllers\ProjectDataController', 'save', 'Auth'],
        '/admin/projects/data/delete'   => ['Controllers\ProjectDataController', 'delete', 'Auth'],

        // ---------------------- SERVICES (ADMIN POST) -------------------------
        '/admin/services/save'          => ['Controllers\AdminController', 'saveService', 'Auth'],
        '/admin/services/delete'        => ['Controllers\AdminController', 'deleteService', 'Auth'],

        // ---------------------- PACKAGES (ADMIN POST) -------------------------
        '/admin/packages/save'          => ['Controllers\AdminPackageController', 'savePackage', 'Auth'],
        '/admin/packages/delete'        => ['Controllers\AdminPackageController', 'deletePackage', 'Auth'],

        // ---------------------- USERS (ADMIN POST) ----------------------------
        '/admin/users/save'             => ['Controllers\AdminController', 'saveUser', 'Auth'],
        '/admin/users/delete'           => ['Controllers\AdminController', 'deleteUser', 'Auth'],

        // ---------------------- ABOUT (ADMIN POST) ----------------------------
        '/admin/about/save'             => ['Controllers\AboutController', 'save', 'Auth'],
        '/admin/about/team/save'        => ['Controllers\AboutController', 'saveTeamMember', 'Auth'],
        '/admin/about/team/delete'      => ['Controllers\AboutController', 'deleteTeamMember', 'Auth'],
    ]
];

return $routes;