<h1>Enrollments</h1>
<a href="?page=admin&section=enrollments&action=create">+ Enroll Student</a> |
<a href="?page=admin&section=enrollments&action=import">Import from CSV</a>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Student Name</th>
        <th>Username</th>
        <th>Course Code</th>
        <th>Course Title</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($enrollments as $enroll): ?>
    <tr>
        <td><?= htmlspecialchars($enroll['first_name'] . ' ' . $enroll['last_name']) ?></td>
        <td><?= htmlspecialchars($enroll['username']) ?></td>
        <td><?= htmlspecialchars($enroll['course_code']) ?></td>
        <td><?= htmlspecialchars($enroll['course_title']) ?></td>
        <td>
            <a href="?page=admin&section=enrollments&action=delete&id=<?= $enroll['id'] ?>" onclick="return confirm('Remove this enrollment?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table> 