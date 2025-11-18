<?php
// src/Database.php

/**
 * Handles the secure PDO database connection using configuration from the .env file.
 * Implements the Singleton pattern to ensure a single connection instance 
 * is used throughout the application.
 */
class Database {
    
    // Static property to hold the single instance of the class (Singleton)
    private static ?Database $instance = null; 
    
    // Property to hold the PDO connection object
    private \PDO $pdo; 

    // 1. Private constructor prevents direct instantiation
    private function __construct() {
        // Retrieve configuration securely from the global environment variables ($_ENV)
        $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
        $db   = $_ENV['DB_NAME'] ?? 'cloud27';
        $user = $_ENV['DB_USER'] ?? 'root';
        $pass = $_ENV['DB_PASS'] ?? '';
        $charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';

        // Set up the DSN (Data Source Name)
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        
        // PDO Options for security and predictable fetching
        $options = [
            // Ensure PDO throws exceptions on errors instead of silently failing
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION, 
            // Fetch results as associative arrays by default
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,     
            // Disable emulated prepares for better security and performance
            \PDO::ATTR_EMULATE_PREPARES   => false,                 
        ];

        // 2. Establish the PDO connection
        try {
            // Note: The global namespace prefix (\PDO) is used here for safety
            $this->pdo = new \PDO($dsn, $user, $pass, $options);
            
        } catch (\PDOException $e) {
            // Log the detailed error for debugging
            error_log("FATAL Database connection failed: " . $e->getMessage());
            
            // Halt execution and display a generic, non-technical error message
            http_response_code(500);
            die('<h1>Database Error (500)</h1><p>A critical connection failed. The application cannot run.</p>');
        }
    }

    // 3. Static method to get the single instance
    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    // 4. Public method to retrieve the PDO connection object (Useful, but not strictly needed for the Model)
    public function getConnection(): \PDO {
        return $this->pdo;
    }

    // 5. CRITICAL FIX: Method to execute prepared statements 
    /**
     * Executes a prepared statement and returns the PDO statement object.
     * Use this method for SELECT, INSERT, UPDATE, and DELETE queries.
     * * @param string $sql The SQL query string.
     * @param array $args An array of parameters for prepared statement execution.
     * @return \PDOStatement The executed PDO statement object.
     */
    public function run(string $sql, array $args = []): \PDOStatement {
        if (empty($args)) {
            // For simple queries without parameters (e.g., SELECT * FROM services)
            return $this->pdo->query($sql);
        }

        // For secure queries using prepared statements
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }

    // Prevent external cloning of the instance
    private function __clone() {}
    
    // Prevent deserialization
    public function __wakeup() {
        throw new \Exception("Cannot unserialize a singleton.");
    }
}