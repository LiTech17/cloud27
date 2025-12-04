<?php
// src/Upload.php

namespace Core;

/**
 * Secure file upload handler with comprehensive validation.
 * Handles image uploads with security best practices.
 */
class Upload {
    
    /**
     * Default configuration for uploads
     */
    private const DEFAULT_MAX_SIZE = 5242880; // 5MB in bytes
    private const DEFAULT_TARGET_DIR = 'uploads/';
    
    /**
     * Allowed MIME types for image uploads
     */
    private const ALLOWED_MIME_TYPES = [
        'image/png',
        'image/jpeg',
        'image/jpg',
        'image/webp',
        'image/gif',
        'image/x-icon',
        'application/pdf'
    ];
    
    /**
     * Allowed file extensions
     */
    private const ALLOWED_EXTENSIONS = [
        'png', 'jpg', 'jpeg', 'webp', 'gif', 'ico'
    ];
    
    /**
     * Upload configuration
     */
    private $config = [];
    
    /**
     * Last error message
     */
    private $lastError = '';
    
    /**
     * Constructor with optional configuration
     * 
     * @param array $config Configuration options
     */
    public function __construct(array $config = []) {
        $this->config = array_merge([
            'max_size' => self::DEFAULT_MAX_SIZE,
            'target_dir' => self::DEFAULT_TARGET_DIR,
            'allowed_mime_types' => self::ALLOWED_MIME_TYPES,
            'allowed_extensions' => self::ALLOWED_EXTENSIONS,
            'create_dir' => true,
            'dir_permissions' => 0755
        ], $config);
        
        // Ensure target directory exists
        if ($this->config['create_dir']) {
            $this->ensureDirectoryExists();
        }
        error_log("Upload Class Initialized (Patched Version)");
    }
    
    /**
     * Upload a single file
     * 
     * @param array $file The uploaded file array from $_FILES
     * @param string|null $customName Optional custom filename (without extension)
     * @return array ['success' => bool, 'filename' => string|null, 'path' => string|null, 'error' => string|null]
     */
    public function uploadFile(array $file, ?string $customName = null): array {
        // Reset error
        $this->lastError = '';
        
        // Validate file upload
        if (!$this->validateUpload($file)) {
            return $this->buildResponse(false, null, $this->lastError);
        }
        
        // Validate file size
        if (!$this->validateFileSize($file['size'])) {
            return $this->buildResponse(false, null, $this->lastError);
        }
        
        // Validate MIME type
        if (!$this->validateMimeType($file['tmp_name'])) {
            return $this->buildResponse(false, null, $this->lastError);
        }
        
        // Validate extension
        $extension = $this->getFileExtension($file['name']);
        if (!$this->validateExtension($extension)) {
            return $this->buildResponse(false, null, $this->lastError);
        }
        
        // Generate filename
        $filename = $this->generateFilename($customName, $extension);
        $targetPath = $this->getTargetPath($filename);
        
        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            $this->lastError = 'Failed to move uploaded file to target directory.';
            error_log("Upload Error: {$this->lastError} - Target: {$targetPath}");
            return $this->buildResponse(false, null, $this->lastError);
        }
        
        // Set proper file permissions
        chmod($targetPath, 0644);
        
