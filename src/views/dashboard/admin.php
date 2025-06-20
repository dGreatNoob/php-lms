<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - LMS</title>
    <link rel="stylesheet" href="../public/css/style.css">
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
                    <h1 class="dashboard__title">Welcome, <?= htmlspecialchars($full_name) ?></h1>
                    <p class="dashboard__subtitle">Manage your learning management system</p>
                </div>
            </header>

            <div class="dashboard__content">
                <div class="container">
                    <!-- Quick Stats -->
                    <div class="grid grid--cols-1 grid--cols-md-2 grid--cols-lg-4 mb-6">
                        <div class="card">
                            <div class="card__body">
                                <div class="flex flex--between">
                                    <div>
                                        <p class="text-muted text-sm">Total Courses</p>
                                        <h3 class="text-xl font-bold"><?= isset($stats['courses']) ? $stats['courses'] : '0' ?></h3>
                                    </div>
                                    <div class="text-2xl">📚</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card__body">
                                <div class="flex flex--between">
                                    <div>
                                        <p class="text-muted text-sm">Active Students</p>
                                        <h3 class="text-xl font-bold"><?= isset($stats['students']) ? $stats['students'] : '0' ?></h3>
                                    </div>
                                    <div class="text-2xl">👥</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card__body">
                                <div class="flex flex--between">
                                    <div>
                                        <p class="text-muted text-sm">Total Lectures</p>
                                        <h3 class="text-xl font-bold"><?= isset($stats['lectures']) ? $stats['lectures'] : '0' ?></h3>
                                    </div>
                                    <div class="text-2xl">🎓</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card__body">
                                <div class="flex flex--between">
                                    <div>
                                        <p class="text-muted text-sm">Pending Submissions</p>
                                        <h3 class="text-xl font-bold"><?= isset($stats['submissions']) ? $stats['submissions'] : '0' ?></h3>
                                    </div>
                                    <div class="text-2xl">📝</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card mb-6">
                        <div class="card__header">
                            <h2 class="card__title">Quick Actions</h2>
                            <p class="card__subtitle">Common administrative tasks</p>
                        </div>
                        <div class="card__body">
                            <div class="grid grid--cols-1 grid--cols-sm-2 grid--cols-md-3 grid--cols-lg-5">
                                <a href="?page=admin&section=courses" class="btn btn--primary">
                                    <span>📚</span>
                                    <span>Manage Courses</span>
                                </a>
                                <a href="?page=admin&section=topics" class="btn btn--secondary">
                                    <span>📋</span>
                                    <span>Manage Topics</span>
                                </a>
                                <a href="?page=admin&section=lectures" class="btn btn--secondary">
                                    <span>🎓</span>
                                    <span>Manage Lectures</span>
                                </a>
                                <a href="?page=admin&section=enrollments" class="btn btn--secondary">
                                    <span>👥</span>
                                    <span>Manage Enrollments</span>
                                </a>
                                <a href="?page=admin&section=archive" class="btn btn--secondary">
                                    <span>🗄️</span>
                                    <span>Archive/Restore</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="card">
                        <div class="card__header">
                            <h2 class="card__title">Recent Activity</h2>
                            <p class="card__subtitle">Latest system updates and changes</p>
                        </div>
                        <div class="card__body">
                            <?php if (isset($recent_activity) && !empty($recent_activity)): ?>
                                <div class="table-container">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Action</th>
                                                <th>Details</th>
                                                <th>User</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recent_activity as $activity): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($activity['action']) ?></td>
                                                    <td><?= htmlspecialchars($activity['details']) ?></td>
                                                    <td><?= htmlspecialchars($activity['user']) ?></td>
                                                    <td><?= htmlspecialchars($activity['date']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center p-8">
                                    <p class="text-muted">No recent activity to display</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="../public/js/theme.js"></script>
</body>
</html> 