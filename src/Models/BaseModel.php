<?php
// src/Models/BaseModel.php

namespace Models;

abstract class BaseModel {

    /** @var string $table Must be defined by child classes */
    protected string $table;

    public function __construct() {
        // No direct connection here; Database::getInstance() manages PDO
    }

    // ------------------------------------------------------------------
    // INTERNAL QUERY HELPERS
    // ------------------------------------------------------------------

    /**
     * Execute SELECT query and return all rows.
     */
    protected function query(string $sql, array $params = []): array {
        $stmt = \Database::getInstance()->run($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Execute INSERT/UPDATE/DELETE and return whether rows were affected.
     */
    protected function execute(string $sql, array $params = []): bool {
        $stmt = \Database::getInstance()->run($sql, $params);
        return $stmt->rowCount() > 0;
    }
    
    // ------------------------------------------------------------------
    // NEW FIX: Last Insert ID Helper
    // ------------------------------------------------------------------

    /**
     * Retrieves the last inserted ID from the database connection.
     * Assumes Database::getInstance() returns an object with a getConnection() method 
     * that returns the underlying PDO object, which has lastInsertId().
     * * @return int The ID of the last inserted row.
     */
    protected function getLastInsertId(): int
    {
        // We must assume the Database::getInstance() wrapper has a method 
        // to access the raw PDO object (or the ID directly).
        // Common assumption: \Database::getInstance()->getConnection()->lastInsertId()
        
        // Note: I will call run()->lastInsertId() here, but you may need to adjust 
        // this based on the exact methods available on \Database::getInstance().
        
        $db = \Database::getInstance();
        
        // This line is a common pattern for obtaining the last ID from a wrapper
        // that manages a PDO connection. Adjust if your Database class is different.
        return (int) $db->getConnection()->lastInsertId(); 
    }
    
    // ------------------------------------------------------------------
    // CRUD HELPERS
    // ------------------------------------------------------------------

    /**
     * Find a record by primary key.
     */
    public function findOne(int $id): ?array {
        return $this->findBy('id', $id);
    }

    /**
     * Find a record by a specific column.
     * * @param string $column Column name
     * @param mixed $value Value to search for
     * @return array|null Record data or null if not found
     */
    public function findBy(string $column, mixed $value): ?array {
        // Basic validation for column name to prevent SQL injection
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $column)) {
            throw new \InvalidArgumentException("Invalid column name: {$column}");
        }

        $sql = "SELECT * FROM {$this->table} WHERE {$column} = :value LIMIT 1";
        $stmt = \Database::getInstance()->run($sql, [':value' => $value]);
        $data = $stmt->fetch();
        return $data ?: null;
    }

    /**
     * Insert a record.
     */
    protected function insert(array $data): bool {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$this->table} ({$columns})
                 VALUES ({$placeholders})";

        $params = [];
        foreach ($data as $col => $val) {
            $params[":{$col}"] = $val;
        }

        // We only use execute(), which returns bool.
        // The ProjectModel will now call getLastInsertId() after this.
        return $this->execute($sql, $params);
    }

    /**
     * Update a record by ID.
     */
    protected function update(int $id, array $data): bool {
        $set = [];
        $params = [':id' => $id];

        foreach ($data as $col => $val) {
            if ($col === 'id') continue;
            $set[] = "{$col} = :{$col}";
            $params[":{$col}"] = $val;
        }

        if (empty($set)) return false;

        $setClause = implode(', ', $set);
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE id = :id";

        return $this->execute($sql, $params);
    }

    /**
     * Delete a record by ID.
     */
    protected function delete(int $id): bool {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->execute($sql, [':id' => $id]);
    }
}