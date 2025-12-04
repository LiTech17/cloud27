<?php

namespace Services;

class ImageUploadHandler
{
    private const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/webp'];
    private const MAX_SIZE = 5 * 1024 * 1024; // 5MB
    private const UPLOAD_DIR = BASE_PATH . '/uploads/hero/';

    /**
     * Handle the upload of a hero image
     * 
     * @param array $file The $_FILES['image'] array
     * @return array|false Returns array with filename on success, false on failure
     */
    public function handleUpload(array $file): array|false
    {
        // 1. Validate
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['error' => 'File upload error code: ' . $file['error']];
        }

        if ($file['size'] > self::MAX_SIZE) {
            return ['error' => 'File too large. Max size is 5MB.'];
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if (!in_array($mimeType, self::ALLOWED_TYPES)) {
            return ['error' => 'Invalid file type. Allowed: JPG, PNG, WEBP.'];
        }

        // 2. Generate Filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('hero_') . '_' . time() . '.' . $extension;

        // 3. Create Directories if not exist (Safety check)
        $dirs = ['original', 'optimized', 'thumbnails'];
        foreach ($dirs as $dir) {
            if (!is_dir(self::UPLOAD_DIR . $dir)) {
                mkdir(self::UPLOAD_DIR . $dir, 0755, true);
            }
        }

        // 4. Move Original
        $originalPath = self::UPLOAD_DIR . 'original/' . $filename;
        if (!move_uploaded_file($file['tmp_name'], $originalPath)) {
            return ['error' => 'Failed to move uploaded file.'];
        }

        // 5. Process Images (Optimize & Thumbnail)
        try {
            $this->processImage($originalPath, self::UPLOAD_DIR . 'optimized/' . $filename, 1920, 1080);
            $this->processImage($originalPath, self::UPLOAD_DIR . 'thumbnails/' . $filename, 400, 225);
        } catch (\Exception $e) {
            // Cleanup on failure
            $this->deleteImage($filename);
            return ['error' => 'Image processing failed: ' . $e->getMessage()];
        }

        return ['filename' => $filename];
    }

    /**
     * Delete all versions of an image
     */
    public function deleteImage(string $filename): void
    {
        $dirs = ['original', 'optimized', 'thumbnails'];
        foreach ($dirs as $dir) {
            $path = self::UPLOAD_DIR . $dir . '/' . $filename;
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }

    /**
     * Resize and optimize image
     */
    private function processImage(string $source, string $destination, int $maxWidth, int $maxHeight): void
    {
        list($width, $height, $type) = getimagesize($source);
        
        $ratio = $width / $height;
        $targetRatio = $maxWidth / $maxHeight;

        if ($targetRatio > $ratio) {
            $newWidth = $maxHeight * $ratio;
            $newHeight = $maxHeight;
        } else {
            $newHeight = $maxWidth / $ratio;
            $newWidth = $maxWidth;
        }

        $src = $this->createImageFromType($source, $type);
        $dst = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve transparency for PNG/WEBP
        if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_WEBP) {
            imagecolortransparent($dst, imagecolorallocatealpha($dst, 0, 0, 0, 127));
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        $this->saveImageByType($dst, $destination, $type);

        imagedestroy($src);
        imagedestroy($dst);
    }

    private function createImageFromType(string $path, int $type)
    {
        switch ($type) {
            case IMAGETYPE_JPEG: return imagecreatefromjpeg($path);
            case IMAGETYPE_PNG: return imagecreatefrompng($path);
            case IMAGETYPE_WEBP: return imagecreatefromwebp($path);
            default: throw new \Exception('Unsupported image type');
        }
    }

    private function saveImageByType($image, string $path, int $type): void
    {
        switch ($type) {
            case IMAGETYPE_JPEG: imagejpeg($image, $path, 85); break;
            case IMAGETYPE_PNG: imagepng($image, $path, 8); break;
            case IMAGETYPE_WEBP: imagewebp($image, $path, 85); break;
        }
    }
}
