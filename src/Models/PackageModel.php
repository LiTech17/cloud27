<?php
// src/Models/PackageModel.php

namespace Models;

class PackageModel extends BaseModel {

    protected string $table = 'pricing_packages';

    public function getAllPackages(): array {
        $sql = "SELECT * FROM {$this->table} ORDER BY price_base ASC";
        $packages = $this->query($sql);

        return array_map(function($package) {
            $package['features'] = json_decode($package['features'], true) ?? [];
            return $package;
        }, $packages);
    }

    public function getPackageById(int $id): ?array {
        $package = $this->findOne($id);

        if ($package) {
            $package['features'] = json_decode($package['features'], true) ?? [];
        }
        return $package;
    }

    public function createPackage(array $data): bool {
        if (isset($data['features']) && is_array($data['features'])) {
            $data['features'] = json_encode($data['features']);
        }

        $data['slug'] = $data['slug'] ?? $this->slugify($data['title']);

        return $this->insert($data);
    }

    public function updatePackage(int $id, array $data): bool {
        if (isset($data['features']) && is_array($data['features'])) {
            $data['features'] = json_encode($data['features']);
        }

        return $this->update($id, $data);
    }

    public function deletePackage(int $id): bool {
        return $this->delete($id);
    }

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
