<?php
namespace Models;

use Database;

class UserModel
{
    protected string $table = 'users';
    protected array $allowedFields = ['username', 'email', 'password', 'is_admin'];

    /* ------------------------------------------------------
     * Helper: log error consistently
     * ------------------------------------------------------ */
    private function logError(string $method, \PDOException $e): void
    {
        error_log("UserModel::$method - " . $e->getMessage());
    }

    /* ------------------------------------------------------
     * Helper: Find user by any column
     * ------------------------------------------------------ */
    public function findBy(string $column, mixed $value): array|false
    {
        try {
            $db = Database::getInstance();
            $sql = "SELECT * FROM {$this->table} WHERE {$column} = ? LIMIT 1";
            $stmt = $db->run($sql, [$value]);
            return $stmt->fetch() ?: false;
        } catch (\PDOException $e) {
            $this->logError("findBy", $e);
            return false;
        }
    }

    /* ------------------------------------------------------
     * Client Utility Methods (New)
     * ------------------------------------------------------ */

    /**
     * Retrieves all non-admin users (clients) with their ID and Username.
     * Used for populating client selection lists.
     */
    public function getAllClients(): array
    {
        try {
            $db = Database::getInstance();
            $sql = "SELECT id, username
                    FROM {$this->table}
                    WHERE is_admin = 0
                    ORDER BY username ASC";

            return $db->run($sql)->fetchAll();
        } catch (\PDOException $e) {
            $this->logError("getAllClients", $e);
            return [];
        }
    }

    /**
     * Retrieves a single client record by their username.
     * Used by the controller to resolve the client_id.
     */
    public function getClientByUsername(string $username): array|false
    {
        // Reuses the existing findBy method to look up the user
        return $this->findBy('username', $username);
    }

    /* ------------------------------------------------------
     * Auth
     * ------------------------------------------------------ */
    public function authenticate(string $username, string $password): array|false
    {
        try {
            $user = $this->findBy('username', $username);

            if (!$user || !password_verify($password, $user['password'])) {
                return false;
            }

            // Optional: Automatic password rehashing
            if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
                $this->updateUser($user['id'], [
                    'password' => password_hash($password, PASSWORD_DEFAULT)
                ]);
            }

            unset($user['password']); // Never return hash
            return $user;
        } catch (\PDOException $e) {
            $this->logError("authenticate", $e);
            return false;
        }
    }

    /* ------------------------------------------------------
     * CRUD
     * ------------------------------------------------------ */
    public function getAllUsers(): array
    {
        try {
            $db = Database::getInstance();
            $sql = "SELECT id, username, email, is_admin, created_at
                    FROM {$this->table}
                    ORDER BY is_admin DESC, username ASC";

            return $db->run($sql)->fetchAll();
        } catch (\PDOException $e) {
            $this->logError("getAllUsers", $e);
            return [];
        }
    }

    public function getUserById(int $id): array|false
    {
        return $this->findBy('id', $id);
    }

    /**
     * Creates a new user in the database.
     * @param array $data Contains username, password (hashed), email, and is_admin.
     * @return int|bool Returns the new user ID (int) on success, or false on failure.
     */
    public function createUser(array $data): int|bool
    {
        try {
            $db = Database::getInstance();

            // Check if the username or email already exists to prevent integrity violation
            if ($this->usernameExists($data['username'])) {
                // Return 0 or throw a more specific exception if needed
                throw new \PDOException("Username already exists: {$data['username']}");
            }
            if ($this->emailExists($data['email'])) {
                 throw new \PDOException("Email already exists: {$data['email']}");
            }

            $sql = "INSERT INTO {$this->table} (username, password, email, is_admin)
                    VALUES (?, ?, ?, ?)";

            $db->run($sql, [
                $data['username'],
                $data['password'],
                $data['email'],
                $data['is_admin']
            ]);

            // --- CRITICAL CHANGE: Return the ID of the newly inserted row ---
            return (int)$db->lastInsertId();
            
        } catch (\PDOException $e) {
            // Log the error, which may include the username/email collision message
            $this->logError("createUser", $e); 
            return false;
        }
    }

    public function updateUser(int $id, array $data): bool
    {
        try {
            $db = Database::getInstance();

            // Build dynamic fields safely
            $fields = [];
            $params = [];

            foreach ($this->allowedFields as $field) {
                if (isset($data[$field])) {
                    $fields[] = "{$field} = ?";
                    $params[] = $data[$field];
                }
            }

            if (empty($fields)) {
                return false;
            }

            $params[] = $id;

            $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = ?";
            $db->run($sql, $params);

            return true;
        } catch (\PDOException $e) {
            $this->logError("updateUser", $e);
            return false;
        }
    }

    public function deleteUser(int $id): bool
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->run("DELETE FROM {$this->table} WHERE id = ?", [$id]);
            return $stmt->rowCount() > 0;
        } catch (\PDOException $e) {
            $this->logError("deleteUser", $e);
            return false;
        }
    }

    /* ------------------------------------------------------
     * Utility checks
     * ------------------------------------------------------ */
    public function usernameExists(string $username): bool
    {
        return (bool)$this->findBy('username', $username);
    }

    public function emailExists(string $email): bool
    {
        return (bool)$this->findBy('email', $email);
    }
}