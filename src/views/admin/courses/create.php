<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Course - LMS</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="sidebar__header">
                <h2 class="sidebar__title">LMS Admin</h2>
            </div>
            
            <nav class="sidebar__nav">
                <div class="sidebar__section">
                    <h3 class="sidebar__section-title">Navigation</h3>
                    <a href="?page=admin" class="sidebar__link">
                        <span>🏠</span>
                        <span>Dashboard</span>
                    </a>
                </div>
                
                <div class="sidebar__section">
                    <h3 class="sidebar__section-title">Management</h3>
                    <a href="?page=admin&section=courses" class="sidebar__link sidebar__link--active">
                        <span>📚</span>
                        <span>Courses</span>
                    </a>
                    <a href="?page=admin&section=topics" class="sidebar__link">
                        <span>📋</span>
                        <span>Topics & Subtopics</span>
                    </a>
                    <a href="?page=admin&section=lectures" class="sidebar__link">
                        <span>🎓</span>
                        <span>Lectures</span>
                    </a>
                    <a href="?page=admin&section=enrollments" class="sidebar__link">
                        <span>👥</span>
                        <span>Enrollments</span>
                    </a>
                </div>
                
                <div class="sidebar__section">
                    <h3 class="sidebar__section-title">System</h3>
                    <a href="?page=admin&section=archive" class="sidebar__link">
                        <span>🗄️</span>
                        <span>Archive/Restore</span>
                    </a>
                    <a href="?page=logout" class="sidebar__link sidebar__link--logout">
                        <span>🚪</span>
                        <span>Logout</span>
                    </a>
                </div>
                
                <div class="sidebar__section">
                    <button class="btn btn--icon btn--secondary" data-theme-toggle aria-label="Toggle dark mode">
                        🌙
                    </button>
                </div>
            </nav>
        </aside>

        <main class="dashboard__main" id="main-content">
            <header class="dashboard__header">
                <div class="container">
                    <h1 class="dashboard__title">Create Course</h1>
                    <p class="dashboard__subtitle">Add a new course to your learning system</p>
                </div>
            </header>

            <div class="dashboard__content">
                <div class="container">
                    <div class="card">
                        <div class="card__header">
                            <h2 class="card__title">Course Information</h2>
                            <p class="card__subtitle">Fill in the details for your new course</p>
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

                            <form method="POST" action="?page=admin&section=courses&action=create" class="form" data-validate>
                                <div class="form__group">
                                    <label for="code" class="form__label form__label--required">
                                        Course Code
                                    </label>
                                    <input 
                                        type="text" 
                                        id="code"
                                        name="code" 
                                        class="form__input" 
                                        required
                                        placeholder="e.g., CS101, MATH201"
                                        aria-describedby="code-help"
                                    >
                                    <div id="code-help" class="form__help-text">
                                        A unique identifier for the course (e.g., CS101, MATH201)
                                    </div>
                                </div>

                                <div class="form__group">
                                    <label for="title" class="form__label form__label--required">
                                        Course Title
                                    </label>
                                    <input 
                                        type="text" 
                                        id="title"
                                        name="title" 
                                        class="form__input" 
                                        required
                                        placeholder="e.g., Introduction to Computer Science"
                                        aria-describedby="title-help"
                                    >
                                    <div id="title-help" class="form__help-text">
                                        The full name of the course
                                    </div>
                                </div>

                                <div class="form__group">
                                    <label for="semester" class="form__label form__label--required">
                                        Semester
                                    </label>
                                    <input 
                                        type="text" 
                                        id="semester"
                                        name="semester" 
                                        class="form__input" 
                                        required
                                        placeholder="e.g., Fall 2024, Spring 2025"
                                        aria-describedby="semester-help"
                                    >
                                    <div id="semester-help" class="form__help-text">
                                        The semester when this course is offered
                                    </div>
                                </div>

                                <div class="flex flex--gap-4">
                                    <button type="submit" class="btn btn--primary">
                                        <span>✅</span>
                                        <span>Create Course</span>
                                    </button>
                                    <a href="?page=admin&section=courses" class="btn btn--secondary">
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