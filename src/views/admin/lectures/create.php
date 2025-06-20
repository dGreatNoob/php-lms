<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Lecture - LMS</title>
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
                    <a href="?page=admin&section=courses" class="sidebar__link">
                        <span>📚</span>
                        <span>Courses</span>
                    </a>
                    <a href="?page=admin&section=topics" class="sidebar__link">
                        <span>📋</span>
                        <span>Topics & Subtopics</span>
                    </a>
                    <a href="?page=admin&section=lectures" class="sidebar__link sidebar__link--active">
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
                    <h1 class="dashboard__title">Create Lecture</h1>
                    <p class="dashboard__subtitle">Add new content to your course topics</p>
                </div>
            </header>

            <div class="dashboard__content">
                <div class="container">
                    <div class="card">
                        <div class="card__header">
                            <h2 class="card__title">Lecture Information</h2>
                            <p class="card__subtitle">Fill in the details for your new lecture</p>
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

                            <form method="POST" action="?page=admin&section=lectures&action=create" enctype="multipart/form-data" class="form" data-validate>
                                <div class="form__group">
                                    <label for="topic_id" class="form__label form__label--required">
                                        Topic
                                    </label>
                                    <select name="topic_id" id="topic_id" class="form__select" required>
                                        <option value="">-- Select Topic --</option>
                                        <?php
                                        // Build a tree of topics for hierarchical dropdown
                                        $tree = [];
                                        foreach ($topics as $topic) {
                                            $tree[$topic['course_id']][$topic['parent_topic_id']][] = $topic;
                                        }
                                        function renderTopicOptions($tree, $course_id, $parent_id = null, $level = 0) {
                                            if (!isset($tree[$course_id][$parent_id])) return;
                                            foreach ($tree[$course_id][$parent_id] as $topic) {
                                                $indent = str_repeat('&nbsp;&nbsp;&nbsp;', $level);
                                                echo '<option value="' . $topic['id'] . '">' . $indent . htmlspecialchars($topic['title']) . ' (' . htmlspecialchars($topic['course_title']) . ')</option>';
                                                renderTopicOptions($tree, $course_id, $topic['id'], $level + 1);
                                            }
                                        }
                                        foreach ($tree as $course_id => $byParent) {
                                            renderTopicOptions($tree, $course_id, null, 0);
                                        }
                                        ?>
                                    </select>
                                    <div class="form__help-text">
                                        Select the topic this lecture belongs to
                                    </div>
                                </div>

                                <div class="form__group">
                                    <label for="title" class="form__label form__label--required">
                                        Lecture Title
                                    </label>
                                    <input 
                                        type="text" 
                                        id="title"
                                        name="title" 
                                        class="form__input" 
                                        required
                                        placeholder="e.g., Introduction to Variables, Basic Syntax"
                                        aria-describedby="title-help"
                                    >
                                    <div id="title-help" class="form__help-text">
                                        A clear, descriptive title for the lecture
                                    </div>
                                </div>

                                <div class="form__group">
                                    <label for="content" class="form__label">
                                        Content
                                    </label>
                                    <textarea 
                                        id="content"
                                        name="content" 
                                        class="form__textarea" 
                                        rows="6"
                                        placeholder="Enter the lecture content, instructions, or description..."
                                    ></textarea>
                                    <div class="form__help-text">
                                        The main content or description of the lecture
                                    </div>
                                </div>

                                <div class="form__group">
                                    <label for="file" class="form__label">
                                        Attach File
                                    </label>
                                    <input 
                                        type="file" 
                                        id="file"
                                        name="file" 
                                        class="form__input" 
                                        accept=".pdf,.doc,.docx,.txt,.zip"
                                    >
                                    <div class="form__help-text">
                                        Supported formats: PDF, DOC, DOCX, TXT, ZIP (optional)
                                    </div>
                                </div>

                                <div class="form__group">
                                    <label for="image" class="form__label">
                                        Attach Image
                                    </label>
                                    <input 
                                        type="file" 
                                        id="image"
                                        name="image" 
                                        class="form__input" 
                                        accept="image/*"
                                    >
                                    <div class="form__help-text">
                                        Supported formats: JPG, JPEG, PNG, GIF, WEBP (optional)
                                    </div>
                                </div>

                                <div class="form__group">
                                    <label class="form__label">
                                        <input type="checkbox" name="requires_submission" value="1" class="form__checkbox">
                                        <span class="ml-2">Requires Submission</span>
                                    </label>
                                    <div class="form__help-text">
                                        Check this if students need to submit assignments for this lecture
                                    </div>
                                </div>

                                <div id="submission-options" class="form__group" style="display:none;">
                                    <div class="card card--secondary">
                                        <div class="card__header">
                                            <h3 class="card__title">Submission Settings</h3>
                                        </div>
                                        <div class="card__body">
                                            <div class="form__group">
                                                <label class="form__label form__label--required">Submission Type</label>
                                                <div class="flex flex--gap-4">
                                                    <label class="form__radio">
                                                        <input type="radio" name="submission_type" value="file" checked>
                                                        <span class="form__radio-label">File Upload</span>
                                                    </label>
                                                    <label class="form__radio">
                                                        <input type="radio" name="submission_type" value="text">
                                                        <span class="form__radio-label">Text Response</span>
                                                    </label>
                                                    <label class="form__radio">
                                                        <input type="radio" name="submission_type" value="both">
                                                        <span class="form__radio-label">Both</span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="form__group">
                                                <label for="submission_instructions" class="form__label">
                                                    Submission Instructions
                                                </label>
                                                <textarea 
                                                    id="submission_instructions"
                                                    name="submission_instructions" 
                                                    class="form__textarea" 
                                                    rows="3"
                                                    placeholder="Provide clear instructions for students on what to submit..."
                                                ></textarea>
                                                <div class="form__help-text">
                                                    Instructions that will be shown to students when they submit
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form__group">
                                    <label for="due_date" class="form__label">
                                        Due Date
                                    </label>
                                    <input 
                                        type="datetime-local" 
                                        id="due_date"
                                        name="due_date" 
                                        class="form__input"
                                    >
                                    <div class="form__help-text">
                                        Optional due date for submissions (if applicable)
                                    </div>
                                </div>

                                <div class="flex flex--gap-4">
                                    <button type="submit" class="btn btn--primary">
                                        <span>✅</span>
                                        <span>Create Lecture</span>
                                    </button>
                                    <a href="?page=admin&section=lectures" class="btn btn--secondary">
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
    <script>
    document.querySelector('input[name="requires_submission"]').addEventListener('change', function() {
        const submissionOptions = document.getElementById('submission-options');
        submissionOptions.style.display = this.checked ? 'block' : 'none';
    });
    </script>
</body>
</html> 