<?php
// src/Models/UserModel.php

namespace Models;

use Database;

class UserModel {

    /**
     * Finds a user by their username.
     * @param string $username
     * @return array|bool User data as associative array or false if not found.
     */
    public function findByUsername(string $username): array|bool {
        try {
            $db = Database::getInstance();
            $sql = "SELECT * FROM users WHERE username = ?";
            
            $stmt = $db->run($sql, [$username]);
            
            return $stmt->fetch();

        } catch (\PDOException $e) {
            error_log("UserModel Error (findByUsername): " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Inserts a new user into the database (for initial setup only).
     * This will be run manually or via a separate script once.
     * @param string $username
     * @param string $passwordRaw Unhashed password string
     * @return bool Success status.
     */
    public function createNewUser(string $username, string $passwordRaw): bool {
        try {
            $db = Database::getInstance();
            // Hash the password securely using PHP's built-in hashing function
            $hashedPassword = password_hash($passwordRaw, PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            
            $db->run($sql, [$username, $hashedPassword]);
            
            return true;
        } catch (\PDOException $e) {
            error_log("UserModel Error (createNewUser): " . $e->getMessage());
            return false;
        }
    }
}