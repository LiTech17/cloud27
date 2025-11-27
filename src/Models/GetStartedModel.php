<?php
// src/Models/GetStartedModel.php

namespace Models;

// Assumes Core\Upload and Exception are correctly namespaced/imported
use Core\Upload;
use Exception;
use Models\UserModel; 
// CRITICAL: Ensure Database is available for transaction management
use Database; 

// Extends your provided BaseModel for DB operations
class GetStartedModel extends BaseModel 
{
    /** @var string $table The database table used for storing project/onboarding requests. */
    protected string $table = 'onboarding_details'; 

    // Define the pricing structure constants for quote calculation (UNCHANGED)
    private const PRICING = [
        'Basic' => ['base' => 1990.00, 'hosting' => 49.00, 'extra_page' => 150.00],
        'Standard' => ['base' => 3490.00, 'hosting' => 69.00, 'extra_page' => 120.00],
        'Premium' => ['base' => 4999.00, 'hosting' => 89.00, 'extra_page' => 100.00],
    ];

    private const ADDONS = [
        // Price is set to 0.00 if included in the package
        'social_media' => ['Basic' => 250.00, 'Standard' => 0.00, 'Premium' => 0.00],
        'whatsapp_button' => ['Basic' => 150.00, 'Standard' => 0.00, 'Premium' => 0.00],
        'custom_contact_form' => ['Basic' => 300.00, 'Standard' => 0.00, 'Premium' => 0.00],
        'complex_form' => ['Basic' => 600.00, 'Standard' => 600.00, 'Premium' => 0.00],
        'live_chat' => ['Basic' => 350.00, 'Standard' => 350.00, 'Premium' => 350.00],
        'ecommerce' => ['Basic' => 1999.00, 'Standard' => 1999.00, 'Premium' => 1999.00], 
        'booking_system' => ['Basic' => 700.00, 'Standard' => 700.00, 'Premium' => 700.00],
        'multi_language' => ['Basic' => 700.00, 'Standard' => 700.00, 'Premium' => 700.00],
    ];
    
    /** @var int The number of pages included in the base package price. */
    private const BASE_INCLUDED_PAGES = 5; 

    public function __construct()
    {
        // Call the parent constructor to initialize the BaseModel
        parent::__construct();
    }

    // ------------------------------------------------------------------------
    // VALIDATION & SANITIZATION (UNCHANGED)
    // ------------------------------------------------------------------------

    /**
     * Cleans and validates the data from the multi-step form.
     */
    public function validateAndSanitize(array $data): array
    {
        $errors = [];
        $sanitized = [];

        // --- Step 1: Business Profile ---
        $companyName = trim($data['company_name'] ?? '');
        if (empty($companyName)) {
            $errors['company_name'] = 'Company Name is required.';
        }
        $sanitized['company_name'] = htmlspecialchars($companyName, ENT_QUOTES, 'UTF-8');
        $sanitized['products_services'] = htmlspecialchars(trim($data['products_services'] ?? ''), ENT_QUOTES, 'UTF-8');
        $sanitized['mission_statement'] = htmlspecialchars(trim($data['mission_statement'] ?? ''), ENT_QUOTES, 'UTF-8'); 
        $sanitized['industry'] = htmlspecialchars(trim($data['industry'] ?? ''), ENT_QUOTES, 'UTF-8');
        $sanitized['usp'] = htmlspecialchars(trim($data['usp'] ?? ''), ENT_QUOTES, 'UTF-8');
        $sanitized['competitors'] = htmlspecialchars(trim($data['competitors'] ?? ''), ENT_QUOTES, 'UTF-8');
        
        // --- Step 2: Contact Details ---
        $repName = trim($data['rep_name'] ?? '');
        $repEmail = trim($data['rep_email'] ?? '');
        
        if (empty($repName)) {
            $errors['rep_name'] = 'Representative Name is required.';
        }
        if (!filter_var($repEmail, FILTER_VALIDATE_EMAIL)) {
            $errors['rep_email'] = 'A valid email is required.';
        }
        $sanitized['rep_name'] = htmlspecialchars($repName, ENT_QUOTES, 'UTF-8');
        $sanitized['rep_role'] = htmlspecialchars(trim($data['rep_role'] ?? ''), ENT_QUOTES, 'UTF-8');
        // Use filter_var to clean the email, ensuring it's safe for DB use
        $sanitized['rep_email'] = filter_var($repEmail, FILTER_SANITIZE_EMAIL); 
        $sanitized['rep_phone'] = htmlspecialchars(trim($data['rep_phone'] ?? ''), ENT_QUOTES, 'UTF-8');
        $sanitized['rep_whatsapp'] = htmlspecialchars(trim($data['rep_whatsapp'] ?? ''), ENT_QUOTES, 'UTF-8');
        $sanitized['comm_method'] = htmlspecialchars(trim($data['comm_method'] ?? ''), ENT_QUOTES, 'UTF-8');

        // --- Step 3: Branding Details ---
        $sanitized['brand_colors'] = htmlspecialchars(trim($data['brand_colors'] ?? ''), ENT_QUOTES, 'UTF-8');
        $sanitized['fonts'] = htmlspecialchars(trim($data['fonts'] ?? ''), ENT_QUOTES, 'UTF-8');
        
        // --- Step 4: Project Data ---
        if (empty($data['project_title'])) {
            $errors['project_title'] = 'Project Title is required.';
        }
        if (!isset($data['confirmation']) || $data['confirmation'] !== '1') {
            $errors['confirmation'] = 'You must confirm the information is correct.';
        }
        
        // Sanitize arrays for multiple selections (ensure array keys are strings)
        $sanitized['objectives'] = array_map('strval', $data['objectives'] ?? []);
        $sanitized['pages'] = array_map('strval', $data['pages'] ?? []);
        $sanitized['features'] = array_map('strval', $data['features'] ?? []);
        $sanitized['tech_req'] = htmlspecialchars(trim($data['tech_req'] ?? ''), ENT_QUOTES, 'UTF-8');
        $sanitized['additional_notes'] = htmlspecialchars(trim($data['additional_notes'] ?? ''), ENT_QUOTES, 'UTF-8');
        $sanitized['project_title'] = htmlspecialchars(trim($data['project_title'] ?? ''), ENT_QUOTES, 'UTF-8');
        
        return [
            'errors' => $errors,
            'sanitized_data' => $sanitized,
        ];
    }
    
