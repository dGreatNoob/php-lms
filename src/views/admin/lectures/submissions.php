<h1>Submissions for Lecture: <?= htmlspecialchars($lecture['title']) ?></h1>
<a href="?page=admin&section=lectures">&larr; Back to Lectures</a>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Student</th>
        <th>Submitted At</th>
        <th>File</th>
        <th>Text</th>
        <th>Grade</th>
        <th>Feedback</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($submissions as $sub): ?>
    <tr>
        <td><?= htmlspecialchars($sub['first_name'] . ' ' . $sub['last_name']) ?> (<?= htmlspecialchars($sub['username']) ?>)</td>
        <td><?= htmlspecialchars($sub['submitted_at']) ?></td>
        <td>
            <?php if ($sub['file_path']): ?>
                <a href="/uploads/<?= htmlspecialchars(basename($sub['file_path'])) ?>" download>Download</a>
            <?php endif; ?>
        </td>
        <td>
            <?php if ($sub['text_submission']): ?>
                <div style="max-width:300px; max-height:100px; overflow:auto; border:1px solid #ccc; padding:4px; background:#fafafa;">
                    <?= nl2br(htmlspecialchars($sub['text_submission'])) ?>
                </div>
            <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($sub['grade']) ?></td>
        <td><?= nl2br(htmlspecialchars($sub['feedback'])) ?></td>
        <td>
            <form method="POST" style="margin:0;">
                <input type="hidden" name="submission_id" value="<?= $sub['id'] ?>">
                <input type="text" name="grade" value="<?= htmlspecialchars($sub['grade']) ?>" placeholder="Grade" size="6">
                <br>
                <textarea name="feedback" rows="2" cols="20" placeholder="Feedback"><?= htmlspecialchars($sub['feedback']) ?></textarea>
                <br>
                <button type="submit">Save</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table> 