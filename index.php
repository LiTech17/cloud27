<?php
// index.php - The Front Controller

// =========================================================================
// 0. GLOBAL ERROR AND EXCEPTION HANDLER (CRITICAL FOR AJAX DEBUGGING) 🚨
// This must be at the very top to ensure it catches errors before any output,
// and forces a JSON response for easier debugging of AJAX 500 errors.
// =========================================================================

// Handler for uncaught exceptions and errors (Throwable)
set_exception_handler(function (\Throwable $e) { // Use \Throwable for maximum coverage
    // 1. Log the full error
    $logMessage = "GLOBAL UNCAUGHT THROWABLE: " . get_class($e) . "\n";
    $logMessage .= "Message: " . $e->getMessage() . "\n";
    $logMessage .= "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    $logMessage .= "Trace:\n" . $e->getTraceAsString();
    error_log($logMessage);
    
    // 2. Respond with JSON (only if headers haven't been sent)
    if (!headers_sent()) {
        // Correcting the status code assignment for better compliance
        http_response_code(500); 
        header('Content-Type: application/json');
        
        echo json_encode([
            'success' => false,
            'message' => 'A critical, uncaught server error occurred (Global Handler).',
            'errors' => ['general' => 'Uncaught system error.'],
            'internal_error' => $e->getMessage(),
            'error_file' => $e->getFile(),
            'error_line' => $e->getLine()
        ]);
        exit(1);
    }
    // If headers were sent, just stop execution
    die("A critical error occurred. Check server logs.");
});

// Handler for fatal PHP errors (e.g., memory exhaustion, compile errors)
register_shutdown_function(function() {
    $error = error_get_last();
    // Check if it's a fatal error type
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_RECOVERABLE_ERROR])) {
        
        error_log("GLOBAL FATAL PHP ERROR: Type " . $error['type'] . " | Message: " . $error['message'] . " in " . $error['file'] . " on line " . $error['line']);
        
        // Attempt to output JSON if headers haven't been sent
        if (!headers_sent()) {
            // Correcting the status code assignment
            http_response_code(500);
            header('Content-Type: application/json');
            
            echo json_encode([
                'success' => false,
                'message' => 'A fatal PHP error occurred (Shutdown Handler).',
                'internal_error' => $error['message'],
                'error_file' => $error['file'],
                'error_line' => $error['line']
            ]);
        }
    }
});


// Start session immediately for authentication management in Phase 4/5
session_start();

// 1. ARCHITECTURAL SETUP: Define Constants
define('APP_ROOT', __DIR__);
define('VIEW_PATH', APP_ROOT . '/views/');

// Define the root URL path for use in HTML links (e.g., /cloud27-project)
$baseDir = dirname($_SERVER['SCRIPT_NAME']);
define('BASE_PATH', $baseDir === '/' || $baseDir === '\\' ? '' : $baseDir); 
define('SRC_PATH', __DIR__ . '/src');

// =========================================================================
// Load Environment Variables (.env) for secure configuration
// =========================================================================
(function () {
    $path = APP_ROOT . '/.env';
    if (!file_exists($path)) {
        error_log("FATAL: .env file not found at $path");
        http_response_code(500);
        die("<h1>Configuration Error</h1><p>The application is missing its configuration file (.env).</p>");
    }

    $lines = file($path, 4); // 4 is FILE_IGNORE_EMPTY_LINES

    foreach ($lines as $line) {
        $line = trim($line);
        if (str_starts_with($line, '#') || strpos($line, '=') === false) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"'"); 

        if (!array_key_exists($key, $_SERVER) && !array_key_exists($key, $_ENV)) {
            $_ENV[$key] = $value;
        }
    }
})();
// =========================================================================


// =========================================================================
// ADDED: PHPMailer Manual Inclusion & ALIASING for Exception Conflict ⚠️
// =========================================================================
require_once APP_ROOT . '/PHPMailer/src/Exception.php';
require_once APP_ROOT . '/PHPMailer/src/PHPMailer.php';
require_once APP_ROOT . '/PHPMailer/src/SMTP.php';

// Define PHPMailer namespaces, but ALIAS the Exception class to avoid conflict 
// with PHP's native \Exception and the autoloader loading the Router class.
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException; // <-- CRITICAL FIX
use PHPMailer\PHPMailer\SMTP;
// =========================================================================


// ------------------------------------------------
// 2. Autoloading (Corrected & Robust Mapping to SRC_PATH)
// ------------------------------------------------
/**
 * Simple Autoloader: Automatically includes classes based on their namespace.
 */
spl_autoload_register(function ($className) {
    
    // Check for global classes like 'Router' which might not have a namespace.
    // If the class name doesn't contain a namespace separator, assume it's directly in src/.
    $fileName = str_contains($className, '\\') 
        ? str_replace('\\', DIRECTORY_SEPARATOR, $className) 
        : $className; // Use raw name for root classes (e.g., 'Router')

    // 2. Construct the file path using the defined SRC_PATH constant
    $filePath = SRC_PATH . '/' . $fileName . '.php'; 
    
    // 3. Include the file if it exists
    if (file_exists($filePath)) {
        require_once $filePath;
        return;
    }
});

// 3. CONFIGURATION: Load Routes
require_once APP_ROOT . '/config/routes.php';


// 4. DISPATCH: Get URI and Run Router (SIMPLIFIED)
// Get the requested URI and remove any query string parameters (?...)
$uri = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');

try {
    // Instantiate the Router
    // NOTE: Router class must be defined in src/Router.php
    $router = new Router($routes);
    
    // Delegate ALL routing (GET and POST) to the Router class
    $router->dispatch($uri);

} catch (\Throwable $e) { // Use \Throwable to catch Errors and Exceptions
    // This is the least likely catch to run due to the global handler, 
    // but it remains for completeness and non-AJAX responses.
    error_log("Router Dispatch Exception: " . $e->getMessage());
    http_response_code(500);
    echo "<h1>Internal Server Error (500)</h1><p>An unexpected error occurred during dispatch.</p>";
}