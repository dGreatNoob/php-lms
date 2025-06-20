<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Topic - LMS</title>
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
                    <h1 class="dashboard__title">Create Topic</h1>
                    <p class="dashboard__subtitle">Add a new topic or subtopic to organize your course content</p>
                </div>
            </header>

            <div class="dashboard__content">
                <div class="container">
                    <div class="card">
                        <div class="card__header">
                            <h2 class="card__title">Topic Information</h2>
                            <p class="card__subtitle">Fill in the details for your new topic</p>
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

                            <form method="POST" action="?page=admin&section=topics&action=create" class="form" data-validate>
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
                                        Select the course this topic belongs to
                                    </div>
                                </div>

                                <div class="form__group">
                                    <label for="parent_topic_id" class="form__label">
                                        Parent Topic (Optional)
                                    </label>
                                    <select name="parent_topic_id" id="parent_topic_id" class="form__select">
                                        <option value="">-- None (Top-level topic) --</option>
                                        <?php foreach ($all_topics as $topic): ?>
                                            <option value="<?= $topic['id'] ?>" data-course="<?= $topic['course_id'] ?>"><?= htmlspecialchars($topic['title']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="form__help-text">
                                        Leave empty to create a top-level topic, or select a parent to create a subtopic
                                    </div>
                                </div>

                                <div class="form__group">
                                    <label for="title" class="form__label form__label--required">
                                        Topic Title
                                    </label>
                                    <input 
                                        type="text" 
                                        id="title"
                                        name="title" 
                                        class="form__input" 
                                        required
                                        placeholder="e.g., Introduction to Programming, Variables and Data Types"
                                        aria-describedby="title-help"
                                    >
                                    <div id="title-help" class="form__help-text">
                                        A clear, descriptive title for the topic
                                    </div>
                                </div>

                                <div class="form__group">
                                    <label for="description" class="form__label">
                                        Description
                                    </label>
                                    <textarea 
                                        id="description"
                                        name="description" 
                                        class="form__textarea" 
                                        rows="4"
                                        placeholder="Provide a brief description of what this topic covers..."
                                    ></textarea>
                                    <div class="form__help-text">
                                        Optional description to help organize and understand the topic content
                                    </div>
                                </div>

                                <div class="flex flex--gap-4">
                                    <button type="submit" class="btn btn--primary">
                                        <span>✅</span>
                                        <span>Create Topic</span>
                                    </button>
                                    <a href="?page=admin&section=topics" class="btn btn--secondary">
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
    // Filter parent topics by selected course
    const courseSelect = document.getElementById('course_id');
    const parentSelect = document.getElementById('parent_topic_id');
    
    function filterParentTopics() {
        const courseId = courseSelect.value;
        for (const opt of parentSelect.options) {
            if (!opt.value) continue;
            opt.style.display = (opt.getAttribute('data-course') === courseId) ? '' : 'none';
        }
        parentSelect.value = '';
    }
    
    courseSelect.addEventListener('change', filterParentTopics);
    window.addEventListener('DOMContentLoaded', filterParentTopics);
    </script>
</body>
</html>
 