<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'], $_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php?page=login");
    exit;
}

if (!isset($_GET['id'])) {
    echo "Lecture ID not provided.";
    exit;
}

$lecture_id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM lectures WHERE id = ?");
$stmt->bind_param("i", $lecture_id);

if ($stmt->execute()) {
    header("Location: ../landing/dashboard.php");
    exit;
} else {
    echo "Error deleting lecture.";
}
