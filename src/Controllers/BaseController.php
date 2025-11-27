<?php
// src/Controllers/BaseController.php

namespace Controllers;

use Controllers\ErrorController; 
// Import the globally available Logger class
use \Logger;

/**
 * BaseController provides common methods for all controllers, 
 * such as rendering views and sending JSON responses.
 */
abstract class BaseController 
{
    
    /**
     * Renders a view file by including the header, the main content view, and the footer.
     * @param string $viewFileName The name of the view file without the .php extension (e.g., 'home').
     * @param array $data Data to be made available to the view files (e.g., ['pageTitle' => 'Home']).
     */
    protected function render(string $viewFileName, array $data = []): void {
        // Extract the $data array variables into the local symbol table.
        extract($data);

        // Assuming VIEW_PATH is defined correctly (e.g., in your application config)
        $viewPath = VIEW_PATH . $viewFileName . '.php';

        if (!file_exists($viewPath)) {
            // Log the error using the custom Logger
            Logger::error("View file missing: {$viewFileName}.php"); 
            // Use the assumed ErrorController to show a 404
            (new ErrorController())->show404("View file not found: {$viewFileName}.php"); 
            return;
        }

        // Start output buffering to capture all included files before sending to browser
        ob_start();

        // 1. Include Header Partial
        require_once VIEW_PATH . '_partials/Header.php';

        // 2. Include Main View Content
        require_once $viewPath;

        // 3. Include Footer Partial
        require_once VIEW_PATH . '_partials/Footer.php';

        // Send the complete buffered content to the client
        echo ob_get_clean();
    }
    
    /**
     * Outputs a JSON response and safely terminates script execution.
     * @param int $httpCode The HTTP status code (e.g., 200, 422, 500)
     * @param array $data The data to be encoded as JSON
     */
    protected function jsonResponse(int $httpCode, array $data): void
    {
        Logger::info("Attempting to send JSON response with HTTP code {$httpCode}.");

        // 1. Clear any unintended previous output buffered by render() or other includes.
        if (ob_get_length() > 0) {
            ob_clean();
            // Log a warning if output was cleared, as this indicates a potential issue (headers already sent)
            Logger::log('WARN', "Output buffer was cleared before setting headers. Potential prior output detected.");
        }

        // 2. Set the HTTP Response Code
        http_response_code($httpCode);

        // 3. Set the Content-Type header to application/json
        header('Content-Type: application/json; charset=UTF-8');

        // 4. Output the JSON data
        echo json_encode($data); 
        
        // Log the successful preparation before exit
        Logger::info("JSON response prepared for HTTP {$httpCode}. Calling exit.");

        // 5. CRITICAL: Terminate execution immediately to prevent any further script output
        exit;
    }
}