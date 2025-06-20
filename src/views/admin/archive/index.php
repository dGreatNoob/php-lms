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
        <aside class="sidebar">
            <div class="sidebar__header">
                <h2 class="sidebar__title">LMS Admin</h2>
            </div>
            
            <nav class="sidebar__nav">
                <div class="sidebar__section">
                    <h3 class="sidebar__section-title">Navigation</h3>
                    <a href="?page=admin" class="sidebar__link">
                        <span>🏠</span>
                        <span>Dashboard</span>
                    </a>
                </div>
                
                <div class="sidebar__section">
                    <h3 class="sidebar__section-title">Management</h3>
                    <a href="?page=admin&section=courses" class="sidebar__link">
                        <span>📚</span>
                        <span>Courses</span>
                    </a>
                    <a href="?page=admin&section=topics" class="sidebar__link">
                        <span>📋</span>
                        <span>Topics & Subtopics</span>
                    </a>
                    <a href="?page=admin&section=lectures" class="sidebar__link">
                        <span>🎓</span>
                        <span>Lectures</span>
                    </a>
                    <a href="?page=admin&section=enrollments" class="sidebar__link">
                        <span>👥</span>
                        <span>Enrollments</span>
                    </a>
                </div>
                
                <div class="sidebar__section">
                    <h3 class="sidebar__section-title">System</h3>
                    <a href="?page=admin&section=archive" class="sidebar__link sidebar__link--active">
                        <span>🗄️</span>
                        <span>Archive/Restore</span>
                    </a>
                    <a href="?page=logout" class="sidebar__link sidebar__link--logout">
                        <span>🚪</span>
                        <span>Logout</span>
                    </a>
                </div>
                
                <div class="sidebar__section">
                    <button class="btn btn--icon btn--secondary" data-theme-toggle aria-label="Toggle dark mode">
                        🌙
                    </button>
                </div>
            </nav>
        </aside>

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
                                        <option value="users">Users</option>
                                    </select>
                                    <input type="text" id="archive-search" class="form__input" placeholder="Search archived items...">
                                </div>
                            </div>
                        </div>
                        <div class="card__body">
                            <!-- Archived Lectures -->
                            <div id="archive-lectures" class="archive-table">
                                <div class="table-container">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Topic ID</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($archived_lectures as $item): ?>
                                            <tr>
                                                <td>
                                                    <div class="font-medium"><?= htmlspecialchars($item['title']) ?></div>
                                                </td>
                                                <td>
                                                    <span class="badge badge--info"><?= htmlspecialchars($item['topic_id']) ?></span>
                                                </td>
                                                <td>
                                                    <div class="flex flex--gap-2">
                                                        <a href="?page=admin&section=lectures&action=restore&id=<?= $item['id'] ?>" 
                                                           class="btn btn--sm btn--success"
                                                           data-tooltip="Restore lecture">
                                                            🔄 Restore
                                                        </a>
                                                        <a href="?page=admin&section=archive&action=delete_permanent_lecture&id=<?= $item['id'] ?>" 
                                                           class="btn btn--sm btn--danger"
                                                           onclick="return confirm('Permanently delete this lecture? This cannot be undone.')"
                                                           data-tooltip="Delete permanently">
                                                            🗑️ Delete Permanently
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Archived Topics -->
                            <div id="archive-topics" class="archive-table" style="display:none;">
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
                                            <?php foreach ($archived_topics as $item): ?>
                                            <tr>
                                                <td>
                                                    <div class="font-medium"><?= htmlspecialchars($item['title']) ?></div>
                                                </td>
                                                <td>
                                                    <span class="badge badge--primary"><?= htmlspecialchars($item['course_title']) ?></span>
                                                </td>
                                                <td>
                                                    <div class="flex flex--gap-2">
                                                        <a href="?page=admin&section=archive&action=restore_topic&id=<?= $item['id'] ?>" 
                                                           class="btn btn--sm btn--success"
                                                           data-tooltip="Restore topic">
                                                            🔄 Restore
                                                        </a>
                                                        <a href="?page=admin&section=archive&action=delete_permanent_topic&id=<?= $item['id'] ?>" 
                                                           class="btn btn--sm btn--danger"
                                                           onclick="return confirm('Permanently delete this topic? This cannot be undone.')"
                                                           data-tooltip="Delete permanently">
                                                            🗑️ Delete Permanently
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Archived Courses -->
                            <div id="archive-courses" class="archive-table" style="display:none;">
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
                                            <?php foreach ($archived_courses as $item): ?>
                                            <tr>
                                                <td>
                                                    <span class="badge badge--primary"><?= htmlspecialchars($item['code']) ?></span>
                                                </td>
                                                <td>
                                                    <div class="font-medium"><?= htmlspecialchars($item['title']) ?></div>
                                                </td>
                                                <td>
                                                    <div class="flex flex--gap-2">
                                                        <a href="?page=admin&section=archive&action=restore_course&id=<?= $item['id'] ?>" 
                                                           class="btn btn--sm btn--success"
                                                           data-tooltip="Restore course">
                                                            🔄 Restore
                                                        </a>
                                                        <a href="?page=admin&section=archive&action=delete_permanent_course&id=<?= $item['id'] ?>" 
                                                           class="btn btn--sm btn--danger"
                                                           onclick="return confirm('Permanently delete this course? This cannot be undone.')"
                                                           data-tooltip="Delete permanently">
                                                            🗑️ Delete Permanently
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Archived Users -->
                            <div id="archive-users" class="archive-table" style="display:none;">
                                <div class="table-container">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Full Name</th>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($archived_users as $item): ?>
                                            <tr>
                                                <td>
                                                    <div class="font-medium"><?= htmlspecialchars($item['first_name'] . ' ' . $item['last_name']) ?></div>
                                                </td>
                                                <td>
                                                    <span class="badge badge--info"><?= htmlspecialchars($item['username']) ?></span>
                                                </td>
                                                <td><?= htmlspecialchars($item['email']) ?></td>
                                                <td>
                                                    <div class="flex flex--gap-2">
                                                        <a href="?page=admin&section=archive&action=restore_user&id=<?= $item['id'] ?>" 
                                                           class="btn btn--sm btn--success"
                                                           data-tooltip="Restore user">
                                                            🔄 Restore
                                                        </a>
                                                        <a href="?page=admin&section=archive&action=delete_permanent_user&id=<?= $item['id'] ?>" 
                                                           class="btn btn--sm btn--danger"
                                                           onclick="return confirm('Permanently delete this user? This cannot be undone.')"
                                                           data-tooltip="Delete permanently">
                                                            🗑️ Delete Permanently
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
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

    <script src="js/script.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
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
            const rows = table.querySelectorAll('tbody tr');
            keyword = keyword.toLowerCase();
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(keyword) ? '' : 'none';
            });
        }

        typeSelect.addEventListener('change', function() {
            showTable(this.value);
        });

        searchInput.addEventListener('input', function() {
            filterTable(typeSelect.value, this.value);
        });

        // Initialize with first table
        showTable(typeSelect.value);
    });
    </script>
</body>
</html> 