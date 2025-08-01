<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Topics - LMS</title>
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
                    <h1 class="dashboard__title">Manage Topics & Subtopics</h1>
                    <p class="dashboard__subtitle">Organize your course content hierarchically</p>
                </div>
            </header>

            <div class="dashboard__content">
                <div class="container">
                    <div class="card">
                        <div class="card__header">
                            <div class="flex flex--between">
                                <div>
                                    <h2 class="card__title">All Topics</h2>
                                    <p class="card__subtitle">Total: <?= count($topics) ?> topics</p>
                                </div>
                                <?php if (!empty($topics)): ?>
                                <a href="?page=admin&section=topics&action=create" class="btn btn--primary">
                                    <span>➕</span>
                                    <span>Add Topic</span>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card__body">
                            <form method="GET" class="mb-4">
                                <input type="hidden" name="page" value="admin">
                                <input type="hidden" name="section" value="topics">
                                <div class="form__group">
                                    <label for="course_id" class="form__label">Filter by Course</label>
                                    <div class="flex flex--gap-2">
                                        <select name="course_id" id="course_id" class="form__select" onchange="this.form.submit()">
                                            <option value="">All Courses</option>
                                            <?php foreach ($courses as $course): ?>
                                                <option value="<?= $course['id'] ?>" <?= (isset($_GET['course_id']) && $_GET['course_id'] == $course['id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($course['title']) ?> (<?= htmlspecialchars($course['code']) ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <a href="?page=admin&section=topics" class="btn btn--secondary">Clear</a>
                                    </div>
                                </div>
                            </form>
                        
                            <?php if (empty($topics)): ?>
                                <div class="text-center p-8">
                                    <div class="text-4xl mb-4">📋</div>
                                    <h3 class="text-lg font-semibold mb-2">No Topics Found</h3>
                                    <p class="text-muted mb-4">Get started by creating your first topic.</p>
                                    <a href="?page=admin&section=topics&action=create" class="btn btn--primary">
                                        Create First Topic
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="table-container">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Course</th>
                                                <th>Description</th>
                                                <th>Level</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Build a tree of topics by course and parent_topic_id
                                            $tree = [];
                                            foreach ($topics as $topic) {
                                                $tree[$topic['course_id']][$topic['parent_topic_id']][] = $topic;
                                            }
                                            
                                            function renderTopicTree($tree, $course_id, $parent_id = null, $level = 0) {
                                                if (!isset($tree[$course_id][$parent_id])) return;
                                                foreach ($tree[$course_id][$parent_id] as $topic) {
                                                    $indent = $level * 20;
                                                    $levelClass = 'topic-level-' . min($level, 4);
                                                    ?>
                                                    <tr class="<?= $levelClass ?>">
                                                        <td>
                                                            <div class="flex flex--items-center">
                                                                <div style="width: <?= $indent ?>px; min-width: <?= $indent ?>px;"></div>
                                                                <span class="font-medium"><?= htmlspecialchars($topic['title']) ?></span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge--primary"><?= htmlspecialchars($topic['course_title']) ?></span>
                                                        </td>
                                                        <td><?= htmlspecialchars($topic['description']) ?></td>
                                                        <td>
                                                            <span class="badge badge--<?= $level === 0 ? 'success' : ($level === 1 ? 'info' : 'secondary') ?>">
                                                                Level <?= $level ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="flex flex--gap-2">
                                                                <a href="?page=admin&section=topics&action=edit&id=<?= $topic['id'] ?>" 
                                                                   class="btn btn--sm btn--secondary"
                                                                   data-tooltip="Edit topic">
                                                                    ✏️ Edit
                                                                </a>
                                                                <button type="button"
                                                                    class="btn btn--sm btn--danger js-delete-trigger"
                                                                    data-delete-url="?page=admin&section=topics&action=delete&id=<?= $topic['id'] ?>"
                                                                    data-entity-name="<?= htmlspecialchars($topic['title']) ?>"
                                                                    data-entity-type="topic"
                                                                    data-tooltip="Delete topic">
                                                                    🗑️ Delete
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    renderTopicTree($tree, $course_id, $topic['id'], $level + 1);
                                                }
                                            }
                                            
                                            // Render topics grouped by course
                                            foreach ($tree as $course_id => $byParent) {
                                                renderTopicTree($tree, $course_id, null, 0);
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
<div class="modal-backdrop" id="delete-modal" style="display: none;">
        <div class="modal">
            <div class="modal__header">
                <h3 class="modal__title">Confirm Deletion</h3>
                <button class="modal__close" data-modal-close="#delete-modal">&times;</button>
            </div>
            <div class="modal__body">
                <div class="alert alert--error">
                    <span class="alert__icon">⚠️</span>
                    <div class="alert__content">
                        <div class="alert__title">Warning!</div>
                        <div class="alert__message" id="delete-modal-warning-message">
                            This is a generic warning.
                        </div>
                    </div>
                </div>
                <p class="mt-4">Are you sure you want to proceed? This action cannot be undone.</p>
            </div>
            <div class="modal__footer">
                <button class="btn btn--secondary" data-modal-close="#delete-modal">Cancel</button>
                <a href="#" id="confirm-delete-btn" class="btn btn--danger">Confirm Delete</a>
            </div>
        </div>
    </div>
<!-- Confirmation Modal -->
<div class="modal-backdrop" id="archive-modal" style="display: none;">
    <div class="modal">
        <div class="modal__header">
            <h3 class="modal__title">Confirm Archive</h3>
            <button class="modal__close" data-modal-close="#archive-modal">&times;</button>
        </div>
        <div class="modal__body">
            <div class="alert alert--warning">
                <span class="alert__icon">⚠️</span>
                <div class="alert__content">
                    <div class="alert__title">Archive Warning!</div>
                    <div class="alert__message" id="archive-modal-warning-message">
                        This is a generic warning.
                    </div>
                </div>
            </div>
            <p class="mt-4">Are you sure you want to archive this topic? You can restore it later from the Archive/Restore section.</p>
        </div>
        <div class="modal__footer">
            <button class="btn btn--secondary" data-modal-close="#archive-modal">Cancel</button>
            <a href="#" id="confirm-archive-btn" class="btn btn--danger">Confirm Archive</a>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.js-delete-trigger').forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            const archiveUrl = trigger.dataset.deleteUrl;
            const entityName = trigger.dataset.entityName;
            let warning = `You are about to archive the topic: <strong>${entityName}</strong>. This will also archive all its sub-topics and lectures.`;
            document.getElementById('archive-modal-warning-message').innerHTML = warning;
            document.getElementById('confirm-archive-btn').href = archiveUrl;
            document.getElementById('archive-modal').style.display = 'flex';
        });
    });
    document.querySelectorAll('[data-modal-close]').forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            const modal = document.querySelector(trigger.dataset.modalClose);
            if(modal) {
                modal.style.display = 'none';
            }
        });
    });
    document.getElementById('archive-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.style.display = 'none';
        }
    });
});
</script> 