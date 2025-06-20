<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Lectures - LMS</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .table-container {
            overflow-x: auto;
        }
        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .table th, .table td {
            padding: 0.75em 0.5em;
            text-align: left;
            vertical-align: top;
            white-space: normal;
            word-break: break-word;
        }
        .table th {
            background: #f8f9fa;
            font-weight: 600;
        }
        .table tbody tr {
            border-bottom: 1px solid #e5e7eb;
        }
        .table .actions {
            display: flex;
            gap: 0.5em;
            justify-content: flex-start;
            align-items: center;
        }
        .table .actions .btn {
            padding: 0.35em 0.5em;
            font-size: 1.1em;
            border-radius: 4px;
            min-width: 32px;
            min-height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .table .actions .btn[data-tooltip]:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            background: #222;
            color: #fff;
            padding: 0.25em 0.5em;
            border-radius: 4px;
            font-size: 0.95em;
            white-space: nowrap;
            left: 50%;
            top: 110%;
            transform: translateX(-50%);
            z-index: 10;
            pointer-events: none;
        }
        .table td.title-col {
            font-weight: 500;
            color: #222;
        }
        @media (max-width: 900px) {
            .table th, .table td {
                font-size: 0.98em;
                padding: 0.5em 0.3em;
            }
            .table .actions .btn {
                min-width: 28px;
                min-height: 28px;
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <?php include __DIR__ . '/../sidebar.php'; ?>

        <main class="dashboard__main" id="main-content">
            <header class="dashboard__header">
                <div class="container">
                    <h1 class="dashboard__title">Manage Lectures</h1>
                    <p class="dashboard__subtitle">Create and organize your course content</p>
                </div>
            </header>

            <div class="dashboard__content">
                <div class="container">
                    <div class="card">
                        <div class="card__header">
                            <div class="flex flex--between">
                                <div>
                                    <h2 class="card__title">All Lectures</h2>
                                    <p class="card__subtitle">Total: <?= count($lectures) ?> lectures</p>
                                </div>
                                <?php if (!empty($lectures)): ?>
                                    <a href="?page=admin&section=lectures&action=create" class="btn btn--primary">
                                        <span>➕</span>
                                        <span>Add Lecture</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card__body">
                            <form method="GET" class="mb-4" id="filter-form">
                                <input type="hidden" name="page" value="admin">
                                <input type="hidden" name="section" value="lectures">
                                <div class="grid grid--cols-3 grid--gap-4">
                                    <div class="form__group">
                                        <label for="course_id" class="form__label">Filter by Course</label>
                                        <select name="course_id" id="course_id" class="form__select">
                                            <option value="">All Courses</option>
                                            <?php foreach ($courses as $course): ?>
                                                <option value="<?= $course['id'] ?>" <?= (isset($_GET['course_id']) && $_GET['course_id'] == $course['id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($course['title']) ?> (<?= htmlspecialchars($course['code']) ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form__group">
                                        <label for="topic_id" class="form__label">Filter by Topic</label>
                                        <select name="topic_id" id="topic_id" class="form__select">
                                            <option value="">All Topics</option>
                                            <?php foreach ($topics as $topic): ?>
                                                <option value="<?= $topic['id'] ?>" data-course-id="<?= $topic['course_id'] ?>" <?= (isset($_GET['topic_id']) && $_GET['topic_id'] == $topic['id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($topic['title']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form__group">
                                        <label for="submit" class="form__label">&nbsp;</label>
                                        <div class="flex flex--gap-2">
                                            <button type="submit" class="btn btn--primary">Filter</button>
                                            <a href="?page=admin&section=lectures" class="btn btn--secondary">Clear</a>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <?php if (empty($lectures)): ?>
                                <div class="text-center p-8">
                                    <div class="text-4xl mb-4">🎬</div>
                                    <h3 class="font-bold text-lg">No Lectures Found</h3>
                                    <?php if (empty($courses) && empty($topics)): ?>
                                        <p class="text-gray-500">Create a <a href="?page=admin&section=courses&view=create" class="text-primary-500">course</a> and a <a href="?page=admin&section=topics&view=create" class="text-primary-500">topic</a> first.</p>
                                    <?php else: ?>
                                        <a href="?page=admin&section=lectures&action=create" class="btn btn--primary mt-4">Add Lecture</a>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="table-container">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="title-col">Title</th>
                                                <th>Topic</th>
                                                <th>Course</th>
                                                <th>Content</th>
                                                <th>Attachments</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($lectures as $lecture): ?>
                                            <tr>
                                                <td class="title-col">
                                                    <?= htmlspecialchars($lecture['title']) ?>
                                                </td>
                                                <td>
                                                    <span class="badge badge--info"><?= htmlspecialchars($lecture['topic_title']) ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge badge--primary"><?= htmlspecialchars($lecture['course_title']) ?></span>
                                                </td>
                                                <td>
                                                    <div class="text-sm text-muted">
                                                        <?= strlen($lecture['content']) > 50 ? htmlspecialchars(substr($lecture['content'], 0, 50)) . '...' : htmlspecialchars($lecture['content']) ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="flex flex--gap-1">
                                                        <?php if (!empty($lecture['attachment'])): ?>
                                                            <a href="uploads/<?= htmlspecialchars(basename($lecture['attachment'])) ?>" 
                                                               download 
                                                               class="btn btn--xs btn--secondary"
                                                               data-tooltip="Download attachment">
                                                                📎
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="text-muted text-sm">None</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="actions">
                                                        <a href="?page=admin&section=lectures&action=edit&id=<?= $lecture['id'] ?>" 
                                                           class="btn btn--sm btn--secondary"
                                                           data-tooltip="Edit">
                                                            ✏️
                                                        </a>
                                                        <button type="button"
                                                            class="btn btn--sm btn--danger js-delete-trigger"
                                                            data-delete-url="?page=admin&section=lectures&action=delete&id=<?= $lecture['id'] ?>"
                                                            data-entity-name="<?= htmlspecialchars($lecture['title']) ?>"
                                                            data-entity-type="lecture"
                                                            data-tooltip="Archive">
                                                            🗑️
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
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
                <p class="mt-4">Are you sure you want to archive this lecture? You can restore it later from the Archive/Restore section.</p>
            </div>
            <div class="modal__footer">
                <button class="btn btn--secondary" data-modal-close="#archive-modal">Cancel</button>
                <a href="#" id="confirm-archive-btn" class="btn btn--danger">Confirm Archive</a>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../../partials/footer.php'; ?>
</body>
</html>

<script>
    const allTopics = <?= json_encode($topics) ?>;
</script>
<script src="js/script.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.js-delete-trigger').forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            const archiveUrl = trigger.dataset.deleteUrl;
            const entityName = trigger.dataset.entityName;
            let warning = `You are about to archive the lecture: <strong>${entityName}</strong>. This will also delete all student submissions for this lecture.`;
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