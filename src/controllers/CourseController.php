<?php
class CourseController {
    public function index() {
        require_once __DIR__ . '/../models/Course.php';
        $courses = Course::all();
        include __DIR__ . '/../views/admin/courses/index.php';
    }
    public function create() {
        require_once __DIR__ . '/../models/Course.php';
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = trim($_POST['code'] ?? '');
            $title = trim($_POST['title'] ?? '');
            $semester = trim($_POST['semester'] ?? '');
            if (!$code || !$title || !$semester) {
                $error = 'All fields are required.';
            } else {
                $result = Course::create($code, $title, $semester);
                if ($result) {
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
        $error = '';
        $course = Course::find($id);
        if (!$course) {
            header('Location: ?page=admin&section=courses');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = trim($_POST['code'] ?? '');
            $title = trim($_POST['title'] ?? '');
            $semester = trim($_POST['semester'] ?? '');
            if (!$code || !$title || !$semester) {
                $error = 'All fields are required.';
            } else {
                $result = Course::update($id, $code, $title, $semester);
                if ($result) {
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
        if ($id) {
            Course::delete($id);
        }
        header('Location: ?page=admin&section=courses');
        exit;
    }
} 