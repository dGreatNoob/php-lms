<?php
class TopicController {
    public function index() {
        require_once __DIR__ . '/../models/Topic.php';
        require_once __DIR__ . '/../models/Course.php';

        $course_id = isset($_GET['course_id']) && !empty($_GET['course_id']) ? intval($_GET['course_id']) : null;
        
        $topics = Topic::all($course_id);
        $courses = Course::all();

        include __DIR__ . '/../views/admin/topics/index.php';
    }
    public function create() {
        require_once __DIR__ . '/../models/Topic.php';
        require_once __DIR__ . '/../models/Activity.php';
        
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $course_id = intval($_POST['course_id'] ?? 0);
            $parent_topic_id = $_POST['parent_topic_id'] !== '' ? intval($_POST['parent_topic_id']) : null;
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            if (!$course_id || !$title) {
                $error = 'Course and title are required.';
            } else {
                $result = Topic::create($course_id, $parent_topic_id, $title, $description);
                if ($result) {
                    // Log the activity
                    global $conn;
                    $user_id = $_SESSION['user_id'] ?? null;
                    $topic_id = $conn->insert_id;
                    Activity::logTopicCreated($user_id, $topic_id, $title);
                    
                    header('Location: ?page=admin&section=topics');
                    exit;
                } else {
                    $error = 'Failed to create topic.';
                }
            }
        }
        $courses = Topic::getCourses();
        $all_topics = Topic::all();
        include __DIR__ . '/../views/admin/topics/create.php';
    }
    public function edit($id = null) {
        require_once __DIR__ . '/../models/Topic.php';
        require_once __DIR__ . '/../models/Activity.php';
        
        $error = '';
        $topic = Topic::find($id);
        if (!$topic) {
            header('Location: ?page=admin&section=topics');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $course_id = intval($_POST['course_id'] ?? 0);
            $parent_topic_id = $_POST['parent_topic_id'] !== '' ? intval($_POST['parent_topic_id']) : null;
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            if (!$course_id || !$title) {
                $error = 'Course and title are required.';
            } else {
                $result = Topic::update($id, $course_id, $parent_topic_id, $title, $description);
                if ($result) {
                    // Log the activity
                    $user_id = $_SESSION['user_id'] ?? null;
                    Activity::logTopicUpdated($user_id, $id, $title);
                    
                    header('Location: ?page=admin&section=topics');
                    exit;
                } else {
                    $error = 'Failed to update topic.';
                }
            }
        }
        $courses = Topic::getCourses();
        $all_topics = Topic::all();
        include __DIR__ . '/../views/admin/topics/edit.php';
    }
    public function delete($id = null) {
        require_once __DIR__ . '/../models/Topic.php';
        require_once __DIR__ . '/../models/Activity.php';
        
        if ($id) {
            $topic = Topic::find($id);
            if ($topic) {
                $result = Topic::delete($id);
                if ($result) {
                    // Log the activity
                    $user_id = $_SESSION['user_id'] ?? null;
                    Activity::logTopicArchived($user_id, $id, $topic['title']);
                }
            }
        }
        header('Location: ?page=admin&section=topics');
        exit;
    }
} 