<?php
session_start();

require_once __DIR__ . '/../config/db.php';

// Simple autoloader for controllers
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/../src/controllers/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

$page = $_GET['page'] ?? null;
$section = $_GET['section'] ?? null;
$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

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
            } else {
                $dashboard->archive();
                exit;
            }
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
    // Add more routes as needed
    default:
        (new DashboardController())->index();
        break;
} 