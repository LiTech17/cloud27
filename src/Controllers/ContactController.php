<?php
// src/Controllers/ContactController.php
namespace Controllers; // <-- ADDED

class ContactController extends BaseController {
    
    /**
     * Handles the GET request for the /contact route.
     * Shows the contact form to the user.
     */
    public function showForm(): void {
        $data = [
            'pageTitle' => 'Contact Us | Start Your Project'
        ];
        $this->render('contact', $data);
    }

    /**
     * Handles the POST request for the /contact route (to be implemented in Phase 2).
     */
    public function submitForm(): void {
        // Placeholder for Phase 2 implementation
        $data = [
            'pageTitle' => 'Contact Us | Processing...',
            'message' => 'Processing logic coming in Phase 2!'
        ];
        $this->render('contact', $data);
    }
}