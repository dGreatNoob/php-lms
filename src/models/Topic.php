<?php
class Topic {
    public static function all($course_id = null) {
        global $conn;
        $sql = 'SELECT t.*, c.title as course_title, c.code as course_code FROM topics t JOIN courses c ON t.course_id = c.id WHERE t.archived = 0';
        
        if ($course_id) {
            $sql .= ' AND t.course_id = ?';
        }
        
        $sql .= ' ORDER BY c.title, t.parent_topic_id, t.title';

        $stmt = $conn->prepare($sql);
        if ($course_id) {
            $stmt->bind_param('i', $course_id);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    public static function allArchived() {
        global $conn;
        $sql = 'SELECT t.*, c.title AS course_title FROM topics t JOIN courses c ON t.course_id = c.id WHERE t.archived = 1';
        $result = $conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    public static function create($course_id, $parent_topic_id, $title, $description) {
        global $conn;
        $stmt = $conn->prepare('INSERT INTO topics (course_id, parent_topic_id, title, description) VALUES (?, ?, ?, ?)');
        if ($parent_topic_id === null) {
            $null = null;
            $stmt->bind_param('iiss', $course_id, $null, $title, $description);
        } else {
            $stmt->bind_param('iiss', $course_id, $parent_topic_id, $title, $description);
        }
        return $stmt->execute();
    }
    public static function find($id) {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM topics WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public static function update($id, $course_id, $parent_topic_id, $title, $description) {
        global $conn;
        $stmt = $conn->prepare('UPDATE topics SET course_id = ?, parent_topic_id = ?, title = ?, description = ? WHERE id = ?');
        if ($parent_topic_id === null) {
            $null = null;
            $stmt->bind_param('iissi', $course_id, $null, $title, $description, $id);
        } else {
            $stmt->bind_param('iissi', $course_id, $parent_topic_id, $title, $description, $id);
        }
        return $stmt->execute();
    }
    public static function delete($id) {
        global $conn;

        // 1. Archive all lectures under this topic
        $conn->query("UPDATE lectures SET archived = 1 WHERE topic_id = $id");

        // 2. Find and recursively delete all child topics
        $child_stmt = $conn->prepare('SELECT id FROM topics WHERE parent_topic_id = ?');
        $child_stmt->bind_param('i', $id);
        $child_stmt->execute();
        $child_result = $child_stmt->get_result();
        while($child_row = $child_result->fetch_assoc()) {
            self::delete($child_row['id']);
        }

        // 3. Archive the topic itself
        $stmt = $conn->prepare('UPDATE topics SET archived = 1 WHERE id = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
    public static function restore($id) {
        global $conn;
        $stmt = $conn->prepare('UPDATE topics SET archived = 0 WHERE id = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
    public static function getCourses() {
        global $conn;
        $result = $conn->query('SELECT id, code, title FROM courses');
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    public static function deletePermanent($id) {
        global $conn;
        // Delete all lectures under this topic (and their submissions)
        $lecture_stmt = $conn->prepare('SELECT id FROM lectures WHERE topic_id = ?');
        $lecture_stmt->bind_param('i', $id);
        $lecture_stmt->execute();
        $lecture_result = $lecture_stmt->get_result();
        require_once __DIR__ . '/Lecture.php';
        while ($row = $lecture_result->fetch_assoc()) {
            Lecture::deletePermanent($row['id']);
        }
        // Now delete the topic
        $stmt = $conn->prepare('DELETE FROM topics WHERE id = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
    public static function deleteAllArchived() {
        global $conn;
        $stmt = $conn->prepare('DELETE FROM topics WHERE archived = 1');
        $stmt->execute();
    }
} 