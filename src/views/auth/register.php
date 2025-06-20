<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - LMS</title>
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
                <h1 class="auth-card__title">Create Account</h1>
                <p class="auth-card__subtitle">Join our learning community</p>
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
                <div class="grid grid--cols-2 grid--cols-1">
                    <div class="form__group">
                        <label for="first_name" class="form__label form__label--required">
                            First Name
                        </label>
                        <input 
                            type="text" 
                            id="first_name"
                            name="first_name" 
                            class="form__input" 
                            required 
                            autocomplete="given-name"
                            placeholder="Enter your first name"
                        >
                    </div>

                    <div class="form__group">
                        <label for="last_name" class="form__label form__label--required">
                            Last Name
                        </label>
                        <input 
                            type="text" 
                            id="last_name"
                            name="last_name" 
                            class="form__input" 
                            required 
                            autocomplete="family-name"
                            placeholder="Enter your last name"
                        >
                    </div>
                </div>

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
                        placeholder="Choose a username"
                        aria-describedby="username-help"
                    >
                    <div id="username-help" class="form__help-text">
                        This will be your login username
                    </div>
                </div>

                <div class="form__group">
                    <label for="email" class="form__label form__label--required">
                        Email Address
                    </label>
                    <input 
                        type="email" 
                        id="email"
                        name="email" 
                        class="form__input" 
                        required 
                        autocomplete="email"
                        placeholder="Enter your email address"
                        aria-describedby="email-help"
                    >
                    <div id="email-help" class="form__help-text">
                        We'll use this for important notifications
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
                            autocomplete="new-password"
                            placeholder="Create a strong password"
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
                        Use at least 8 characters with letters, numbers, and symbols
                    </div>
                </div>

                <div class="form__group">
                    <label for="role" class="form__label form__label--required">
                        Account Type
                    </label>
                    <select 
                        id="role"
                        name="role" 
                        class="form__select" 
                        required
                        aria-describedby="role-help"
                    >
                        <option value="">Select account type</option>
                        <option value="student">Student</option>
                        <option value="admin">Administrator</option>
                    </select>
                    <div id="role-help" class="form__help-text">
                        Choose your role in the learning system
                    </div>
                </div>

                <button type="submit" class="btn btn--primary btn--lg w-full">
                    Create Account
                </button>
            </form>

            <div class="text-center mt-6">
                <p class="text-muted">
                    Already have an account? 
                    <a href="?page=login" class="font-medium">Sign in here</a>
                </p>
            </div>
        </div>
    </div>

    <script src="../public/js/theme.js"></script>
</body>
</html> 