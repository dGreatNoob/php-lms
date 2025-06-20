<?php
class SubmissionsController {
    public function index() {
        require_once __DIR__ . '/../models/Submission.php';
        require_once __DIR__ . '/../models/Course.php';
        require_once __DIR__ . '/../models/Topic.php';
        require_once __DIR__ . '/../models/Lecture.php';
        require_once __DIR__ . '/../models/User.php';
        require_once __DIR__ . '/../models/Activity.php';
        $submissions = Submission::allWithDetails();
        // Handle grading/feedback POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submission_id'])) {
            $submission_id = intval($_POST['submission_id']);
            $grade = trim($_POST['grade'] ?? '');
            $feedback = trim($_POST['feedback'] ?? '');
            $result = Submission::updateGradeAndFeedback($submission_id, $grade, $feedback);
            if ($result) {
                $user_id = $_SESSION['user_id'] ?? null;
                $submission = Submission::find($submission_id);
                if ($submission) {
                    $student = User::findById($submission['student_id']);
                    $lecture = Lecture::find($submission['lecture_id']);
                    $student_name = $student ? $student['first_name'] . ' ' . $student['last_name'] : 'Unknown Student';
                    Activity::logSubmissionGraded($user_id, $submission_id, $student_name, $lecture['title']);
                }
            }
            // Refresh submissions after update
            $submissions = Submission::allWithDetails();
        }
        include __DIR__ . '/../views/admin/submissions/index.php';
    }
} 