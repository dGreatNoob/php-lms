<?php
/**
 * Health Check Endpoint
 * Used by load balancers and monitoring systems to check application health
 */

// Set content type
header('Content-Type: application/json');

// Initialize response
$health = [
    'status' => 'healthy',
    'timestamp' => date('c'),
    'version' => '1.0.0',
    'checks' => []
];

try {
    // Check database connection
    require_once __DIR__ . '/../config/db.php';
    
    if ($conn && $conn->ping()) {
        $health['checks']['database'] = [
            'status' => 'healthy',
            'message' => 'Database connection successful'
        ];
    } else {
        throw new Exception('Database connection failed');
    }
    
    // Check storage directories
    $storageDir = __DIR__ . '/../storage';
    $uploadsDir = __DIR__ . '/uploads';
    
    if (is_writable($storageDir) && is_writable($uploadsDir)) {
        $health['checks']['storage'] = [
            'status' => 'healthy',
            'message' => 'Storage directories are writable'
        ];
    } else {
        throw new Exception('Storage directories not writable');
    }
    
    // Check disk space (warn if less than 1GB free)
    $freeSpace = disk_free_space(__DIR__);
    $totalSpace = disk_total_space(__DIR__);
    $freeSpaceGB = round($freeSpace / (1024 * 1024 * 1024), 2);
    
    if ($freeSpace > 1073741824) { // 1GB
        $health['checks']['disk_space'] = [
            'status' => 'healthy',
            'message' => "Free space: {$freeSpaceGB}GB",
            'free_space_gb' => $freeSpaceGB
        ];
    } else {
        $health['checks']['disk_space'] = [
            'status' => 'warning',
            'message' => "Low disk space: {$freeSpaceGB}GB",
            'free_space_gb' => $freeSpaceGB
        ];
        $health['status'] = 'warning';
    }
    
    // Check PHP version
    $phpVersion = PHP_VERSION;
    $health['checks']['php'] = [
        'status' => 'healthy',
        'version' => $phpVersion
    ];
    
    // Check required extensions
    $requiredExtensions = ['mysqli', 'gd', 'mbstring'];
    $missingExtensions = [];
    
    foreach ($requiredExtensions as $extension) {
        if (!extension_loaded($extension)) {
            $missingExtensions[] = $extension;
        }
    }
    
    if (empty($missingExtensions)) {
        $health['checks']['php_extensions'] = [
            'status' => 'healthy',
            'message' => 'All required extensions loaded'
        ];
    } else {
        $health['checks']['php_extensions'] = [
            'status' => 'error',
            'message' => 'Missing extensions: ' . implode(', ', $missingExtensions),
            'missing' => $missingExtensions
        ];
        $health['status'] = 'unhealthy';
    }
    
} catch (Exception $e) {
    $health['status'] = 'unhealthy';
    $health['error'] = $e->getMessage();
    
    // Log the health check failure
    error_log("Health check failed: " . $e->getMessage());
    
    // Set HTTP status code to 503 Service Unavailable
    http_response_code(503);
}

// Add system info
$health['system'] = [
    'php_version' => PHP_VERSION,
    'memory_usage' => memory_get_usage(true),
    'memory_peak' => memory_get_peak_usage(true),
    'load_average' => function_exists('sys_getloadavg') ? sys_getloadavg() : null,
    'uptime' => function_exists('shell_exec') ? trim(shell_exec('uptime')) : null
];

// Output JSON response
echo json_encode($health, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);