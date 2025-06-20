<?php
class Enrollment {
    public static function all() {
        global $conn;
        $sql = 'SELECT e.id, u.first_name, u.last_name, u.username, c.code AS course_code, c.title AS course_title FROM enrollments e JOIN users u ON e.student_id = u.id JOIN courses c ON e.course_id = c.id';
        $result = $conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    
    public static function find($id) {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM enrollments WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    public static function create($student_id, $course_id) {
        global $conn;
        $stmt = $conn->prepare('INSERT INTO enrollments (student_id, course_id) VALUES (?, ?)');
        $stmt->bind_param('ii', $student_id, $course_id);
        return $stmt->execute();
    }
    
    public static function delete($id) {
        global $conn;
        $stmt = $conn->prepare('DELETE FROM enrollments WHERE id = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
} 