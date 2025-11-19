<?php
// src/Models/UserModel.php

namespace Models;

use Database;

class UserModel {

    /**
     * Authenticates a user and returns their user data including is_admin flag.
     * Uses the 'password' column name from your database schema.
     * @return array|false Returns the user data array or false on failure.
     */
    public function authenticate(string $username, string $password): array|false {
        try {
            $db = Database::getInstance();
            // Select all necessary columns, including 'is_admin'
            $sql = "SELECT id, username, password, is_admin FROM users WHERE username = ?";
            $stmt = $db->run($sql, [$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Remove password hash before returning data
                unset($user['password']);
                return $user;
            }
            return false;
        } catch (\PDOException $e) {
            error_log("UserModel Error (authenticate): " . $e->getMessage());
            return false;
        }
    }
    
    // ----------------------------------------------------------------
    // USER MANAGEMENT CRUD METHODS (NEW)
    // ----------------------------------------------------------------

    /**
     * Retrieves all users for the Admin panel.
     */
    public function getAllUsers(): array {
        try {
            $db = Database::getInstance();
            // Select all necessary columns, ordered by Admin status first
            $sql = "SELECT id, username, email, is_admin, created_at FROM users ORDER BY is_admin DESC, username ASC";
            $stmt = $db->run($sql);
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log("UserModel Error (getAllUsers): " . $e->getMessage());
            return [];
        }
    }

    /**
     * Retrieves a single user by ID.
     */
    public function getUserById(int $id): array|bool {
        try {
            $db = Database::getInstance();
            $sql = "SELECT id, username, email, is_admin FROM users WHERE id = ?";
            $stmt = $db->run($sql, [$id]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            error_log("UserModel Error (getUserById): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Creates a new user.
     * @param array $data Must include 'username', HASHED 'password', 'email', 'is_admin'.
     */
    public function createUser(array $data): bool {
        try {
            $db = Database::getInstance();
            $sql = "INSERT INTO users (username, password, email, is_admin) VALUES (?, ?, ?, ?)";
            $db->run($sql, [
                $data['username'], 
                $data['password'], // This is already the HASH from the controller
                $data['email'], 
                $data['is_admin']
            ]);
            return true;
        } catch (\PDOException $e) {
            error_log("UserModel Error (createUser): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Updates an existing user.
     * @param int $id User ID.
     * @param array $data Data fields to update. If 'password' is present, it must be HASHED.
     */
    public function updateUser(int $id, array $data): bool {
        try {
            $db = Database::getInstance();
            $params = [];
            $set = "username = ?, email = ?, is_admin = ?";
            $params[] = $data['username'];
            $params[] = $data['email'];
            $params[] = $data['is_admin'];

            if (!empty($data['password'])) {
                // Password hashing is done in the controller, so we just append the field
                $set .= ", password = ?";
                $params[] = $data['password']; 
            }

            $sql = "UPDATE users SET $set WHERE id = ?";
            $params[] = $id;

            $db->run($sql, $params);
            return true;
        } catch (\PDOException $e) {
            error_log("UserModel Error (updateUser): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deletes a user by ID.
     */
    public function deleteUser(int $id): bool {
        try {
            $db = Database::getInstance();
            $sql = "DELETE FROM users WHERE id = ?";
            $stmt = $db->run($sql, [$id]);
            // Check if any row was affected
            return $stmt->rowCount() > 0;
        } catch (\PDOException $e) {
            error_log("UserModel Error (deleteUser): " . $e->getMessage());
            return false;
        }
    }
    
    // The deprecated findByUsername and createNewUser are removed.
}