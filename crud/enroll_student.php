<?php
session_start();
include '../config/db.php';

// Check if admin
if (!isset($_SESSION['user_id'], $_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php?page=login");
    exit;
}

// Fetch users (non-admins)
$users = $conn->query("SELECT id, name FROM users WHERE role != 'admin'");

// Fetch courses
$courses = $conn->query("SELECT id, title FROM courses");

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = intval($_POST['student_id']);
    $course_id = intval($_POST['course_id']);

    // Prevent duplicate enrollments
    $check = $conn->prepare("SELECT * FROM enrollments WHERE student_id = ? AND course_id = ?");
    $check->bind_param("ii", $student_id, $course_id);
    $check->execute();
    $checkResult = $check->get_result();

    if ($checkResult->num_rows === 0) {
        $stmt = $conn->prepare("INSERT INTO enrollments (student_id, course_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $student_id, $course_id);
        $stmt->execute();
        $message = "Enrollment successful!";
    } else {
        $message = "Student is already enrolled in this course.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Enroll Student</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 30px;
      background-color: #f4f6f8;
    }
    form {
      background: white;
      padding: 20px;
      border-radius: 8px;
      max-width: 400px;
      margin: auto;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    label, select {
      display: block;
      margin-bottom: 10px;
    }
    select, button {
      width: 100%;
      padding: 8px;
    }
    button {
      background-color: #2ed573;
      color: white;
      border: none;
      border-radius: 4px;
      margin-top: 10px;
    }
    .message {
      margin: 20px auto;
      max-width: 400px;
      text-align: center;
      color: green;
    }
  </style>
</head>
<body>

<h2 style="text-align: center;">Enroll a Student</h2>

<?php if (!empty($message)): ?>
  <p class="message"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="POST">
  <label for="student_id">Select Student:</label>
  <select name="student_id" required>
    <option value="">-- Choose Student --</option>
    <?php while ($user = $users->fetch_assoc()): ?>
      <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?></option>
    <?php endwhile; ?>
  </select>

  <label for="course_id">Select Course:</label>
  <select name="course_id" required>
    <option value="">-- Choose Course --</option>
    <?php while ($course = $courses->fetch_assoc()): ?>
      <option value="<?= $course['id'] ?>"><?= htmlspecialchars($course['title']) ?></option>
    <?php endwhile; ?>
  </select>

  <button type="submit">Enroll Student</button>
</form>

</body>
</html>
