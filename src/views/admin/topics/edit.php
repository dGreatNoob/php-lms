<h1>Edit Topic</h1>
<?php if (!empty($error)): ?><p style="color:red;"> <?= htmlspecialchars($error) ?> </p><?php endif; ?>
<form method="POST" id="topicForm">
    <label>Course:
        <select name="course_id" id="course_id" required>
            <option value="">-- Select Course --</option>
            <?php foreach ($courses as $course): ?>
                <option value="<?= $course['id'] ?>" <?= $topic['course_id'] == $course['id'] ? 'selected' : '' ?>>[<?= htmlspecialchars($course['code']) ?>] <?= htmlspecialchars($course['title']) ?></option>
            <?php endforeach; ?>
        </select>
    </label><br>
    <label>Parent Topic (optional):
        <select name="parent_topic_id" id="parent_topic_id">
            <option value="">-- None --</option>
            <?php foreach ($all_topics as $t): ?>
                <?php if ($t['id'] != $topic['id']): ?>
                    <option value="<?= $t['id'] ?>" data-course="<?= $t['course_id'] ?>" <?= $topic['parent_topic_id'] == $t['id'] ? 'selected' : '' ?>><?= htmlspecialchars($t['title']) ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </label><br>
    <label>Title: <input type="text" name="title" value="<?= htmlspecialchars($topic['title']) ?>" required></label><br>
    <label>Description: <textarea name="description"><?= htmlspecialchars($topic['description']) ?></textarea></label><br>
    <button type="submit">Update</button>
</form>
<a href="?page=admin&section=topics">Back to Topics</a>
<script>
// Filter parent topics by selected course
const courseSelect = document.getElementById('course_id');
const parentSelect = document.getElementById('parent_topic_id');
function filterParentTopics() {
    const courseId = courseSelect.value;
    for (const opt of parentSelect.options) {
        if (!opt.value) continue;
        opt.style.display = (opt.getAttribute('data-course') === courseId) ? '' : 'none';
    }
    // If the current parent is not in the filtered list, reset
    if (parentSelect.selectedOptions.length && parentSelect.selectedOptions[0].style.display === 'none') {
        parentSelect.value = '';
    }
}
courseSelect.addEventListener('change', filterParentTopics);
window.addEventListener('DOMContentLoaded', filterParentTopics);
</script> 