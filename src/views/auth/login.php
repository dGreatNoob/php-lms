<!DOCTYPE html>
<html lang="en" class="theme-light">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <div class="center-viewport">
        <div class="form-container">
            <div class="form-header">
                <h2>Login</h2>
                <button id="theme-toggle" aria-label="Toggle dark mode">🌓</button>
            </div>
            <?php if (!empty($error)): ?>
                <div class="alert alert-error" role="alert">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            <form method="POST">
                <label>Username
                    <input type="text" name="username" required autocomplete="username" autofocus>
                </label>
                <label>Password
                    <input type="password" name="password" required autocomplete="current-password">
                </label>
                <button type="submit">Login</button>
            </form>
            <p class="form-footer">New user? <a href="?page=register">Register here</a></p>
        </div>
    </div>
    <script src="../public/js/theme.js"></script>
</body>
</html> 