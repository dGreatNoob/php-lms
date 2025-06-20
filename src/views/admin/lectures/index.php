<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Lectures - LMS</title>
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
                    <a href="?page=admin&section=lectures" class="sidebar__link sidebar__link--active">
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
                    <a href="?page=admin&section=archive" class="sidebar__link">
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
                    <h1 class="dashboard__title">Manage Lectures</h1>
                    <p class="dashboard__subtitle">Create and organize your course content</p>
                </div>
            </header>

            <div class="dashboard__content">
                <div class="container">
                    <div class="card">
                        <div class="card__header">
                            <div class="flex flex--between">
                                <div>
                                    <h2 class="card__title">All Lectures</h2>
                                    <p class="card__subtitle">Total: <?= count($lectures) ?> lectures</p>
                                </div>
                                <a href="?page=admin&section=lectures&action=create" class="btn btn--primary">
                                    <span>➕</span>
                                    <span>Add Lecture</span>
                                </a>
                            </div>
                        </div>
                        <div class="card__body">
                            <?php if (empty($lectures)): ?>
                                <div class="text-center p-8">
                                    <div class="text-4xl mb-4">🎓</div>
                                    <h3 class="text-lg font-semibold mb-2">No Lectures Found</h3>
                                    <p class="text-muted mb-4">Get started by creating your first lecture.</p>
                                    <a href="?page=admin&section=lectures&action=create" class="btn btn--primary">
                                        Create First Lecture
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="table-container">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Topic</th>
                                                <th>Course</th>
                                                <th>Content</th>
                                                <th>Attachments</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($lectures as $lecture): ?>
                                            <tr>
                                                <td>
                                                    <div class="font-medium"><?= htmlspecialchars($lecture['title']) ?></div>
                                                </td>
                                                <td>
                                                    <span class="badge badge--info"><?= htmlspecialchars($lecture['topic_title']) ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge badge--primary"><?= htmlspecialchars($lecture['course_title']) ?></span>
                                                </td>
                                                <td>
                                                    <div class="text-sm text-muted">
                                                        <?= strlen($lecture['content']) > 50 ? htmlspecialchars(substr($lecture['content'], 0, 50)) . '...' : htmlspecialchars($lecture['content']) ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="flex flex--gap-1">
                                                        <?php if (!empty($lecture['file_path'])): ?>
                                                            <a href="uploads/<?= htmlspecialchars(basename($lecture['file_path'])) ?>" 
                                                               download 
                                                               class="btn btn--xs btn--secondary"
                                                               title="<?= htmlspecialchars(basename($lecture['file_path'])) ?>">
                                                                📎 File
                                                            </a>
                                                        <?php endif; ?>
                                                        <?php if (!empty($lecture['image_path'])): ?>
                                                            <a href="uploads/<?= htmlspecialchars(basename($lecture['image_path'])) ?>" 
                                                               target="_blank"
                                                               class="btn btn--xs btn--secondary"
                                                               title="<?= htmlspecialchars(basename($lecture['image_path'])) ?>">
                                                                🖼️ Image
                                                            </a>
                                                        <?php endif; ?>
                                                        <?php if (empty($lecture['file_path']) && empty($lecture['image_path'])): ?>
                                                            <span class="text-muted text-sm">None</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="flex flex--gap-2">
                                                        <a href="?page=admin&section=lectures&action=edit&id=<?= $lecture['id'] ?>" 
                                                           class="btn btn--sm btn--secondary"
                                                           data-tooltip="Edit lecture">
                                                            ✏️ Edit
                                                        </a>
                                                        <a href="?page=admin&section=lectures&action=submissions&id=<?= $lecture['id'] ?>" 
                                                           class="btn btn--sm btn--info"
                                                           data-tooltip="View submissions">
                                                            📝 Submissions
                                                        </a>
                                                        <a href="?page=admin&section=lectures&action=archive&id=<?= $lecture['id'] ?>" 
                                                           class="btn btn--sm btn--warning"
                                                           onclick="return confirm('Archive this lecture?')"
                                                           data-tooltip="Archive lecture">
                                                            📦 Archive
                                                        </a>
                                                        <a href="?page=admin&section=lectures&action=delete&id=<?= $lecture['id'] ?>" 
                                                           class="btn btn--sm btn--danger"
                                                           onclick="return confirm('Are you sure you want to delete this lecture? This action cannot be undone.')"
                                                           data-tooltip="Delete lecture">
                                                            🗑️ Delete
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="js/script.js"></script>
</body>
</html> 