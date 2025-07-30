<?php
/**
 * Database Configuration
 * Loads environment variables and establishes database connection
 */

// Load environment variables
function loadEnv($filePath) {
    if (!file_exists($filePath)) {
        return;
    }
    
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        
        list($name, $value) = array_map('trim', explode('=', $line, 2));
        // Remove quotes if present
        $value = trim($value, '"\'');
        $_ENV[$name] = $value;
        putenv("$name=$value");
    }
}

// Load environment-specific .env file
$envFile = __DIR__ . '/../.env';
if (isset($_ENV['APP_ENV'])) {
    $specificEnvFile = __DIR__ . '/../.env.' . $_ENV['APP_ENV'];
    if (file_exists($specificEnvFile)) {
        $envFile = $specificEnvFile;
    }
}
loadEnv($envFile);

// Database configuration from environment
$config = [
    'host' => $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? 'localhost',
    'port' => $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?? 3306,
    'database' => $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?? 'php_lms',
    'username' => $_ENV['DB_USER'] ?? getenv('DB_USER') ?? 'root',
    'password' => $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?? '',
];

// Create database connection
try {
    $conn = new mysqli(
        $config['host'],
        $config['username'],
        $config['password'],
        $config['database'],
        $config['port']
    );
    
    // Set charset to utf8mb4 for full UTF-8 support
    $conn->set_charset('utf8mb4');
    
    if ($conn->connect_error) {
        throw new Exception('Database connection failed: ' . $conn->connect_error);
    }
    
    // Only show success message in development
    $isDebug = ($_ENV['APP_DEBUG'] ?? getenv('APP_DEBUG') ?? 'false') === 'true';
    if ($isDebug) {
        // Uncomment for debugging connection issues
        // echo "<div class='message success'>Database connected successfully!</div>";
    }
    
} catch (Exception $e) {
    $errorMsg = $isDebug ? $e->getMessage() : 'Database connection failed';
    die("<div class='message error'>$errorMsg</div>");
}

// Global function to get environment variable
function env($key, $default = null) {
    return $_ENV[$key] ?? getenv($key) ?? $default;
}

