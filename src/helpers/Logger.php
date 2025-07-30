<?php
/**
 * Simple Logger Class
 * Provides logging functionality for different log levels
 */

class Logger
{
    const DEBUG = 'DEBUG';
    const INFO = 'INFO';
    const WARNING = 'WARNING';
    const ERROR = 'ERROR';
    const CRITICAL = 'CRITICAL';

    private static $logDir = null;

    /**
     * Initialize logger
     */
    public static function init(): void
    {
        self::$logDir = __DIR__ . '/../../storage/logs';
        
        if (!is_dir(self::$logDir)) {
            mkdir(self::$logDir, 0755, true);
        }
    }

    /**
     * Log a message
     */
    public static function log(string $level, string $message, array $context = []): void
    {
        if (self::$logDir === null) {
            self::init();
        }

        $timestamp = date('Y-m-d H:i:s');
        $logEntry = [
            'timestamp' => $timestamp,
            'level' => $level,
            'message' => $message,
            'context' => $context,
            'memory_usage' => memory_get_usage(true),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'cli',
            'user_id' => $_SESSION['user_id'] ?? null
        ];

        $logLine = json_encode($logEntry) . "\n";
        
        // Write to daily log file
        $logFile = self::$logDir . '/app_' . date('Y-m-d') . '.log';
        file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX);

        // Also write errors to separate error log
        if (in_array($level, [self::ERROR, self::CRITICAL])) {
            $errorFile = self::$logDir . '/error_' . date('Y-m-d') . '.log';
            file_put_contents($errorFile, $logLine, FILE_APPEND | LOCK_EX);
        }
    }

    /**
     * Log debug message
     */
    public static function debug(string $message, array $context = []): void
    {
        if (env('APP_DEBUG', false)) {
            self::log(self::DEBUG, $message, $context);
        }
    }

    /**
     * Log info message
     */
    public static function info(string $message, array $context = []): void
    {
        self::log(self::INFO, $message, $context);
    }

    /**
     * Log warning message
     */
    public static function warning(string $message, array $context = []): void
    {
        self::log(self::WARNING, $message, $context);
    }

    /**
     * Log error message
     */
    public static function error(string $message, array $context = []): void
    {
        self::log(self::ERROR, $message, $context);
    }

    /**
     * Log critical message
     */
    public static function critical(string $message, array $context = []): void
    {
        self::log(self::CRITICAL, $message, $context);
    }

    /**
     * Clean old log files
     */
    public static function cleanOldLogs(int $daysToKeep = 30): void
    {
        if (self::$logDir === null) {
            self::init();
        }

        $cutoffDate = time() - ($daysToKeep * 24 * 60 * 60);
        $logFiles = glob(self::$logDir . '/*.log');

        foreach ($logFiles as $logFile) {
            if (filemtime($logFile) < $cutoffDate) {
                unlink($logFile);
            }
        }
    }
}