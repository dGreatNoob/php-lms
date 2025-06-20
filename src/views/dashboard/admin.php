<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - LMS</title>
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
                    <a href="?page=admin" class="sidebar__link sidebar__link--active">
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

                    <!-- Recent Activity -->
                    <div class="card">
                        <div class="card__header">
                            <h2 class="card__title">Recent Activity</h2>
                            <p class="card__subtitle">Latest system updates and changes</p>
                        </div>
                        <div class="card__body">
                            <?php if (isset($recent_activity) && !empty($recent_activity)): ?>
                                <div class="recent-activity-container">
                                    <div class="table-container">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th style="width: 120px;">Action</th>
                                                    <th>Details</th>
                                                    <th style="width: 150px;">User</th>
                                                    <th style="width: 180px;">Date & Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($recent_activity as $activity): ?>
                                                    <tr>
                                                        <td>
                                                            <span class="badge <?= htmlspecialchars($activity['action_class']) ?>">
                                                                <?= htmlspecialchars($activity['action']) ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="font-medium"><?= htmlspecialchars($activity['details']) ?></div>
                                                        </td>
                                                        <td>
                                                            <span class="text-sm text-muted"><?= htmlspecialchars($activity['user']) ?></span>
                                                        </td>
                                                        <td>
                                                            <span class="text-sm text-muted"><?= htmlspecialchars($activity['date']) ?></span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="text-center p-8">
                                    <div class="text-4xl mb-4">📋</div>
                                    <p class="text-muted">No recent activity to display</p>
                                    <p class="text-sm text-muted mt-2">Activities will appear here as users interact with the system</p>
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