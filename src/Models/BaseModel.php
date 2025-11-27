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
    // CRUD HELPERS
    // ------------------------------------------------------------------

    /**
     * Find a record by primary key.
     */
    protected function findOne(int $id): ?array {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = \Database::getInstance()->run($sql, [':id' => $id]);
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
