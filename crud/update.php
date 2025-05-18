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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $course_id = intval($_POST['course_id']);

    $stmt = $conn->prepare("UPDATE lectures SET title = ?, content = ?, course_id = ? WHERE id = ?");
    $stmt->bind_param("ssii", $title, $content, $course_id, $lecture_id);

    if ($stmt->execute()) {
        header("Location: ../landing/dashboard.php");
        exit;
    } else {
        echo "Error updating lecture.";
    }
}

$stmt = $conn->prepare("SELECT * FROM lectures WHERE id = ?");
$stmt->bind_param("i", $lecture_id);
$stmt->execute();
$lecture = $stmt->get_result()->fetch_assoc();

$courses = $conn->query("SELECT id, title FROM courses");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Lecture</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, sans-serif;
      background-color: #f4f6f8;
      margin: 0;
      padding: 40px;
    }

    .container {
      max-width: 600px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    h1 {
      margin-bottom: 25px;
      color: #2f3542;
    }

    label {
      font-weight: bold;
      display: block;
      margin-top: 15px;
      color: #2f3542;
    }

    input[type="text"],
    textarea,
    select {
      width: 100%;
      padding: 10px;
      margin-top: 6px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    button {
      margin-top: 20px;
      padding: 10px 18px;
      border: none;
      border-radius: 5px;
      background-color: #2ed573;
      color: white;
      cursor: pointer;
      font-weight: bold;
    }

    a {
      margin-left: 10px;
      color: #2f3542;
      text-decoration: none;
      font-weight: bold;
    }

    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Edit Lecture</h1>
    <form method="POST">
      <label>Lecture Title:</label>
      <input type="text" name="title" value="<?= htmlspecialchars($lecture['title']) ?>" required>

      <label>Description:</label>
      <textarea name="content" rows="5" required><?= htmlspecialchars($lecture['content']) ?></textarea>

      <label>Course:</label>
      <select name="course_id" required>
        <?php while ($course = $courses->fetch_assoc()): ?>
          <option value="<?= $course['id'] ?>" <?= $course['id'] == $lecture['course_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($course['title']) ?>
          </option>
        <?php endwhile; ?>
      </select>

      <button type="submit">Update Lecture</button>
      <a href="../dashboard.php">Cancel</a>
    </form>
  </div>
</body>
</html>
