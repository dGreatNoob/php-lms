<?php
class AuthController {
    public function login() {
        require_once __DIR__ . '/../models/User.php';
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $user = User::findByUsername($username);
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['username'] = $user['username'];
                header('Location: ?page=dashboard');
                exit;
            } else {
                $error = 'Invalid username or password.';
            }
        }
        include __DIR__ . '/../views/auth/login.php';
    }

    public function register() {
        require_once __DIR__ . '/../models/User.php';
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $first_name = trim($_POST['first_name'] ?? '');
            $last_name = trim($_POST['last_name'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 'student';
            if (!$first_name || !$last_name || !$username || !$email || !$password) {
                $error = 'All fields are required.';
            } elseif (User::findByUsername($username)) {
                $error = 'Username already exists.';
            } elseif (User::findByEmail($email)) {
                $error = 'Email already exists.';
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                User::create($first_name, $last_name, $username, $email, $hashed, $role);
                header('Location: ?page=login');
                exit;
            }
        }
        include __DIR__ . '/../views/auth/register.php';
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: ?page=login');
        exit;
    }
} 