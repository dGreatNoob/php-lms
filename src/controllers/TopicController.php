<?php
class TopicController {
    public function index() {
        require_once __DIR__ . '/../models/Topic.php';
        $topics = Topic::all();
        include __DIR__ . '/../views/admin/topics/index.php';
    }
    public function create() {
        require_once __DIR__ . '/../models/Topic.php';
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
        if ($id) {
            Topic::delete($id);
        }
        header('Location: ?page=admin&section=topics');
        exit;
    }
} 