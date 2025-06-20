<?php
// src/views/admin/sidebar.php
$active_section = $_GET['section'] ?? '';
?>
<aside class="sidebar">
    <div class="sidebar__header">
        <h2 class="sidebar__title">LMS Admin</h2>
    </div>
    <nav class="sidebar__nav">
        <div class="sidebar__section">
            <h3 class="sidebar__section-title">Navigation</h3>
            <a href="?page=admin" class="sidebar__link<?= !isset($_GET['section']) ? ' sidebar__link--active' : '' ?>">
                <span>🏠</span>
                <span>Dashboard</span>
            </a>
        </div>
        <div class="sidebar__section">
            <h3 class="sidebar__section-title">Management</h3>
            <a href="?page=admin&section=courses" class="sidebar__link<?= $active_section === 'courses' ? ' sidebar__link--active' : '' ?>">
                <span>📚</span>
                <span>Courses</span>
            </a>
            <a href="?page=admin&section=topics" class="sidebar__link<?= $active_section === 'topics' ? ' sidebar__link--active' : '' ?>">
                <span>📋</span>
                <span>Topics & Subtopics</span>
            </a>
            <a href="?page=admin&section=lectures" class="sidebar__link<?= $active_section === 'lectures' ? ' sidebar__link--active' : '' ?>">
                <span>🎓</span>
                <span>Lectures</span>
            </a>
            <a href="?page=admin&section=enrollments" class="sidebar__link<?= $active_section === 'enrollments' ? ' sidebar__link--active' : '' ?>">
                <span>👥</span>
                <span>Enrollments</span>
            </a>
            <a href="?page=admin&section=submissions" class="sidebar__link<?= $active_section === 'submissions' ? ' sidebar__link--active' : '' ?>">
                <span>📥</span>
                <span>Submissions</span>
            </a>
            <a href="?page=admin&section=archive" class="sidebar__link<?= $active_section === 'archive' ? ' sidebar__link--active' : '' ?>">
                <span>🗄️</span>
                <span>Archive/Restore</span>
            </a>
        </div>
        <div class="sidebar__section">
            <h3 class="sidebar__section-title">Account</h3>
            <a href="?page=admin&section=profile" class="sidebar__link<?= $active_section === 'profile' ? ' sidebar__link--active' : '' ?>">
                <span>👤</span>
                <span>Profile/Settings</span>
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