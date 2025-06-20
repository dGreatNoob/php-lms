<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course - LMS</title>
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
                    <h1 class="dashboard__title">Edit Course</h1>
                    <p class="dashboard__subtitle">Update course information</p>
                </div>
            </header>

            <div class="dashboard__content">
                <div class="container">
                    <div class="card">
                        <div class="card__header">
                            <h2 class="card__title">Edit Course: <?= htmlspecialchars($course['title']) ?></h2>
                            <p class="card__subtitle">Update the course details below</p>
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

                            <form method="POST" class="form" data-validate>
                                <div class="form__group">
                                    <label for="code" class="form__label form__label--required">
                                        Course Code
                                    </label>
                                    <input 
                                        type="text" 
                                        id="code"
                                        name="code" 
                                        class="form__input" 
                                        value="<?= htmlspecialchars($course['code']) ?>" 
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
                                        value="<?= htmlspecialchars($course['title']) ?>" 
                                        required
                                        placeholder="e.g., Introduction to Computer Science"
                                        aria-describedby="title-help"
                                    >
                                    <div id="title-help" class="form__help-text">
                                        The full name of the course
                                    </div>
                                </div>

                                <div class="form__group">
                                    <label for="semester" class="form__label form__label--required">Semester</label>
                                    <?php
                                        $semester_val = htmlspecialchars($course['semester']);
                                        $period = '1st Semester';
                                        $year = '';
                                        if (strpos($semester_val, '–') !== false) {
                                            $parts = explode(' ', $semester_val, 2);
                                            $period_text = $parts[0] . ' ' . explode(' ', $parts[1])[0];
                                            if (in_array($period_text, ['1st Semester', '2nd Semester', 'Summer Class'])) {
                                                $period = $period_text;
                                            }
                                            preg_match('/(\d{4}–\d{4})/', $semester_val, $matches);
                                            if(isset($matches[0])) {
                                                $year = $matches[0];
                                            }
                                        }

                                        $current_year_for_range = date('Y');
                                        $year_options = [];
                                        for ($i = -2; $i <= 3; $i++) {
                                            $start_year = $current_year_for_range + $i;
                                            $end_year = $start_year + 1;
                                            $year_options[] = "{$start_year}–{$end_year}";
                                        }

                                        if (!empty($year) && !in_array($year, $year_options)) {
                                            array_unshift($year_options, $year);
                                            sort($year_options);
                                        }
                                    ?>
                                    <div class="flex flex--gap-2">
                                        <select name="semester_period" id="semester_period" class="form__select">
                                            <option value="1st Semester" <?= $period === '1st Semester' ? 'selected' : '' ?>>1st Semester</option>
                                            <option value="2nd Semester" <?= $period === '2nd Semester' ? 'selected' : '' ?>>2nd Semester</option>
                                            <option value="Summer Class" <?= $period === 'Summer Class' ? 'selected' : '' ?>>Summer Class</option>
                                        </select>
                                        <select name="semester_year" id="semester_year" class="form__select">
                                            <?php foreach ($year_options as $year_option): ?>
                                                <option value="<?= $year_option ?>" <?= ($year_option === $year) ? 'selected' : '' ?>>
                                                    <?= $year_option ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <p class="form__help-text">Select the period and enter the academic year.</p>
                                </div>

                                <div class="form__footer">
                                    <button type="submit" class="btn btn--primary">
                                        <span>✔️</span>
                                        <span>Update Course</span>
                                    </button>
                                    <a href="?page=admin&section=courses" class="btn btn--secondary">
                                        <span>↪️</span>
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