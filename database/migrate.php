<?php
/**
 * Database Migration Runner
 * Applies database migrations in order
 */

require_once __DIR__ . '/../config/db.php';

// Colors for output
function colorOutput($text, $color = 'white') {
    $colors = [
        'red' => "\033[0;31m",
        'green' => "\033[0;32m",
        'yellow' => "\033[1;33m",
        'blue' => "\033[0;34m",
        'white' => "\033[0m"
    ];
    
    return $colors[$color] . $text . $colors['white'];
}

function logMessage($message, $color = 'white') {
    echo colorOutput("[" . date('Y-m-d H:i:s') . "] " . $message, $color) . "\n";
}

// Create migrations table if it doesn't exist
function createMigrationsTable($conn) {
    $sql = "
        CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL UNIQUE,
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB;
    ";
    
    if ($conn->query($sql)) {
        logMessage("Migrations table ready", 'green');
    } else {
        logMessage("Failed to create migrations table: " . $conn->error, 'red');
        exit(1);
    }
}

// Get executed migrations
function getExecutedMigrations($conn) {
    $result = $conn->query("SELECT migration FROM migrations ORDER BY id");
    $executed = [];
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $executed[] = $row['migration'];
        }
    }
    
    return $executed;
}

// Get available migration files
function getAvailableMigrations() {
    $migrationsDir = __DIR__ . '/migrations';
    $migrations = [];
    
    if (is_dir($migrationsDir)) {
        $files = scandir($migrationsDir);
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                $migrations[] = $file;
            }
        }
        sort($migrations);
    }
    
    return $migrations;
}

// Execute migration
function executeMigration($conn, $migrationFile) {
    $migrationPath = __DIR__ . '/migrations/' . $migrationFile;
    
    if (!file_exists($migrationPath)) {
        logMessage("Migration file not found: $migrationFile", 'red');
        return false;
    }
    
    $sql = file_get_contents($migrationPath);
    
    // Split multiple statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $statement) {
        if (empty($statement)) continue;
        
        if (!$conn->query($statement)) {
            logMessage("Failed to execute migration $migrationFile: " . $conn->error, 'red');
            return false;
        }
    }
    
    // Record migration as executed
    $stmt = $conn->prepare("INSERT INTO migrations (migration) VALUES (?)");
    $stmt->bind_param('s', $migrationFile);
    
    if ($stmt->execute()) {
        logMessage("Migration executed successfully: $migrationFile", 'green');
        return true;
    } else {
        logMessage("Failed to record migration: " . $conn->error, 'red');
        return false;
    }
}

// Main migration logic
function runMigrations($conn) {
    logMessage("Starting database migrations...", 'blue');
    
    createMigrationsTable($conn);
    
    $executed = getExecutedMigrations($conn);
    $available = getAvailableMigrations();
    
    logMessage("Found " . count($available) . " migration files", 'blue');
    logMessage("Already executed: " . count($executed), 'blue');
    
    $pending = array_diff($available, $executed);
    
    if (empty($pending)) {
        logMessage("No pending migrations", 'green');
        return true;
    }
    
    logMessage("Pending migrations: " . count($pending), 'yellow');
    
    foreach ($pending as $migration) {
        logMessage("Executing migration: $migration", 'yellow');
        
        if (!executeMigration($conn, $migration)) {
            logMessage("Migration failed, stopping execution", 'red');
            return false;
        }
    }
    
    logMessage("All migrations completed successfully!", 'green');
    return true;
}

// Check if running from command line
if (php_sapi_name() !== 'cli') {
    // Running from web - require authentication
    session_start();
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        http_response_code(403);
        die('Access denied');
    }
    
    // Set content type for web output
    header('Content-Type: text/plain');
}

// Run migrations
try {
    if (!isset($conn) || !$conn) {
        logMessage("Database connection not available", 'red');
        exit(1);
    }
    
    $success = runMigrations($conn);
    
    if (!$success) {
        exit(1);
    }
    
} catch (Exception $e) {
    logMessage("Migration error: " . $e->getMessage(), 'red');
    exit(1);
}