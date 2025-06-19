<h1>Create Lecture</h1>
<?php if (!empty($error)): ?><p style="color:red;"> <?= htmlspecialchars($error) ?> </p><?php endif; ?>
<form method="POST" action="?page=admin&section=lectures&action=create" enctype="multipart/form-data">
    <label>Topic:
        <select name="topic_id" required>
            <option value="">-- Select Topic --</option>
            <?php
            // Build a tree of topics for hierarchical dropdown
            $tree = [];
            foreach ($topics as $topic) {
                $tree[$topic['course_id']][$topic['parent_topic_id']][] = $topic;
            }
            function renderTopicOptions($tree, $course_id, $parent_id = null, $level = 0) {
                if (!isset($tree[$course_id][$parent_id])) return;
                foreach ($tree[$course_id][$parent_id] as $topic) {
                    $indent = str_repeat('&nbsp;&nbsp;&nbsp;', $level);
                    echo '<option value="' . $topic['id'] . '">' . $indent . htmlspecialchars($topic['title']) . ' (' . htmlspecialchars($topic['course_title']) . ')</option>';
                    renderTopicOptions($tree, $course_id, $topic['id'], $level + 1);
                }
            }
            foreach ($tree as $course_id => $byParent) {
                renderTopicOptions($tree, $course_id, null, 0);
            }
            ?>
        </select>
    </label><br>
    <label>Title: <input type="text" name="title" required></label><br>
    <label>Content: <textarea name="content"></textarea></label><br>
    <label>Attach File (pdf, doc, docx, txt, zip): <input type="file" name="file" accept=".pdf,.doc,.docx,.txt,.zip"></label><br>
    <label>Attach Image (jpg, jpeg, png, gif, webp): <input type="file" name="image" accept="image/*"></label><br>
    <label><input type="checkbox" name="requires_submission" value="1"> Requires Submission</label><br>
    <div id="submission-options" style="display:none; margin-left:20px;">
        <label>Submission Type:
            <input type="radio" name="submission_type" value="file" checked> File
            <input type="radio" name="submission_type" value="text"> Text
            <input type="radio" name="submission_type" value="both"> Both
        </label><br>
        <label>Submission Instructions:<br>
            <textarea name="submission_instructions" rows="2" cols="40"></textarea>
        </label><br>
    </div>
    <label>Due Date: <input type="datetime-local" name="due_date"></label><br>
    <script>
    document.querySelector('input[name="requires_submission"]').addEventListener('change', function() {
        document.getElementById('submission-options').style.display = this.checked ? 'block' : 'none';
    });
    </script>
    <button type="submit">Create</button>
</form>
<a href="?page=admin&section=lectures">Back to Lectures</a> 