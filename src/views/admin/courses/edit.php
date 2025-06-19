<h1>Edit Course</h1>
<?php if (!empty($error)): ?><p style="color:red;"> <?= htmlspecialchars($error) ?> </p><?php endif; ?>
<form method="POST">
    <label>Course Code: <input type="text" name="code" value="<?= htmlspecialchars($course['code']) ?>" required></label><br>
    <label>Title: <input type="text" name="title" value="<?= htmlspecialchars($course['title']) ?>" required></label><br>
    <label>Semester: <input type="text" name="semester" value="<?= htmlspecialchars($course['semester']) ?>" required></label><br>
    <button type="submit">Update</button>
</form>
<a href="?page=admin&section=courses">Back to Courses</a> 