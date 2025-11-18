<?php
// config/routes.php

/**
 * Defines the application's routing map.
 * Format: [URI => [ControllerClass (FQN), Method]]
 */
$routes = [
    // Use the Fully Qualified Name (FQN): 'Controllers\ControllerName'
    '/'          => ['Controllers\HomeController', 'index'],
    '/about'     => ['Controllers\HomeController', 'about'],
    '/services'  => ['Controllers\HomeController', 'services'],
    '/contact'   => ['Controllers\ContactController', 'showForm'], 

    // Future routes will be added here 
];