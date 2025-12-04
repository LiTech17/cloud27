<?php
// src/Services/ValidationService.php

namespace Services;

/**
 * ValidationService handles the cleaning and validation of incoming
 * user data from the Get Started form.
 */
class ValidationService
{
    /**
     * Cleans and validates the data from the multi-step form.
     * * @param array $data Raw $_POST data.
     * @return array Contains 'errors' (array) and 'sanitized_data' (array).
     */
    public function validateOnboardingForm(array $data): array
    {
        $errors = [];
        $sanitized = [];

        // Example: Step 1: Business Profile
        $companyName = trim($data['company_name'] ?? '');
        if (empty($companyName)) {
            $errors['company_name'] = 'Company Name is required.';
        }
        $sanitized['company_name'] = htmlspecialchars($companyName, ENT_QUOTES, 'UTF-8');
        
        $sanitized['products_services'] = htmlspecialchars(trim($data['products_services'] ?? ''), ENT_QUOTES, 'UTF-8');
        $sanitized['industry'] = htmlspecialchars(trim($data['industry'] ?? ''), ENT_QUOTES, 'UTF-8');
        $sanitized['usp'] = htmlspecialchars(trim($data['usp'] ?? ''), ENT_QUOTES, 'UTF-8');
        $sanitized['competitors'] = htmlspecialchars(trim($data['competitors'] ?? ''), ENT_QUOTES, 'UTF-8');

        // Example: Step 2: Contact Details
        $sanitized['rep_name'] = htmlspecialchars(trim($data['rep_name'] ?? ''), ENT_QUOTES, 'UTF-8');
        $sanitized['rep_role'] = htmlspecialchars(trim($data['rep_role'] ?? ''), ENT_QUOTES, 'UTF-8');
        
        $repEmail = trim($data['rep_email'] ?? '');
        if (!filter_var($repEmail, FILTER_VALIDATE_EMAIL)) {
            $errors['rep_email'] = 'A valid email is required.';
        }
        $sanitized['rep_email'] = filter_var($repEmail, FILTER_SANITIZE_EMAIL); 
        
        $sanitized['rep_phone'] = htmlspecialchars(trim($data['rep_phone'] ?? ''), ENT_QUOTES, 'UTF-8');
        $sanitized['rep_whatsapp'] = htmlspecialchars(trim($data['rep_whatsapp'] ?? ''), ENT_QUOTES, 'UTF-8');
        $sanitized['comm_method'] = htmlspecialchars(trim($data['comm_method'] ?? ''), ENT_QUOTES, 'UTF-8');

        // Step 3: Branding
        $sanitized['brand_colors'] = htmlspecialchars(trim($data['brand_colors'] ?? ''), ENT_QUOTES, 'UTF-8');
        $sanitized['fonts'] = htmlspecialchars(trim($data['fonts'] ?? ''), ENT_QUOTES, 'UTF-8');

        // Step 4: Project Data
        $projectTitle = trim($data['project_title'] ?? '');
        if (empty($projectTitle)) {
            $errors['project_title'] = 'Project Title is required.';
        }
        $sanitized['project_title'] = htmlspecialchars($projectTitle, ENT_QUOTES, 'UTF-8');

        $sanitized['tech_req'] = htmlspecialchars(trim($data['tech_req'] ?? ''), ENT_QUOTES, 'UTF-8');
        $sanitized['additional_notes'] = htmlspecialchars(trim($data['additional_notes'] ?? ''), ENT_QUOTES, 'UTF-8');

        // Arrays
        $sanitized['objectives'] = $data['objectives'] ?? [];
        $sanitized['pages'] = $data['pages'] ?? [];
        $sanitized['features'] = $data['features'] ?? [];
        
        // Map features to addons for QuoteService
        // QuoteService expects 'addons' key with array of feature keys
        $sanitized['addons'] = $sanitized['features'];
        
        // Check for required confirmation field
        if (!isset($data['confirmation']) || $data['confirmation'] !== '1') {
            $errors['confirmation'] = 'You must confirm the information is correct.';
        }
        
        return [
            'errors' => $errors,
            'sanitized_data' => $sanitized,
        ];
    }
}