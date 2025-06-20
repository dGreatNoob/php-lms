<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile & Settings - LMS</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard">
        <?php
        if (($_SESSION['role'] ?? '') === 'admin') {
            include __DIR__ . '/../sidebar.php';
        } else {
            include __DIR__ . '/../../dashboard/sidebar.php';
        }
        ?>
        <main class="dashboard__main" id="main-content">
            <header class="dashboard__header">
                <div class="container">
                    <h1 class="dashboard__title">Profile & Settings</h1>
                    <p class="dashboard__subtitle">View and update your account information</p>
                </div>
            </header>
            <div class="dashboard__content">
                <div class="container">
                    <div class="card mb-6">
                        <div class="card__header">
                            <h2 class="card__title">User Information</h2>
                        </div>
                        <div class="card__body">
                            <?php if ($user): ?>
                                <div class="grid grid--cols-1 grid--cols-md-2">
                                    <div>
                                        <div class="mb-2"><span class="badge badge--info">Full Name</span> <span class="font-medium"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></span></div>
                                        <div class="mb-2"><span class="badge badge--info">Username</span> <span><?= htmlspecialchars($user['username']) ?></span></div>
                                        <div class="mb-2"><span class="badge badge--info">Email</span> <span><?= htmlspecialchars($user['email']) ?></span></div>
                                        <div class="mb-2"><span class="badge badge--info">Role</span> <span><?= htmlspecialchars($user['role']) ?></span></div>
                                    </div>
                                </div>
                                <?php if (($user['role'] ?? '') === 'student'): ?>
                                <div class="mt-6">
                                    <h3 class="text-lg font-semibold mb-2">Enrolled Courses</h3>
                                    <?php if (!empty($enrolled_courses)): ?>
                                        <ul class="list-disc ml-6">
                                            <?php foreach ($enrolled_courses as $course): ?>
                                                <li><span class="badge badge--primary mr-2"><?= htmlspecialchars($course['code']) ?></span><?= htmlspecialchars($course['title']) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <div class="text-muted">Not enrolled in any courses.</div>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="alert alert--error">User not found.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card__header">
                            <h2 class="card__title">Change Password</h2>
                        </div>
                        <div class="card__body">
                            <?php if (!empty($message)): ?>
                                <div class="alert alert--<?= strpos($message, 'success') !== false ? 'success' : 'error' ?>">
                                    <span class="alert__icon">ℹ️</span>
                                    <div class="alert__content">
                                        <div class="alert__message"><?= htmlspecialchars($message) ?></div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <form method="POST" class="form" autocomplete="off">
                                <div class="form__group">
                                    <label for="current_password" class="form__label">Current Password</label>
                                    <input type="password" id="current_password" name="current_password" class="form__input" required autocomplete="current-password">
                                </div>
                                <div class="form__group">
                                    <label for="new_password" class="form__label">New Password</label>
                                    <input type="password" id="new_password" name="new_password" class="form__input" required autocomplete="new-password">
                                </div>
                                <div class="form__group">
                                    <label for="confirm_password" class="form__label">Confirm New Password</label>
                                    <input type="password" id="confirm_password" name="confirm_password" class="form__input" required autocomplete="new-password">
                                </div>
                                <div class="flex flex--gap-4 mt-4">
                                    <button type="submit" class="btn btn--primary">Change Password</button>
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