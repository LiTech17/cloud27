<?php
// config/routes.php

/**
 * Defines the application's routing map, keyed by HTTP Method.
 */
$routes = [
    'GET' => [
        '/'           => ['Controllers\HomeController', 'index'],
        '/about'      => ['Controllers\HomeController', 'about'],
        '/services'   => ['Controllers\HomeController', 'services'],
        '/contact'    => ['Controllers\ContactController', 'showForm'], // Handles page load
    ],

    'POST' => [
        // This is the CRITICAL ADDITION for form submissions!
        '/contact'    => ['Controllers\ContactController', 'submitForm'], // Handles AJAX/POST submission
    ]
];