<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LMS</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="auth-layout">
        <div class="auth-card">
            <button class="auth-card__theme-toggle" data-theme-toggle aria-label="Toggle dark mode">
                🌙
            </button>
            
            <div class="auth-card__header">
                <h1 class="auth-card__title">Welcome Back</h1>
                <p class="auth-card__subtitle">Sign in to your account to continue</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert--error" role="alert">
                    <span class="alert__icon">⚠️</span>
                    <div class="alert__content">
                        <div class="alert__message"><?= htmlspecialchars($error) ?></div>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" class="form">
                <div class="form__group">
                    <label for="username" class="form__label form__label--required">
                        Username
                    </label>
                    <input 
                        type="text" 
                        id="username"
                        name="username" 
                        class="form__input" 
                        required 
                        autocomplete="username" 
                        autofocus
                        placeholder="Enter your username"
                        aria-describedby="username-help"
                    >
                    <div id="username-help" class="form__help-text">
                        Enter the username you registered with
                    </div>
                </div>

                <div class="form__group">
                    <label for="password" class="form__label form__label--required">
                        Password
                    </label>
                    <div class="form__password-group">
                        <input 
                            type="password" 
                            id="password"
                            name="password" 
                            class="form__input" 
                            required 
                            autocomplete="current-password"
                            placeholder="Enter your password"
                            aria-describedby="password-help"
                        >
                        <button 
                            type="button" 
                            class="form__password-toggle" 
                            data-password-toggle
                            aria-label="Show password"
                        >
                            👁️
                        </button>
                    </div>
                    <div id="password-help" class="form__help-text">
                        Enter your password
                    </div>
                </div>

                <button type="submit" class="btn btn--primary btn--lg w-full">
                    Sign In
                </button>
            </form>

            <div class="text-center mt-6">
                <p class="text-muted">
                    Don't have an account? 
                    <a href="?page=register" class="font-medium">Create one here</a>
                </p>
            </div>
        </div>
    </div>

    <script src="../public/js/theme.js"></script>
</body>
</html> 