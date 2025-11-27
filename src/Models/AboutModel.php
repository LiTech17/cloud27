<?php
// src/Models/AboutModel.php

namespace Models;

class AboutModel extends BaseModel {

    protected string $table = 'about_content';

    public function getContent(): ?array {
        $sql = "SELECT * FROM {$this->table} WHERE id = 1 LIMIT 1";
        $result = $this->query($sql);
        return $result[0] ?? null;
    }

    public function updateContent(array $data): bool {
        $existing = $this->getContent();

        if ($existing) {
            return $this->update(1, $data);
        }

        $data['id'] = 1;
        return $this->insert($data);
    }

    public function getTeamMembers(): array {
        $sql = "SELECT * FROM team_members 
                ORDER BY display_order ASC, id ASC";
        return $this->query($sql);
    }

    public function getTeamMember(int $id): ?array {
        $sql = "SELECT * FROM team_members WHERE id = :id";
        $stmt = \Database::getInstance()->run($sql, [':id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function addTeamMember(array $data): bool {
        $sql = "INSERT INTO team_members
                (name, position, bio, image_path, display_order)
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

    public function updateTeamMember(int $id, array $data): bool {
        $allowed = ['name', 'position', 'bio', 'image_path', 'display_order'];
        $setClauses = [];
        $params = [':id' => $id];

        foreach ($data as $key => $value) {
            if (in_array($key, $allowed)) {
                $setClauses[] = "{$key} = :{$key}";
                $params[":{$key}"] = $value;
            }
        }

        if (empty($setClauses)) {
            return false;
        }

        $sql = "UPDATE team_members SET " . implode(', ', $setClauses)
             . " WHERE id = :id";

        return $this->execute($sql, $params);
    }

    public function deleteTeamMember(int $id): bool {
        $sql = "DELETE FROM team_members WHERE id = :id";
        return $this->execute($sql, [':id' => $id]);
    }

    public function initializeContent(): bool {
        $existing = $this->getContent();

        if (!$existing) {
            $default = [
                'id' => 1,
                'company_name' => 'Cloud27',
                'tagline' => 'Building the Future, Today',
                'mission_statement' =>
                    'Our mission is to deliver innovative cloud solutions that empower businesses to succeed in the digital age.',
                'about_text' =>
                    'Cloud27 is a leading provider of web development and cloud hosting solutions...',
                'hero_image' => null,
                'company_image' => null,
                'founded_year' => date('Y'),
                'employee_count' => '10-50',
                'office_location' => 'South Africa'
            ];

            return $this->insert($default);
        }

        return true;
    }
}
