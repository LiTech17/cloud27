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
define('SRC_PATH', __DIR__ . '/src');

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
    $lines = file($path, 4); 

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
require_once APP_ROOT . '/PHPMailer/src/Exception.php';
require_once APP_ROOT . '/PHPMailer/src/PHPMailer.php';
require_once APP_ROOT . '/PHPMailer/src/SMTP.php';

// Define PHPMailer namespaces for global access throughout the application
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
// =========================================================================


// ------------------------------------------------
// 2. Autoloading (Corrected & Robust Mapping to SRC_PATH)
// ------------------------------------------------
/**
 * Simple Autoloader: Automatically includes classes based on their namespace.
 * Maps 'Controllers\AboutController' to 'src/Controllers/AboutController.php'
 * and 'Router' (if un-namespaced) to 'src/Router.php'.
 */
spl_autoload_register(function ($className) {
    
    // 1. Convert namespace separator (\) to directory separator (/)
    // This maps 'Controllers\AboutController' to 'Controllers/AboutController'
    $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $className);

    // 2. Construct the file path using the defined SRC_PATH constant
    // e.g., 'src/Controllers/AboutController.php'
    $filePath = SRC_PATH . '/' . $fileName . '.php'; 
    
    // 3. Include the file if it exists
    if (file_exists($filePath)) {
        require_once $filePath;
        return;
    }
    
    // Note: The previous separate fallback path is removed as it was redundant/incorrectly calculated.
    // The single path check correctly handles both namespaced classes (Models\, Controllers\) 
    // and root classes (like Router) placed directly in the 'src/' folder.
});

// 3. CONFIGURATION: Load Routes
require_once APP_ROOT . '/config/routes.php';


// 4. DISPATCH: Get URI and Run Router (SIMPLIFIED)
// Get the requested URI and remove any query string parameters (?...)
$uri = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');

try {
    // Instantiate the Router
    $router = new Router($routes);
    
    // Delegate ALL routing (GET and POST) to the Router class
    $router->dispatch($uri);

} catch (Exception $e) {
    // Basic Global Error Handling (for unexpected application exceptions)
    error_log("Unhandled Exception: " . $e->getMessage());
    http_response_code(500);
    echo "<h1>Internal Server Error (500)</h1><p>An unexpected error occurred.</p>";
}