<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Enrollments - LMS</title>
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
                    <h1 class="dashboard__title">Import Enrollments</h1>
                    <p class="dashboard__subtitle">Bulk import student enrollments from CSV file</p>
                </div>
            </header>

            <div class="dashboard__content">
                <div class="container">
                    <div class="card">
                        <div class="card__header">
                            <h2 class="card__title">CSV Import</h2>
                            <p class="card__subtitle">Upload a CSV file to import multiple enrollments</p>
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
                                    <label for="csv" class="form__label form__label--required">
                                        CSV File
                                    </label>
                                    <input 
                                        type="file" 
                                        id="csv"
                                        name="csv" 
                                        class="form__input" 
                                        accept=".csv" 
                                        required
                                    >
                                    <div class="form__help-text">
                                        Select a CSV file with enrollment data
                                    </div>
                                </div>

                                <div class="flex flex--gap-4">
                                    <button type="submit" class="btn btn--primary">
                                        <span>📤</span>
                                        <span>Import Enrollments</span>
                                    </button>
                                    <a href="?page=admin&section=enrollments" class="btn btn--secondary">
                                        <span>↩️</span>
                                        <span>Cancel</span>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card__header">
                            <h2 class="card__title">CSV Format Requirements</h2>
                        </div>
                        <div class="card__body">
                            <div class="alert alert--info" role="alert">
                                <span class="alert__icon">ℹ️</span>
                                <div class="alert__content">
                                    <div class="alert__message">
                                        <strong>Required columns:</strong> <code>first_name,last_name,username,email,password,course_code</code>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form__group">
                                <label class="form__label">Example CSV Row:</label>
                                <div class="bg-gray-100 p-3 rounded font-mono text-sm">
                                    <code>John,Doe,johndoe,john@example.com,secret,MPE101</code>
                                </div>
                                <div class="form__help-text">
                                    Each row should contain student information and the course code they should be enrolled in
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($results)): ?>
                        <div class="card">
                            <div class="card__header">
                                <h2 class="card__title">Import Results</h2>
                                <p class="card__subtitle">Summary of the import process</p>
                            </div>
                            <div class="card__body">
                                <div class="table-container">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Row Data</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($results as $res): ?>
                                            <tr class="<?= $res['success'] ? 'table__row--success' : 'table__row--error' ?>">
                                                <td>
                                                    <code><?= htmlspecialchars(implode(', ', $res['row'])) ?></code>
                                                </td>
                                                <td>
                                                    <span class="badge badge--<?= $res['success'] ? 'success' : 'error' ?>">
                                                        <?= htmlspecialchars($res['status']) ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <script src="js/script.js"></script>
</body>
</html> 