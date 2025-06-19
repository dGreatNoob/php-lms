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
} 