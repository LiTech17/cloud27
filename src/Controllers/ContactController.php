<?php
// src/Controllers/ContactController.php
namespace Controllers; 

use Models\PackageModel;
use Models\ProjectModel;
use Models\UserModel;

class ContactController extends BaseController {
    
    /**
     * Handles the GET request for the /contact route.
     * Shows the contact form to the user and handles package/project pre-selection.
     */
    public function showForm(): void {
        $status = $_GET['status'] ?? '';
        $packageId = $_GET['package_id'] ?? null;
        $projectId = $_GET['project_id'] ?? null;
        
        $initialMessage = '';
        $prefilledName = '';
        $prefilledEmail = '';

        // 1. Pre-fill User Details if Logged In
        if (isset($_SESSION['user_id'])) {
            $userModel = new UserModel();
            $user = $userModel->getUserById($_SESSION['user_id']);
            if ($user) {
                $prefilledName = $user['username']; // Or a separate 'full_name' field if you have one
                $prefilledEmail = $user['email'];
            }
        }

        // 2. Handle Package Pre-selection
        if ($packageId && is_numeric($packageId)) {
            $packageModel = new PackageModel();
            $package = $packageModel->getPackageById((int)$packageId); 
            
            if ($package) {
                $title = htmlspecialchars($package['title'] ?? 'Selected Package');
                $price = number_format($package['price_base'] ?? 0, 2);
                $initialMessage = "I would like to get a quote for the '{$title}' package (Base Price: R{$price}). Please provide details on how to proceed with this plan.";
            }
        }

        // 3. Handle Project Support Context
        if ($projectId && is_numeric($projectId)) {
            $projectModel = new ProjectModel();
            $project = $projectModel->getProjectById((int)$projectId);

            if ($project) {
                // Optional: Verify ownership if strictly private, but for support it's helpful context
                $projectTitle = htmlspecialchars($project['title']);
                $initialMessage = "Reference Project: {$projectTitle} (ID: {$projectId})\n\nI need assistance with the following:\n";
            }
        }

        $data = [
            'pageTitle' => 'Contact Us | Start Your Project',
            'statusMessage' => $status === 'success' ? 'Your form was submitted successfully (via manual redirect).' : '', 
            'statusType' => 'alert-success',
            'initialMessage' => $initialMessage,
            'prefilledName' => $prefilledName,
            'prefilledEmail' => $prefilledEmail
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