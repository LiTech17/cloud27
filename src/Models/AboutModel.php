<?php
// src/Models/AboutModel.php

namespace Models;

/**
 * AboutModel handles all database operations for the About page content.
 * Manages company information, team members, and images.
 */
class AboutModel extends BaseModel {
    
    protected $table = 'about_content';
    
    /**
     * Get all about content settings
     * 
     * @return array|null About content data
     */
    public function getContent(): ?array {
        $sql = "SELECT * FROM {$this->table} WHERE id = 1 LIMIT 1";
        $result = $this->query($sql);
        
        return $result[0] ?? null;
    }
    
    /**
     * Update about content
     * 
     * @param array $data Content data to update
     * @return bool Success status
     */
    public function updateContent(array $data): bool {
        // Check if content exists
        $existing = $this->getContent();
        
        if ($existing) {
            // Update existing content
            return $this->update(1, $data);
        } else {
            // Insert new content with id = 1
            $data['id'] = 1;
            return $this->insert($data);
        }
    }
    
    /**
     * Get all team members
     * 
     * @return array Array of team members
     */
    public function getTeamMembers(): array {
        $sql = "SELECT * FROM team_members ORDER BY display_order ASC, id ASC";
        return $this->query($sql);
    }
    
    /**
     * Get a single team member by ID
     * 
     * @param int $id Team member ID
     * @return array|null Team member data
     */
    public function getTeamMember(int $id): ?array {
        $sql = "SELECT * FROM team_members WHERE id = :id";
        $stmt = \Database::getInstance()->run($sql, [':id' => $id]);
        $result = $stmt->fetch();
        
        return $result ?: null;
    }
    
    /**
     * Add a new team member
     * 
     * @param array $data Team member data
     * @return bool Success status
     */
    public function addTeamMember(array $data): bool {
        $sql = "INSERT INTO team_members (name, position, bio, image_path, display_order) 
                VALUES (:name, :position, :bio, :image_path, :display_order)";
        
        $params = [
            ':name' => $data['name'],
            ':position' => $data['position'],
            ':bio' => $data['bio'] ?? '',
            ':image_path' => $data['image_path'] ?? null,
            ':display_order' => $data['display_order'] ?? 0
        ];
        
        return $this->execute($sql, $params);
    }
    
    /**
     * Update a team member
     * 
     * @param int $id Team member ID
     * @param array $data Updated data
     * @return bool Success status
     */
    public function updateTeamMember(int $id, array $data): bool {
        $setClauses = [];
        $params = [':id' => $id];
        
        $allowedFields = ['name', 'position', 'bio', 'image_path', 'display_order'];
        
        foreach ($data as $key => $value) {
            if (in_array($key, $allowedFields)) {
                $setClauses[] = "{$key} = :{$key}";
                $params[":{$key}"] = $value;
            }
        }
        
        if (empty($setClauses)) {
            return false;
        }
        
        $sql = "UPDATE team_members SET " . implode(', ', $setClauses) . " WHERE id = :id";
        
        return $this->execute($sql, $params);
    }
    
    /**
     * Delete a team member
     * 
     * @param int $id Team member ID
     * @return bool Success status
     */
    public function deleteTeamMember(int $id): bool {
        $sql = "DELETE FROM team_members WHERE id = :id";
        return $this->execute($sql, [':id' => $id]);
    }
    
    /**
     * Initialize about content table with default data if empty
     * 
     * @return bool Success status
     */
    public function initializeContent(): bool {
        $existing = $this->getContent();
        
        if (!$existing) {
            $defaultData = [
                'id' => 1,
                'company_name' => 'Cloud27',
                'tagline' => 'Building the Future, Today',
                'mission_statement' => 'Our mission is to deliver innovative cloud solutions that empower businesses to succeed in the digital age.',
                'about_text' => 'Cloud27 is a leading provider of web development and cloud hosting solutions. We combine cutting-edge technology with exceptional service to help businesses thrive online.',
                'hero_image' => null,
                'company_image' => null,
                'founded_year' => date('Y'),
                'employee_count' => '10-50',
                'office_location' => 'South Africa'
            ];
            
            return $this->insert($defaultData);
        }
        
        return true;
    }
}