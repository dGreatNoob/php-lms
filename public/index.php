<?php
/**
 * PHP LMS Application Entry Point
 * Handles routing, authentication, and security
 */

// Load configuration first (defines env() function)
require_once __DIR__ . '/../config/db.php';

// Start session with secure settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', env('SECURE_COOKIES', false) ? 1 : 0);
session_start();
require_once __DIR__ . '/../src/helpers/Security.php';
require_once __DIR__ . '/../src/helpers/Logger.php';

// Initialize logger
Logger::init();

// Set security headers
Security::setSecureHeaders();

// Force HTTPS in production
Security::forceHTTPS();

// Enhanced autoloader for controllers and helpers
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/../src/controllers/' . $class . '.php',
        __DIR__ . '/../src/models/' . $class . '.php',
        __DIR__ . '/../src/helpers/' . $class . '.php',
    ];
    
    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Sanitize and validate input parameters
$page = Security::sanitizeInput($_GET['page'] ?? '', 50);
$section = Security::sanitizeInput($_GET['section'] ?? '', 50);
$action = Security::sanitizeInput($_GET['action'] ?? 'index', 50);
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;

// Log request for monitoring
Logger::info('Request received', [
    'page' => $page,
    'section' => $section,
    'action' => $action,
    'id' => $id,
    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
]);

// Error handling wrapper
try {

if (!isset($_SESSION['user_id'])) {
    // Not logged in: show login or registration
    if ($page === 'register') {
        (new AuthController())->register();
    } else {
        (new AuthController())->login();
    }
    exit;
}

// Admin management routing
if ($page === 'admin' && $_SESSION['role'] === 'admin') {
    switch ($section) {
        case 'courses':
            $controller = new CourseController();
            break;
        case 'topics':
            $controller = new TopicController();
            break;
        case 'lectures':
            $controller = new LectureController();
            break;
        case 'enrollments':
            $controller = new EnrollmentController();
            break;
        case 'submissions':
            $controller = new SubmissionsController();
            break;
        case 'archive':
            require_once __DIR__ . '/../src/controllers/DashboardController.php';
            $dashboard = new DashboardController();
            if ($action === 'restore_topic' && $id) {
                require_once __DIR__ . '/../src/models/Topic.php';
                Topic::restore($id);
                header('Location: ?page=admin&section=archive');
                exit;
            } elseif ($action === 'restore_course' && $id) {
                require_once __DIR__ . '/../src/models/Course.php';
                Course::restore($id);
                header('Location: ?page=admin&section=archive');
                exit;
            } elseif ($action === 'restore_user' && $id) {
                require_once __DIR__ . '/../src/models/User.php';
                User::restore($id);
                header('Location: ?page=admin&section=archive');
                exit;
            } elseif ($action === 'delete_permanent_lecture' && $id) {
                require_once __DIR__ . '/../src/models/Lecture.php';
                Lecture::deletePermanent($id);
                header('Location: ?page=admin&section=archive');
                exit;
            } elseif ($action === 'delete_permanent_topic' && $id) {
                require_once __DIR__ . '/../src/models/Topic.php';
                Topic::deletePermanent($id);
                header('Location: ?page=admin&section=archive');
                exit;
            } elseif ($action === 'delete_permanent_course' && $id) {
                require_once __DIR__ . '/../src/models/Course.php';
                Course::deletePermanent($id);
                header('Location: ?page=admin&section=archive');
                exit;
            } elseif ($action === 'delete_permanent_user' && $id) {
                require_once __DIR__ . '/../src/models/User.php';
                User::deletePermanent($id);
                header('Location: ?page=admin&section=archive');
                exit;
            } elseif ($action === 'delete_all_archived') {
                require_once __DIR__ . '/../src/models/Lecture.php';
                require_once __DIR__ . '/../src/models/Topic.php';
                require_once __DIR__ . '/../src/models/Course.php';
                require_once __DIR__ . '/../src/models/User.php';
                Lecture::deleteAllArchived();
                Topic::deleteAllArchived();
                Course::deleteAllArchived();
                User::deleteAllArchived();
                header('Location: ?page=admin&section=archive');
                exit;
            } else {
                $dashboard->archive();
                exit;
            }
        case 'profile':
            require_once __DIR__ . '/../src/controllers/ProfileController.php';
            (new ProfileController())->index();
            break;
        default:
            (new DashboardController())->index();
            exit;
    }
    // Call the appropriate method
    if (method_exists($controller, $action)) {
        $controller->$action($id);
    } else {
        $controller->index();
    }
    exit;
}

// Logged in: route to dashboard or other pages
switch ($page) {
    case 'dashboard':
        (new DashboardController())->index();
        break;
    case 'logout':
        (new AuthController())->logout();
        break;
    case 'profile':
        require_once __DIR__ . '/../src/controllers/ProfileController.php';
        (new ProfileController())->index();
        break;
    // Add more routes as needed
    default:
        (new DashboardController())->index();
        break;
}

} catch (Exception $e) {
    // Log the error
    Logger::error('Application error', [
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ]);
    
    // Log security event for suspicious errors
    if (strpos($e->getMessage(), 'SQL') !== false || 
        strpos($e->getMessage(), 'injection') !== false) {
        Security::logSecurityEvent('potential_sql_injection', [
            'error' => $e->getMessage(),
            'request_uri' => $_SERVER['REQUEST_URI'] ?? ''
        ]);
    }
    
    // Show appropriate error page based on environment
    if (env('APP_DEBUG', false)) {
        // Development: show detailed error
        echo "<h1>Application Error</h1>";
        echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
        echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    } else {
        // Production: show generic error
        http_response_code(500);
        echo "<h1>Something went wrong</h1>";
        echo "<p>We're sorry, but something went wrong. Please try again later.</p>";
    }
} 