<?php
// src/Models/ServiceModel.php

namespace Models;

use Database;

class ServiceModel {
    
    /**
     * Retrieves all services from the database.
     */
    public function getAllServices(): array {
        try {
            $db = Database::getInstance();
            $sql = "SELECT id, title, description FROM services ORDER BY id DESC";
            $stmt = $db->run($sql);
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log("ServiceModel Error (getAllServices): " . $e->getMessage());
            return []; // Return empty array on failure
        }
    }

    /** * Retrieves a single service by its ID. (READ for C/U/D) 
     */ 
    public function getServiceById(int $id): array|bool { 
        try { 
            $db = Database::getInstance(); 
            $sql = "SELECT id, title, description FROM services WHERE id = ?"; 
            $stmt = $db->run($sql, [$id]); 
            return $stmt->fetch(); 
        } catch (\PDOException $e) { 
            error_log("ServiceModel Error (getServiceById): " . $e->getMessage()); 
            return false; 
        } 
    } 

    /** * Creates a new service. (CREATE) 
     * @param array $data ['title' => '...', 'description' => '...'] 
     */ 
    public function createService(array $data): bool { 
        try { 
            $db = Database::getInstance(); 
            $sql = "INSERT INTO services (title, description) VALUES (?, ?)"; 
            $db->run($sql, [$data['title'], $data['description']]); 
            return true; 
        } catch (\PDOException $e) { 
            error_log("ServiceModel Error (createService): " . $e->getMessage()); 
            return false; 
        } 
    } 

    /** * Updates an existing service. (UPDATE) 
     * @param int $id The ID of the service to update. 
     * @param array $data ['title' => '...', 'description' => '...'] 
     */ 
    public function updateService(int $id, array $data): bool { 
        try { 
            $db = Database::getInstance(); 
            $sql = "UPDATE services SET title = ?, description = ? WHERE id = ?"; 
            $db->run($sql, [$data['title'], $data['description'], $id]); 
            return true; 
        } catch (\PDOException $e) { 
            error_log("ServiceModel Error (updateService): " . $e->getMessage()); 
            return false; 
        } 
    } 

    /** * Deletes a service by its ID. (DELETE) 
     */ 
    public function deleteService(int $id): bool { 
        try { 
            $db = Database::getInstance(); 
            $sql = "DELETE FROM services WHERE id = ?"; 
            $db->run($sql, [$id]); 
            // The row count check is safe using PDO statements
            return $db->run("SELECT ROW_COUNT()")->fetchColumn() > 0;
        } catch (\PDOException $e) { 
            error_log("ServiceModel Error (deleteService): " . $e->getMessage()); 
            return false; 
        } 
    } 
}