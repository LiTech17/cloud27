<?php
// src/Models/ServiceModel.php

namespace Models;

use Database; // Import the unnamespaced Database class

/**
 * Manages data interactions for the 'services' table.
 */
class ServiceModel {
    
    /**
     * Fetches all services from the database.
     * * @return array|bool Returns an array of services or false on failure.
     */
    public function getAllServices(): array|bool {
        try {
            // Get the single instance of the database connection
            $db = Database::getInstance();
            
            // Prepare and execute the query
            $stmt = $db->run("SELECT id, title, description, icon_class FROM services ORDER BY id ASC");
            
            // Return all fetched results
            return $stmt->fetchAll();

        } catch (\PDOException $e) {
            // Log the error for debugging, but return false to the controller
            error_log("ServiceModel Error: Could not fetch services. " . $e->getMessage());
            return false;
        }
    }
}