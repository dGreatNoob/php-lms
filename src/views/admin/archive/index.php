<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archive & Restore - LMS</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard">
        <?php include __DIR__ . '/../sidebar.php'; ?>

        <main class="dashboard__main" id="main-content">
            <header class="dashboard__header">
                <div class="container">
                    <h1 class="dashboard__title">Archive & Restore</h1>
                    <p class="dashboard__subtitle">Manage archived content and restore items</p>
                </div>
            </header>

            <div class="dashboard__content">
                <div class="container">
                    <div class="card">
                        <div class="card__header">
                            <div class="flex flex--between">
                                <div>
                                    <h2 class="card__title">Archived Items</h2>
                                    <p class="card__subtitle">Restore or permanently delete archived content</p>
                                </div>
                                <div class="flex flex--gap-2">
                                    <select id="archive-type" class="form__select">
                                        <option value="lectures">Lectures</option>
                                        <option value="topics">Topics</option>
                                        <option value="courses">Courses</option>
                                    </select>
                                    <input type="text" id="archive-search" class="form__input" placeholder="Search archived items...">
                                    <button type="button" class="btn btn--danger" id="delete-all-archived-btn">Delete All Archived</button>
                                </div>
                            </div>
                        </div>
                        <div class="card__body">
                            <!-- Archived Lectures -->
                            <div id="archive-lectures" class="archive-table">
                                <h3 class="mb-2">Archived Lectures</h3>
                                <div class="table-container">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Topic</th>
                                                <th>Course</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($archived_lectures)): ?>
                                                <tr><td colspan="4" class="text-center text-muted">No archived lectures found.</td></tr>
                                            <?php else: foreach ($archived_lectures as $item): ?>
                                            <tr>
                                                <td><div class="font-medium"><?= htmlspecialchars($item['title']) ?></div></td>
                                                <td><span class="badge badge--info"><?= htmlspecialchars($item['topic_title']) ?></span></td>
                                                <td><span class="badge badge--primary"><?= htmlspecialchars($item['course_title']) ?></span></td>
                                                <td>
                                                    <div class="flex flex--gap-2">
                                                        <a href="?page=admin&section=lectures&action=restore&id=<?= $item['id'] ?>" class="btn btn--sm btn--success" data-tooltip="Restore lecture">🔄 Restore</a>
                                                        <a href="?page=admin&section=archive&action=delete_permanent_lecture&id=<?= $item['id'] ?>" class="btn btn--sm btn--danger" onclick="return confirm('Permanently delete this lecture? This cannot be undone.')" data-tooltip="Delete permanently">🗑️ Delete Permanently</a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Archived Topics -->
                            <div id="archive-topics" class="archive-table" style="display:none;">
                                <h3 class="mb-2">Archived Topics</h3>
                                <div class="table-container">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Course</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($archived_topics)): ?>
                                                <tr><td colspan="3" class="text-center text-muted">No archived topics found.</td></tr>
                                            <?php else: foreach ($archived_topics as $item): ?>
                                            <tr>
                                                <td><div class="font-medium"><?= htmlspecialchars($item['title']) ?></div></td>
                                                <td><span class="badge badge--primary"><?= htmlspecialchars($item['course_title']) ?></span></td>
                                                <td>
                                                    <div class="flex flex--gap-2">
                                                        <a href="?page=admin&section=archive&action=restore_topic&id=<?= $item['id'] ?>" class="btn btn--sm btn--success" data-tooltip="Restore topic">🔄 Restore</a>
                                                        <a href="?page=admin&section=archive&action=delete_permanent_topic&id=<?= $item['id'] ?>" class="btn btn--sm btn--danger" onclick="return confirm('Permanently delete this topic? This cannot be undone.')" data-tooltip="Delete permanently">🗑️ Delete Permanently</a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Archived Courses -->
                            <div id="archive-courses" class="archive-table" style="display:none;">
                                <h3 class="mb-2">Archived Courses</h3>
                                <div class="table-container">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Title</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($archived_courses)): ?>
                                                <tr><td colspan="3" class="text-center text-muted">No archived courses found.</td></tr>
                                            <?php else: foreach ($archived_courses as $item): ?>
                                            <tr>
                                                <td><span class="badge badge--primary"><?= htmlspecialchars($item['code']) ?></span></td>
                                                <td><div class="font-medium"><?= htmlspecialchars($item['title']) ?></div></td>
                                                <td>
                                                    <div class="flex flex--gap-2">
                                                        <a href="?page=admin&section=archive&action=restore_course&id=<?= $item['id'] ?>" class="btn btn--sm btn--success" data-tooltip="Restore course">🔄 Restore</a>
                                                        <a href="?page=admin&section=archive&action=delete_permanent_course&id=<?= $item['id'] ?>" class="btn btn--sm btn--danger" onclick="return confirm('Permanently delete this course? This cannot be undone.')" data-tooltip="Delete permanently">🗑️ Delete Permanently</a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Delete All Confirmation Modal -->
    <div class="modal-backdrop" id="delete-all-modal" style="display: none;">
        <div class="modal">
            <div class="modal__header">
                <h3 class="modal__title">Delete All Archived Items</h3>
                <button class="modal__close" data-modal-close="#delete-all-modal">&times;</button>
            </div>
            <div class="modal__body">
                <div class="alert alert--error">
                    <span class="alert__icon">⚠️</span>
                    <div class="alert__content">
                        <div class="alert__title">Are you sure?</div>
                        <div class="alert__message">This will permanently delete <strong>all archived lectures, topics, courses, and users</strong>. This action cannot be undone.</div>
                    </div>
                </div>
            </div>
            <div class="modal__footer">
                <button class="btn btn--secondary" data-modal-close="#delete-all-modal">Cancel</button>
                <a href="?page=admin&section=archive&action=delete_all_archived" class="btn btn--danger">Delete All</a>
            </div>
        </div>
    </div>

    <script src="js/script.js"></script>
    <script>
    // Helper to get URL parameter
    function getUrlParam(name) {
        const url = new URL(window.location.href);
        return url.searchParams.get(name);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('archive-type');
        const searchInput = document.getElementById('archive-search');
        const tables = {
            lectures: document.getElementById('archive-lectures'),
            topics: document.getElementById('archive-topics'),
            courses: document.getElementById('archive-courses')
        };

        // Set initial filter from URL param if present
        const urlType = getUrlParam('type');
        if (urlType && tables[urlType]) {
            typeSelect.value = urlType;
        }

        function showTable(type) {
            for (const key in tables) {
                tables[key].style.display = (key === type) ? '' : 'none';
            }
            filterTable(type, searchInput.value);
        }

        function filterTable(type, keyword) {
            const table = tables[type].querySelector('table');
            const rows = table.querySelectorAll('tbody tr');
            keyword = keyword.toLowerCase();
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(keyword) ? '' : 'none';
            });
        }

        typeSelect.addEventListener('change', function() {
            showTable(this.value);
            // Update URL without reloading
            const url = new URL(window.location.href);
            url.searchParams.set('type', this.value);
            window.history.replaceState({}, '', url);
        });

        searchInput.addEventListener('input', function() {
            filterTable(typeSelect.value, this.value);
        });

        // Initialize with first table or from URL
        showTable(typeSelect.value);

        // Update all restore/delete links to include current filter
        function updateActionLinks() {
            const type = typeSelect.value;
            document.querySelectorAll('.archive-table a').forEach(link => {
                if (link.href && (link.href.includes('restore') || link.href.includes('delete_permanent'))) {
                    const url = new URL(link.href, window.location.origin);
                    url.searchParams.set('type', type);
                    link.href = url.toString();
                }
            });
        }
        updateActionLinks();
        typeSelect.addEventListener('change', updateActionLinks);
    });

    document.getElementById('delete-all-archived-btn').addEventListener('click', function() {
        document.getElementById('delete-all-modal').style.display = 'flex';
    });
    document.querySelectorAll('[data-modal-close="#delete-all-modal"]').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('delete-all-modal').style.display = 'none';
        });
    });
    </script>
</body>
</html> 