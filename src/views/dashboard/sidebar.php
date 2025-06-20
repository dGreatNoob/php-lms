<?php
// src/views/dashboard/sidebar.php
$page = $_GET['page'] ?? '';
?>
<aside class="sidebar">
    <div class="sidebar__header">
        <h2 class="sidebar__title">LMS Student</h2>
    </div>
    <nav class="sidebar__nav">
        <div class="sidebar__section">
            <h3 class="sidebar__section-title">Navigation</h3>
            <a href="?page=dashboard" class="sidebar__link<?= ($page === 'dashboard' || $page === '') ? ' sidebar__link--active' : '' ?>">
                <span>🏠</span>
                <span>Dashboard</span>
            </a>
        </div>
        <div class="sidebar__section">
            <h3 class="sidebar__section-title">Account</h3>
            <a href="?page=profile" class="sidebar__link<?= $page === 'profile' ? ' sidebar__link--active' : '' ?>">
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