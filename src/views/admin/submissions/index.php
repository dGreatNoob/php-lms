<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Submissions - LMS</title>
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
                    <h1 class="dashboard__title">All Student Submissions</h1>
                    <p class="dashboard__subtitle">View, grade, and give feedback on all student submissions</p>
                </div>
            </header>
            <div class="dashboard__content">
                <div class="container">
                    <div class="card">
                        <div class="card__header">
                            <h2 class="card__title">Submissions by Course, Topic, Lecture</h2>
                        </div>
                        <div class="card__body">
                            <?php
                            $filter = $_GET['filter'] ?? 'unchecked';
                            ?>
                            <div class="mb-4 flex flex--gap-2">
                                <form method="GET" class="flex flex--gap-2">
                                    <input type="hidden" name="page" value="admin">
                                    <input type="hidden" name="section" value="submissions">
                                    <select name="filter" class="form__select">
                                        <option value="unchecked"<?= $filter === 'unchecked' ? ' selected' : '' ?>>Unchecked Only</option>
                                        <option value="checked"<?= $filter === 'checked' ? ' selected' : '' ?>>Checked Only</option>
                                    </select>
                                    <button type="submit" class="btn btn--primary">Filter</button>
                                </form>
                            </div>
                            <?php
                            // Group submissions by course > topic > lecture
                            $grouped = [];
                            foreach ($submissions as $sub) {
                                $is_checked = !empty($sub['grade']) || !empty($sub['feedback']);
                                if (($filter === 'unchecked' && !$is_checked) || ($filter === 'checked' && $is_checked)) {
                                    $grouped[$sub['course_code'] . '|' . $sub['course_title']]
                                        [$sub['topic_title']]
                                        [$sub['lecture_title']][] = $sub;
                                }
                            }
                            ?>
                            <?php if (empty($submissions) || empty($grouped)): ?>
                                <div class="alert alert--info" role="alert">
                                    <span class="alert__icon">ℹ️</span>
                                    <div class="alert__content">
                                        <div class="alert__message">No submissions have been received yet.</div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="accordion">
                                    <?php foreach ($grouped as $courseKey => $topics):
                                        list($courseCode, $courseTitle) = explode('|', $courseKey, 2); ?>
                                        <div class="accordion__item">
                                            <div class="accordion__header flex flex--between accordion__header--clickable" tabindex="0">
                                                <div>
                                                    <span class="badge badge--primary mr-4"><?= htmlspecialchars($courseCode) ?></span>
                                                    <span class="font-semibold"><?= htmlspecialchars($courseTitle) ?></span>
                                                </div>
                                                <span class="accordion__icon">▼</span>
                                            </div>
                                            <div class="accordion__content" style="display: none;">
                                                <?php foreach ($topics as $topicTitle => $lectures): ?>
                                                    <div class="accordion__item">
                                                        <div class="accordion__header accordion__header--clickable font-semibold mb-2" tabindex="0">
                                                            Topic: <?= htmlspecialchars($topicTitle) ?>
                                                            <span class="accordion__icon">▼</span>
                                                        </div>
                                                        <div class="accordion__content" style="display: none;">
                                                            <?php foreach ($lectures as $lectureTitle => $subs): ?>
                                                                <div class="accordion__item">
                                                                    <div class="accordion__header accordion__header--clickable font-medium mb-2" tabindex="0">
                                                                        Lecture: <?= htmlspecialchars($lectureTitle) ?>
                                                                        <span class="accordion__icon">▼</span>
                                                                    </div>
                                                                    <div class="accordion__content" style="display: none;">
                                                                        <div class="table-container mb-4">
                                                                            <table class="table">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>Student</th>
                                                                                        <th>Submitted At</th>
                                                                                        <th>Status</th>
                                                                                        <th>File</th>
                                                                                        <th>Text</th>
                                                                                        <th>Grade</th>
                                                                                        <th>Feedback</th>
                                                                                        <th>Actions</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php foreach ($subs as $sub): ?>
                                                                                    <?php
                                                                                    $due = $sub['due_date'] ? strtotime($sub['due_date']) : null;
                                                                                    $submitted = strtotime($sub['submitted_at']);
                                                                                    $status = 'on time';
                                                                                    $badge = 'success';
                                                                                    if ($due) {
                                                                                        if ($submitted > $due) {
                                                                                            $status = 'late';
                                                                                            $badge = 'warning';
                                                                                        } else {
                                                                                            $status = 'on time';
                                                                                            $badge = 'success';
                                                                                        }
                                                                                    } else {
                                                                                        $status = 'no due date';
                                                                                        $badge = 'muted';
                                                                                    }
                                                                                    ?>
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
                                                                                            <span class="badge badge--<?= $badge ?> text-capitalize"><?= htmlspecialchars($status) ?></span>
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($sub['file_path']): ?>
                                                                                                <a href="uploads/<?= htmlspecialchars(basename($sub['file_path'])) ?>" download class="btn btn--sm btn--secondary">📎 Download</a>
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
                                                                                            <form method="POST" class="form">
                                                                                                <input type="hidden" name="submission_id" value="<?= $sub['id'] ?>">
                                                                                                <div class="form__group">
                                                                                                    <label for="grade-<?= $sub['id'] ?>" class="form__label">Grade</label>
                                                                                                    <input type="text" id="grade-<?= $sub['id'] ?>" name="grade" value="<?= htmlspecialchars($sub['grade']) ?>" placeholder="Grade" class="form__input form__input--sm">
                                                                                                </div>
                                                                                                <div class="form__group">
                                                                                                    <label for="feedback-<?= $sub['id'] ?>" class="form__label">Feedback</label>
                                                                                                    <textarea id="feedback-<?= $sub['id'] ?>" name="feedback" rows="3" placeholder="Feedback" class="form__textarea form__textarea--sm"><?= htmlspecialchars($sub['feedback']) ?></textarea>
                                                                                                </div>
                                                                                                <button type="submit" class="btn btn--sm btn--primary w-full">
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
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
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
        // Make all accordion parents clickable
        document.querySelectorAll('.accordion__header--clickable').forEach(function(header) {
            header.addEventListener('click', function(e) {
                // Only toggle if not clicking on a form element inside
                if (e.target.closest('form')) return;
                const parent = header.parentElement;
                const content = parent.querySelector('.accordion__content');
                const icon = header.querySelector('.accordion__icon');
                if (!content) return;
                if (content.style.display === 'block') {
                    content.style.display = 'none';
                    if (icon) icon.textContent = '▼';
                } else {
                    content.style.display = 'block';
                    if (icon) icon.textContent = '▲';
                }
            });
        });
    });
    </script>
</body>
</html> 