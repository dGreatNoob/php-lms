<h1>Archive / Trash</h1>

<label for="archive-type">Show:</label>
<select id="archive-type">
    <option value="lectures">Lectures</option>
    <option value="topics">Topics</option>
    <option value="courses">Courses</option>
    <option value="users">Users</option>
</select>
&nbsp;
<label for="archive-search">Search:</label>
<input type="text" id="archive-search" placeholder="Type to search...">

<div id="archive-lectures" class="archive-table">
<h2>Archived Lectures</h2>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Title</th>
        <th>Topic ID</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($archived_lectures as $item): ?>
    <tr>
        <td><?= htmlspecialchars($item['title']) ?></td>
        <td><?= htmlspecialchars($item['topic_id']) ?></td>
        <td>
            <a href="?page=admin&section=lectures&action=restore&id=<?= $item['id'] ?>">Restore</a> |
            <a href="?page=admin&section=archive&action=delete_permanent_lecture&id=<?= $item['id'] ?>" onclick="return confirm('Permanently delete this lecture? This cannot be undone.')">Delete Permanently</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</div>

<div id="archive-topics" class="archive-table" style="display:none;">
<h2>Archived Topics</h2>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Title</th>
        <th>Course</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($archived_topics as $item): ?>
    <tr>
        <td><?= htmlspecialchars($item['title']) ?></td>
        <td><?= htmlspecialchars($item['course_title']) ?></td>
        <td>
            <a href="?page=admin&section=archive&action=restore_topic&id=<?= $item['id'] ?>">Restore</a> |
            <a href="?page=admin&section=archive&action=delete_permanent_topic&id=<?= $item['id'] ?>" onclick="return confirm('Permanently delete this topic? This cannot be undone.')">Delete Permanently</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</div>

<div id="archive-courses" class="archive-table" style="display:none;">
<h2>Archived Courses</h2>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Code</th>
        <th>Title</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($archived_courses as $item): ?>
    <tr>
        <td><?= htmlspecialchars($item['code']) ?></td>
        <td><?= htmlspecialchars($item['title']) ?></td>
        <td>
            <a href="?page=admin&section=archive&action=restore_course&id=<?= $item['id'] ?>">Restore</a> |
            <a href="?page=admin&section=archive&action=delete_permanent_course&id=<?= $item['id'] ?>" onclick="return confirm('Permanently delete this course? This cannot be undone.')">Delete Permanently</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</div>

<div id="archive-users" class="archive-table" style="display:none;">
<h2>Archived Users</h2>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Full Name</th>
        <th>Username</th>
        <th>Email</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($archived_users as $item): ?>
    <tr>
        <td><?= htmlspecialchars($item['first_name'] . ' ' . $item['last_name']) ?></td>
        <td><?= htmlspecialchars($item['username']) ?></td>
        <td><?= htmlspecialchars($item['email']) ?></td>
        <td>
            <a href="?page=admin&section=archive&action=restore_user&id=<?= $item['id'] ?>">Restore</a> |
            <a href="?page=admin&section=archive&action=delete_permanent_user&id=<?= $item['id'] ?>" onclick="return confirm('Permanently delete this user? This cannot be undone.')">Delete Permanently</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</div>

<script>
const typeSelect = document.getElementById('archive-type');
const searchInput = document.getElementById('archive-search');
const tables = {
    lectures: document.getElementById('archive-lectures'),
    topics: document.getElementById('archive-topics'),
    courses: document.getElementById('archive-courses'),
    users: document.getElementById('archive-users')
};
function showTable(type) {
    for (const key in tables) {
        tables[key].style.display = (key === type) ? '' : 'none';
    }
    filterTable(type, searchInput.value);
}
function filterTable(type, keyword) {
    const table = tables[type].querySelector('table');
    const rows = table.querySelectorAll('tr');
    keyword = keyword.toLowerCase();
    for (let i = 1; i < rows.length; i++) { // skip header
        const row = rows[i];
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(keyword) ? '' : 'none';
    }
}
typeSelect.addEventListener('change', function() {
    showTable(this.value);
});
searchInput.addEventListener('input', function() {
    filterTable(typeSelect.value, this.value);
});
document.addEventListener('DOMContentLoaded', function() {
    showTable(typeSelect.value);
});
</script> 