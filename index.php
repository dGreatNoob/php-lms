<?php
session_start();
include 'config/db.php';

$page = $_GET['page'] ?? 'login'; // default to login

switch ($page) {
    case 'login':
        include 'auth/login.php';
        break;
    case 'register':
        include 'auth/register.php';
        break;
    case 'dashboard':
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?page=login");
            exit;
        }
        include 'dashboard/admin.php';
        break;
    case 'logout':
        session_unset();
        session_destroy();
        header("Location: index.php?page=login");
        exit;
    default:
        echo "404 - Page not found.";
}
