<?php
class Topic {
    public static function all() {
        global $conn;
        $sql = 'SELECT t.*, c.title AS course_title FROM topics t JOIN courses c ON t.course_id = c.id WHERE t.archived = 0';
        $result = $conn->query($sql);
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
        $stmt = $conn->prepare('DELETE FROM topics WHERE id = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
} 