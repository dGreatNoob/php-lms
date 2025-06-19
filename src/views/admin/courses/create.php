<h1>Create Course</h1>
<?php if (!empty($error)): ?><p style="color:red;"><?= htmlspecialchars($error) ?></p><?php endif; ?>
<form method="POST" action="?page=admin&section=courses&action=create">
    <label>Course Code: <input type="text" name="code" required></label><br>
    <label>Title: <input type="text" name="title" required></label><br>
    <label>Semester: <input type="text" name="semester" required></label><br>
    <button type="submit">Create</button>
</form>
<a href="?page=admin&section=courses">Back to Courses</a> 