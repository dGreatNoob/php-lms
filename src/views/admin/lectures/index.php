<h1>Lectures</h1>
<a href="?page=admin&section=lectures&action=create">+ Add Lecture</a>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Title</th>
        <th>Topic</th>
        <th>Course</th>
        <th>Content</th>
        <th>File</th>
        <th>Image</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($lectures as $lecture): ?>
    <tr>
        <td><?= htmlspecialchars($lecture['title']) ?></td>
        <td><?= htmlspecialchars($lecture['topic_title']) ?></td>
        <td><?= htmlspecialchars($lecture['course_title']) ?></td>
        <td><?= htmlspecialchars($lecture['content']) ?></td>
        <td>
            <?php if (!empty($lecture['file_path'])): ?>
                <a href="/uploads/<?= htmlspecialchars(basename($lecture['file_path'])) ?>" download title="<?= htmlspecialchars(basename($lecture['file_path'])) ?>">Download File</a>
            <?php endif; ?>
        </td>
        <td>
            <?php if (!empty($lecture['image_path'])): ?>
                <a href="/uploads/<?= htmlspecialchars(basename($lecture['image_path'])) ?>" download title="<?= htmlspecialchars(basename($lecture['image_path'])) ?>">Download Image</a>
            <?php endif; ?>
        </td>
        <td>
            <a href="?page=admin&section=lectures&action=edit&id=<?= $lecture['id'] ?>">Edit</a> |
            <a href="?page=admin&section=lectures&action=delete&id=<?= $lecture['id'] ?>" onclick="return confirm('Delete this lecture?')">Delete</a> |
            <a href="?page=admin&section=lectures&action=archive&id=<?= $lecture['id'] ?>" onclick="return confirm('Archive this lecture?')">Archive</a> |
            <a href="?page=admin&section=lectures&action=submissions&id=<?= $lecture['id'] ?>">View Submissions</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table> 