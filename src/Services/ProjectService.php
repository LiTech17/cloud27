<?php
// src/Services/ProjectService.php

namespace Services;

use Models\ProjectModel;
use Models\OnboardingDetailsModel;
use Models\UserModel;
use Services\FileService;
use Services\EmailService;
use Database;
use Exception;

/**
 * ProjectService handles the business logic for creating and managing projects.
 * It orchestrates the interaction between Models, FileService, and EmailService.
 */
class ProjectService
{
    private ProjectModel $projectModel;
    private OnboardingDetailsModel $onboardingDetailsModel;
    private UserModel $userModel;
    private FileService $fileService;
    private EmailService $emailService;

    public function __construct()
    {
        $this->projectModel = new ProjectModel();
        $this->onboardingDetailsModel = new OnboardingDetailsModel();
        $this->userModel = new UserModel();
        $this->fileService = new FileService();
        $this->emailService = new EmailService();
    }

    /**
     * Orchestrates the full onboarding process:
     * 1. Resolves or creates the client user.
     * 2. Creates the project record.
     * 3. Uploads files.
     * 4. Saves detailed onboarding data.
     * 5. Sends notification emails.
     * 
     * @param array $sanitizedData Validated form data.
     * @param array $quoteData Calculated quote data.
     * @param array $fileData $_FILES array.
     * @return array Result containing success status, project_id, and any error messages.
     */
    public function createProjectFromOnboarding(array $sanitizedData, array $quoteData, array $fileData): array
    {
        $db = Database::getInstance();
        $db->beginTransaction();

        try {
            // 1. Resolve or Create Client User
            $clientId = $this->resolveOrCreateClient($sanitizedData['rep_email'], $sanitizedData['rep_name']);

            // 2. Create Project Record
            $projectPayload = [
                'client_id' => $clientId,
                'title' => $sanitizedData['project_title'],
                'description' => $sanitizedData['additional_notes'] ?? null,
                'package_name' => $quoteData['package_name'] ?? 'Custom',
                'status' => 'New',
                'budget' => $quoteData['total_once_off'] ?? 0,
            ];

            $projectId = $this->projectModel->createProjectAndReturnId($projectPayload);

            if (!$projectId) {
                throw new Exception("Failed to create project record.");
            }

            // 3. Process File Uploads (SKIPPED - Handled later)
            // Files are no longer collected during onboarding to simplify the process.
            $logoPath = null;
            $assetsPaths = [];
            
            // 4. Save Onboarding Details
            $detailsData = array_merge($sanitizedData, [
                'package_name' => $quoteData['package_name'],
                'budget_estimate' => (float) $quoteData['total_once_off'],
                'logo_path' => null,
                'assets_paths' => json_encode([]),
                'quote_json' => json_encode($quoteData)
            ]);

            $this->onboardingDetailsModel->saveDetails($projectId, $clientId, $detailsData);

            $db->commit();

            // 5. Send Emails (After commit to ensure data is safe)
            try {
                $emailData = array_merge($sanitizedData, [
                    'project_id' => $projectId,
                    'quote' => $quoteData,
                ]);
                $this->emailService->sendClientQuoteConfirmation($emailData);
                $this->emailService->sendAdminNotification($emailData);
            } catch (\Throwable $e) {
                error_log("Email sending failed: " . $e->getMessage());
                // Do not fail the request if email fails
            }

            return [
                'success' => true,
                'project_id' => $projectId,
                'client_id' => $clientId
            ];

        } catch (Exception $e) {
            $db->rollBack();
            error_log("ProjectService Error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Resolves an existing client or creates a new one.
     */
    private function resolveOrCreateClient(string $email, string $name): int
    {
        // 1. Check if user exists
        $user = $this->userModel->findBy('email', $email);
        if ($user) {
            return (int)$user['id'];
        }

        // 2. Create new user
        $baseUsername = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', explode('@', $email)[0] . substr($name, 0, 1)));
        $username = $baseUsername;
        $counter = 1;
        while ($this->userModel->usernameExists($username)) {
            $username = $baseUsername . $counter++;
        }

        $password = bin2hex(random_bytes(10)); // Random initial password
        
        $newClientData = [
            'username' => $username,
            'email' => $email,
            'password' => $password, // UserModel handles hashing
            'is_admin' => 0
        ];

        $result = $this->userModel->createUser($newClientData);

        if (is_array($result) && isset($result['error'])) {
            throw new Exception("Failed to create client: " . $result['error']);
        }

        if (!$result) {
            throw new Exception("Failed to create client user.");
        }

        return (int)$result;
    }
}