    // ------------------------------------------------------------------------
    // QUOTATION LOGIC (UNCHANGED)
    // ------------------------------------------------------------------------
    
    /**
     * Determines the most appropriate package based on the project features.
     */
    private function determineBasePackage(array $selectedFeatures): string
    {
        $minPackage = 'Basic';
        
        foreach ($selectedFeatures as $feature) {
            // Skip features not defined in the ADDONS structure
            $addonPrices = self::ADDONS[$feature] ?? null;
            if (!$addonPrices) continue;

            // Upgrade to Premium if any feature is included there (0.00 cost) and minPackage is lower
            if (($addonPrices['Premium'] ?? -1) === 0.00) {
                $minPackage = 'Premium';
                break; // Highest tier, no need to check further
            }
            // Upgrade to Standard if any feature is included there and minPackage is Basic
            elseif (($addonPrices['Standard'] ?? -1) === 0.00 && $minPackage === 'Basic') {
                $minPackage = 'Standard';
            }
        }
        
        // Business Rule: Complex systems must be at least Standard
        $isComplex = in_array('ecommerce', $selectedFeatures) || in_array('booking_system', $selectedFeatures);
        if ($isComplex && $minPackage === 'Basic') {
             $minPackage = 'Standard';
        }

        return $minPackage;
    }


    /**
     * Calculates the dynamic quotation based on client input and pricing rules.
     */
    public function calculateQuote(array $formData): array
    {
        $selectedFeatures = $formData['features'] ?? [];
        $determinedPackage = $this->determineBasePackage($selectedFeatures);
        $package = self::PRICING[$determinedPackage];
        
        $basePackageCost = $package['base'];
        $hostingFee = $package['hosting'];
        $extraPageCostPer = $package['extra_page'];

        // --- Calculate Add-on Costs ---
        $totalAddonsCost = 0.00;
        $addonDetails = [];
        
        foreach ($selectedFeatures as $featureKey) {
            $addonPrices = self::ADDONS[$featureKey] ?? null;
            if (!$addonPrices) continue;

            $cost = $addonPrices[$determinedPackage];
            
            if ($cost > 0.00) {
                $totalAddonsCost += $cost;
                $addonDetails[] = ['name' => ucfirst(str_replace('_', ' ', $featureKey)), 'cost' => number_format($cost, 2, '.', '')];
            }
        }
        
        // --- Calculate Extra Page Costs ---
        $selectedPagesCount = count($formData['pages'] ?? []);
        $billablePages = max(0, $selectedPagesCount - self::BASE_INCLUDED_PAGES);
        $extraPageTotal = $billablePages * $extraPageCostPer;
        
        // --- Calculate Totals ---
        $totalOnceOff = $basePackageCost + $totalAddonsCost + $extraPageTotal;

        // Return all monetary values as strings with two decimal places for consistency
        return [
            'package_name' => $determinedPackage,
            'base_cost' => number_format($basePackageCost, 2, '.', ''),
            'hosting_pm' => number_format($hostingFee, 2, '.', ''),
            'extra_page_count' => $billablePages,
            'extra_page_total' => number_format($extraPageTotal, 2, '.', ''),
            'addon_details' => $addonDetails,
            'total_addons_cost' => number_format($totalAddonsCost, 2, '.', ''),
            'total_once_off' => number_format($totalOnceOff, 2, '.', ''),
        ];
    }
    
