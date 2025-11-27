<?php
// src/Logger.php - The File Logger Utility

class Logger
{
    // APP_ROOT is defined in index.php
    private const LOG_FILE = APP_ROOT . '/logs/app.log'; 
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * Writes an event message to the central application log file.
     * @param string $level The severity level (e.g., INFO, WARN, ERROR, AUTH).
     * @param string $message The event description.
     */
    public static function log(string $level, string $message): void
    {
        // 1. Ensure the logs directory exists
        $logDir = dirname(self::LOG_FILE);
        if (!is_dir($logDir)) {
            // Attempt to create the directory
            if (!mkdir($logDir, 0777, true)) {
                 // Fallback to PHP's built-in logging if directory creation fails
                error_log("CRITICAL: Failed to create log directory at {$logDir}");
                return;
            }
        }

        // 2. Format the log line: [TIMESTAMP] [LEVEL] [USER:ID] MESSAGE
        // Use ?? 'N/A' for safety if session hasn't started or user isn't logged in
        // NOTE: This assumes session_start() has been called before Logger is used.
        $userId = $_SESSION['user_id'] ?? 'N/A'; 
        $logLine = sprintf(
            "[%s] [%s] [USER:%s] %s\n",
            date(self::DATE_FORMAT),
            strtoupper($level),
            $userId,
            $message
        );

        // 3. Append the log line to the file
        file_put_contents(self::LOG_FILE, $logLine, FILE_APPEND | LOCK_EX);
    }
    
    /** Helper for logging authentication attempts. */
    public static function auth(string $message): void
    {
        self::log('AUTH', $message);
    }

    /** Helper for logging administrative actions. */
    public static function admin(string $message): void
    {
        self::log('ADMIN', $message);
    }
    
    /** Helper for logging errors. */
    public static function error(string $message): void
    {
        self::log('ERROR', $message);
    }

    /** Helper for general information logging. */
    public static function info(string $message): void
    {
        self::log('INFO', $message);
    }
}