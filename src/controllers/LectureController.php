<?php
class LectureController {
    public function index() {
        require_once __DIR__ . '/../models/Lecture.php';
        require_once __DIR__ . '/../models/Topic.php';
        $lectures = Lecture::all(0);
        include __DIR__ . '/../views/admin/lectures/index.php';
    }
    public function create() {
        require_once __DIR__ . '/../models/Lecture.php';
        require_once __DIR__ . '/../models/Topic.php';
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $topic_id = intval($_POST['topic_id'] ?? 0);
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $file_path = null;
            $image_path = null;
            $requires_submission = !empty($_POST['requires_submission']) ? 1 : 0;
            $submission_type = $_POST['submission_type'] ?? 'file';
            $submission_instructions = trim($_POST['submission_instructions'] ?? '');
            $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
            // Handle file upload
            if (!empty($_FILES['file']['name'])) {
                $file_path = $this->handleUpload($_FILES['file'], ['pdf','doc','docx','txt','zip']);
                if ($file_path === false) $error = 'Invalid file upload.';
            }
            // Handle image upload
            if (!empty($_FILES['image']['name'])) {
                $image_path = $this->handleUpload($_FILES['image'], ['jpg','jpeg','png','gif','webp']);
                if ($image_path === false) $error = 'Invalid image upload.';
            }
            if (!$topic_id || !$title) {
                $error = 'Topic and title are required.';
            } elseif (!$error) {
                $result = Lecture::create($topic_id, $title, $content, $file_path, $image_path, $requires_submission, $submission_type, $submission_instructions, $due_date);
                if ($result) {
                    header('Location: ?page=admin&section=lectures');
                    exit;
                } else {
                    $error = 'Failed to create lecture.';
                }
            }
        }
        $topics = Topic::all();
        include __DIR__ . '/../views/admin/lectures/create.php';
    }
    public function edit($id = null) {
        require_once __DIR__ . '/../models/Lecture.php';
        require_once __DIR__ . '/../models/Topic.php';
        $error = '';
        $lecture = Lecture::find($id);
        if (!$lecture) {
            header('Location: ?page=admin&section=lectures');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $topic_id = intval($_POST['topic_id'] ?? 0);
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $file_path = $lecture['file_path'];
            $image_path = $lecture['image_path'];
            $requires_submission = !empty($_POST['requires_submission']) ? 1 : 0;
            $submission_type = $_POST['submission_type'] ?? 'file';
            $submission_instructions = trim($_POST['submission_instructions'] ?? '');
            $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
            // Handle file deletion
            if (!empty($_POST['delete_file']) && $file_path) {
                $this->deleteUpload($file_path);
                $file_path = null;
            }
            if (!empty($_POST['delete_image']) && $image_path) {
                $this->deleteUpload($image_path);
                $image_path = null;
            }
            // Handle file upload
            if (!empty($_FILES['file']['name'])) {
                $new_file = $this->handleUpload($_FILES['file'], ['pdf','doc','docx','txt','zip']);
                if ($new_file === false) {
                    $error = 'Invalid file upload.';
                } else {
                    if ($file_path) $this->deleteUpload($file_path);
                    $file_path = $new_file;
                }
            }
            // Handle image upload
            if (!empty($_FILES['image']['name'])) {
                $new_image = $this->handleUpload($_FILES['image'], ['jpg','jpeg','png','gif','webp']);
                if ($new_image === false) {
                    $error = 'Invalid image upload.';
                } else {
                    if ($image_path) $this->deleteUpload($image_path);
                    $image_path = $new_image;
                }
            }
            if (!$topic_id || !$title) {
                $error = 'Topic and title are required.';
            } elseif (!$error) {
                $result = Lecture::update($id, $topic_id, $title, $content, $file_path, $image_path, $requires_submission, $submission_type, $submission_instructions, $due_date);
                if ($result) {
                    header('Location: ?page=admin&section=lectures');
                    exit;
                } else {
                    $error = 'Failed to update lecture.';
                }
            }
        }
        $topics = Topic::all();
        include __DIR__ . '/../views/admin/lectures/edit.php';
    }
    public function delete($id = null) {
        require_once __DIR__ . '/../models/Lecture.php';
        if ($id) {
            Lecture::delete($id);
        }
        header('Location: ?page=admin&section=lectures');
        exit;
    }
    public function archive($id = null) {
        require_once __DIR__ . '/../models/Lecture.php';
        if ($id) {
            Lecture::archive($id);
        }
        header('Location: ?page=admin&section=lectures');
        exit;
    }
    public function restore($id = null) {
        require_once __DIR__ . '/../models/Lecture.php';
        if ($id) {
            Lecture::restore($id);
        }
        header('Location: ?page=admin&section=archive');
        exit;
    }
    public function archiveList() {
        require_once __DIR__ . '/../models/Lecture.php';
        $lectures = Lecture::all(1);
        include __DIR__ . '/../views/admin/lectures/archive.php';
    }
    public function submissions($id = null) {
        require_once __DIR__ . '/../models/Submission.php';
        require_once __DIR__ . '/../models/Lecture.php';
        require_once __DIR__ . '/../models/User.php';
        if (!$id) {
            header('Location: ?page=admin&section=lectures');
            exit;
        }
        $lecture = Lecture::find($id);
        $submissions = Submission::allForLectureWithStudent($id);
        // Handle grading/feedback POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submission_id'])) {
            $submission_id = intval($_POST['submission_id']);
            $grade = trim($_POST['grade'] ?? '');
            $feedback = trim($_POST['feedback'] ?? '');
            Submission::updateGradeAndFeedback($submission_id, $grade, $feedback);
            // Refresh submissions after update
            $submissions = Submission::allForLectureWithStudent($id);
        }
        include __DIR__ . '/../views/admin/lectures/submissions.php';
    }
    private function handleUpload($file, $allowed_exts) {
        if ($file['error'] !== UPLOAD_ERR_OK) return false;
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed_exts)) return false;
        $safeName = uniqid('upload_', true) . '.' . $ext;
        $dest = __DIR__ . '/../../public/uploads/' . $safeName;
        if (!move_uploaded_file($file['tmp_name'], $dest)) return false;
        return 'uploads/' . $safeName;
    }
    private function deleteUpload($path) {
        $fullPath = __DIR__ . '/../../public/' . ltrim($path, '/');
        if (file_exists($fullPath)) {
            @unlink($fullPath);
        }
    }
} 