    // ------------------------------------------------------------------------
    // DATA PERSISTENCE & FILE UPLOAD (UPDATED)
    // ------------------------------------------------------------------------

    /**
     * Handles file uploads using the Core\Upload class.
     * (Logic remains the same)
     */
    private function processUploads(array $fileData, string $inputName, string $targetDir): array
    {
        $uploadConfig = [
            'target_dir' => 'uploads/' . $targetDir . '/',
            // Set max file size and allowed extensions for security
            'allowed_extensions' => ['png', 'jpg', 'jpeg', 'webp', 'gif', 'svg', 'pdf', 'zip'],
            'max_file_size' => 5 * 1024 * 1024, // 5MB limit
        ];
        // Assumes Core\Upload is available
        $uploader = new Upload($uploadConfig); 
        
        // Reformat $_FILES structure for single vs multiple inputs if necessary
        $files = $fileData[$inputName] ?? null;
        
        if (empty($files) || (is_array($files) && ($files['error'] === UPLOAD_ERR_NO_FILE))) {
             return ['paths' => [], 'error' => null];
        }

        // Handle single file upload (e.g., logo_upload)
        if (is_string($files['name'] ?? null)) { 
            $result = $uploader->uploadFile($files, 'logo-' . time()); 
            if (!$result['success']) {
                return ['paths' => [], 'error' => $result['error']];
            }
            // Return relative path to the file
            return ['paths' => [$uploadConfig['target_dir'] . $result['filename']], 'error' => null]; 
        }
        
        // Handle multiple file upload (e.g., other_assets[])
        $uploadedPaths = [];
        $errors = [];

        // Need to restructure the standard $_FILES array for multi-upload processing
        $fileCount = count($files['name']);
        $filesArray = [];
        for ($i = 0; $i < $fileCount; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $filesArray[] = [
                    'name' => $files['name'][$i],
                    'type' => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error' => $files['error'][$i],
                    'size' => $files['size'][$i],
                ];
            }
        }

        foreach ($filesArray as $file) {
            $result = $uploader->uploadFile($file, 'asset-' . uniqid());
            if ($result['success']) {
                $uploadedPaths[] = $uploadConfig['target_dir'] . $result['filename']; // Return relative path
            } else {
                $errors[] = $file['name'] . ': ' . $result['error'];
            }
        }

