<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll Student - LMS</title>
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
                    <h1 class="dashboard__title">Enroll Student</h1>
                    <p class="dashboard__subtitle">Manually enroll a student in a course</p>
                </div>
            </header>

            <div class="dashboard__content">
                <div class="container">
                    <div class="card">
                        <div class="card__header">
                            <h2 class="card__title">Enrollment Information</h2>
                            <p class="card__subtitle">Select a student and course to create an enrollment</p>
                        </div>
                        <div class="card__body">
                            <?php if (!empty($error)): ?>
                                <div class="alert alert--error" role="alert">
                                    <span class="alert__icon">⚠️</span>
                                    <div class="alert__content">
                                        <div class="alert__message"><?= htmlspecialchars($error) ?></div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="?page=admin&section=enrollments&action=create" class="form" data-validate>
                                <?php if (!empty($quick_student)): ?>
                                <div class="form__group">
                                    <label class="form__label">Student</label>
                                    <div class="card mb-4 p-4" style="background: var(--color-bg-tertiary);">
                                        <div class="font-semibold mb-2"><?= htmlspecialchars($quick_student['first_name'] . ' ' . $quick_student['last_name']) ?></div>
                                        <div class="mb-1"><span class="badge badge--info">Username</span> <?= htmlspecialchars($quick_student['username']) ?></div>
                                        <div><span class="badge badge--muted">Email</span> <?= htmlspecialchars($quick_student['email']) ?></div>
                                    </div>
                                    <input type="hidden" name="student_id" value="<?= $quick_student['id'] ?>">
                                </div>
                                <?php else: ?>
                                <div class="form__group">
                                    <label for="student_id" class="form__label form__label--required">
                                        Student
                                    </label>
                                    <select name="student_id" id="student_id" class="form__select" required>
                                        <option value="">-- Select Student --</option>
                                        <?php foreach ($students as $student): ?>
                                            <option value="<?= $student['id'] ?>"><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?> (<?= htmlspecialchars($student['username']) ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="form__help-text">
                                        Choose the student to enroll
                                    </div>
                                </div>
                                <?php endif; ?>
                                <div class="form__group">
                                    <label for="course_id" class="form__label form__label--required">
                                        Course
                                    </label>
                                    <select name="course_id" id="course_id" class="form__select" required>
                                        <option value="">-- Select Course --</option>
                                        <?php foreach ($courses as $course): ?>
                                            <option value="<?= $course['id'] ?>">[<?= htmlspecialchars($course['code']) ?>] <?= htmlspecialchars($course['title']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="form__help-text">
                                        Choose the course to enroll the student in
                                    </div>
                                </div>
                                <div class="flex flex--gap-4">
                                    <button type="submit" class="btn btn--primary">
                                        <span>✅</span>
                                        <span>Enroll Student</span>
                                    </button>
                                    <a href="?page=admin&section=enrollments" class="btn btn--secondary">
                                        <span>↩️</span>
                                        <span>Cancel</span>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="js/script.js"></script>
</body>
</html> 