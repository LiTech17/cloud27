<?php
// src/Controllers/BaseController.php

namespace Controllers; // <-- ADDED: Defines the namespace for all inheriting controllers

abstract class BaseController {
    /**
     * Renders a view file by including the header, the main content view, and the footer.
     * * @param string $viewFileName The name of the view file without the .php extension (e.g., 'home').
     * @param array $data Data to be made available to the view files (e.g., ['pageTitle' => 'Home']).
     */
    protected function render(string $viewFileName, array $data = []): void {
        // Extract the $data array variables into the local symbol table.
        extract($data);

        $viewPath = VIEW_PATH . $viewFileName . '.php';

        if (!file_exists($viewPath)) {
            // If the requested view file doesn't exist, log an error and show the 404 page.
            error_log("View file missing: {$viewFileName}.php");
            // FIXED: Since BaseController is now in the Controllers namespace, 
            // it can refer to ErrorController directly.
            (new ErrorController())->show404("View file not found: {$viewFileName}.php"); 
            return;
        }

        // Start output buffering to capture all included files before sending to browser
        ob_start();

        // 1. Include Header Partial (Shared structure and CSS)
        require_once VIEW_PATH . '_partials/Header.php';

        // 2. Include Main View Content (The page-specific HTML)
        require_once $viewPath;

        // 3. Include Footer Partial (Closing tags and scripts)
        require_once VIEW_PATH . '_partials/Footer.php';

        // Send the complete buffered content to the client
        echo ob_get_clean();
    }
}