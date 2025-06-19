<!DOCTYPE html>
<html lang="en" class="theme-light">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <div class="center-viewport">
        <div class="form-container">
            <div class="form-header">
                <h2>Register</h2>
                <button id="theme-toggle" aria-label="Toggle dark mode">🌓</button>
            </div>
            <?php if (!empty($error)): ?>
                <div class="alert alert-error" role="alert">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            <form method="POST">
                <label>First Name
                    <input type="text" name="first_name" required autocomplete="given-name">
                </label>
                <label>Last Name
                    <input type="text" name="last_name" required autocomplete="family-name">
                </label>
                <label>Username
                    <input type="text" name="username" required autocomplete="username">
                </label>
                <label>Email
                    <input type="email" name="email" required autocomplete="email">
                </label>
                <label>Password
                    <input type="password" name="password" required autocomplete="new-password">
                </label>
                <label>Role
                    <select name="role" required>
                        <option value="student">Student</option>
                        <option value="admin">Admin</option>
                    </select>
                </label>
                <button type="submit">Register</button>
            </form>
            <p class="form-footer">Already registered? <a href="?page=login">Login here</a></p>
        </div>
    </div>
    <script src="../public/js/theme.js"></script>
</body>
</html> 