        return $this->buildResponse(true, $filename, null, $targetPath);
    }
    
    /**
     * Upload multiple files
     * 
     * @param array $files Array of file arrays from $_FILES
     * @return array Array of upload results
     */
    public function uploadMultiple(array $files): array {
        $results = [];
        
        foreach ($files as $key => $file) {
            // Handle multiple file upload format
            if (is_array($file['name'])) {
                for ($i = 0; $i < count($file['name']); $i++) {
                    $singleFile = [
                        'name' => $file['name'][$i],
                        'type' => $file['type'][$i],
                        'tmp_name' => $file['tmp_name'][$i],
                        'error' => $file['error'][$i],
                        'size' => $file['size'][$i]
                    ];
                    $results[] = $this->uploadFile($singleFile);
                }
            } else {
                $results[] = $this->uploadFile($file);
            }
        }
        
        return $results;
    }
    
    /**
     * Delete an uploaded file
     * 
     * @param string $filename The filename to delete
     * @return bool True on success, false on failure
     */
    public function deleteFile(string $filename): bool {
        $filepath = $this->getTargetPath($filename);
        
        if (!file_exists($filepath)) {
            $this->lastError = 'File does not exist.';
            return false;
        }
        
        if (!unlink($filepath)) {
            $this->lastError = 'Failed to delete file.';
            error_log("Delete Error: Could not delete file: {$filepath}");
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate file upload
     * 
     * @param array $file File array
     * @return bool
     */
    private function validateUpload(array $file): bool {
        if (!isset($file) || !is_array($file)) {
            $this->lastError = 'Invalid file upload.';
            return false;
        }
        
        // Ensure 'error' key exists
        if (!array_key_exists('error', $file)) {
            $this->lastError = 'Invalid file upload structure (missing error code).';
            return false;
        }

        // Handle null error code (treat as no file or error)
        if ($file['error'] === null) {
            $this->lastError = 'File upload error code is null.';
            return false;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->lastError = $this->getUploadErrorMessage((int)$file['error']);
            error_log("Upload Error: {$this->lastError} (Code: {$file['error']})");
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate file size
     * 
     * @param int $size File size in bytes
     * @return bool
     */
    private function validateFileSize(int $size): bool {
        if ($size > $this->config['max_size']) {
            $maxSizeMB = round($this->config['max_size'] / 1048576, 2);
            $fileSizeMB = round($size / 1048576, 2);
            $this->lastError = "File size ({$fileSizeMB}MB) exceeds maximum allowed size ({$maxSizeMB}MB).";
            error_log("Upload Error: {$this->lastError}");
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate MIME type
     * 
     * @param string $tmpName Temporary file path
     * @return bool
     */
    private function validateMimeType(string $tmpName): bool {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $tmpName);
        unset($finfo);
        
        if (!in_array($mimeType, $this->config['allowed_mime_types'], true)) {
            $this->lastError = "Invalid file type. Detected MIME type: {$mimeType}";
            error_log("Upload Error: {$this->lastError}");
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate file extension
     * 
     * @param string $extension File extension
     * @return bool
     */
    private function validateExtension(string $extension): bool {
        if (!in_array($extension, $this->config['allowed_extensions'], true)) {
            $this->lastError = "Invalid file extension: .{$extension}";
            error_log("Upload Error: {$this->lastError}");
            return false;
        }
        
        return true;
    }
    
    /**
     * Get file extension
     * 
     * @param string $filename Original filename
     * @return string Lowercase extension
     */
    private function getFileExtension(string $filename): string {
        return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    }
    
    /**
     * Generate unique filename
     * 
     * @param string|null $customName Custom name without extension
     * @param string $extension File extension
     * @return string Generated filename
     */
    private function generateFilename(?string $customName, string $extension): string {
        if ($customName) {
            // Sanitize custom name
            $customName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $customName);
            return $customName . '.' . $extension;
        }
        
        // Generate unique filename with timestamp and random bytes
        try {
            $randomBytes = bin2hex(random_bytes(8));
        } catch (\Exception $e) {
            // Fallback to less secure but still unique method
            $randomBytes = md5(uniqid(mt_rand(), true));
        }
        
        return time() . '_' . $randomBytes . '.' . $extension;
    }
    
    /**
     * Get full target path for filename
     * 
     * @param string $filename Filename
     * @return string Full path
     */
    private function getTargetPath(string $filename): string {
        return rtrim($this->config['target_dir'], '/') . '/' . $filename;
    }
    
    /**
     * Ensure target directory exists
     * 
     * @return bool
     */
    private function ensureDirectoryExists(): bool {
        $dir = $this->config['target_dir'];
        
        if (!is_dir($dir)) {
            if (!mkdir($dir, $this->config['dir_permissions'], true)) {
                error_log("Upload Error: Failed to create directory: {$dir}");
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Get upload error message
     * 
     * @param int $errorCode PHP upload error code
     * @return string Error message
     */
    private function getUploadErrorMessage(int $errorCode): string {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize directive in php.ini.',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE directive in HTML form.',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded.',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.'
        ];
        
        return $errors[$errorCode] ?? 'Unknown upload error.';
    }
    
    /**
     * Build response array
     * 
     * @param bool $success Success status
     * @param string|null $filename Filename
     * @param string|null $error Error message
     * @param string|null $path Full file path
     * @return array
     */
    private function buildResponse(bool $success, ?string $filename, ?string $error, ?string $path = null): array {
        return [
            'success' => $success,
            'filename' => $filename,
            'path' => $path,
            'error' => $error
        ];
    }
    
    /**
     * Get the last error message
     * 
     * @return string
     */
    public function getLastError(): string {
        return $this->lastError;
    }
    
    /**
     * Get upload configuration
     * 
     * @return array
     */
    public function getConfig(): array {
        return $this->config;
    }
    
    /**
     * Static helper: Upload a file with default configuration
     * 
     * @param array $file File from $_FILES
     * @param string $targetDir Target directory
     * @param int $maxSize Maximum file size
     * @return array Upload result
     */
    public static function quick(array $file, string $targetDir = 'uploads/', int $maxSize = 5242880): array {
        $uploader = new self([
            'target_dir' => $targetDir,
            'max_size' => $maxSize
        ]);
        
        return $uploader->uploadFile($file);
    }
}