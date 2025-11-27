<?php
// src/Controllers/GetStartedController.php

namespace Controllers;

use Models\GetStartedModel; 
use Exception;
// Removed "use EmailService;" here, assuming it's correctly autoloaded or namespaced below.
// If EmailService is in the global namespace, the '\EmailService' call in the submit method is correct.

/**
 * GetStartedController manages the multi-step onboarding process, 
 * orchestrating the validation, quotation calculation, final persistence, and email notification.
 */
class GetStartedController extends BaseController 
{
    private GetStartedModel $getStartedModel;

    public function __construct()
    {
        $this->getStartedModel = new GetStartedModel();
    }

    /**
     * Display the multi-step onboarding form view. 
     */
    public function index(): void
    {
        // Data to pass to the view
        $data = [
            'initialMessage' => $_SESSION['onboarding_message'] ?? '',
            'statusMessage' => $_SESSION['status_message'] ?? '',
            'statusType' => $_SESSION['status_type'] ?? 'alert-info'
        ];

        // Clear session messages after displaying
        unset($_SESSION['onboarding_message'], $_SESSION['status_message'], $_SESSION['status_type']);

        // Calling the render method inherited from BaseController
        $this->render('get-started', $data); 
    }

    /**
     * Handles the final form submission for the onboarding process via AJAX.
     */
    public function submit(): void
    {
        error_log("ONBOARDING LOG: Submission started.");

        // Enforce POST request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            error_log("ONBOARDING LOG: Method not POST. Sending 405 response.");
            $this->jsonResponse(405, ['success' => false, 'message' => 'Method Not Allowed.']);
        }

        // Initialize sanitized data for error logging outside the try block
        $sanitizedData = [];
        
        // --- 1. Delegate Validation and Sanitation ---
        try {
            $validationResult = $this->getStartedModel->validateAndSanitize($_POST);
            
            $errors = $validationResult['errors'];
            $sanitizedData = $validationResult['sanitized_data'];
            $fileData = $_FILES; 
    
            if (!empty($errors)) {
                error_log("ONBOARDING LOG: Validation failed. Sending 422 response.");
                $this->jsonResponse(422, [
                    'success' => false, 
                    'message' => 'Please correct the following errors.', 
                    'errors' => $errors
                ]);
            }
            
            error_log("ONBOARDING LOG: Validation PASSED. Starting core business logic.");

            // 2. Delegate Quote Calculation
            error_log("ONBOARDING LOG: Calculating quote...");
            $quote = $this->getStartedModel->calculateQuote($sanitizedData);
            error_log("ONBOARDING LOG: Quote calculated successfully.");

            // 3. Delegate Saving (Data + Files)
            error_log("ONBOARDING LOG: Attempting to save data and files...");
            $newProjectId = $this->getStartedModel->saveOnboardingData($sanitizedData, $quote, $fileData);
            
            if (!$newProjectId) {
                // Catches model logic failure that returns false instead of throwing
                error_log("ONBOARDING LOG: CRITICAL ERROR - Database failed to save the project record (Model returned false).");
                throw new Exception("Database failed to save the project record.");
            }
            
            error_log("ONBOARDING LOG: Project saved successfully. ID: {$newProjectId}.");
            
            // ================================================================
            // 4. EMAIL LOGIC START - Wrapped in its own try/catch to ensure
            //    submission success is returned even if email fails.
            // ================================================================
            
            try {
                $emailService = new \EmailService(); 
                
                $emailData = array_merge($sanitizedData, [
                    'project_id' => $newProjectId,
                    'quote' => $quote
                ]);
                
                // 5. Send Admin Notification Email (New Lead)
                [$adminSuccess, $adminError] = $emailService->sendAdminNewProjectEmail($emailData);
                if (!$adminSuccess) {
                    error_log("CRITICAL: Admin Email Failed for Project ID {$newProjectId}: " . ($adminError ?? 'Unknown SMTP error'));
                }
                
                // 6. Send Client Auto-Reply with Quote Confirmation
                [$clientSuccess, $clientError] = $emailService->sendClientQuoteConfirmation($emailData);
                if (!$clientSuccess) {
                    error_log("Client Email Failed for Project ID {$newProjectId}: " . ($clientError ?? 'Unknown SMTP error'));
                }
            } catch (Exception $emailE) {
                error_log("WARNING: Email Service Exception for Project ID {$newProjectId}: " . $emailE->getMessage());
            }
            
            // ================================================================
            // EMAIL LOGIC END
            // ================================================================

            // Success Response (using inherited jsonResponse)
            error_log("ONBOARDING LOG: Sending 201 success response.");
            $this->jsonResponse(201, [
                'success' => true, 
                'message' => '🎉 Project submitted and quotation generated successfully! A confirmation and quote summary have been sent to your email.',
                'project_id' => $newProjectId,
                'quote' => $quote
            ]);

        } catch (Exception $e) {
            // 🚨 ENHANCED DEBUGGING CATCH BLOCK 🚨
            $userEmail = $sanitizedData['rep_email'] ?? 'unknown user';
            error_log("ONBOARDING LOG: CATCH BLOCK ACTIVATED. Submission failed for {$userEmail}: " . $e->getMessage());
            
            // Error Response (using inherited jsonResponse)
            $this->jsonResponse(500, [
                'success' => false, 
                // Display a generic message to the user/client
                'message' => 'An internal server error occurred during submission. Please contact support.', 
                'errors' => ['general' => 'Internal server error.'],
                // CRITICAL FOR DEBUGGING: Include the exact exception message.
                'internal_error' => $e->getMessage() 
            ]);
        }
    }
}