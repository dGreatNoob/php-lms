<?php
session_start();
include '../config/db.php';

// Check if admin
if (!isset($_SESSION['user_id'], $_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php?page=login");
    exit;
}

// Fetch courses for the dropdown
$courses = $conn->query("SELECT id, title FROM courses");

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['content']);
    $course_id = intval($_POST['course_id']);

    // Basic validation
    if ($title === '' || $course_id === 0) {
        $message = "Please provide a lecture title and select a course.";
    } else {
        // Prevent duplicate lecture titles for the same course
        $check = $conn->prepare("SELECT * FROM lectures WHERE title = ? AND course_id = ?");
        $check->bind_param("si", $title, $course_id);
        $check->execute();
        $checkResult = $check->get_result();

        if ($checkResult->num_rows === 0) {
            $stmt = $conn->prepare("INSERT INTO lectures (title, content, course_id) VALUES (?, ?, ?)");
            $stmt->bind_param("ssi", $title, $description, $course_id);
            if ($stmt->execute()) {
                $message = "Lecture created successfully!";
            } else {
                $message = "Error creating lecture. Please try again.";
            }
        } else {
            $message = "A lecture with this title already exists for the selected course.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Create Lecture</title>
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
    label, input, select, textarea {
      display: block;
      margin-bottom: 10px;
      width: 100%;
    }
    input, select, textarea {
      padding: 8px;
      box-sizing: border-box;
      font-family: Arial, sans-serif;
      font-size: 1em;
    }
    textarea {
      min-height: 100px;
      resize: vertical;
    }
    button {
      background-color: #2ed573;
      color: white;
      border: none;
      border-radius: 4px;
      padding: 10px;
      width: 100%;
      cursor: pointer;
      font-size: 1em;
    }

    .back-button {
        background-color: #576574;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 10px 20px;
        font-family: Arial, sans-serif;
        cursor: pointer;
        width: 100%;
        font-size: 1em;
        margin-top: 15px;
    }
    .back-button:hover {
        background-color: #485460;
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

    <h2 style="text-align: center;">Create New Lecture</h2>

    <?php if (!empty($message)): ?>
    <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
    <label for="title">Lecture Title:</label>
    <input type="text" id="title" name="title" required />

    <label for="content">Description:</label>
    <textarea id="content" name="content"></textarea>

    <label for="course_id">Select Course:</label>
    <select id="course_id" name="course_id" required>
        <option value="">-- Choose Course --</option>
        <?php while ($course = $courses->fetch_assoc()): ?>
        <option value="<?= $course['id'] ?>"><?= htmlspecialchars($course['title']) ?></option>
        <?php endwhile; ?>
    </select>

    <button type="submit">Create Lecture</button>

    <button type="button" onclick="window.location.href='../dashboard.php'" class="back-button">
    &larr; Back to Dashboard
    </button>


</form>

</body>
</html>
