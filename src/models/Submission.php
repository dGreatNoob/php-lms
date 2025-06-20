<?php
class Submission {
    public static function create($student_id, $lecture_id, $text_submission = null, $file_path = null) {
        global $conn;
        $stmt = $conn->prepare('INSERT INTO submissions (student_id, lecture_id, text_submission, file_path) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('iiss', $student_id, $lecture_id, $text_submission, $file_path);
        return $stmt->execute();
    }
    
    public static function find($id) {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM submissions WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    public static function findByStudentAndLecture($student_id, $lecture_id) {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM submissions WHERE student_id = ? AND lecture_id = ?');
        $stmt->bind_param('ii', $student_id, $lecture_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    public static function allForLecture($lecture_id) {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM submissions WHERE lecture_id = ?');
        $stmt->bind_param('i', $lecture_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    
    public static function updateGradeAndFeedback($id, $grade, $feedback) {
        global $conn;
        $stmt = $conn->prepare('UPDATE submissions SET grade = ?, feedback = ? WHERE id = ?');
        $stmt->bind_param('ssi', $grade, $feedback, $id);
        return $stmt->execute();
    }
    
    public static function allForLectureWithStudent($lecture_id) {
        global $conn;
        $stmt = $conn->prepare('SELECT s.*, u.first_name, u.last_name, u.username FROM submissions s JOIN users u ON s.student_id = u.id WHERE s.lecture_id = ?');
        $stmt->bind_param('i', $lecture_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
} 