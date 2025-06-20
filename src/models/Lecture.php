<?php
class Lecture {
    public static function all($course_id = null, $topic_id = null) {
        global $conn;
        
        $sql = 'SELECT l.*, t.title AS topic_title, c.title AS course_title
                FROM lectures l
                JOIN topics t ON l.topic_id = t.id
                JOIN courses c ON t.course_id = c.id
                WHERE l.archived = 0';

        $params = [];
        $types = '';

        if ($course_id) {
            $sql .= ' AND c.id = ?';
            $params[] = $course_id;
            $types .= 'i';
        }

        if ($topic_id) {
            $sql .= ' AND t.id = ?';
            $params[] = $topic_id;
            $types .= 'i';
        }

        $sql .= ' ORDER BY c.title, t.title, l.title';
        
        $stmt = $conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    public static function create($topic_id, $title, $content, $attachment_path, $allow_submissions, $due_date) {
        global $conn;
        $sql = 'INSERT INTO lectures (topic_id, title, content, attachment, allow_submissions, due_date) VALUES (?, ?, ?, ?, ?, ?)';
        $stmt = $conn->prepare($sql);
        // "isssis" corresponds to integer, string, string, string, integer, string
        $stmt->bind_param('isssis', $topic_id, $title, $content, $attachment_path, $allow_submissions, $due_date);
        return $stmt->execute();
    }
    public static function find($id) {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM lectures WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public static function update($id, $topic_id, $title, $content, $file_path = null, $image_path = null, $requires_submission = 0, $submission_type = 'file', $submission_instructions = null, $due_date = null) {
        global $conn;
        $stmt = $conn->prepare('UPDATE lectures SET topic_id = ?, title = ?, content = ?, file_path = ?, image_path = ?, requires_submission = ?, submission_type = ?, submission_instructions = ?, due_date = ? WHERE id = ?');
        $stmt->bind_param('issssisssi', $topic_id, $title, $content, $file_path, $image_path, $requires_submission, $submission_type, $submission_instructions, $due_date, $id);
        return $stmt->execute();
    }
    public static function deleteSubmissions($lecture_id) {
        global $conn;
        $stmt = $conn->prepare('DELETE FROM submissions WHERE lecture_id = ?');
        $stmt->bind_param('i', $lecture_id);
        return $stmt->execute();
    }
    public static function delete($id) {
        self::deleteSubmissions($id);
        global $conn;
        $stmt = $conn->prepare('UPDATE lectures SET archived = 1 WHERE id = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
    public static function archive($id) {
        global $conn;
        $stmt = $conn->prepare('UPDATE lectures SET archived = 1 WHERE id = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
    public static function restore($id) {
        global $conn;
        $stmt = $conn->prepare('UPDATE lectures SET archived = 0 WHERE id = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
    public static function deletePermanent($id) {
        self::deleteSubmissions($id);
        global $conn;
        $stmt = $conn->prepare('DELETE FROM lectures WHERE id = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
} 