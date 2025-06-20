<?php
class ProfileController {
    public function index() {
        require_once __DIR__ . '/../models/User.php';
        $user_id = $_SESSION['user_id'] ?? null;
        $user = $user_id ? User::findById($user_id) : null;
        $message = '';
        $enrolled_courses = [];
        if ($user && $user['role'] === 'student') {
            require_once __DIR__ . '/../models/Enrollment.php';
            $enrolled_courses = Enrollment::getCoursesForStudent($user_id);
        }
        // Handle password change
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['current_password'], $_POST['new_password'], $_POST['confirm_password'])) {
            $current = $_POST['current_password'];
            $new = $_POST['new_password'];
            $confirm = $_POST['confirm_password'];
            if (!$user || !password_verify($current, $user['password'])) {
                $message = 'Current password is incorrect.';
            } elseif ($new !== $confirm) {
                $message = 'New passwords do not match.';
            } elseif (strlen($new) < 6) {
                $message = 'New password must be at least 6 characters.';
            } else {
                global $conn;
                $hash = password_hash($new, PASSWORD_DEFAULT);
                $stmt = $conn->prepare('UPDATE users SET password = ? WHERE id = ?');
                $stmt->bind_param('si', $hash, $user_id);
                $stmt->execute();
                $message = 'Password updated successfully!';
                // Refresh user info
                $user = User::findById($user_id);
            }
        }
        include __DIR__ . '/../views/admin/profile/index.php';
    }
} 