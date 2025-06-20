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
        <?php include __DIR__ . '/../sidebar.php'; ?>

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
                                <?php if (!empty($enrollments)): ?>
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
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card__body">
                            <div class="tabs flex flex--gap-2 mb-4">
                                <button class="tab-btn btn btn--secondary active" data-tab="enrolled" type="button">Enrolled Students</button>
                                <button class="tab-btn btn btn--secondary" data-tab="unenrolled" type="button">Unenrolled Students</button>
                            </div>
                            <div class="tab-content tab-content--enrolled" style="display: block;">
                                <?php
                                // Group enrollments by course code
                                $grouped = [];
                                foreach ($enrollments as $enroll) {
                                    $grouped[$enroll['course_code']]['course_title'] = $enroll['course_title'];
                                    $grouped[$enroll['course_code']]['students'][] = $enroll;
                                }
                                ?>
                                <?php if (empty($enrollments)): ?>
                                    <div class="text-center p-8">
                                        <div class="text-4xl mb-4">👥</div>
                                        <h3 class="text-lg font-semibold mb-2">No Enrollments Found</h3>
                                        <p class="text-muted mb-4">Get started by enrolling your first student.</p>
                                        <div class="flex flex--gap-2 flex--center">
                                            <a href="?page=admin&section=enrollments&action=import" class="btn btn--secondary">
                                                Import from CSV
                                            </a>
                                            <a href="?page=admin&section=enrollments&action=create" class="btn btn--primary">
                                                Enroll Student
                                            </a>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="accordion">
                                        <?php foreach ($grouped as $code => $data): ?>
                                        <div class="accordion__item">
                                            <div class="accordion__header flex flex--between" tabindex="0">
                                                <div>
                                                    <span class="badge badge--primary mr-4"><?= htmlspecialchars($code) ?></span>
                                                    <span class="font-semibold"><?= htmlspecialchars($data['course_title']) ?></span>
                                                </div>
                                                <span class="accordion__icon">▼</span>
                                            </div>
                                            <div class="accordion__content" style="display: none;">
                                                <div class="table-container">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Student Name</th>
                                                                <th>Username</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($data['students'] as $enroll): ?>
                                                            <tr>
                                                                <td><div class="font-medium"><?= htmlspecialchars($enroll['first_name'] . ' ' . $enroll['last_name']) ?></div></td>
                                                                <td><span class="badge badge--info"><?= htmlspecialchars($enroll['username']) ?></span></td>
                                                                <td>
                                                                    <div class="flex flex--gap-2">
                                                                        <button type="button" 
                                                                           class="btn btn--sm btn--danger js-delete-trigger"
                                                                           data-delete-url="?page=admin&section=enrollments&action=delete&id=<?= $enroll['id'] ?>"
                                                                           data-entity-name="this enrollment"
                                                                           data-entity-type="enrollment"
                                                                           data-tooltip="Remove enrollment">
                                                                            <span class="sr-only">Remove</span>🗑️
                                                                        </button>
                                                                    </div>
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
                                <?php endif; ?>
                            </div>
                            <div class="tab-content tab-content--unenrolled" style="display: none;">
                                <?php if (empty($unenrolled_students)): ?>
                                    <div class="text-center p-8">
                                        <div class="text-4xl mb-4">🙅‍♂️</div>
                                        <h3 class="text-lg font-semibold mb-2">No Unenrolled Students</h3>
                                        <p class="text-muted mb-4">All students are currently enrolled in at least one course.</p>
                                    </div>
                                <?php else: ?>
                                    <div class="table-container">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Student Name</th>
                                                    <th>Username</th>
                                                    <th>Email</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($unenrolled_students as $student): ?>
                                                <tr>
                                                    <td><div class="font-medium"><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></div></td>
                                                    <td><span class="badge badge--info"><?= htmlspecialchars($student['username']) ?></span></td>
                                                    <td><?= htmlspecialchars($student['email']) ?></td>
                                                    <td>
                                                        <a href="?page=admin&section=enrollments&action=create&student_id=<?= $student['id'] ?>" class="btn btn--sm btn--primary" data-tooltip="Quick Enroll">
                                                            <span class="sr-only">Quick Enroll</span>➕
                                                        </a>
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
            </div>
        </main>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal-backdrop" id="delete-modal" style="display: none;">
        <div class="modal">
            <div class="modal__header">
                <h3 class="modal__title">Confirm Unenrollment</h3>
                <button class="modal__close" data-modal-close="#delete-modal">&times;</button>
            </div>
            <div class="modal__body">
                <div class="alert alert--error">
                    <span class="alert__icon">⚠️</span>
                    <div class="alert__content">
                        <div class="alert__title">Unenroll this student?</div>
                        <div class="alert__message" id="delete-modal-warning-message">Are you sure you want to unenroll this student from the course? This action cannot be undone.</div>
                    </div>
                </div>
            </div>
            <div class="modal__footer">
                <button class="btn btn--secondary" data-modal-close="#delete-modal">Cancel</button>
                <a href="#" id="confirm-delete-btn" class="btn btn--danger">Unenroll</a>
            </div>
        </div>
    </div>

    <script src="js/script.js"></script>
    <script>
    // Tab switching logic
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            tabBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            tabContents.forEach(tc => tc.style.display = 'none');
            document.querySelector('.tab-content--' + btn.dataset.tab).style.display = 'block';
        });
    });
    // Accordion logic
    const accHeaders = document.querySelectorAll('.accordion__header');
    accHeaders.forEach(header => {
        header.addEventListener('click', () => {
            const item = header.parentElement;
            const content = item.querySelector('.accordion__content');
            const icon = header.querySelector('.accordion__icon');
            if (content.style.display === 'block') {
                content.style.display = 'none';
                icon.textContent = '▼';
            } else {
                content.style.display = 'block';
                icon.textContent = '▲';
            }
        });
    });
    // Modal logic for unenroll
    const deleteTriggers = document.querySelectorAll('.js-delete-trigger');
    const deleteModal = document.getElementById('delete-modal');
    const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
    deleteTriggers.forEach(btn => {
        btn.addEventListener('click', function() {
            const url = btn.getAttribute('data-delete-url');
            deleteModal.style.display = 'flex';
            confirmDeleteBtn.setAttribute('href', url);
        });
    });
    document.querySelectorAll('[data-modal-close="#delete-modal"]').forEach(btn => {
        btn.addEventListener('click', function() {
            deleteModal.style.display = 'none';
        });
    });
    </script>
</body>
</html> 