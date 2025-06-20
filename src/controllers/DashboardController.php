<?php
class DashboardController {
    public function index() {
        require_once __DIR__ . '/../models/User.php';
        require_once __DIR__ . '/../models/Enrollment.php';
        require_once __DIR__ . '/../models/Course.php';
        require_once __DIR__ . '/../models/Topic.php';
        require_once __DIR__ . '/../models/Lecture.php';
        require_once __DIR__ . '/../models/Activity.php';
        
        $role = $_SESSION['role'] ?? 'student';
        $user_id = $_SESSION['user_id'] ?? null;
        $full_name = '';
        
        if ($user_id) {
            $user = User::findById($user_id);
            if ($user) {
                $full_name = $user['first_name'] . ' ' . $user['last_name'];
            }
        }
        
        if ($role === 'admin') {
            // Fetch statistics for admin dashboard
            global $conn;
            
            // Get course count
            $course_result = $conn->query('SELECT COUNT(*) as count FROM courses WHERE archived = 0');
            $course_count = $course_result->fetch_assoc()['count'];
            
            // Get student count
            $student_result = $conn->query('SELECT COUNT(*) as count FROM users WHERE role = "student" AND archived = 0');
            $student_count = $student_result->fetch_assoc()['count'];
            
            // Get lecture count
            $lecture_result = $conn->query('SELECT COUNT(*) as count FROM lectures WHERE archived = 0');
            $lecture_count = $lecture_result->fetch_assoc()['count'];
            
            // Get pending submissions count
            $submission_result = $conn->query('SELECT COUNT(*) as count FROM submissions WHERE grade IS NULL');
            $submission_count = $submission_result->fetch_assoc()['count'];
            
            $stats = [
                'courses' => $course_count,
                'students' => $student_count,
                'lectures' => $lecture_count,
                'submissions' => $submission_count
            ];
            
            // Fetch recent activity
            $recent_activity = Activity::getFormattedRecent(15);
            
            include __DIR__ . '/../views/dashboard/admin.php';
        } else {
            // Fetch enrolled courses for the student
            global $conn;
            $courses = [];
            $topic_tree = [];
            $lectures_by_topic = [];
            require_once __DIR__ . '/../models/Submission.php';
            // Handle student submission
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lecture_id']) && (isset($_POST['submit_lecture']) || isset($_POST['resubmit']))) {
                $lecture_id = intval($_POST['lecture_id']);
                $student_id = $user_id;
                $text_submission = null;
                $file_path = null;
                if (!empty($_POST['submission_text'])) {
                    $text_submission = trim($_POST['submission_text']);
                }
                if (!empty($_FILES['submission_file']['name'])) {
                    $file = $_FILES['submission_file'];
                    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    $allowed_exts = ['pdf','doc','docx','txt','zip','jpg','jpeg','png','gif','webp'];
                    if (in_array($ext, $allowed_exts) && $file['error'] === UPLOAD_ERR_OK) {
                        $safeName = uniqid('submission_', true) . '.' . $ext;
                        $dest = __DIR__ . '/../../public/uploads/' . $safeName;
                        if (move_uploaded_file($file['tmp_name'], $dest)) {
                            $file_path = 'uploads/' . $safeName;
                        }
                    }
                }
                // Remove previous submission if resubmitting
                if (isset($_POST['resubmit'])) {
                    $prev = Submission::findByStudentAndLecture($student_id, $lecture_id);
                    if ($prev && $prev['file_path']) {
                        $prev_path = __DIR__ . '/../../public/' . ltrim($prev['file_path'], '/');
                        if (file_exists($prev_path)) @unlink($prev_path);
                    }
                    $conn->query("DELETE FROM submissions WHERE student_id = $student_id AND lecture_id = $lecture_id");
                }
                if ($text_submission || $file_path) {
                    $submission_id = Submission::create($student_id, $lecture_id, $text_submission, $file_path);
                    
                    // Log the submission activity
                    if ($submission_id) {
                        $lecture = Lecture::find($lecture_id);
                        if ($lecture) {
                            Activity::logSubmissionSubmitted($student_id, $submission_id, $lecture['title']);
                        }
                    }
                }
            }
            if ($user_id) {
                // Get enrolled course IDs
                $stmt = $conn->prepare('SELECT course_id FROM enrollments WHERE student_id = ?');
                $stmt->bind_param('i', $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $course_ids = [];
                while ($row = $result->fetch_assoc()) {
                    $course_ids[] = $row['course_id'];
                }
                if (!empty($course_ids)) {
                    // Fetch course details
                    $in = implode(',', array_fill(0, count($course_ids), '?'));
                    $types = str_repeat('i', count($course_ids));
                    $stmt = $conn->prepare('SELECT * FROM courses WHERE id IN (' . $in . ') AND archived = 0');
                    $stmt->bind_param($types, ...$course_ids);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $courses = $result->fetch_all(MYSQLI_ASSOC);
                    // Fetch topics for these courses
                    $topic_stmt = $conn->prepare('SELECT * FROM topics WHERE course_id IN (' . $in . ') AND archived = 0');
                    $topic_stmt->bind_param($types, ...$course_ids);
                    $topic_stmt->execute();
                    $topic_result = $topic_stmt->get_result();
                    $topics = $topic_result->fetch_all(MYSQLI_ASSOC);
                    // Build topic tree by course
                    foreach ($courses as $course) {
                        $topic_tree[$course['id']] = [];
                        foreach ($topics as $topic) {
                            if ($topic['course_id'] == $course['id']) {
                                $topic_tree[$course['id']][] = $topic;
                            }
                        }
                    }
                    // Fetch lectures for these topics
                    $topic_ids = array_column($topics, 'id');
                    if (!empty($topic_ids)) {
                        $in_topics = implode(',', array_fill(0, count($topic_ids), '?'));
                        $topic_types = str_repeat('i', count($topic_ids));
                        $lecture_stmt = $conn->prepare('SELECT * FROM lectures WHERE topic_id IN (' . $in_topics . ') AND archived = 0');
                        $lecture_stmt->bind_param($topic_types, ...$topic_ids);
                        $lecture_stmt->execute();
                        $lecture_result = $lecture_stmt->get_result();
                        $lectures = $lecture_result->fetch_all(MYSQLI_ASSOC);
                        foreach ($lectures as $lecture) {
                            $lectures_by_topic[$lecture['topic_id']][] = $lecture;
                        }
                    }
                }
            }
            include __DIR__ . '/../views/dashboard/student.php';
        }
    }
    
    public function archive() {
        require_once __DIR__ . '/../models/Lecture.php';
        require_once __DIR__ . '/../models/Topic.php';
        require_once __DIR__ . '/../models/Course.php';
        require_once __DIR__ . '/../models/User.php';
        $archived_lectures = Lecture::all(1);
        $archived_topics = Topic::allArchived();
        $archived_courses = Course::allArchived();
        $archived_users = User::allArchived();
        include __DIR__ . '/../views/admin/archive/index.php';
    }
} 