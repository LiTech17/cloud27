<?php
// index.php - The Front Controller

// Start session immediately for authentication management in Phase 4/5
session_start();

// 1. ARCHITECTURAL SETUP: Define Constants
define('APP_ROOT', __DIR__);
define('VIEW_PATH', APP_ROOT . '/views/');

// Define the root URL path for use in HTML links (e.g., /cloud27-project)
// This fixes the 404 issue when clicking navigation links in a sub-directory setup.
$baseDir = dirname($_SERVER['SCRIPT_NAME']);
define('BASE_PATH', $baseDir === '/' || $baseDir === '\\' ? '' : $baseDir); 

// =========================================================================
// NEW: Load Environment Variables (.env) for secure configuration
// =========================================================================
(function () {
    $path = APP_ROOT . '/.env';
    if (!file_exists($path)) {
        // Log a fatal error if the environment file is missing
        error_log("FATAL: .env file not found at $path");
        http_response_code(500);
        die("<h1>Configuration Error</h1><p>The application is missing its configuration file (.env).</p>");
    }

    // FIX APPLIED HERE: Replaced undefined constants with integer value 4.
    // 4 is the value for FILE_IGNORE_EMPTY_LINES, which is sufficient.
    $lines = file($path, 4); // <-- FIX

    foreach ($lines as $line) {
        $line = trim($line);
        // Ignore comments or lines without an '='
        if (str_starts_with($line, '#') || strpos($line, '=') === false) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        // Trim surrounding quotes/whitespace from the value
        $value = trim($value, " \t\n\r\0\x0B\"'"); 

        // Set variables to global $_ENV superglobal
        if (!array_key_exists($key, $_SERVER) && !array_key_exists($key, $_ENV)) {
            $_ENV[$key] = $value;
        }
    }
})();
// =========================================================================


// =========================================================================
// ADDED: PHPMailer MANUAL INCLUSION (Phase 2)
// Since we are not using Composer autoloading for the library, we manually 
// include the necessary core files.
require_once APP_ROOT . '/PHPMailer/src/Exception.php';
require_once APP_ROOT . '/PHPMailer/src/PHPMailer.php';
require_once APP_ROOT . '/PHPMailer/src/SMTP.php';

// Define PHPMailer namespaces for global access throughout the application
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
// =========================================================================


// 2. AUTOLOADER: Simple Autoloader for 'src' folder
spl_autoload_register(function ($class) {
    // Converts namespaced class (e.g., 'Controllers\HomeController') to file path
    $file = APP_ROOT . '/src/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});


// 3. CONFIGURATION: Load Routes
require_once APP_ROOT . '/config/routes.php';



// 4. DISPATCH: Get URI and Run Router
// Get the requested URI and remove any query string parameters (?...)
$uri = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET'; // Get the request method

try {
    // Instantiate the Router
    $router = new Router($routes);
    
    // =========================================================================
    // MODIFIED DISPATCH LOGIC: Handle POST requests for contact form
    // =========================================================================
    $routeKey = $uri;
    
    // Check for the specific POST route: /contact
    if ($routeKey === '/contact' && $method === 'POST') {
        
        // Explicitly set the target for the POST request
        $controllerClass = 'Controllers\ContactController';
        $methodToCall = 'submitForm';
        
        if (class_exists($controllerClass) && method_exists($controllerClass, $methodToCall)) {
             $controller = new $controllerClass();
             $controller->$methodToCall(); // Call the POST handler
        } else {
             // Fallback error if the expected handler is missing
             // We use the FQN for ErrorController since we are outside a namespace
             (new \Controllers\ErrorController())->show404('Internal Error: Contact handler missing.');
        }

    } else {
        // Use the default Router dispatch for all other GET routes
        $router->dispatch($uri);
    }
    // =========================================================================

} catch (Exception $e) {
    // Basic Global Error Handling (for unexpected application exceptions)
    error_log("Unhandled Exception: " . $e->getMessage());
    http_response_code(500);
    echo "<h1>Internal Server Error (500)</h1><p>An unexpected error occurred.</p>";
}