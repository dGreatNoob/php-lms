<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - LMS</title>
    <link rel="stylesheet" href="css/style.css">
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
                    <!-- Quick Stats -->
                    <div class="grid grid--cols-1 grid--cols-md-3 mb-6">
                        <div class="card">
                            <div class="card__body">
                                <div class="flex flex--between">
                                    <div>
                                        <p class="text-muted text-sm">Enrolled Courses</p>
                                        <h3 class="text-xl font-bold"><?= count($courses) ?></h3>
                                    </div>
                                    <div class="text-2xl">📚</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card__body">
                                <div class="flex flex--between">
                                    <div>
                                        <p class="text-muted text-sm">Completed Lectures</p>
                                        <h3 class="text-xl font-bold"><?= $stats['completed_lectures'] ?? 0 ?></h3>
                                    </div>
                                    <div class="text-2xl">✅</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card__body">
                                <div class="flex flex--between">
                                    <div>
                                        <p class="text-muted text-sm">Pending Submissions</p>
                                        <h3 class="text-xl font-bold"><?= $stats['pending_submissions'] ?? 0 ?></h3>
                                    </div>
                                    <div class="text-2xl">📝</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card__header">
                            <h2 class="card__title">Your Enrolled Courses</h2>
                            <p class="card__subtitle">Access your course materials and submit assignments</p>
                        </div>
                        <div class="card__body">
                            <?php
                            if (!function_exists('render_topic')) {
                                function render_topic($topic, $lectures_by_topic, $user_id, $level = 0) {
                                    $indent_class = 'ml-' . ($level * 4);
                                    $topic_id = $topic['id'];
                                    ?>
                                    <div class="topic-accordion topic-accordion-level-<?= $level ?>" id="topic-accordion-<?= $topic_id ?>">
                                        <button type="button" class="topic-accordion__title flex flex--between" aria-expanded="false" aria-controls="topic-details-<?= $topic_id ?>">
                                            <span><?= htmlspecialchars($topic['title']) ?></span>
                                            <span class="badge badge--info">Topic</span>
                                        </button>
                                        <div class="topic-accordion__details" id="topic-details-<?= $topic_id ?>" style="display: none;">
                                            <?php
                                            if (!empty($lectures_by_topic[$topic_id])) {
                                                echo "<div class='mb-2'>";
                                                foreach ($lectures_by_topic[$topic_id] as $lecture) {
                                                    render_lecture($lecture, $user_id);
                                                }
                                                echo "</div>";
                                            }
                                            if (!empty($topic['children'])) {
                                                foreach ($topic['children'] as $child_topic) {
                                                    render_topic($child_topic, $lectures_by_topic, $user_id, $level + 1);
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }

                            if (!function_exists('render_lecture')) {
                                function render_lecture($lecture, $user_id) {
                                    $existing = $user_id ? Submission::findByStudentAndLecture($user_id, $lecture['id']) : null;
                                    $status = 'pending';
                                    $status_text = 'Not Started';
                                    if ($existing) {
                                        if ($existing['grade']) {
                                            $status = 'graded';
                                            $status_text = 'Graded';
                                        } else {
                                            $status = 'submitted';
                                            $status_text = 'Submitted';
                                        }
                                    }
                                    ?>
                                    <div class="lecture-accordion" id="lecture-accordion-<?= $lecture['id'] ?>">
                                        <button type="button" class="lecture-accordion__title flex flex--between" aria-expanded="false" aria-controls="lecture-details-<?= $lecture['id'] ?>">
                                            <span><?= htmlspecialchars($lecture['title']) ?></span>
                                            <span class="badge badge--<?= $status === 'graded' ? 'success' : ($status === 'submitted' ? 'info' : 'muted') ?>">
                                                <?= $status_text ?>
                                            </span>
                                        </button>
                                        <div class="lecture-accordion__details" id="lecture-details-<?= $lecture['id'] ?>" style="display: none;">
                                            <div class="card__body">
                                                <p class="text-muted mb-3"><?= htmlspecialchars($lecture['content'] ?? '') ?></p>

                                                <?php if (!empty($lecture['attachment'])): ?>
                                                    <div class="flex flex--gap-2 mb-4">
                                                        <a href="uploads/<?= htmlspecialchars(basename($lecture['attachment'])) ?>" download class="btn btn--sm btn--secondary">
                                                            📎 Download Attachment
                                                        </a>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if ($existing && ($existing['grade'] || $existing['feedback'])): ?>
                                                    <div class="alert alert--success mb-4">
                                                        <div class="alert__content">
                                                            <strong>Feedback:</strong> 
                                                            <?php if($existing['grade']) echo "Grade: ".htmlspecialchars($existing['grade']); ?>
                                                            <?= nl2br(htmlspecialchars($existing['feedback'])) ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if (!empty($lecture['allow_submissions'])): ?>
                                                    <?php if (!empty($lecture['due_date'])): ?>
                                                        <?php 
                                                            $now = new DateTime();
                                                            $due = new DateTime($lecture['due_date']);
                                                            $is_overdue = $now > $due && empty($existing);
                                                        ?>
                                                        <div class="alert alert--info mb-2">
                                                            <strong>Submission Deadline:</strong> <?= date('F j, Y, g:i A', strtotime($lecture['due_date'])) ?>
                                                            <?php if ($is_overdue): ?>
                                                                <span style="color: #dc2626; font-weight: bold; margin-left: 1em;">Overdue</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="submission-area mt-4">
                                                        <h6 class="font-semibold mb-3">Submission</h6>
                                                        <?php if ($existing): ?>
                                                            <div class="alert alert--info mb-3">
                                                                <div class="alert__content">
                                                                    Submitted on <?= htmlspecialchars($existing['submitted_at']) ?>
                                                                </div>
                                                            </div>
                                                            <?php if ($existing['file_path']): ?>
                                                                <a href="uploads/<?= htmlspecialchars($existing['file_path']) ?>" download class="btn btn--sm btn--secondary mb-2">
                                                                    📎 Download Your Submission
                                                                </a>
                                                            <?php endif; ?>
                                                            <?php if ($existing['text_submission']): ?>
                                                                <div class="mb-2"><strong>Your Text Submission:</strong><br><?= nl2br(htmlspecialchars($existing['text_submission'])) ?></div>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                        <form method="POST" enctype="multipart/form-data">
                                                            <input type="hidden" name="lecture_id" value="<?= $lecture['id'] ?>">
                                                            <?php if ($existing): ?>
                                                                <input type="hidden" name="resubmit" value="1">
                                                            <?php else: ?>
                                                                <input type="hidden" name="submit_lecture" value="1">
                                                            <?php endif; ?>
                                                            <div class="form__group">
                                                                <label for="submission_text_<?= $lecture['id'] ?>" class="form__label">Text Submission (optional)</label>
                                                                <textarea id="submission_text_<?= $lecture['id'] ?>" name="submission_text" class="form__textarea" rows="3"></textarea>
                                                            </div>
                                                            <div class="form__group">
                                                                <label for="submission_file_<?= $lecture['id'] ?>" class="form__label">File Submission (optional)</label>
                                                                <input type="file" id="submission_file_<?= $lecture['id'] ?>" name="submission_file" class="form__input">
                                                            </div>
                                                            <button type="submit" class="btn btn--primary mt-2">
                                                                <?= $existing ? 'Resubmit' : 'Submit' ?>
                                                            </button>
                                                        </form>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                            <?php if (empty($courses)): ?>
                                <div class="text-center p-8">
                                    <div class="text-4xl mb-4">📚</div>
                                    <h3 class="text-lg font-semibold mb-2">No Courses Enrolled</h3>
                                    <p class="text-muted">You are not enrolled in any courses yet. Contact your administrator to get started.</p>
                                </div>
                            <?php else: ?>
                                <div class="accordion">
                                    <?php foreach ($courses as $course): ?>
                                        <div class="accordion__item">
                                            <button class="accordion__header">
                                                <div class="flex-grow text-left">
                                                    <h3 class="card__title">
                                                        <?= htmlspecialchars($course['title']) ?>
                                                        <span class="badge badge--primary ml-2"><?= htmlspecialchars($course['code']) ?></span>
                                                    </h3>
                                                    <p class="card__subtitle mt-1"><?= htmlspecialchars($course['description'] ?? '') ?></p>
                                                </div>
                                                <span class="accordion__icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                                </span>
                                            </button>
                                            <div class="accordion__content">
                                                <?php if (!empty($topic_tree[$course['id']])): ?>
                                                    <div class="space-y-6">
                                                        <?php
                                                        foreach ($topic_tree[$course['id']] as $topic) {
                                                            render_topic($topic, $lectures_by_topic, $user_id);
                                                        }
                                                        ?>
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

    <script src="js/script.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log('Student dashboard JavaScript loaded');
        
        const accordions = document.querySelectorAll('.accordion__item');
        console.log('Found accordion items:', accordions.length);
    
        accordions.forEach((item, index) => {
            const header = item.querySelector('.accordion__header');
            if (header) {
                header.addEventListener('click', (e) => {
                    e.preventDefault();
                    console.log('Accordion clicked:', index);
                    
                    // Close all other accordions
                    accordions.forEach(otherItem => {
                        if (otherItem !== item) {
                            otherItem.classList.remove('accordion__item--active');
                        }
                    });
                    
                    // Toggle the current one
                    item.classList.toggle('accordion__item--active');
                    console.log('Accordion active:', item.classList.contains('accordion__item--active'));
                });
            }
        });
    
        // Optionally, open the first accordion by default
        if (accordions.length > 0) {
            accordions[0].classList.add('accordion__item--active');
            console.log('First accordion opened by default');
        }
    });
    </script>
    <style>
    .lecture-accordion { border-bottom: 1px solid #e5e7eb; margin-bottom: 0.5em; }
    .lecture-accordion__title {
        width: 100%;
        background: none;
        border: none;
        font-size: 1.08em;
        font-weight: 500;
        padding: 1em 0.5em;
        text-align: left;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        outline: none;
        transition: background 0.15s;
    }
    .lecture-accordion__title[aria-expanded="true"] { background: #f3f4f6; }
    .lecture-accordion__details { padding: 0.5em 0.5em 1em 0.5em; }
    .topic-accordion { border-bottom: 1px solid #e5e7eb; margin-bottom: 0.5em; }
    .topic-accordion__title {
        width: 100%;
        background: none;
        border: none;
        font-size: 1.04em;
        font-weight: 500;
        padding: 0.7em 0.5em;
        text-align: left;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        outline: none;
        transition: background 0.15s;
    }
    .topic-accordion__title[aria-expanded="true"] { background: #f3f4f6; }
    .topic-accordion__details { padding: 0.5em 0.5em 0.5em 1.5em; }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Topic accordions (per level)
        for (let level = 0; level < 10; level++) {
            const topicAccordions = document.querySelectorAll('.topic-accordion-level-' + level + ' .topic-accordion__title');
            topicAccordions.forEach(btn => {
                btn.addEventListener('click', function() {
                    const expanded = this.getAttribute('aria-expanded') === 'true';
                    // Close all at this level
                    topicAccordions.forEach(b => {
                        b.setAttribute('aria-expanded', 'false');
                        document.getElementById('topic-details-' + b.parentElement.id.split('-').pop()).style.display = 'none';
                    });
                    // Open this one if it was closed
                    if (!expanded) {
                        this.setAttribute('aria-expanded', 'true');
                        document.getElementById('topic-details-' + this.parentElement.id.split('-').pop()).style.display = 'block';
                    }
                });
            });
        }
        // Lecture accordions (as before)
        const accordions = document.querySelectorAll('.lecture-accordion__title');
        accordions.forEach(btn => {
            btn.addEventListener('click', function() {
                const expanded = this.getAttribute('aria-expanded') === 'true';
                // Close all
                accordions.forEach(b => {
                    b.setAttribute('aria-expanded', 'false');
                    document.getElementById('lecture-details-' + b.parentElement.id.split('-').pop()).style.display = 'none';
                });
                // Open this one if it was closed
                if (!expanded) {
                    this.setAttribute('aria-expanded', 'true');
                    document.getElementById('lecture-details-' + this.parentElement.id.split('-').pop()).style.display = 'block';
                }
            });
        });
    });
    </script>
</body>
</html> 