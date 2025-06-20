<?php
class Activity {
    
    public static function log($user_id, $action_type, $entity_type, $entity_id, $details = null) {
        global $conn;
        $stmt = $conn->prepare('INSERT INTO activities (user_id, action_type, entity_type, entity_id, details) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('issis', $user_id, $action_type, $entity_type, $entity_id, $details);
        return $stmt->execute();
    }
    
    public static function getRecent($limit = 20) {
        global $conn;
        $stmt = $conn->prepare('
            SELECT a.*, u.first_name, u.last_name, u.username 
            FROM activities a 
            LEFT JOIN users u ON a.user_id = u.id 
            ORDER BY a.created_at DESC 
            LIMIT ?
        ');
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public static function formatActivity($activity) {
        $action_names = [
            'create' => 'Created',
            'update' => 'Updated',
            'delete' => 'Deleted',
            'archive' => 'Archived',
            'restore' => 'Restored',
            'enroll' => 'Enrolled',
            'unenroll' => 'Unenrolled',
            'submit' => 'Submitted',
            'grade' => 'Graded'
        ];

        $action_class_map = [
            'create'   => 'badge--success',
            'restore'  => 'badge--success',
            'update'   => 'badge--primary',
            'enroll'   => 'badge--primary',
            'submit'   => 'badge--primary',
            'grade'    => 'badge--info',
            'archive'  => 'badge--warning',
            'delete'   => 'badge--danger',
            'unenroll' => 'badge--danger'
        ];
        
        $entity_names = [
            'course' => 'Course',
            'topic' => 'Topic',
            'lecture' => 'Lecture',
            'user' => 'User',
            'enrollment' => 'Enrollment',
            'submission' => 'Submission'
        ];
        
        $action_text = $action_names[$activity['action_type']] ?? ucfirst($activity['action_type']);
        $entity_name = $entity_names[$activity['entity_type']] ?? ucfirst($activity['entity_type']);
        
        $user_name = $activity['first_name'] && $activity['last_name'] 
            ? $activity['first_name'] . ' ' . $activity['last_name']
            : ($activity['username'] ?? 'System');
            
        $details = $activity['details'] ?: "{$action_text} {$entity_name}";
        
        return [
            'action' => $action_text,
            'action_class' => $action_class_map[$activity['action_type']] ?? 'badge--muted',
            'details' => $details,
            'user' => $user_name,
            'date' => date('M j, Y g:i A', strtotime($activity['created_at'])),
            'timestamp' => $activity['created_at']
        ];
    }
    
    public static function getFormattedRecent($limit = 20) {
        $activities = self::getRecent($limit);
        $formatted = [];
        
        foreach ($activities as $activity) {
            $formatted[] = self::formatActivity($activity);
        }
        
        return $formatted;
    }
    
    public static function logCourseCreated($user_id, $course_id, $course_title) {
        return self::log($user_id, 'create', 'course', $course_id, "Created course: {$course_title}");
    }
    
    public static function logCourseUpdated($user_id, $course_id, $course_title) {
        return self::log($user_id, 'update', 'course', $course_id, "Updated course: {$course_title}");
    }
    
    public static function logCourseArchived($user_id, $course_id, $course_title) {
        return self::log($user_id, 'archive', 'course', $course_id, "Archived course: {$course_title}");
    }
    
    public static function logCourseRestored($user_id, $course_id, $course_title) {
        return self::log($user_id, 'restore', 'course', $course_id, "Restored course: {$course_title}");
    }
    
    public static function logTopicCreated($user_id, $topic_id, $topic_title) {
        return self::log($user_id, 'create', 'topic', $topic_id, "Created topic: {$topic_title}");
    }
    
    public static function logTopicUpdated($user_id, $topic_id, $topic_title) {
        return self::log($user_id, 'update', 'topic', $topic_id, "Updated topic: {$topic_title}");
    }
    
    public static function logTopicArchived($user_id, $topic_id, $topic_title) {
        return self::log($user_id, 'archive', 'topic', $topic_id, "Archived topic: {$topic_title}");
    }
    
    public static function logTopicRestored($user_id, $topic_id, $topic_title) {
        return self::log($user_id, 'restore', 'topic', $topic_id, "Restored topic: {$topic_title}");
    }
    
    public static function logLectureCreated($user_id, $lecture_id, $lecture_title) {
        return self::log($user_id, 'create', 'lecture', $lecture_id, "Created lecture: {$lecture_title}");
    }
    
    public static function logLectureUpdated($user_id, $lecture_id, $lecture_title) {
        return self::log($user_id, 'update', 'lecture', $lecture_id, "Updated lecture: {$lecture_title}");
    }
    
    public static function logLectureArchived($user_id, $lecture_id, $lecture_title) {
        return self::log($user_id, 'archive', 'lecture', $lecture_id, "Archived lecture: {$lecture_title}");
    }
    
    public static function logLectureRestored($user_id, $lecture_id, $lecture_title) {
        return self::log($user_id, 'restore', 'lecture', $lecture_id, "Restored lecture: {$lecture_title}");
    }
    
    public static function logUserCreated($user_id, $new_user_id, $user_name) {
        return self::log($user_id, 'create', 'user', $new_user_id, "Created user: {$user_name}");
    }
    
    public static function logUserArchived($user_id, $archived_user_id, $user_name) {
        return self::log($user_id, 'archive', 'user', $archived_user_id, "Archived user: {$user_name}");
    }
    
    public static function logUserRestored($user_id, $restored_user_id, $user_name) {
        return self::log($user_id, 'restore', 'user', $restored_user_id, "Restored user: {$user_name}");
    }
    
    public static function logEnrollmentCreated($user_id, $enrollment_id, $student_name, $course_title) {
        return self::log($user_id, 'enroll', 'enrollment', $enrollment_id, "Enrolled {$student_name} in {$course_title}");
    }
    
    public static function logEnrollmentDeleted($user_id, $enrollment_id, $student_name, $course_title) {
        return self::log($user_id, 'unenroll', 'enrollment', $enrollment_id, "Unenrolled {$student_name} from {$course_title}");
    }
    
    public static function logSubmissionSubmitted($user_id, $submission_id, $lecture_title) {
        return self::log($user_id, 'submit', 'submission', $submission_id, "Submitted assignment for: {$lecture_title}");
    }
    
    public static function logSubmissionGraded($user_id, $submission_id, $student_name, $lecture_title) {
        return self::log($user_id, 'grade', 'submission', $submission_id, "Graded {$student_name}'s submission for: {$lecture_title}");
    }
} 