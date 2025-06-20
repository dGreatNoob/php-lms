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
        <?php include __DIR__ . '/sidebar.php'; ?>

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
                                    $has_children = !empty($topic['children']);
                                    $accordion_id = 'topic-accordion-' . $topic['id'];
                                    $indent_class = 'ml-' . ($level * 4);
                                    echo "<div class='topic-group {$indent_class}'>";
                                    echo "<div class='topic-header flex flex--between items-center'>";
                                    echo "<h4 class='font-semibold text-lg mb-3'>" . htmlspecialchars($topic['title']) . "</h4>";
                                    if ($has_children) {
                                        echo "<button class='btn btn--icon btn--sm topic-toggle' data-target='{$accordion_id}' aria-label='Toggle subtopics'>";
                                        echo "<span>▼</span>";
                                        echo "</button>";
                                    }
                                    echo "</div>";
                                    // Lectures for this topic
                                    if (!empty($lectures_by_topic[$topic['id']])) {
                                        echo "<div class='space-y-4'>";
                                        foreach ($lectures_by_topic[$topic['id']] as $lecture) {
                                            render_lecture($lecture, $user_id);
                                        }
                                        echo "</div>";
                                    }
                                    // Subtopics (children)
                                    if ($has_children) {
                                        echo "<div class='topic-children' id='{$accordion_id}' style='display:none; margin-left:1.5rem;'>";
                                        foreach ($topic['children'] as $child_topic) {
                                            render_topic($child_topic, $lectures_by_topic, $user_id, $level + 1);
                                        }
                                        echo "</div>";
                                    }
                                    echo "</div>";
                                }
                            }

                            if (!function_exists('render_lecture')) {
                                function render_lecture($lecture, $user_id) {
                                    $existing = $user_id ? Submission::findByLectureAndStudent($lecture['id'], $user_id) : null;
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
                                    <div class="card lecture-card lecture-card--<?= $status ?>">
                                        <div class="card__body">
                                            <div class="flex flex--between mb-3">
                                                <h5 class="font-medium"><?= htmlspecialchars($lecture['title']) ?></h5>
                                                <span class="badge badge--<?= $status === 'graded' ? 'success' : ($status === 'submitted' ? 'info' : 'muted') ?>">
                                                    <?= $status_text ?>
                                                </span>
                                            </div>

                                            <p class="text-muted mb-3"><?= htmlspecialchars($lecture['content'] ?? '') ?></p>

                                            <?php if (!empty($lecture['file_path']) || !empty($lecture['image_path'])): ?>
                                                <div class="flex flex--gap-2 mb-4">
                                                    <?php if (!empty($lecture['file_path'])): ?>
                                                        <a href="uploads/<?= htmlspecialchars($lecture['file_path']) ?>" download class="btn btn--sm btn--secondary">
                                                            📎 Download File
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if (!empty($lecture['image_path'])): ?>
                                                        <a href="uploads/<?= htmlspecialchars($lecture['image_path']) ?>" target="_blank" class="btn btn--sm btn--secondary">
                                                            🖼️ View Image
                                                        </a>
                                                    <?php endif; ?>
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

                                            <?php if (!empty($lecture['requires_submission'])): ?>
                                                <div class="submission-area">
                                                    <button class="btn btn--primary btn--sm" onclick="this.nextElementSibling.style.display = this.nextElementSibling.style.display === 'none' ? 'block' : 'none'">
                                                        <?= $existing ? 'View/Resubmit' : 'Submit Assignment' ?>
                                                    </button>
                                                    <div class="submission-form mt-4" style="display: none;">
                                                        <div class="border border-primary-200 rounded-lg p-4 bg-primary-50">
                                                            <h6 class="font-semibold mb-3">Submission Details</h6>
                                                            <?php if (!empty($lecture['submission_instructions'])): ?>
                                                                <div class="mb-3">
                                                                    <p class="text-sm"><strong>Instructions:</strong></p>
                                                                    <p class="text-sm text-muted"><?= nl2br(htmlspecialchars($lecture['submission_instructions'])) ?></p>
                                                                </div>
                                                            <?php endif; ?>

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
                                                                    <div class="mb-3">
                                                                        <p class="text-sm font-medium">Your Text Submission:</p>
                                                                        <div class="bg-white p-3 rounded border text-sm">
                                                                            <?= nl2br(htmlspecialchars($existing['text_submission'])) ?>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                            <?php endif; ?>

                                                            <form method="POST" enctype="multipart/form-data" class="space-y-3">
                                                                <input type="hidden" name="lecture_id" value="<?= $lecture['id'] ?>">
                                                                <?php if ($lecture['submission_type'] === 'file' || $lecture['submission_type'] === 'both'): ?>
                                                                    <div class="form__group">
                                                                        <label for="file_<?= $lecture['id'] ?>" class="form__label">
                                                                            Upload File <?= $existing ? '(Optional: replace existing)' : '' ?>
                                                                        </label>
                                                                        <input type="file" id="file_<?= $lecture['id'] ?>" name="submission_file" class="form__input">
                                                                    </div>
                                                                <?php endif; ?>
                                                                <?php if ($lecture['submission_type'] === 'text' || $lecture['submission_type'] === 'both'): ?>
                                                                    <div class="form__group">
                                                                        <label for="text_<?= $lecture['id'] ?>" class="form__label">
                                                                            Text Submission
                                                                        </label>
                                                                        <textarea id="text_<?= $lecture['id'] ?>" name="submission_text" rows="4" class="form__textarea" placeholder="Enter your submission text here..."><?= $existing['text_submission'] ?? '' ?></textarea>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <button type="submit" name="<?= $existing ? 'resubmit' : 'submit_lecture' ?>" value="1" class="btn btn--primary">
                                                                    <?= $existing ? '🔄 Resubmit' : '📤 Submit Assignment' ?>
                                                                </button>
                                                            </form>
                                                        </div>
                                                     </div>
                                                </div>
                                            <?php endif; ?>
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

        // Nested topic dropdown logic
        document.querySelectorAll('.topic-toggle').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var target = document.getElementById(btn.getAttribute('data-target'));
                if (target) {
                    target.style.display = (target.style.display === 'none' || target.style.display === '') ? 'block' : 'none';
                }
            });
        });
    });
    </script>
</body>
</html> 