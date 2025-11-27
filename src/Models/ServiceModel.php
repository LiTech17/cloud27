<?php
namespace Models;

class ServiceModel extends BaseModel
{
    protected string $table = 'services';

    /** Allowed columns for mass assignment */
    protected array $allowedFields = ['title', 'description'];

    /**
     * Get all services.
     */
    public function getAllServices(): array
    {
        $sql = "SELECT id, title, description 
                FROM {$this->table} 
                ORDER BY id DESC";

        return $this->query($sql);
    }

    /**
     * Get a single service by ID.
     */
    public function getServiceById(int $id): ?array
    {
        return $this->findOne($id);
    }

    /**
     * Create a new service.
     */
    public function createService(array $data): bool
    {
        $filtered = $this->filterAllowed($data);
        return $this->insert($filtered);
    }

    /**
     * Update an existing service.
     */
    public function updateService(int $id, array $data): bool
    {
        $filtered = $this->filterAllowed($data);
        return $this->update($id, $filtered);
    }

    /**
     * Delete a service by ID.
     */
    public function deleteService(int $id): bool
    {
        return $this->delete($id);
    }

    /**
     * Optional: Validate service input.
     * Extend this later for advanced validation rules.
     */
    public function validate(array $data): array
    {
        $errors = [];

        if (empty($data['title'])) {
            $errors[] = 'Service title is required.';
        }

        if (empty($data['description'])) {
            $errors[] = 'Service description is required.';
        }

        return $errors;
    }

    /**
     * Filter incoming data by allowed fields.
     */
    private function filterAllowed(array $data): array
    {
        return array_intersect_key($data, array_flip($this->allowedFields));
    }
}
