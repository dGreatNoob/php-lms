<?php
class EnrollmentController {
    public function index() {
        require_once __DIR__ . '/../models/Enrollment.php';
        $enrollments = Enrollment::all();
        include __DIR__ . '/../views/admin/enrollments/index.php';
    }
    public function create() {
        require_once __DIR__ . '/../models/Enrollment.php';
        require_once __DIR__ . '/../models/Course.php';
        require_once __DIR__ . '/../models/User.php';
        require_once __DIR__ . '/../models/Activity.php';
        global $conn;
        $error = '';
        // Fetch students (role=student)
        $students = [];
        $res = $conn->query("SELECT id, first_name, last_name, username FROM users WHERE role = 'student'");
        if ($res) $students = $res->fetch_all(MYSQLI_ASSOC);
        $courses = Course::all();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $student_id = intval($_POST['student_id'] ?? 0);
            $course_id = intval($_POST['course_id'] ?? 0);
            if (!$student_id || !$course_id) {
                $error = 'Student and course are required.';
            } else {
                $result = Enrollment::create($student_id, $course_id);
                if ($result) {
                    // Log the enrollment activity
                    $user_id = $_SESSION['user_id'] ?? null;
                    $enrollment_id = $conn->insert_id;
                    
                    $student = User::findById($student_id);
                    $course = Course::find($course_id);
                    
                    if ($student && $course) {
                        $student_name = $student['first_name'] . ' ' . $student['last_name'];
                        Activity::logEnrollmentCreated($user_id, $enrollment_id, $student_name, $course['title']);
                    }
                    
                    header('Location: ?page=admin&section=enrollments');
                    exit;
                } else {
                    $error = 'Failed to enroll student. They may already be enrolled.';
                }
            }
        }
        include __DIR__ . '/../views/admin/enrollments/create.php';
    }
    public function delete($id = null) {
        require_once __DIR__ . '/../models/Enrollment.php';
        require_once __DIR__ . '/../models/User.php';
        require_once __DIR__ . '/../models/Course.php';
        require_once __DIR__ . '/../models/Activity.php';
        if ($id) {
            $enrollment = Enrollment::find($id);
            if ($enrollment) {
                $student = User::findById($enrollment['student_id']);
                $course = Course::find($enrollment['course_id']);
                
                $result = Enrollment::delete($id);
                if ($result && $student && $course) {
                    // Log the unenrollment activity
                    $user_id = $_SESSION['user_id'] ?? null;
                    $student_name = $student['first_name'] . ' ' . $student['last_name'];
                    Activity::logEnrollmentDeleted($user_id, $id, $student_name, $course['title']);
                }
            }
        }
        header('Location: ?page=admin&section=enrollments');
        exit;
    }
    public function import() {
        require_once __DIR__ . '/../models/Enrollment.php';
        require_once __DIR__ . '/../models/User.php';
        require_once __DIR__ . '/../models/Course.php';
        require_once __DIR__ . '/../models/Activity.php';
        $results = [];
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv'])) {
            $file = $_FILES['csv'];
            if ($file['error'] === UPLOAD_ERR_OK && pathinfo($file['name'], PATHINFO_EXTENSION) === 'csv') {
                $handle = fopen($file['tmp_name'], 'r');
                if ($handle) {
                    $header = fgetcsv($handle);
                    while (($row = fgetcsv($handle)) !== false) {
                        $data = array_combine($header, $row);
                        $first_name = trim($data['first_name'] ?? '');
                        $last_name = trim($data['last_name'] ?? '');
                        $username = trim($data['username'] ?? '');
                        $email = trim($data['email'] ?? '');
                        $password = $data['password'] ?? '';
                        $course_code = trim($data['course_code'] ?? '');
                        if (!$first_name || !$last_name || !$username || !$email || !$password || !$course_code) {
                            $results[] = [ 'row' => $row, 'status' => 'Missing required fields', 'success' => false ];
                            continue;
                        }
                        $user = User::findByUsername($username);
                        if (!$user) {
                            $hashed = password_hash($password, PASSWORD_DEFAULT);
                            $user_id = User::create($first_name, $last_name, $username, $email, $hashed, 'student');
                        } else {
                            $user_id = $user['id'];
                        }
                        $course = Course::findByCode($course_code);
                        if (!$course) {
                            $results[] = [ 'row' => $row, 'status' => 'Course not found', 'success' => false ];
                            continue;
                        }
                        $enrolled = Enrollment::create($user_id, $course['id']);
                        if ($enrolled) {
                            // Log the enrollment activity for CSV import
                            global $conn;
                            $user_id_admin = $_SESSION['user_id'] ?? null;
                            $enrollment_id = $conn->insert_id;
                            $student_name = $first_name . ' ' . $last_name;
                            Activity::logEnrollmentCreated($user_id_admin, $enrollment_id, $student_name, $course['title']);
                            
                            $results[] = [ 'row' => $row, 'status' => 'Enrolled', 'success' => true ];
                        } else {
                            $results[] = [ 'row' => $row, 'status' => 'Already enrolled or error', 'success' => false ];
                        }
                    }
                    fclose($handle);
                } else {
                    $error = 'Failed to open CSV file.';
                }
            } else {
                $error = 'Please upload a valid CSV file.';
            }
        }
        include __DIR__ . '/../views/admin/enrollments/import.php';
    }
} 