        return ['paths' => $uploadedPaths, 'error' => empty($errors) ? null : implode('; ', $errors)];
    }

    /**
     * Helper method to safely retrieve the last inserted ID.
     */
    private function getProjectIdFromLastInsert(): int
    {
        // Use Database::getInstance()->lastInsertId() consistently
        if (class_exists('Database') && method_exists(Database::getInstance(), 'lastInsertId')) {
            return (int)Database::getInstance()->lastInsertId();
        }
        error_log("CRITICAL: Failed to retrieve last insert ID for project.");
        return 0; 
    }

    /**
     * Attempts to find an existing user by email or creates a new client user.
     * (Logic remains the same)
     */
    private function resolveOrCreateClient(string $email, string $name): int
    {
        $userModel = new UserModel();
        
        // 1. Check if user exists by email
        $user = $userModel->findBy('email', $email);

        if ($user) {
            return (int)$user['id'];
        }

        // 2. User not found, attempt to create a new client user
        $baseUsername = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', explode('@', $email)[0] . substr($name, 0, 1)));
        
        // Ensure username is unique
        $username = $baseUsername;
        $counter = 1;
        while ($userModel->usernameExists($username)) {
            $username = $baseUsername . $counter++;
        }

        $initialPassword = bin2hex(random_bytes(16)); 
        
        $newClientData = [
            'username' => $username,
            'email' => $email,
            'password' => password_hash($initialPassword, PASSWORD_DEFAULT),
            'is_admin' => 0, // 0 for client user
        ];

        $newClientId = $userModel->createUser($newClientData);

        if (!$newClientId) {
            throw new Exception("Automated client user creation failed for email: " . $email);
        }
        
        // TODO: Trigger an email notification to the client with their new username 
        
        return $newClientId;
    }
    
    /**
     * Inserts a basic master record into the 'projects' table to obtain the FK ID.
     * WARNING: This assumes a 'projects' table exists with columns client_id, project_title, and status.
     * @throws Exception If project creation fails.
     */
    private function createProjectMasterRecord(int $clientId, string $title): int
    {
        // Assuming Database::getInstance() returns a connection handler with execute/lastInsertId
        $db = Database::getInstance();
        
        // Use a simple master table for the Foreign Key
        $sql = "INSERT INTO projects (client_id, project_title, status) VALUES (:client_id, :project_title, :status)";
        $params = [
            ':client_id' => $clientId,
            ':project_title' => $title,
            ':status' => 'New',
        ];

        if ($db->execute($sql, $params)) {
            return (int)$db->lastInsertId();
        }

        throw new Exception("Failed to create master project record in 'projects' table.");
    }

    /**
     * Persists the client data and final quote into the database using a transaction.
     * @param array $formData The sanitized form data.
     * @param array $quoteData The calculated quote.
     * @param array $fileData The $_FILES array.
     * @return int|bool The ID of the newly created project record or false on failure.
     * @throws Exception If file upload, user resolution/creation, or DB transaction fails.
     */
    public function saveOnboardingData(array $formData, array $quoteData, array $fileData): int|bool
    {
        $db = Database::getInstance();
        $db->beginTransaction(); // Start Transaction

        try {
            // 1. Resolve or Create Client User (Dependency A: Client must exist)
            $clientId = $this->resolveOrCreateClient(
                $formData['rep_email'], 
                $formData['rep_name']
            );

            // 2. Create the Master Project Record FIRST (Dependency B: Project must exist)
            $masterProjectId = $this->createProjectMasterRecord($clientId, $formData['project_title']);

            // Create a unique, permanent upload directory based on a hash of email and time
            $uploadDir = 'projects/' . hash('sha256', $formData['rep_email'] . time());
            
            // 3. Process Uploads
            $logoUpload = $this->processUploads($fileData, 'logo_upload', $uploadDir);
            if ($logoUpload['error']) {
                throw new Exception("Logo upload failed: " . $logoUpload['error']);
            }
            $logoPath = $logoUpload['paths'][0] ?? null;

            $assetsUpload = $this->processUploads($fileData, 'other_assets', $uploadDir);
            if ($assetsUpload['error']) {
                throw new Exception("Assets upload failed: " . $assetsUpload['error']);
            }
            $assetsPaths = $assetsUpload['paths'];
            
            // 4. Prepare data for insertion into 'onboarding_details'
            $dataForInsert = [];

            // --- A. Populate Form Data Fields ---
            foreach ($formData as $key => $value) {
                // For TEXT/LONGTEXT columns that hold multiple selections, JSON encode them
                if (in_array($key, ['objectives', 'pages', 'features'])) {
                    $dataForInsert[$key] = json_encode($value);
                } 
                else {
                    $dataForInsert[$key] = $value;
                }
            }
            
            // --- B. Add/Override Processed Data Fields ---
            $dataForInsert['project_id'] = $masterProjectId; // **FIXED FK**
            $dataForInsert['client_id'] = $clientId; // Link to the resolved/created user
            $dataForInsert['package_name'] = $quoteData['package_name'];
            $dataForInsert['budget_estimate'] = (float) $quoteData['total_once_off']; 
            $dataForInsert['status'] = 'New Onboarding'; 
            
            // File Paths and Quote JSON
            $dataForInsert['logo_path'] = $logoPath;
            $dataForInsert['assets_paths'] = json_encode($assetsPaths);
            $dataForInsert['quote_json'] = json_encode($quoteData);

            // 5. Insert the complete record into the 'onboarding_details' table
            $success = $this->insert($dataForInsert);
            
            if (!$success) {
                throw new Exception("Failed to insert into onboarding_details.");
            }
            
            $newOnboardingId = $this->getProjectIdFromLastInsert();

            $db->commit(); // Commit the transaction if both inserts succeeded

            return $newOnboardingId; 

        } catch (Exception $e) {
            $db->rollBack(); // Rollback on any failure
            throw $e; // Re-throw the exception for the controller to handle
        }
    }
}