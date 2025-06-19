<!DOCTYPE html>
<html lang="en" class="theme-light">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <div class="dashboard-shell">
        <nav class="sidebar">
            <div class="sidebar-title">LMS Student</div>
            <a href="?page=dashboard">Dashboard</a>
            <a href="?page=logout" class="sidebar-logout">Logout</a>
            <button id="theme-toggle" aria-label="Toggle dark mode" style="margin-top:2rem;">🌓</button>
        </nav>
        <main class="dashboard-main">
            <div class="dashboard-header">
                <h2>Welcome, <?= htmlspecialchars($full_name) ?></h2>
            </div>
            <div class="card">
                <h3>Your Enrolled Courses</h3>
                <?php if (empty($courses)): ?>
                    <p class="text-zinc-600 dark:text-zinc-300">You are not enrolled in any courses.</p>
                <?php else: ?>
                    <div class="space-y-6">
                    <?php foreach ($courses as $course): ?>
                        <div class="mb-4">
                            <div class="text-lg font-bold" style="color:var(--color-primary);margin-bottom:0.5rem;">
                                <?= htmlspecialchars($course['title']) ?> (<?= htmlspecialchars($course['code']) ?>)
                            </div>
                            <?php if (!empty($topic_tree[$course['id']])): ?>
                                <ul style="margin:0; padding-left:1.5rem;">
                                    <?php foreach ($topic_tree[$course['id']] as $topic): ?>
                                        <li style="margin-bottom:0.5rem;">
                                            <div class="font-semibold" style="color:var(--color-text);margin-bottom:0.2rem;"> <?= htmlspecialchars($topic['title']) ?> </div>
                                            <?php if (!empty($lectures_by_topic[$topic['id']])): ?>
                                                <ul style="margin:0; padding-left:1.2rem;">
                                                    <?php foreach ($lectures_by_topic[$topic['id']] as $lecture): ?>
                                                        <li class="card" style="padding:1rem 1.2rem; margin-bottom:0.7rem;">
                                                            <div class="font-medium" style="color:var(--color-text);margin-bottom:0.3rem;"> <?= htmlspecialchars($lecture['title']) ?> </div>
                                                            <div style="display:flex; flex-wrap:wrap; gap:0.7rem; margin-bottom:0.3rem;">
                                                                <?php if (!empty($lecture['file_path'])): ?>
                                                                    <a href="../public/uploads/<?= htmlspecialchars($lecture['file_path']) ?>" download class="button button-small">Download File</a>
                                                                <?php endif; ?>
                                                                <?php if (!empty($lecture['image_path'])): ?>
                                                                    <a href="../public/uploads/<?= htmlspecialchars($lecture['image_path']) ?>" target="_blank" class="button button-small">View Image</a>
                                                                <?php endif; ?>
                                                            </div>
                                                            <?php
                                                            $existing = null;
                                                            if ($user_id) {
                                                                $existing = Submission::findByStudentAndLecture($user_id, $lecture['id']);
                                                            }
                                                            ?>
                                                            <?php if ($existing && ($existing['grade'] || $existing['feedback'])): ?>
                                                                <div class="alert alert-success" style="margin-bottom:0.5rem;">
                                                                    <?php if ($existing['grade']): ?>
                                                                        <strong>Grade:</strong> <?= htmlspecialchars($existing['grade']) ?><br>
                                                                    <?php endif; ?>
                                                                    <?php if ($existing['feedback']): ?>
                                                                        <strong>Feedback:</strong> <?= nl2br(htmlspecialchars($existing['feedback'])) ?><br>
                                                                    <?php endif; ?>
                                                                </div>
                                                            <?php endif; ?>
                                                            <?php if (!empty($lecture['requires_submission'])): ?>
                                                                <div class="card" style="background:var(--color-bg);border:1px dashed var(--color-primary);margin-top:0.5rem;">
                                                                    <strong style="color:var(--color-primary);">Submission Required:</strong> <?= htmlspecialchars($lecture['submission_type']) ?><br>
                                                                    <?php if (!empty($lecture['submission_instructions'])): ?>
                                                                        <em style="color:var(--color-muted);"> <?= nl2br(htmlspecialchars($lecture['submission_instructions'])) ?> </em><br>
                                                                    <?php endif; ?>
                                                                    <?php if ($existing): ?>
                                                                        <div style="color:var(--color-success);margin-top:0.3rem;">You have submitted on <?= htmlspecialchars($existing['submitted_at']) ?>.</div>
                                                                        <?php if ($existing['file_path']): ?>
                                                                            <a href="../public/uploads/<?= htmlspecialchars($existing['file_path']) ?>" download class="button button-small">Download Your Submission</a><br>
                                                                        <?php endif; ?>
                                                                        <?php if ($existing['text_submission']): ?>
                                                                            <div><strong>Your Text Submission:</strong><br><span style="white-space:pre-line;"><?= nl2br(htmlspecialchars($existing['text_submission'])) ?></span></div>
                                                                        <?php endif; ?>
                                                                        <form method="POST" enctype="multipart/form-data" style="margin-top:0.5rem;">
                                                                            <input type="hidden" name="lecture_id" value="<?= $lecture['id'] ?>">
                                                                            <button type="submit" name="resubmit" value="1" class="button button-small">Resubmit</button>
                                                                        </form>
                                                                    <?php else: ?>
                                                                        <form method="POST" enctype="multipart/form-data" style="margin-top:0.5rem;display:flex;flex-direction:column;gap:0.5rem;">
                                                                            <input type="hidden" name="lecture_id" value="<?= $lecture['id'] ?>">
                                                                            <?php if ($lecture['submission_type'] === 'file' || $lecture['submission_type'] === 'both'): ?>
                                                                                <label>File: <input type="file" name="submission_file"></label>
                                                                            <?php endif; ?>
                                                                            <?php if ($lecture['submission_type'] === 'text' || $lecture['submission_type'] === 'both'): ?>
                                                                                <label>Text:<br><textarea name="submission_text" rows="3" cols="40"></textarea></label>
                                                                            <?php endif; ?>
                                                                            <button type="submit" name="submit_lecture" value="1" class="button button-small">Submit</button>
                                                                        </form>
                                                                    <?php endif; ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p style="color:var(--color-muted);">No topics found for this course.</p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
    <script src="../public/js/theme.js"></script>
</body>
</html> 