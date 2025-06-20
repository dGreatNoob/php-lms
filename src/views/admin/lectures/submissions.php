<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecture Submissions - LMS</title>
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
                    <div class="flex flex--items-center flex--justify-between">
                        <div>
                            <h1 class="dashboard__title">Lecture Submissions</h1>
                            <p class="dashboard__subtitle"><?= htmlspecialchars($lecture['title']) ?></p>
                        </div>
                        <a href="?page=admin&section=lectures" class="btn btn--secondary">
                            <span>↩️</span>
                            <span>Back to Lectures</span>
                        </a>
                    </div>
                </div>
            </header>

            <div class="dashboard__content">
                <div class="container">
                    <div class="card">
                        <div class="card__header">
                            <h2 class="card__title">Student Submissions</h2>
                            <p class="card__subtitle">Review and grade student submissions for this lecture</p>
                        </div>
                        <div class="card__body">
                            <?php if (empty($submissions)): ?>
                                <div class="alert alert--info" role="alert">
                                    <span class="alert__icon">ℹ️</span>
                                    <div class="alert__content">
                                        <div class="alert__message">No submissions have been received for this lecture yet.</div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="table-container">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Student</th>
                                                <th>Submitted At</th>
                                                <th>File</th>
                                                <th>Text</th>
                                                <th>Grade</th>
                                                <th>Feedback</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($submissions as $sub): ?>
                                            <tr>
                                                <td>
                                                    <div class="flex flex--col">
                                                        <strong><?= htmlspecialchars($sub['first_name'] . ' ' . $sub['last_name']) ?></strong>
                                                        <span class="text-sm text-gray-600"><?= htmlspecialchars($sub['username']) ?></span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-sm"><?= htmlspecialchars($sub['submitted_at']) ?></span>
                                                </td>
                                                <td>
                                                    <?php if ($sub['file_path']): ?>
                                                        <a href="uploads/<?= htmlspecialchars(basename($sub['file_path'])) ?>" 
                                                           download 
                                                           class="btn btn--sm btn--secondary">
                                                            📎 Download
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-gray-500">No file</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($sub['text_submission']): ?>
                                                        <div class="max-w-xs max-h-24 overflow-auto border border-gray-200 p-2 bg-gray-50 rounded text-sm">
                                                            <?= nl2br(htmlspecialchars($sub['text_submission'])) ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-gray-500">No text</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge badge--<?= $sub['grade'] ? 'success' : 'secondary' ?>">
                                                        <?= htmlspecialchars($sub['grade'] ?: 'Not graded') ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($sub['feedback']): ?>
                                                        <div class="max-w-xs max-h-24 overflow-auto text-sm">
                                                            <?= nl2br(htmlspecialchars($sub['feedback'])) ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-gray-500">No feedback</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <form method="POST" class="form form--inline">
                                                        <input type="hidden" name="submission_id" value="<?= $sub['id'] ?>">
                                                        <div class="form__group">
                                                            <label for="grade-<?= $sub['id'] ?>" class="form__label">Grade</label>
                                                            <input 
                                                                type="text" 
                                                                id="grade-<?= $sub['id'] ?>"
                                                                name="grade" 
                                                                value="<?= htmlspecialchars($sub['grade']) ?>" 
                                                                placeholder="Grade" 
                                                                class="form__input form__input--sm"
                                                                size="6"
                                                            >
                                                        </div>
                                                        <div class="form__group">
                                                            <label for="feedback-<?= $sub['id'] ?>" class="form__label">Feedback</label>
                                                            <textarea 
                                                                id="feedback-<?= $sub['id'] ?>"
                                                                name="feedback" 
                                                                rows="2" 
                                                                cols="20" 
                                                                placeholder="Feedback"
                                                                class="form__textarea form__textarea--sm"
                                                            ><?= htmlspecialchars($sub['feedback']) ?></textarea>
                                                        </div>
                                                        <button type="submit" class="btn btn--sm btn--primary">
                                                            <span>💾</span>
                                                            <span>Save</span>
                                                        </button>
                                                    </form>
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