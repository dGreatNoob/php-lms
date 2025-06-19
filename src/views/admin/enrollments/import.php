<h1>Import Enrollments from CSV</h1>
<?php if (!empty($error)): ?><p style="color:red;"> <?= htmlspecialchars($error) ?> </p><?php endif; ?>
<form method="POST" enctype="multipart/form-data">
    <label>CSV File: <input type="file" name="csv" accept=".csv" required></label><br>
    <button type="submit">Import</button>
</form>
<p>CSV columns: <code>first_name,last_name,username,email,password,course_code</code></p>
<p>Example row: <code>John,Doe,johndoe,john@example.com,secret,MPE101</code></p>
<a href="?page=admin&section=enrollments">Back to Enrollments</a>
<?php if (!empty($results)): ?>
    <h2>Import Results</h2>
    <table border="1" cellpadding="6" cellspacing="0">
        <tr>
            <th>Row</th>
            <th>Status</th>
        </tr>
        <?php foreach ($results as $res): ?>
        <tr style="color:<?= $res['success'] ? 'green' : 'red' ?>;">
            <td><?= htmlspecialchars(implode(', ', $res['row'])) ?></td>
            <td><?= htmlspecialchars($res['status']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.expand-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const parentRow = this.closest('tr');
            const parentRowId = parentRow.getAttribute('data-rowid');
            const childRows = document.querySelectorAll('.parent-' + parentRowId);
            const expanded = this.textContent === '-';
            childRows.forEach(row => {
                row.style.display = expanded ? 'none' : '';
            });
            this.textContent = expanded ? '+' : '-';
        });
    });
});
</script> 