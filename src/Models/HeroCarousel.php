<?php

namespace Models;

use Database;
use PDOException;

class HeroCarousel extends BaseModel
{
    protected string $table = 'hero_carousel';
    protected array $allowedFields = [
        'image_filename',
        'title',
        'subtitle',
        'cta_text',
        'cta_link',
        'display_order',
        'is_active'
    ];

    /**
     * Get all active slides ordered by display_order
     */
    public function getActiveSlides(): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY display_order ASC";
        return $this->query($sql);
    }

    /**
     * Get all slides (active and inactive) ordered by display_order
     */
    public function getAllSlides(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY display_order ASC";
        return $this->query($sql);
    }

    /**
     * Get a single slide by ID
     */
    public function getSlideById(int $id): ?array
    {
        return $this->findOne($id);
    }

    /**
     * Create a new slide
     */
    public function createSlide(array $data): int|false
    {
        // Set default display order to be last
        if (!isset($data['display_order'])) {
            $maxOrder = $this->getMaxDisplayOrder();
            $data['display_order'] = $maxOrder + 1;
        }

        if ($this->insert($data)) {
            return $this->getLastInsertId();
        }
        return false;
    }

    /**
     * Update an existing slide
     */
    public function updateSlide(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }

    /**
     * Delete a slide
     */
    public function deleteSlide(int $id): bool
    {
        return $this->delete($id);
    }

    /**
     * Toggle active status
     */
    public function toggleActive(int $id): bool
    {
        $slide = $this->getSlideById($id);
        if (!$slide) return false;

        $newState = $slide['is_active'] ? 0 : 1;
        return $this->update($id, ['is_active' => $newState]);
    }

    /**
     * Reorder slides
     */
    public function reorderSlides(array $orderArray): bool
    {
        try {
            $db = Database::getInstance();
            $db->beginTransaction();

            $sql = "UPDATE {$this->table} SET display_order = ? WHERE id = ?";
            foreach ($orderArray as $position => $id) {
                $db->run($sql, [$position + 1, $id]);
            }

            $db->commit();
            return true;
        } catch (PDOException $e) {
            $db->rollBack();
            error_log("Reorder failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get the current maximum display order
     */
    private function getMaxDisplayOrder(): int
    {
        $sql = "SELECT MAX(display_order) as max_order FROM {$this->table}";
        $result = $this->query($sql);
        return (int)($result[0]['max_order'] ?? 0);
    }
}
