<?php
// index.php - The Front Controller

// 1. ARCHITECTURAL SETUP: Define Constants
// APP_ROOT: The root directory of the application
define('APP_ROOT', __DIR__);
// VIEW_PATH: Path to the views folder, used by BaseController::render()
define('VIEW_PATH', APP_ROOT . '/views/');

// 2. AUTOLOADER: Simple Autoloader for 'src' folder
// This avoids using 'require_once' for every class and enables basic autoloading.
spl_autoload_register(function ($class) {
    // Convert the class name (e.g., 'Router' or 'Controllers\HomeController') 
    // to a file path (e.g., 'src/Router.php' or 'src/Controllers/HomeController.php')
    $file = APP_ROOT . '/src/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// 3. CONFIGURATION: Load Routes
// The $routes array will be defined in this file.
require_once APP_ROOT . '/config/routes.php';

// 4. DISPATCH: Get URI and Run Router
// Get the requested URI and remove any query string parameters (?...)
$uri = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');

try {
    // Instantiate and run the Router with the defined routes map
    $router = new Router($routes);
    $router->dispatch($uri);

} catch (Exception $e) {
    // Basic Global Error Handling (for unexpected application exceptions)
    error_log("Unhandled Exception: " . $e->getMessage());
    http_response_code(500);
    echo "<h1>Internal Server Error (500)</h1><p>An unexpected error occurred.</p>";
}