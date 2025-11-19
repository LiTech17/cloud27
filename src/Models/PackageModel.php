<?php
// src/Models/PackageModel.php

namespace Models;

// Assumed BaseModel provides connect/disconnect and utility methods
class PackageModel extends BaseModel {
    protected $table = 'pricing_packages';

    /**
     * Retrieves all pricing packages from the database.
     * @return array Array of package data.
     */
    public function getAllPackages(): array {
        $sql = "SELECT * FROM {$this->table} ORDER BY price_base ASC";
        $packages = $this->query($sql);
        
        // Decode features before returning
        return array_map(function($package) {
            $package['features'] = json_decode($package['features'], true) ?? [];
            return $package;
        }, $packages);
    }

    /**
     * Retrieves a single package by ID.
     * @param int $id The package ID.
     * @return array|null The package data or null if not found.
     */
    public function getPackageById(int $id): ?array {
        $package = $this->findOne($id);

        if ($package) {
            $package['features'] = json_decode($package['features'], true) ?? [];
        }
        return $package;
    }

    /**
     * Creates a new package record.
     * @param array $data Array of package data (excluding ID).
     * @return bool Success status.
     */
    public function createPackage(array $data): bool {
        // Handle feature array serialization
        if (isset($data['features']) && is_array($data['features'])) {
            $data['features'] = json_encode($data['features']);
        }
        // Generate a slug if not provided
        $data['slug'] = $data['slug'] ?? $this->slugify($data['title']);

        return $this->insert($data);
    }

    /**
     * Updates an existing package record.
     * @param int $id The package ID.
     * @param array $data Array of fields to update.
     * @return bool Success status.
     */
    public function updatePackage(int $id, array $data): bool {
        // Handle feature array serialization
        if (isset($data['features']) && is_array($data['features'])) {
            $data['features'] = json_encode($data['features']);
        }
        
        return $this->update($id, $data);
    }

    /**
     * Deletes a package record.
     * @param int $id The package ID.
     * @return bool Success status.
     */
    public function deletePackage(int $id): bool {
        return $this->delete($id);
    }

    /**
     * Simple utility to generate a slug from a string.
     */
    private function slugify(string $text): string {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        return empty($text) ? 'n-a' : $text;
    }
}