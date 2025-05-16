<?php
session_start();
include '../config/db.php';

// Check if user is admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php?page=login");
    exit;
}

$message = "";

// Fetch courses for dropdown
$courses_result = $conn->query("SELECT id, title FROM courses ORDER BY title ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $course_id = intval($_POST['course_id']);
    $description = trim($_POST['description']);

    if (empty($title) || empty($course_id)) {
        $message = "Please fill in all required fields.";
    } else {
        $stmt = $conn->prepare("INSERT INTO lectures (title, course_id, description) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $title, $course_id, $description);
        if ($stmt->execute()) {
            header("Location: dashboard.php?msg=Lecture created successfully");
            exit;
        } else {
            $message = "Error creating lecture: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Create Lecture</title>
    <script src="/js/script.js"></script>
    <style>
                /* Reset & base */
        * {
        box-sizing: border-box;
        }

        body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f6f8;
        margin: 0;
        min-height: 100vh;
        display: grid;
        justify-content: center;
        align-items: center;
        padding: 20px;
        }

        h1 {
        text-align: center;
        font-weight: 700;
        margin-bottom: 30px;
        color: #222;
        }

        form {
        background: #ffffff;
        padding: 30px 40px;
        max-width: 680px;
        width: 100%;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        color: #333;
        font-size: 15px;
        }

        input[type="text"],
        select,
        textarea {
        width: 100%;
        padding: 12px 14px;
        margin-bottom: 20px;
        border-radius: 6px;
        border: 1.5px solid #ccc;
        font-size: 15px;
        transition: border-color 0.3s ease;
        font-family: inherit;
        resize: vertical;
        }

        input[type="text"]:focus,
        select:focus,
        textarea:focus {
        border-color: #2ed573;
        outline: none;
        }

        button {
        display: block;
        width: 100%;
        background-color: #2ed573;
        color: white;
        font-weight: 700;
        border: none;
        padding: 14px;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        }

        button:hover {
        background-color: #27b760;
        }

        .message {
        margin-bottom: 20px;
        padding: 12px 15px;
        border-radius: 6px;
        font-weight: 600;
        color: #fff;
        background-color: #e74c3c;
        text-align: center;
        }

        .message.success {
        background-color: #2ed573;
        }

        /* Modal styles */
        #courseModal {
        display: none; /* Hidden by default */
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        /* use flex only when visible */
        }

        #courseModal.active {
        display: flex;
        }

        #courseModal > div {
        background: #fff;
        padding: 25px 30px;
        border-radius: 10px;
        width: 350px;
        max-width: 90vw;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        position: relative;
        font-family: inherit;
        }

        #courseModal h2 {
        margin-top: 0;
        margin-bottom: 20px;
        font-weight: 700;
        color: #222;
        }

        #addCourseForm label {
        margin-bottom: 6px;
        }

        #addCourseForm input[type="text"] {
        padding: 10px 12px;
        font-size: 14px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 6px;
        width: 100%;
        box-sizing: border-box;
        }

        #addCourseForm button {
        padding: 10px 15px;
        font-size: 14px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-weight: 700;
        transition: background-color 0.3s ease;
        }

        #addCourseForm button[type="submit"] {
        background-color: #2ed573;
        color: white;
        }

        #addCourseForm button[type="submit"]:hover {
        background-color: #27b760;
        }

        #addCourseForm #closeModal {
        background-color: #ccc;
        color: #333;
        margin-left: 10px;
        }

        #addCourseForm #closeModal:hover {
        background-color: #b3b3b3;
        }

        #modalMsg {
        color: #e74c3c;
        margin-top: 10px;
        font-weight: 600;
        display: none;
        font-size: 13px;
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
        form {
            padding: 20px;
        }

        #courseModal > div {
            width: 90vw;
            padding: 20px;
        }
        }


    </style>
</head>
<body>

<h1>Create New Lecture</h1>

<?php if ($message): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<form method="POST" action="">
    <label for="title">Lecture Title *</label>
    <input type="text" id="title" name="title" required />

    <!-- Courses Dropdown -->
        <label for="course_id">Course *</label>
        <select id="course_id" name="course_id" required>
            <option value="">Select course</option>
            <?php while ($course = $courses_result->fetch_assoc()): ?>
                <option value="<?= $course['id'] ?>"><?= htmlspecialchars($course['title']) ?></option>
            <?php endwhile; ?>
            <option value="add_new">+ Add new course</option>
        </select>

        <!-- Modal Structure -->
        <div id="courseModal" style="display:none; position: fixed; inset:0; background: rgba(0,0,0,0.5); z-index: 9999; justify-content:center; align-items:center;">
        <div style="background: white; padding: 25px; border-radius: 10px; width: 350px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); position: relative;">
            <h2 style="margin-top:0;">Add New Course</h2>
            <form id="addCourseForm">
            <label for="newCourseTitle">Course Title *</label>
            <input type="text" id="newCourseTitle" name="title" required style="width:100%; padding:8px; margin-bottom:15px; border:1px solid #ccc; border-radius:4px;"/>
            <button type="submit" style="background:#2ed573; border:none; padding:10px 15px; color:white; border-radius:6px; cursor:pointer;">Create Course</button>
            <button type="button" id="closeModal" style="margin-left:10px; padding:10px 15px; border:none; background:#ccc; border-radius:6px; cursor:pointer;">Cancel</button>
            <p id="modalMsg" style="color:red; margin-top:10px; display:none;"></p>
            </form>
        </div>
    </div>

    <label for="description">Description</label>
    <textarea id="description" name="description" rows="4"></textarea>

    <button type="submit">Create Lecture</button>
</form>

</body>
</html>
