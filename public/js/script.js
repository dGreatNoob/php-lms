/**
 * LMS Enhanced JavaScript
 * Provides a single point of initialization for all dynamic functionality.
 */

// Main DOMContentLoaded listener
document.addEventListener('DOMContentLoaded', function() {
    // Each function is wrapped in a try-catch block to prevent a single error
    // from halting all script execution.
    try { initializeTheme(); } catch (e) { console.error("Theme Error:", e); }
    try { initializePasswordToggles(); } catch (e) { console.error("Password Toggle Error:", e); }
    try { initializeModals(); } catch (e) { console.error("Modal Error:", e); }
    try { initializeFileInputs(); } catch (e) { console.error("File Input Error:", e); }
    try { initializeTableSorting(); } catch (e) { console.error("Table Sort Error:", e); }
    try { initializeSearchAndFilter(); } catch (e) { console.error("Search/Filter Error:", e); }
    try { initializeProgressBars(); } catch (e) { console.error("Progress Bar Error:", e); }
    try { initializeAccessibility(); } catch (e) { console.error("Accessibility Error:", e); }
    try { initializeInteractiveComponents(); } catch (e) { console.error("Interactive Component Error:", e); }
});

// Theme Management
function initializeTheme() {
    const themeToggle = document.querySelector('[data-theme-toggle]');
    if (!themeToggle) return;
    
    const html = document.documentElement;
    const savedTheme = localStorage.getItem('theme') || 'light';
    html.setAttribute('data-theme', savedTheme);
    
    themeToggle.addEventListener('click', function() {
        const newTheme = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeToggle(themeToggle, newTheme);
    });
    
    updateThemeToggle(themeToggle, savedTheme);
}

function updateThemeToggle(toggle, theme) {
    toggle.textContent = theme === 'dark' ? '☀️' : '🌙';
    toggle.setAttribute('aria-label', `Switch to ${theme === 'dark' ? 'light' : 'dark'} mode`);
}

// Password Toggle Functionality
function initializePasswordToggles() {
    const passwordToggles = document.querySelectorAll('[data-password-toggle]');
    if (passwordToggles.length === 0) return;

    passwordToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.previousElementSibling;
            if (input) {
                const type = input.type === 'password' ? 'text' : 'password';
                input.type = type;
                this.textContent = type === 'password' ? '👁️' : '🙈';
                this.setAttribute('aria-label', `${type === 'password' ? 'Show' : 'Hide'} password`);
            }
        });
    });
}

// Modal Management
function initializeModals() {
    const modalTriggers = document.querySelectorAll('[data-modal-trigger]');
    if (modalTriggers.length === 0) return;

    const modalBackdrops = document.querySelectorAll('.modal-backdrop');
    
    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            const modalId = this.getAttribute('data-modal-trigger');
            const modal = document.getElementById(modalId);
            if (modal) openModal(modal);
        });
    });
    
    modalBackdrops.forEach(backdrop => {
        backdrop.addEventListener('click', function(e) {
            if (e.target === this) closeModal(this);
        });
        
        const closeBtn = backdrop.querySelector('[data-modal-close]');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => closeModal(backdrop));
        }
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const openModal = document.querySelector('.modal-backdrop[style*="display: flex"]');
            if (openModal) closeModal(openModal);
        }
    });
}

function openModal(modal) {
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    const firstFocusable = modal.querySelector('button, input, select, textarea, a[href]');
    if (firstFocusable) firstFocusable.focus();
}

function closeModal(modal) {
    modal.style.display = 'none';
    document.body.style.overflow = '';
}

// File Input Enhancement
function initializeFileInputs() {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    if (fileInputs.length === 0) return;

    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            const label = this.closest('.form__file-input')?.querySelector('.form__file-input-label');
            if (label && this.files.length > 0) {
                const file = this.files[0];
                label.innerHTML = `
                    <span>📎</span>
                    <div>
                        <div class="font-medium">${file.name}</div>
                        <div class="text-xs text-muted">${formatFileSize(file.size)}</div>
                    </div>
                `;
            }
        });
    });
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Table Sorting
function initializeTableSorting() {
    const sortableTables = document.querySelectorAll('.table--sortable');
    if (sortableTables.length === 0) return;

    sortableTables.forEach(table => {
        const headers = table.querySelectorAll('th[data-sort]');
        headers.forEach(header => {
            header.addEventListener('click', function() {
                const column = this.getAttribute('data-sort');
                const tbody = table.querySelector('tbody');
                if (!tbody) return;
                const rows = Array.from(tbody.querySelectorAll('tr'));
                const isAscending = this.classList.contains('sort--asc');
                
                headers.forEach(h => h.classList.remove('sort--asc', 'sort--desc'));
                this.classList.add(isAscending ? 'sort--desc' : 'sort--asc');
                
                rows.sort((a, b) => {
                    const aCell = a.cells[header.cellIndex];
                    const bCell = b.cells[header.cellIndex];
                    const aValue = aCell.dataset.sortValue || aCell.textContent.trim();
                    const bValue = bCell.dataset.sortValue || bCell.textContent.trim();
                    
                    const comparison = aValue.localeCompare(bValue, undefined, { numeric: true });
                    return isAscending ? -comparison : comparison;
                });
                
                rows.forEach(row => tbody.appendChild(row));
            });
        });
    });
}

// Search and Filter
function initializeSearchAndFilter() {
    const searchInputs = document.querySelectorAll('[data-search]');
    if (searchInputs.length === 0) return;

    searchInputs.forEach(input => {
        input.addEventListener('input', debounce(function() {
            const searchTerm = this.value.toLowerCase();
            const targetSelector = this.getAttribute('data-search');
            document.querySelectorAll(targetSelector).forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        }, 250));
    });
}

// Progress Bar Animation on Scroll
function initializeProgressBars() {
    const progressBars = document.querySelectorAll('.progress__bar');
    if (progressBars.length === 0) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const progressBar = entry.target;
                const width = progressBar.getAttribute('data-width') || progressBar.style.width;
                progressBar.style.width = width;
                observer.unobserve(progressBar);
            }
        });
    }, { threshold: 0.5 });
    
    progressBars.forEach(bar => observer.observe(bar));
}

// Accessibility Improvements
function initializeAccessibility() {
    // Add skip link if not present
    if (!document.querySelector('.skip-link')) {
        const skipLink = document.createElement('a');
        skipLink.href = '#main-content';
        skipLink.className = 'skip-link';
        skipLink.textContent = 'Skip to main content';
        document.body.insertBefore(skipLink, document.body.firstChild);
    }
  
    const mainContent = document.querySelector('main, .dashboard__main');
    if (mainContent && !mainContent.id) {
        mainContent.id = 'main-content';
    }
}

// General Interactive Components (e.g., tooltips, confirmations)
function initializeInteractiveComponents() {
    // Confirmation dialogs
    document.body.addEventListener('click', function(e) {
        const confirmTrigger = e.target.closest('[data-confirm]');
        if (confirmTrigger) {
            const message = confirmTrigger.dataset.confirm || 'Are you sure?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        }
    });
}

// Utility: Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
