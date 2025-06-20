<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - LMS</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="sidebar__header">
                <h2 class="sidebar__title">LMS Student</h2>
            </div>
            
            <nav class="sidebar__nav">
                <div class="sidebar__section">
                    <h3 class="sidebar__section-title">Navigation</h3>
                    <a href="?page=dashboard" class="sidebar__link sidebar__link--active">
                        <span>🏠</span>
                        <span>Dashboard</span>
                    </a>
                </div>
                
                <div class="sidebar__section">
                    <h3 class="sidebar__section-title">Account</h3>
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
                    <p class="dashboard__subtitle">Your learning journey starts here</p>
                </div>
            </header>

            <div class="dashboard__content">
                <div class="container">
                    <div class="card">
                        <div class="card__header">
                            <h2 class="card__title">Your Enrolled Courses</h2>
                            <p class="card__subtitle">Access your course materials and submit assignments</p>
                        </div>
                        <div class="card__body">
                            <?php if (empty($courses)): ?>
                                <div class="text-center p-8">
                                    <div class="text-4xl mb-4">📚</div>
                                    <h3 class="text-lg font-semibold mb-2">No Courses Enrolled</h3>
                                    <p class="text-muted">You are not enrolled in any courses yet. Contact your administrator to get started.</p>
                                </div>
                            <?php else: ?>
                                <div class="space-y-8">
                                    <?php foreach ($courses as $course): ?>
                                        <div class="card">
                                            <div class="card__header">
                                                <h3 class="card__title">
                                                    <?= htmlspecialchars($course['title']) ?>
                                                    <span class="badge badge--primary ml-2"><?= htmlspecialchars($course['code']) ?></span>
                                                </h3>
                                                <p class="card__subtitle"><?= htmlspecialchars($course['description'] ?? '') ?></p>
                                            </div>
                                            <div class="card__body">
                                                <?php if (!empty($topic_tree[$course['id']])): ?>
                                                    <div class="space-y-6">
                                                        <?php foreach ($topic_tree[$course['id']] as $topic): ?>
                                                            <div class="border-l-4 border-primary-200 pl-4">
                                                                <h4 class="font-semibold text-lg mb-3"><?= htmlspecialchars($topic['title']) ?></h4>
                                                                
                                                                <?php if (!empty($lectures_by_topic[$topic['id']])): ?>
                                                                    <div class="space-y-4">
                                                                        <?php foreach ($lectures_by_topic[$topic['id']] as $lecture): ?>
                                                                            <div class="card">
                                                                                <div class="card__body">
                                                                                    <div class="flex flex--between mb-3">
                                                                                        <h5 class="font-medium"><?= htmlspecialchars($lecture['title']) ?></h5>
                                                                                        <?php if (!empty($lecture['requires_submission'])): ?>
                                                                                            <span class="badge badge--warning">Submission Required</span>
                                                                                        <?php endif; ?>
                                                                                    </div>
                                                                                    
                                                                                    <div class="mb-3">
                                                                                        <p class="text-muted"><?= htmlspecialchars($lecture['content'] ?? '') ?></p>
                                                                                    </div>
                                                                                    
                                                                                    <!-- Lecture Attachments -->
                                                                                    <?php if (!empty($lecture['file_path']) || !empty($lecture['image_path'])): ?>
                                                                                        <div class="flex flex--gap-2 mb-4">
                                                                                            <?php if (!empty($lecture['file_path'])): ?>
                                                                                                <a href="../public/uploads/<?= htmlspecialchars($lecture['file_path']) ?>" 
                                                                                                   download 
                                                                                                   class="btn btn--sm btn--secondary">
                                                                                                    📎 Download File
                                                                                                </a>
                                                                                            <?php endif; ?>
                                                                                            <?php if (!empty($lecture['image_path'])): ?>
                                                                                                <a href="../public/uploads/<?= htmlspecialchars($lecture['image_path']) ?>" 
                                                                                                   target="_blank" 
                                                                                                   class="btn btn--sm btn--secondary">
                                                                                                    🖼️ View Image
                                                                                                </a>
                                                                                            <?php endif; ?>
                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                    
                                                                                    <?php
                                                                                    $existing = null;
                                                                                    if ($user_id) {
                                                                                        $existing = Submission::findByStudentAndLecture($user_id, $lecture['id']);
                                                                                    }
                                                                                    ?>
                                                                                    
                                                                                    <!-- Submission Feedback -->
                                                                                    <?php if ($existing && ($existing['grade'] || $existing['feedback'])): ?>
                                                                                        <div class="alert alert--success mb-4">
                                                                                            <span class="alert__icon">✅</span>
                                                                                            <div class="alert__content">
                                                                                                <div class="alert__title">Submission Feedback</div>
                                                                                                <div class="alert__message">
                                                                                                    <?php if ($existing['grade']): ?>
                                                                                                        <strong>Grade:</strong> <?= htmlspecialchars($existing['grade']) ?><br>
                                                                                                    <?php endif; ?>
                                                                                                    <?php if ($existing['feedback']): ?>
                                                                                                        <strong>Feedback:</strong> <?= nl2br(htmlspecialchars($existing['feedback'])) ?>
                                                                                                    <?php endif; ?>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                    
                                                                                    <!-- Submission Form -->
                                                                                    <?php if (!empty($lecture['requires_submission'])): ?>
                                                                                        <div class="border border-primary-200 rounded-lg p-4 bg-primary-50">
                                                                                            <h6 class="font-semibold mb-3">Submission Requirements</h6>
                                                                                            <p class="text-sm text-muted mb-3">
                                                                                                <strong>Type:</strong> <?= htmlspecialchars($lecture['submission_type']) ?>
                                                                                            </p>
                                                                                            
                                                                                            <?php if (!empty($lecture['submission_instructions'])): ?>
                                                                                                <div class="mb-3">
                                                                                                    <p class="text-sm"><strong>Instructions:</strong></p>
                                                                                                    <p class="text-sm text-muted"><?= nl2br(htmlspecialchars($lecture['submission_instructions'])) ?></p>
                                                                                                </div>
                                                                                            <?php endif; ?>
                                                                                            
                                                                                            <?php if ($existing): ?>
                                                                                                <div class="alert alert--info mb-3">
                                                                                                    <span class="alert__icon">📅</span>
                                                                                                    <div class="alert__content">
                                                                                                        <div class="alert__message">
                                                                                                            Submitted on <?= htmlspecialchars($existing['submitted_at']) ?>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                
                                                                                                <?php if ($existing['file_path']): ?>
                                                                                                    <a href="../public/uploads/<?= htmlspecialchars($existing['file_path']) ?>" 
                                                                                                       download 
                                                                                                       class="btn btn--sm btn--secondary mb-2">
                                                                                                        📎 Download Your Submission
                                                                                                    </a>
                                                                                                <?php endif; ?>
                                                                                                
                                                                                                <?php if ($existing['text_submission']): ?>
                                                                                                    <div class="mb-3">
                                                                                                        <p class="text-sm font-medium">Your Text Submission:</p>
                                                                                                        <div class="bg-white p-3 rounded border text-sm">
                                                                                                            <?= nl2br(htmlspecialchars($existing['text_submission'])) ?>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                <?php endif; ?>
                                                                                                
                                                                                                <form method="POST" enctype="multipart/form-data">
                                                                                                    <input type="hidden" name="lecture_id" value="<?= $lecture['id'] ?>">
                                                                                                    <button type="submit" name="resubmit" value="1" class="btn btn--sm btn--secondary">
                                                                                                        🔄 Resubmit
                                                                                                    </button>
                                                                                                </form>
                                                                                            <?php else: ?>
                                                                                                <form method="POST" enctype="multipart/form-data" class="space-y-3">
                                                                                                    <input type="hidden" name="lecture_id" value="<?= $lecture['id'] ?>">
                                                                                                    
                                                                                                    <?php if ($lecture['submission_type'] === 'file' || $lecture['submission_type'] === 'both'): ?>
                                                                                                        <div class="form__group">
                                                                                                            <label for="file_<?= $lecture['id'] ?>" class="form__label">
                                                                                                                Upload File
                                                                                                            </label>
                                                                                                            <input type="file" 
                                                                                                                   id="file_<?= $lecture['id'] ?>"
                                                                                                                   name="submission_file" 
                                                                                                                   class="form__input">
                                                                                                        </div>
                                                                                                    <?php endif; ?>
                                                                                                    
                                                                                                    <?php if ($lecture['submission_type'] === 'text' || $lecture['submission_type'] === 'both'): ?>
                                                                                                        <div class="form__group">
                                                                                                            <label for="text_<?= $lecture['id'] ?>" class="form__label">
                                                                                                                Text Submission
                                                                                                            </label>
                                                                                                            <textarea id="text_<?= $lecture['id'] ?>"
                                                                                                                      name="submission_text" 
                                                                                                                      rows="4" 
                                                                                                                      class="form__textarea"
                                                                                                                      placeholder="Enter your submission text here..."></textarea>
                                                                                                        </div>
                                                                                                    <?php endif; ?>
                                                                                                    
                                                                                                    <button type="submit" name="submit_lecture" value="1" class="btn btn--primary">
                                                                                                        📤 Submit Assignment
                                                                                                    </button>
                                                                                                </form>
                                                                                            <?php endif; ?>
                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                            </div>
                                                                        <?php endforeach; ?>
                                                                    </div>
                                                                <?php else: ?>
                                                                    <p class="text-muted">No lectures available for this topic.</p>
                                                                <?php endif; ?>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="text-center p-6">
                                                        <p class="text-muted">No topics found for this course.</p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
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