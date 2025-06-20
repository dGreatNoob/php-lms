<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Enrollments - LMS</title>
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
                    <a href="?page=admin&section=enrollments" class="sidebar__link sidebar__link--active">
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
                    <h1 class="dashboard__title">Manage Enrollments</h1>
                    <p class="dashboard__subtitle">Manage student course enrollments</p>
                </div>
            </header>

            <div class="dashboard__content">
                <div class="container">
                    <div class="card">
                        <div class="card__header">
                            <div class="flex flex--between">
                                <div>
                                    <h2 class="card__title">All Enrollments</h2>
                                    <p class="card__subtitle">Total: <?= count($enrollments) ?> enrollments</p>
                                </div>
                                <div class="flex flex--gap-2">
                                    <a href="?page=admin&section=enrollments&action=import" class="btn btn--secondary">
                                        <span>📥</span>
                                        <span>Import CSV</span>
                                    </a>
                                    <a href="?page=admin&section=enrollments&action=create" class="btn btn--primary">
                                        <span>➕</span>
                                        <span>Enroll Student</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card__body">
                            <?php if (empty($enrollments)): ?>
                                <div class="text-center p-8">
                                    <div class="text-4xl mb-4">👥</div>
                                    <h3 class="text-lg font-semibold mb-2">No Enrollments Found</h3>
                                    <p class="text-muted mb-4">Get started by enrolling your first student.</p>
                                    <div class="flex flex--gap-2 justify-center">
                                        <a href="?page=admin&section=enrollments&action=import" class="btn btn--secondary">
                                            Import from CSV
                                        </a>
                                        <a href="?page=admin&section=enrollments&action=create" class="btn btn--primary">
                                            Enroll Student
                                        </a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="table-container">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Student Name</th>
                                                <th>Username</th>
                                                <th>Course Code</th>
                                                <th>Course Title</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($enrollments as $enroll): ?>
                                            <tr>
                                                <td>
                                                    <div class="font-medium"><?= htmlspecialchars($enroll['first_name'] . ' ' . $enroll['last_name']) ?></div>
                                                </td>
                                                <td>
                                                    <span class="badge badge--info"><?= htmlspecialchars($enroll['username']) ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge badge--primary"><?= htmlspecialchars($enroll['course_code']) ?></span>
                                                </td>
                                                <td><?= htmlspecialchars($enroll['course_title']) ?></td>
                                                <td>
                                                    <div class="flex flex--gap-2">
                                                        <a href="?page=admin&section=enrollments&action=delete&id=<?= $enroll['id'] ?>" 
                                                           class="btn btn--sm btn--danger"
                                                           onclick="return confirm('Are you sure you want to remove this enrollment? This action cannot be undone.')"
                                                           data-tooltip="Remove enrollment">
                                                            🗑️ Remove
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