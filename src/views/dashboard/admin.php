<!DOCTYPE html>
<html lang="en" class="theme-light">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <div class="dashboard-shell">
        <nav class="sidebar">
            <div class="sidebar-title">LMS Admin</div>
            <a href="?page=admin&section=courses">Courses</a>
            <a href="?page=admin&section=topics">Topics & Subtopics</a>
            <a href="?page=admin&section=lectures">Lectures</a>
            <a href="?page=admin&section=enrollments">Enrollments</a>
            <a href="?page=admin&section=archive">Archive/Restore</a>
            <a href="?page=logout" class="sidebar-logout">Logout</a>
            <button id="theme-toggle" aria-label="Toggle dark mode" style="margin-top:2rem;">🌓</button>
        </nav>
        <main class="dashboard-main">
            <div class="dashboard-header">
                <h2>Welcome, Admin <?= htmlspecialchars($full_name) ?></h2>
            </div>
            <div class="card">
                <h3>Quick Actions</h3>
                <ul style="margin:1rem 0 0 0; padding:0; list-style:none; display:flex; flex-wrap:wrap; gap:1rem;">
                    <li><a href="?page=admin&section=courses" class="button">Manage Courses</a></li>
                    <li><a href="?page=admin&section=topics" class="button">Manage Topics</a></li>
                    <li><a href="?page=admin&section=lectures" class="button">Manage Lectures</a></li>
                    <li><a href="?page=admin&section=enrollments" class="button">Manage Enrollments</a></li>
                    <li><a href="?page=admin&section=archive" class="button">Archive/Restore</a></li>
                </ul>
            </div>
        </main>
    </div>
    <script src="../public/js/theme.js"></script>
</body>
</html> 