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
    try { initializeDependentDropdowns(); } catch (e) { console.error("Dependent Dropdown Error:", e); }
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

    // Confirmation dialogs
    document.addEventListener('DOMContentLoaded', () => {
        const deleteModal = document.getElementById('delete-modal');
        if (!deleteModal) return;

        const warningMessageEl = document.getElementById('delete-modal-warning-message');
        const confirmBtn = document.getElementById('confirm-delete-btn');

        document.querySelectorAll('.js-delete-trigger').forEach(trigger => {
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                const deleteUrl = trigger.dataset.deleteUrl;
                const entityName = trigger.dataset.entityName;
                const entityType = trigger.dataset.entityType;

                let warning = `You are about to delete the ${entityType}: <strong>${entityName}</strong>.`;
                if (entityType === 'course') {
                    warning = `You are about to archive the course: <strong>${entityName}</strong>. This will also archive all its topics and lectures.`;
                } else if (entityType === 'topic') {
                    warning = `You are about to archive the topic: <strong>${entityName}</strong>. This will also archive all its sub-topics and lectures.`;
                } else if (entityType === 'lecture') {
                    warning = `You are about to archive the lecture: <strong>${entityName}</strong>. This will also delete all student submissions for this lecture.`;
                } else if (entityType === 'enrollment') {
                    warning = `You are about to remove the enrollment for <strong>${entityName}</strong>.`;
                }
                
                warningMessageEl.innerHTML = warning;
                confirmBtn.href = deleteUrl;
                deleteModal.style.display = 'flex';
            });
        });

        // Generic modal close triggers
        deleteModal.querySelectorAll('[data-modal-close]').forEach(trigger => {
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                const modal = document.querySelector(trigger.dataset.modalClose);
                if(modal) {
                    modal.style.display = 'none';
                }
            });
        });

        // Close modal if backdrop is clicked
        deleteModal.addEventListener('click', (e) => {
            if (e.target === deleteModal) {
                deleteModal.style.display = 'none';
            }
        });
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

// Dependent Dropdowns
function initializeDependentDropdowns() {
    // Lecture filters
    const filterForm = document.getElementById('filter-form');
    if (filterForm) {
        const courseSelect = document.getElementById('course_id');
        const topicSelect = document.getElementById('topic_id');
        
        // Check if allTopics is defined and is an array from the view
        if (typeof allTopics !== 'undefined' && Array.isArray(allTopics)) {
            
            const updateTopicOptions = () => {
                const selectedCourseId = courseSelect.value;
                const currentTopicValue = topicSelect.value;

                // Filter topics based on the selected course
                const relevantTopics = allTopics.filter(topic => !selectedCourseId || topic.course_id == selectedCourseId);
                
                // Preserve the "All Topics" option
                topicSelect.innerHTML = '<option value="">All Topics</option>';

                // Repopulate the dropdown
                relevantTopics.forEach(topic => {
                    const option = document.createElement('option');
                    option.value = topic.id;
                    option.textContent = topic.title;
                    // Keep the topic selected if it was before the update
                    option.selected = topic.id == currentTopicValue;
                    topicSelect.appendChild(option);
                });
                
                // If the previously selected topic isn't in the new list, reset to "All Topics"
                if (!relevantTopics.some(topic => topic.id == currentTopicValue)) {
                    topicSelect.value = "";
                }
            };

            courseSelect.addEventListener('change', updateTopicOptions);

            // Initial call to set the correct state on page load
            updateTopicOptions();
        }
    }

    // Lecture filters for dependent dropdowns
    const lectureFilterForm = document.getElementById('filter-form');
    if (lectureFilterForm) {
        const courseSelect = document.getElementById('course_id');
        const topicSelect = document.getElementById('topic_id');
        
        // The 'allTopics' variable is expected to be defined in a <script> tag in the HTML view
        if (typeof allTopics !== 'undefined' && Array.isArray(allTopics)) {
            
            const updateTopicOptions = () => {
                const selectedCourseId = courseSelect.value;
                const currentTopicValue = topicSelect.value;
                
                // Filter topics based on the selected course
                const relevantTopics = allTopics.filter(topic => !selectedCourseId || topic.course_id == selectedCourseId);
                
                // Preserve the "All Topics" option and clear the rest
                topicSelect.innerHTML = '<option value="">All Topics</option>';

                // Repopulate the dropdown with relevant topics
                relevantTopics.forEach(topic => {
                    const option = document.createElement('option');
                    option.value = topic.id;
                    option.textContent = topic.title;
                    option.selected = topic.id == currentTopicValue; // Keep selection if possible
                    topicSelect.appendChild(option);
                });
                
                // If the previously selected topic isn't in the new filtered list, reset the selection
                if (!relevantTopics.some(topic => topic.id == currentTopicValue)) {
                    topicSelect.value = "";
                }
            };

            // Add event listener to the course dropdown
            courseSelect.addEventListener('change', updateTopicOptions);

            // Initial call to set the correct state when the page loads
            updateTopicOptions();
        }
    }
}
