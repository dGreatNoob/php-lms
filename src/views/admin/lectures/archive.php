<h1>Archived Lectures</h1>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Title</th>
        <th>Topic</th>
        <th>Course</th>
        <th>Content</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($lectures as $lecture): ?>
    <tr>
        <td><?= htmlspecialchars($lecture['title']) ?></td>
        <td><?= htmlspecialchars($lecture['topic_title']) ?></td>
        <td><?= htmlspecialchars($lecture['course_title']) ?></td>
        <td><?= htmlspecialchars($lecture['content']) ?></td>
        <td>
            <a href="?page=admin&section=lectures&action=restore&id=<?= $lecture['id'] ?>" onclick="return confirm('Restore this lecture?')">Restore</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<a href="?page=admin&section=lectures">Back to Lectures</a> 