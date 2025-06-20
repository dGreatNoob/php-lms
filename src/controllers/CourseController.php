<?php
class CourseController {
    public function index() {
        require_once __DIR__ . '/../models/Course.php';
        $courses = Course::all();
        include __DIR__ . '/../views/admin/courses/index.php';
    }
    public function create() {
        require_once __DIR__ . '/../models/Course.php';
        require_once __DIR__ . '/../models/Activity.php';
        
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = trim($_POST['code'] ?? '');
            $title = trim($_POST['title'] ?? '');
            $semester_period = trim($_POST['semester_period'] ?? '');
            $semester_year = trim($_POST['semester_year'] ?? '');
            $semester = "{$semester_period} {$semester_year}";

            if (!$code || !$title || !$semester_period || !$semester_year) {
                $error = 'All fields are required.';
            } else {
                $result = Course::create($code, $title, $semester);
                if ($result) {
                    // Log the activity
                    global $conn;
                    $user_id = $_SESSION['user_id'] ?? null;
                    $course_id = $conn->insert_id;
                    Activity::logCourseCreated($user_id, $course_id, $title);
                    
                    header('Location: ?page=admin&section=courses');
                    exit;
                } else {
                    $error = 'Failed to create course. Code may already exist.';
                }
            }
        }
        include __DIR__ . '/../views/admin/courses/create.php';
    }
    public function edit($id = null) {
        require_once __DIR__ . '/../models/Course.php';
        require_once __DIR__ . '/../models/Activity.php';
        
        $error = '';
        $course = Course::find($id);
        if (!$course) {
            header('Location: ?page=admin&section=courses');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = trim($_POST['code'] ?? '');
            $title = trim($_POST['title'] ?? '');
            $semester_period = trim($_POST['semester_period'] ?? '');
            $semester_year = trim($_POST['semester_year'] ?? '');
            $semester = "{$semester_period} {$semester_year}";

            if (!$code || !$title || !$semester_period || !$semester_year) {
                $error = 'All fields are required.';
            } else {
                $result = Course::update($id, $code, $title, $semester);
                if ($result) {
                    // Log the activity
                    $user_id = $_SESSION['user_id'] ?? null;
                    Activity::logCourseUpdated($user_id, $id, $title);
                    
                    header('Location: ?page=admin&section=courses');
                    exit;
                } else {
                    $error = 'Failed to update course.';
                }
            }
        }
        include __DIR__ . '/../views/admin/courses/edit.php';
    }
    public function delete($id = null) {
        require_once __DIR__ . '/../models/Course.php';
        require_once __DIR__ . '/../models/Activity.php';
        
        if ($id) {
            $course = Course::find($id);
            if ($course) {
                $result = Course::delete($id);
                if ($result) {
                    // Log the activity
                    $user_id = $_SESSION['user_id'] ?? null;
                    Activity::logCourseArchived($user_id, $id, $course['title']);
                }
            }
        }
        header('Location: ?page=admin&section=courses');
        exit;
    }
} 