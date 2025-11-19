<?php
// src/Models/BaseModel.php

namespace Models;

/**
 * Abstract class providing common CRUD functionality for all models.
 * Relies on the global Database Singleton (Database::getInstance()) for connection.
 */
abstract class BaseModel {
    // The PDO object is now managed by the Database Singleton, so we don't store it here.
    protected $table; // Must be set by inheriting classes (e.g., 'pricing_packages')

    /**
     * BaseModel constructor. No longer connects directly; connection is via Singleton.
     */
    public function __construct() {
        // The connection is now lazy-loaded/managed by \Database::getInstance()
    }

    /**
     * Disconnects from the database. (Removed manual disconnect as Singleton manages lifetime)
     */
    public function __destruct() {
        // Explicit manual disconnection is no longer necessary as the Singleton holds the resource.
    }

    // ------------------------------------------------------------------------
    // CORE QUERY METHODS
    // ------------------------------------------------------------------------

    /**
     * Executes a raw SQL query and returns the result array. Use for SELECTs.
     * @param string $sql The SQL query string.
     * @param array $params Optional array of parameters for prepared statement.
     * @return array The resulting data set.
     */
    protected function query(string $sql, array $params = []): array {
        // Use the Singleton's run method to execute the query
        $stmt = \Database::getInstance()->run($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Executes an SQL command (INSERT, UPDATE, DELETE).
     * @param string $sql The SQL command string.
     * @param array $params Optional array of parameters for prepared statement.
     * @return bool True if one or more rows were affected (or query succeeded), false otherwise.
     */
    protected function execute(string $sql, array $params = []): bool {
        // Use the Singleton's run method to execute the command
        $stmt = \Database::getInstance()->run($sql, $params);
        // For CUD operations, return true if rows were affected or the command was a success
        return $stmt->rowCount() > 0;
    }

    // ------------------------------------------------------------------------
    // COMMON CRUD ABSTRACTIONS
    // ------------------------------------------------------------------------

    /**
     * Finds a single record by ID.
     * @param int $id The ID of the record.
     * @return array|null The record data or null if not found.
     */
    protected function findOne(int $id): ?array {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        // Use the Singleton's run method
        $stmt = \Database::getInstance()->run($sql, [':id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Inserts a new record into the table.
     * @param array $data Associative array of column names and values.
     * @return bool True on success.
     */
    protected function insert(array $data): bool {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        
        // Map data keys to PDO placeholders
        $params = [];
        foreach ($data as $key => $value) {
            $params[":{$key}"] = $value;
        }

        return $this->execute($sql, $params);
    }

    /**
     * Updates an existing record by ID.
     * @param int $id The ID of the record to update.
     * @param array $data Associative array of column names and new values.
     * @return bool True on success.
     */
    protected function update(int $id, array $data): bool {
        $setClauses = [];
        $params = [':id' => $id];

        foreach ($data as $key => $value) {
            // Skip the ID field if it somehow got passed in the data
            if ($key === 'id') continue; 
            
            $setClauses[] = "{$key} = :{$key}";
            $params[":{$key}"] = $value;
        }
        
        if (empty($setClauses)) {
            return false; // Nothing to update
        }

        $setClause = implode(', ', $setClauses);
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE id = :id";
        
        return $this->execute($sql, $params);
    }

    /**
     * Deletes a record by ID.
     * @param int $id The ID of the record to delete.
     * @return bool True on success.
     */
    protected function delete(int $id): bool {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->execute($sql, [':id' => $id]);
    }
}