<?php
// src/Controllers/ErrorController.php
namespace Controllers; // <-- ADDED

class ErrorController extends BaseController {

    /**
     * Handles the 404 Not Found error. 
     */
    public function show404(string $message = 'The page you requested could not be found.'): void {
        http_response_code(404);
        
        $data = [
            'pageTitle' => '404 Not Found',
            'errorMessage' => $message
        ];
        
        $this->render('404', $data);
    }
}