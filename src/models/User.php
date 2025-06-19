<?php
class User {
    public static function all() {
        global $conn;
        $result = $conn->query("SELECT * FROM users WHERE archived = 0");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    public static function allArchived() {
        global $conn;
        $result = $conn->query("SELECT * FROM users WHERE archived = 1");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    public static function findByUsername($username) {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM users WHERE username = ? AND archived = 0');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public static function findByEmail($email) {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM users WHERE email = ? AND archived = 0');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public static function create($first_name, $last_name, $username, $email, $password, $role) {
        global $conn;
        $stmt = $conn->prepare('INSERT INTO users (first_name, last_name, username, email, password, role) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('ssssss', $first_name, $last_name, $username, $email, $password, $role);
        $stmt->execute();
        return $conn->insert_id;
    }
    public static function findById($id) {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM users WHERE id = ? AND archived = 0');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public static function delete($id) {
        global $conn;
        $stmt = $conn->prepare('UPDATE users SET archived = 1 WHERE id = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
    public static function restore($id) {
        global $conn;
        $stmt = $conn->prepare('UPDATE users SET archived = 0 WHERE id = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
    public static function deletePermanent($id) {
        global $conn;
        $stmt = $conn->prepare('DELETE FROM users WHERE id = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
} 