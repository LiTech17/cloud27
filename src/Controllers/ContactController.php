<?php
// src/Controllers/ContactController.php
namespace Controllers; 

use Models\PackageModel; // Import PackageModel

class ContactController extends BaseController {
    
    /**
     * Handles the GET request for the /contact route.
     * Shows the contact form to the user and handles package pre-selection.
     */
    public function showForm(): void {
        // Use the null coalescing operator (??) to safely check if $_GET['status'] exists.
        $status = $_GET['status'] ?? '';
        $packageId = $_GET['package_id'] ?? null; // Check for package ID
        $initialMessage = '';

        // Handle pre-filling the message if a package is selected
        if ($packageId && is_numeric($packageId)) {
            $packageModel = new PackageModel();
            
            // FIX: Changed 'getById' to the correct method name 'getPackageById'
            $package = $packageModel->getPackageById((int)$packageId); 
            
            if ($package) {
                // Construct the helpful initial message
                // Using null coalescing operator for safe access to array keys
                $title = htmlspecialchars($package['title'] ?? 'Selected Package');
                $price = number_format($package['price_base'] ?? 0, 2);

                $initialMessage = "I would like to get a quote for the '{$title}' package (Base Price: R{$price}). Please provide details on how to proceed with this plan.";
            }
        }

        $data = [
            'pageTitle' => 'Contact Us | Start Your Project',
            'statusMessage' => $status === 'success' ? 'Your form was submitted successfully (via manual redirect).' : '', 
            'statusType' => 'alert-success',
            'initialMessage' => $initialMessage // Pass the pre-filled message to the view
        ];
        $this->render('contact', $data);
    }

    /**
     * Handles the POST request via AJAX and returns a JSON response.
     */
    public function submitForm(): void {
        
        // CRITICAL: Set headers for JSON response
        header('Content-Type: application/json');

        // 1. Basic POST Data Check
        if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['message'])) {
            echo json_encode(['success' => false, 'message' => 'Please fill out all required fields (Name, Email, Message).']);
            return;
        }

        // 2. Sanitize and Validate Data
        $formData = [
            'name' => htmlspecialchars(trim($_POST['name'])),
            'email' => filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL),
            'message' => htmlspecialchars(trim($_POST['message']))
        ];

        if (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'The email address provided is invalid.']);
            return;
        }

        // 3. Instantiate Email Service (Autoloader handles the class)
        $emailService = new \EmailService(); 

        // 4. Send Admin Notification Email
        [$adminSuccess, $adminError] = $emailService->sendAdminEmail($formData);

        if (!$adminSuccess) {
            // Log the detailed error for backend investigation
            error_log("Admin Email Failed: " . ($adminError ?? 'Unknown SMTP error during form submission'));
            
            // Return a generic failure message to the client
            echo json_encode(['success' => false, 'message' => 'An error occurred while sending your request. Please check your SMTP settings if this persists.']);
            return;
        }
        
        // 5. Send Client Auto-Reply Email (Log error silently)
        [$clientSuccess, $clientError] = $emailService->sendClientAutoReply($formData);
        if (!$clientSuccess) {
            error_log("Client Auto-Reply Failed: " . ($clientError ?? 'Unknown SMTP error'));
        }

        // 6. Success Response
        echo json_encode([
            'success' => true, 
            'message' => '🎉 Thank you! Your message has been sent successfully. We will be in touch shortly.'
        ]);
        
        // Ensure script stops here to avoid unexpected output
        exit;
    }
}