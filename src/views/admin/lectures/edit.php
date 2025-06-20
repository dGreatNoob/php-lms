<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lecture - LMS</title>
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
                    <h1 class="dashboard__title">Edit Lecture</h1>
                    <p class="dashboard__subtitle">Update lecture information and content</p>
                </div>
            </header>

            <div class="dashboard__content">
                <div class="container">
                    <div class="card">
                        <div class="card__header">
                            <h2 class="card__title">Edit Lecture: <?= htmlspecialchars($lecture['title']) ?></h2>
                            <p class="card__subtitle">Update the lecture details below</p>
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

                            <form method="POST" enctype="multipart/form-data" class="form" data-validate>
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
                                        function renderTopicOptionsEdit($tree, $course_id, $parent_id = null, $level = 0, $selected_id = null) {
                                            if (!isset($tree[$course_id][$parent_id])) return;
                                            foreach ($tree[$course_id][$parent_id] as $topic) {
                                                $indent = str_repeat('&nbsp;&nbsp;&nbsp;', $level);
                                                $selected = $selected_id == $topic['id'] ? 'selected' : '';
                                                echo '<option value="' . $topic['id'] . '" ' . $selected . '>' . $indent . htmlspecialchars($topic['title']) . ' (' . htmlspecialchars($topic['course_title']) . ')</option>';
                                                renderTopicOptionsEdit($tree, $course_id, $topic['id'], $level + 1, $selected_id);
                                            }
                                        }
                                        foreach ($tree as $course_id => $byParent) {
                                            renderTopicOptionsEdit($tree, $course_id, null, 0, $lecture['topic_id']);
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
                                        value="<?= htmlspecialchars($lecture['title']) ?>" 
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
                                    ><?= htmlspecialchars($lecture['content']) ?></textarea>
                                    <div class="form__help-text">
                                        The main content or description of the lecture
                                    </div>
                                </div>

                                <?php if (!empty($lecture['attachment'])): ?>
                                    <div class="form__group">
                                        <label class="form__label">Current File</label>
                                        <div class="flex flex--items-center flex--gap-2">
                                            <a href="uploads/<?= htmlspecialchars(basename($lecture['attachment'])) ?>" 
                                               download 
                                               class="btn btn--sm btn--secondary">
                                                📎 <?= htmlspecialchars(basename($lecture['attachment'])) ?>
                                            </a>
                                            <label class="form__checkbox">
                                                <input type="checkbox" name="delete_attachment" value="1">
                                                <span class="ml-2">Delete this file</span>
                                            </label>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="form__group">
                                    <label for="attachment" class="form__label">
                                        Attach File
                                    </label>
                                    <input 
                                        type="file" 
                                        id="attachment"
                                        name="attachment" 
                                        class="form__input" 
                                        accept=".pdf,.doc,.docx,.txt,.zip"
                                    >
                                    <div class="form__help-text">
                                        Supported formats: PDF, DOC, DOCX, TXT, ZIP (optional)
                                    </div>
                                </div>

                                <div class="form__group">
                                    <label class="form__label">
                                        <input type="checkbox" name="allow_submissions" value="1" class="form__checkbox" <?= $lecture['allow_submissions'] ? 'checked' : '' ?>>
                                        <span class="ml-2">Allow Submissions</span>
                                    </label>
                                    <div class="form__help-text">
                                        Check this if students need to submit assignments for this lecture
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
                                        value="<?= $lecture['due_date'] ? date('Y-m-d\TH:i', strtotime($lecture['due_date'])) : '' ?>"
                                    >
                                    <div class="form__help-text">
                                        Optional due date for submissions (if applicable)
                                    </div>
                                </div>

                                <div class="flex flex--gap-4">
                                    <button type="submit" class="btn btn--primary">
                                        <span>💾</span>
                                        <span>Update Lecture</span>
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