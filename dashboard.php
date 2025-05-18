<?php
  session_start();
  include 'config/db.php';
// Check if the user is logged in
if (!isset($_SESSION['user_id'], $_SESSION['role'])) {
    header("Location: ../index.php?page=login");
    exit;
}

$role = $_SESSION['role'];
$user_id = intval($_SESSION['user_id']); // Make sure it's an integer

// Prepare query
if ($role === 'admin') {
    $query = "SELECT lectures.*, courses.title AS course_title 
              FROM lectures 
              JOIN courses ON lectures.course_id = courses.id";
    $result = $conn->query($query);
} else {
    $query = "SELECT lectures.*, courses.title AS course_title 
              FROM lectures 
              JOIN courses ON lectures.course_id = courses.id
              WHERE lectures.course_id IN (
                  SELECT course_id FROM enrollments WHERE student_id = ?
              )";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
}

// Fetch username
$user_query = $conn->prepare("SELECT name FROM users WHERE id = ?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user_result = $user_query->get_result();

if ($user_row = $user_result->fetch_assoc()) {
    $username = $user_row['name'];
} else {
    $username = "Unknown User";
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="public/style.css">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, sans-serif;
      display: flex;
      height: 100vh;
    }
    .sidebar {
      width: 220px;
      background: #2f3542;
      color: #fff;
      padding: 20px;
    }
    .sidebar h2 {
      font-size: 20px;
      margin-bottom: 20px;
    }
    .sidebar a {
      color: #fff;
      text-decoration: none;
      display: block;
      margin: 10px 0;
    }
    .main {
      flex: 1;
      padding: 20px;
      background: #f4f6f8;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    th, td {
      padding: 12px;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }
    th {
      background-color: #2f3542;
      color: white;
    }
    .actions button {
      margin-right: 5px;
      padding: 5px 10px;
      border: none;
      cursor: pointer;
      border-radius: 4px;
    }
    .edit { background-color: #ffa502; color: white; }
    .delete { background-color: #e74c3c; color: white; }
  </style>
</head>
<body>


<div class="sidebar">
  <h2><?= htmlspecialchars($username) ?> (<?= ucfirst($role) ?>)</h2>
  <a href="dashboard.php">Home</a>
  <a href="../auth/logout.php">Logout</a>
</div>
<div class="main">
  <h1>Lecture List</h1>

  <?php if ($role !== 'admin'): ?>
    <p style="color: #555; margin-bottom: 20px;"><em>You are viewing lectures you’re enrolled in. Only admins can create, edit, or delete lectures.</em></p>
  <?php endif; ?>

  <?php if ($role === 'admin'): ?>
    <!-- Create lecture button -->
    <button style="margin-bottom: 15px; padding: 8px 16px; background-color: #2ed573; color: white; border: none; border-radius: 4px; cursor: pointer;" 
        onclick="window.location.href='http://localhost:8000/crud/create.php'">
      + Create New Lecture
    </button>

    <button style="margin-left: 10px; margin-bottom: 15px; padding: 8px 16px; background-color: #1e90ff; color: white; border: none; border-radius: 4px; cursor: pointer;" 
            onclick="window.location.href='http://localhost:8000/crud/enroll_student.php'">
      📚 Enroll Student
    </button>


  <?php endif; ?>

  <table>
    <tr>
      <th>Lecture Title</th>
      <th>Course</th>
      <th>Description</th>
      <?php if ($role === 'admin'): ?>
        <th>Actions</th>
      <?php endif; ?>
    </tr>

    <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= htmlspecialchars($row['course_title']) ?></td>
        <td><?= htmlspecialchars($row['content']) ?></td>

        <?php if ($role === 'admin'): ?>
          <td class="actions">
            <button class="edit" onclick="window.location.href='../crud/update.php?id=<?= $row['id'] ?>'">Edit</button>
            <button class="delete" onclick="if(confirm('Delete this lecture?')) window.location.href='../crud/delete.php?id=<?= $row['id'] ?>'">Delete</button>
          </td>
        <?php endif; ?>
      </tr>
    <?php endwhile; ?>
  </table>
</div>


</body>
</html>