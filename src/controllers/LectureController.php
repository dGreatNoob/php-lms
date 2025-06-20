<?php
class LectureController {
    public function index() {
        require_once __DIR__ . '/../models/Lecture.php';
        require_once __DIR__ . '/../models/Course.php';
        require_once __DIR__ . '/../models/Topic.php';

        $course_id = isset($_GET['course_id']) && !empty($_GET['course_id']) ? intval($_GET['course_id']) : null;
        $topic_id = isset($_GET['topic_id']) && !empty($_GET['topic_id']) ? intval($_GET['topic_id']) : null;

        $lectures = Lecture::all($course_id, $topic_id);
        $courses = Course::all();
        $topics = Topic::all(); 

        include __DIR__ . '/../views/admin/lectures/index.php';
    }
    public function create() {
        require_once __DIR__ . '/../models/Topic.php';
        require_once __DIR__ . '/../models/Course.php';
        require_once __DIR__ . '/../models/Lecture.php';
        require_once __DIR__ . '/../models/Activity.php';

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $topic_id = $_POST['topic_id'];
            $title = $_POST['title'];
            $content = $_POST['content'];
            $allow_submissions = isset($_POST['allow_submissions']) ? 1 : 0;
            $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;

            // Handle file upload
            $attachment_path = null;
            if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
                $upload_dir = 'uploads/';
                $file_name = uniqid('upload_') . '.' . pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
                $attachment_path = $upload_dir . $file_name;
                if (!move_uploaded_file($_FILES['attachment']['tmp_name'], $attachment_path)) {
                    $error = 'Failed to upload attachment.';
                    $attachment_path = null;
                }
            }

            if (empty($error) && Lecture::create($topic_id, $title, $content, $attachment_path, $allow_submissions, $due_date)) {
                Activity::log(
                    $_SESSION['user_id'],
                    'create',
                    'lecture',
                    $conn->insert_id,
                    "Created lecture: " . $title
                );
                header('Location: ?page=admin&section=lectures');
                exit;
            } else {
                $error = $error ?: 'Failed to create lecture.';
            }
        }

        $topics = Topic::all();
        $courses = Course::all();
        include __DIR__ . '/../views/admin/lectures/create.php';
    }
    public function edit($id = null) {
        require_once __DIR__ . '/../models/Lecture.php';
        require_once __DIR__ . '/../models/Topic.php';
        require_once __DIR__ . '/../models/Activity.php';
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
            $allow_submissions = !empty($_POST['allow_submissions']) ? 1 : 0;
            $attachment = $lecture['attachment'];
            $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
            // Handle file deletion
            if (!empty($_POST['delete_attachment']) && $attachment) {
                $this->deleteUpload($attachment);
                $attachment = null;
            }
            // Handle file upload
            if (!empty($_FILES['attachment']['name'])) {
                $new_file = $this->handleUpload($_FILES['attachment'], ['pdf','doc','docx','txt','zip']);
                if ($new_file === false) {
                    $error = 'Invalid file upload.';
                } else {
                    if ($attachment) $this->deleteUpload($attachment);
                    $attachment = $new_file;
                }
            }
            if (!$topic_id || !$title) {
                $error = 'Topic and title are required.';
            } elseif (!$error) {
                $result = Lecture::update($id, $topic_id, $title, $content, $attachment, $allow_submissions, $due_date);
                if ($result) {
                    // Log the activity
                    $user_id = $_SESSION['user_id'] ?? null;
                    Activity::logLectureUpdated($user_id, $id, $title);
                    
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
        require_once __DIR__ . '/../models/Activity.php';
        if ($id) {
            $lecture = Lecture::find($id);
            if ($lecture) {
                $result = Lecture::delete($id);
                if ($result) {
                    // Log the activity
                    $user_id = $_SESSION['user_id'] ?? null;
                    Activity::logLectureArchived($user_id, $id, $lecture['title']);
                }
            }
        }
        header('Location: ?page=admin&section=lectures');
        exit;
    }
    public function archive($id = null) {
        require_once __DIR__ . '/../models/Lecture.php';
        require_once __DIR__ . '/../models/Activity.php';
        if ($id) {
            $lecture = Lecture::find($id);
            if ($lecture) {
                $result = Lecture::archive($id);
                if ($result) {
                    // Log the activity
                    $user_id = $_SESSION['user_id'] ?? null;
                    Activity::logLectureArchived($user_id, $id, $lecture['title']);
                }
            }
        }
        header('Location: ?page=admin&section=lectures');
        exit;
    }
    public function restore($id = null) {
        require_once __DIR__ . '/../models/Lecture.php';
        require_once __DIR__ . '/../models/Activity.php';
        if ($id) {
            $lecture = Lecture::find($id);
            if ($lecture) {
                $result = Lecture::restore($id);
                if ($result) {
                    // Log the activity
                    $user_id = $_SESSION['user_id'] ?? null;
                    Activity::logLectureRestored($user_id, $id, $lecture['title']);
                }
            }
        }
        $type = $_GET['type'] ?? 'lectures';
        header('Location: ?page=admin&section=archive&type=' . urlencode($type));
        exit;
    }
    public function archiveList() {
        require_once __DIR__ . '/../models/Lecture.php';
        $lectures = Lecture::all(1);
        include __DIR__ . '/../views/admin/lectures/archive.php';
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