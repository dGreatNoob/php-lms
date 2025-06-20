<?php
class Course {
    // Add course methods here

    public static function all() {
        global $conn;
        $result = $conn->query('SELECT * FROM courses WHERE archived = 0');
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function allArchived() {
        global $conn;
        $result = $conn->query('SELECT * FROM courses WHERE archived = 1');
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function create($code, $title, $semester) {
        global $conn;
        $stmt = $conn->prepare('INSERT INTO courses (code, title, semester) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $code, $title, $semester);
        return $stmt->execute();
    }

    public static function find($id) {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM courses WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public static function update($id, $code, $title, $semester) {
        global $conn;
        $stmt = $conn->prepare('UPDATE courses SET code = ?, title = ?, semester = ? WHERE id = ?');
        $stmt->bind_param('sssi', $code, $title, $semester, $id);
        return $stmt->execute();
    }

    public static function delete($id) {
        global $conn;

        // 1. Find all topics for the course
        $topic_stmt = $conn->prepare('SELECT id FROM topics WHERE course_id = ?');
        $topic_stmt->bind_param('i', $id);
        $topic_stmt->execute();
        $topic_result = $topic_stmt->get_result();
        $topic_ids = [];
        while($row = $topic_result->fetch_assoc()) {
            $topic_ids[] = $row['id'];
        }

        // 2. Archive all topics and their lectures
        require_once __DIR__ . '/../models/Topic.php';
        foreach($topic_ids as $topic_id) {
            Topic::delete($topic_id); // This will handle lectures recursively
        }

        // 3. Archive the course itself
        $stmt = $conn->prepare('UPDATE courses SET archived = 1 WHERE id = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public static function restore($id) {
        global $conn;
        $stmt = $conn->prepare('UPDATE courses SET archived = 0 WHERE id = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public static function findByCode($code) {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM courses WHERE code = ?');
        $stmt->bind_param('s', $code);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public static function deletePermanent($id) {
        global $conn;
        $stmt = $conn->prepare('DELETE FROM courses WHERE id = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public static function deleteAllArchived() {
        global $conn;
        $stmt = $conn->prepare('DELETE FROM courses WHERE archived = 1');
        $stmt->execute();
    }
} 