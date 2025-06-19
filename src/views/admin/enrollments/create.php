<h1>Enroll Student</h1>
<?php if (!empty($error)): ?><p style="color:red;"> <?= htmlspecialchars($error) ?> </p><?php endif; ?>
<form method="POST" action="?page=admin&section=enrollments&action=create">
    <label>Student:
        <select name="student_id" required>
            <option value="">-- Select Student --</option>
            <?php foreach ($students as $student): ?>
                <option value="<?= $student['id'] ?>"><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?> (<?= htmlspecialchars($student['username']) ?>)</option>
            <?php endforeach; ?>
        </select>
    </label><br>
    <label>Course:
        <select name="course_id" required>
            <option value="">-- Select Course --</option>
            <?php foreach ($courses as $course): ?>
                <option value="<?= $course['id'] ?>">[<?= htmlspecialchars($course['code']) ?>] <?= htmlspecialchars($course['title']) ?></option>
            <?php endforeach; ?>
        </select>
    </label><br>
    <button type="submit">Enroll</button>
</form>
<a href="?page=admin&section=enrollments">Back to Enrollments</a> 