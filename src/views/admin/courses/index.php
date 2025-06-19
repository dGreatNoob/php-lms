<h1>Courses</h1>
<a href="?page=admin&section=courses&action=create">+ Add Course</a>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Code</th>
        <th>Title</th>
        <th>Semester</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($courses as $course): ?>
    <tr>
        <td><?= htmlspecialchars($course['code']) ?></td>
        <td><?= htmlspecialchars($course['title']) ?></td>
        <td><?= htmlspecialchars($course['semester']) ?></td>
        <td>
            <a href="?page=admin&section=courses&action=edit&id=<?= $course['id'] ?>">Edit</a> |
            <a href="?page=admin&section=courses&action=delete&id=<?= $course['id'] ?>" onclick="return confirm('Delete this course?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table> 