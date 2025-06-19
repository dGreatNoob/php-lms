<h1>Edit Lecture</h1>
<?php if (!empty($error)): ?><p style="color:red;"> <?= htmlspecialchars($error) ?> </p><?php endif; ?>
<form method="POST" enctype="multipart/form-data">
    <label>Topic:
        <select name="topic_id" required>
            <option value="">-- Select Topic --</option>
            <?php
            // Build a tree of topics for hierarchical dropdown
            $tree = [];
            foreach ($topics as $topic) {
                $tree[$topic['course_id']][$topic['parent_topic_id']][] = $topic;
            }
            function renderTopicOptionsEdit($tree, $course_id, $parent_id = null, $level = 0, $selected_id = null) {
                if (!isset($tree[$course_id][$parent_id])) return;
                foreach ($tree[$course_id][$parent_id] as $topic) {
                    $indent = str_repeat('&nbsp;&nbsp;&nbsp;', $level);
                    $selected = $selected_id == $topic['id'] ? 'selected' : '';
                    echo '<option value="' . $topic['id'] . '" ' . $selected . '>' . $indent . htmlspecialchars($topic['title']) . ' (' . htmlspecialchars($topic['course_title']) . ')</option>';
                    renderTopicOptionsEdit($tree, $course_id, $topic['id'], $level + 1, $selected_id);
                }
            }
            foreach ($tree as $course_id => $byParent) {
                renderTopicOptionsEdit($tree, $course_id, null, 0, $lecture['topic_id']);
            }
            ?>
        </select>
    </label><br>
    <label>Title: <input type="text" name="title" value="<?= htmlspecialchars($lecture['title']) ?>" required></label><br>
    <label>Content: <textarea name="content"><?= htmlspecialchars($lecture['content']) ?></textarea></label><br>
    <?php if (!empty($lecture['file_path'])): ?>
        <label>Current File: <a href="/uploads/<?= htmlspecialchars(basename($lecture['file_path'])) ?>" download><?= htmlspecialchars(basename($lecture['file_path'])) ?></a></label>
        <label><input type="checkbox" name="delete_file" value="1"> Delete this file</label><br>
    <?php endif; ?>
    <label>Attach File (pdf, doc, docx, txt, zip): <input type="file" name="file" accept=".pdf,.doc,.docx,.txt,.zip"></label><br>
    <?php if (!empty($lecture['image_path'])): ?>
        <label>Current Image: <a href="/uploads/<?= htmlspecialchars(basename($lecture['image_path'])) ?>" download><?= htmlspecialchars(basename($lecture['image_path'])) ?></a></label>
        <label><input type="checkbox" name="delete_image" value="1"> Delete this image</label><br>
    <?php endif; ?>
    <label>Attach Image (jpg, jpeg, png, gif, webp): <input type="file" name="image" accept="image/*"></label><br>
    <label><input type="checkbox" name="requires_submission" value="1" <?= $lecture['requires_submission'] ? 'checked' : '' ?>> Requires Submission</label><br>
    <div id="submission-options" style="display:<?= $lecture['requires_submission'] ? 'block' : 'none' ?>; margin-left:20px;">
        <label>Submission Type:
            <input type="radio" name="submission_type" value="file" <?= $lecture['submission_type'] === 'file' ? 'checked' : '' ?>> File
            <input type="radio" name="submission_type" value="text" <?= $lecture['submission_type'] === 'text' ? 'checked' : '' ?>> Text
            <input type="radio" name="submission_type" value="both" <?= $lecture['submission_type'] === 'both' ? 'checked' : '' ?>> Both
        </label><br>
        <label>Submission Instructions:<br>
            <textarea name="submission_instructions" rows="2" cols="40"><?= htmlspecialchars($lecture['submission_instructions']) ?></textarea>
        </label><br>
    </div>
    <script>
    document.querySelector('input[name="requires_submission"]').addEventListener('change', function() {
        document.getElementById('submission-options').style.display = this.checked ? 'block' : 'none';
    });
    </script>
    <button type="submit">Update</button>
</form>
<a href="?page=admin&section=lectures">Back to Lectures</a> 