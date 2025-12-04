<?php
namespace Models;

use Database;
use PDOException;

class UserModel
{
    protected string $table = 'users';
    protected array $allowedFields = ['username', 'email', 'password', 'is_admin'];

    /* ------------------------------------------------------
     * Helper: Log errors
     * ------------------------------------------------------ */
    private function logError(string $method, PDOException $e): void
    {
        error_log("UserModel::$method - " . $e->getMessage());
    }

    /* ------------------------------------------------------
     * Validate allowed columns (prevents SQL injection)
     * ------------------------------------------------------ */
    private function validateColumn(string $column): void
    {
        $allowed = ['id', 'username', 'email'];
        if (!in_array($column, $allowed, true)) {
            throw new \InvalidArgumentException("Invalid column: $column");
        }
    }

    /* ------------------------------------------------------
     * Generic Find
     * ------------------------------------------------------ */
    public function findBy(string $column, mixed $value): array|false
    {
        try {
            $this->validateColumn($column);

            $db = Database::getInstance();
            $sql = "SELECT * FROM {$this->table} WHERE {$column} = ? LIMIT 1";

            $stmt = $db->run($sql, [$value]);
            return $stmt->fetch() ?: false;

        } catch (PDOException $e) {
            $this->logError("findBy", $e);
            return false;
        }
    }

    /* ------------------------------------------------------
     * Clients
     * ------------------------------------------------------ */
    public function getAllClients(): array
    {
        try {
            $db = Database::getInstance();
            $sql = "SELECT id, username FROM {$this->table} 
                    WHERE is_admin = 0 ORDER BY username ASC";

            return $db->run($sql)->fetchAll();

        } catch (PDOException $e) {
            $this->logError("getAllClients", $e);
            return [];
        }
    }

    public function getClientByUsername(string $username): array|false
    {
        return $this->findBy('username', $username);
    }

    /* ------------------------------------------------------
     * Auth
     * ------------------------------------------------------ */
    public function authenticate(string $username, string $password): array|false
    {
        try {
            $user = $this->findBy('username', $username);
            if (!$user) return false;

            if (!password_verify($password, $user['password'])) {
                return false;
            }

            if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
                $this->updateUser($user['id'], [
                    'password' => password_hash($password, PASSWORD_DEFAULT)
                ]);
            }

            unset($user['password']);
            return $user;

        } catch (PDOException $e) {
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

        } catch (PDOException $e) {
            $this->logError("getAllUsers", $e);
            return [];
        }
    }

    public function getUserById(int $id): array|false
    {
        return $this->findBy('id', $id);
    }

    public function createUser(array $data): array|int|false
    {
        try {
            $db = Database::getInstance();

            if ($this->usernameExists($data['username'])) {
                return ['error' => 'username_taken'];
            }

            if ($this->emailExists($data['email'])) {
                return ['error' => 'email_taken'];
            }

            // Ensure hashing done centrally
            $hashed = password_hash($data['password'], PASSWORD_DEFAULT);

            $sql = "INSERT INTO {$this->table} (username, password, email, is_admin)
                    VALUES (?, ?, ?, ?)";

            $db->run($sql, [
                $data['username'],
                $hashed,
                $data['email'],
                $data['is_admin']
            ]);

            return (int)$db->getConnection()->lastInsertId();

        } catch (PDOException $e) {
            $this->logError("createUser", $e);
            return false;
        }
    }

    public function updateUser(int $id, array $data): bool
    {
        try {
            $db = Database::getInstance();

            $fields = [];
            $params = [];

            foreach ($this->allowedFields as $field) {
                if (isset($data[$field])) {
                    if ($field === 'password') {
                        $data[$field] = password_hash($data[$field], PASSWORD_DEFAULT);
                    }

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

        } catch (PDOException $e) {
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

        } catch (PDOException $e) {
            $this->logError("deleteUser", $e);
            return false;
        }
    }

    /* ------------------------------------------------------
     * Utilities
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
