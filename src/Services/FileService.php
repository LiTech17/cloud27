<?php
// src/Services/FileService.php

namespace Services;

use Core\Upload;
use Exception;

/**
 * FileService acts as a wrapper for Core\Upload, handling the specific 
 * configuration and logic required for different types of file uploads 
 * in the application (e.g., logos, assets, documents).
 */
class FileService
{
    /**
     * Handles the logo upload for the onboarding process.
     * * @param array $fileData The entire $_FILES superglobal.
     * @param string $inputName The name of the file input field (e.g., 'logo_upload').
     * @param string $targetDirSubpath Sub-directory within the 'uploads/' folder (e.g., 'logos').
     * @return array ['paths' => array<string>, 'error' => string|null]
     */
    public function processUploads(array $fileData, string $inputName, string $targetDirSubpath): array
    {
        // 1. Check if the specific file input exists and has a file
        $files = $fileData[$inputName] ?? null;
        
        // Check for no file uploaded error (UPLOAD_ERR_NO_FILE)
        if (empty($files) || (is_array($files) && ($files['error'] === UPLOAD_ERR_NO_FILE))) {
            // No file was submitted, which is acceptable if the upload is optional
            return ['paths' => [], 'error' => null];
        }

        // 2. Define configuration specific to this upload context
        $uploadConfig = [
            // The final path will be BASE_UPLOADS_DIR/targetDirSubpath/
            'target_dir' => 'uploads/' . rtrim($targetDirSubpath, '/') . '/',
            // You can override default size/extensions here if needed,
            // but for now, we rely on Core\Upload's defaults.
        ];
        
        // 3. Initialize the Core\Upload utility
        try {
            $uploader = new Upload($uploadConfig);
        } catch (Exception $e) {
            error_log("FileService Error: Uploader initialization failed: " . $e->getMessage());
            return ['paths' => [], 'error' => 'Server error initializing file handler.'];
        }

        // 4. Handle Single File Upload (e.g., the client logo)
        // We need to normalize the $_FILES array structure for single vs. multiple fields
        // The GetStarted form only uses one input 'logo_upload' which should be single.
        if (is_array($files) && isset($files['name']) && !is_array($files['name'])) { 
            
            // Generate a secure, unique custom name based on time
            $customName = 'logo-' . time() . '-' . uniqid(); 
            
            $result = $uploader->uploadFile($files, $customName); 
            
            if (!$result['success']) {
                error_log("FileService Error: Single file upload failed: " . $result['error']);
                return ['paths' => [], 'error' => $result['error']];
            }
            
            // Return the full path for database storage (relative to root)
            $relativePath = $uploadConfig['target_dir'] . $result['filename'];
            return ['paths' => [$relativePath], 'error' => null];
            
        } 
        
        // Handle unexpected file structure or missing file fields
        if (is_array($files) && !empty($files['name'])) {
            // If you anticipate multiple files later, implement uploadMultiple logic here
            error_log("FileService Warning: Received unexpected multiple file array structure for input '{$inputName}'.");
            $result = $uploader->uploadMultiple($files);
            
            $successPaths = array_column(array_filter($result, fn($r) => $r['success']), 'path');
            $firstError = current(array_column(array_filter($result, fn($r) => !$r['success']), 'error'));
            
            return ['paths' => $successPaths, 'error' => $firstError];

        }
        
        // Should be caught by the initial check, but as a fallback
        return ['paths' => [], 'error' => 'Invalid file data received.'];
    }
    
    // You can add other methods here like deleteClientLogo($logoPath), 
    // or handle different types of file uploads (e.g., documentation)
}