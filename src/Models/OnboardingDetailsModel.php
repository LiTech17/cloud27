<?php
// src/Models/OnboardingDetailsModel.php

namespace Models;

/**
 * OnboardingDetailsModel handles storing the detailed survey data 
 * collected during the client's "Get Started" process.
 * * This data includes Business Profile (Step 1), Contact Details (Step 2),
 * Branding Materials (Step 3), and Project Data specifics (Step 4).
 */
class OnboardingDetailsModel extends BaseModel
{
    /** @var string The database table for onboarding details */
    protected string $table = 'onboarding_details';

    /** @var array Columns allowed for mass assignment */
    private array $allowedFields = [
        'project_id',
        'client_id',
        'project_title',
        'package_name',
        'budget_estimate',
        'company_name',
        'products_services',
        'mission_statement',
        'industry',
        'usp',
        'competitors',
        'rep_name',
        'rep_role',
        'rep_email',
        'rep_phone',
        'rep_whatsapp',
        'comm_method',
        'brand_colors',
        'fonts',
        'objectives', // JSON
        'pages',      // JSON
        'features',   // JSON
        'tech_req',
        'additional_notes',
        'logo_path',
        'assets_paths',
        'quote_json'
    ];

    /**
     * Stores the comprehensive onboarding data linked to a newly created project.
     * * @param int $projectId The ID of the Project record.
     * @param int $clientId The ID of the User (Client) record.
     * @param array $data All sanitized input data from the form.
     * @return bool Success status (true on success, false on failure).
     */
    public function saveDetails(int $projectId, int $clientId, array $data): bool
    {
        // 1. Prepare data structure by filtering to allowed keys
        $detailsData = $this->filterAllowed($data);

        // 2. Add required IDs
        $detailsData['project_id'] = $projectId;
        $detailsData['client_id'] = $clientId;

        // 3. Convert array fields (checkbox groups) to JSON strings for database storage
        $detailsData['objectives'] = json_encode($detailsData['objectives'] ?? []);
        $detailsData['pages']      = json_encode($detailsData['pages'] ?? []);
        $detailsData['features']   = json_encode($detailsData['features'] ?? []);
        
        // 4. Filter again to ensure only allowed fields are passed to insert 
        // (important if BaseModel::insert doesn't handle extra fields gracefully)
        $finalData = array_intersect_key($detailsData, array_flip($this->allowedFields));
        
        // 5. Insert into the onboarding_details table
        $success = $this->insert($finalData);
        
        if (!$success) {
            // Log a detailed error if insertion fails
            error_log("Failed to insert onboarding details for Project ID {$projectId}. Data: " . json_encode($finalData));
        }

        return $success;
    }
    
    /**
     * Filter input data to allowed fields only
     * * @param array $data Input data
     * @return array Filtered data
     */
    private function filterAllowed(array $data): array
    {
        // Use array_flip to use allowed fields as keys for efficient intersection
        return array_intersect_key($data, array_flip($this->allowedFields));
    }
}