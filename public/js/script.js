/**
 * LMS Enhanced JavaScript
 * Provides enhanced functionality for the Learning Management System
 */

// Main initialization
document.addEventListener('DOMContentLoaded', function() {
    initializeTheme();
    initializePasswordToggles();
    initializeModals();
    initializeFileInputs();
    initializeTableSorting();
    initializeSearchAndFilter();
    initializeProgressBars();
    initializeAccessibility();
    initializeFormValidation();
    initializeInteractiveComponents();
});

// Theme Management
function initializeTheme() {
    const themeToggle = document.querySelector('[data-theme-toggle]');
    const html = document.documentElement;
    
    // Load saved theme
    const savedTheme = localStorage.getItem('theme') || 'light';
    html.setAttribute('data-theme', savedTheme);
    
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            // Update toggle button
            updateThemeToggle(themeToggle, newTheme);
        });
        
        // Set initial button state
        updateThemeToggle(themeToggle, savedTheme);
    }
}

function updateThemeToggle(toggle, theme) {
    toggle.textContent = theme === 'dark' ? '☀️' : '🌙';
    toggle.setAttribute('aria-label', `Switch to ${theme === 'dark' ? 'light' : 'dark'} mode`);
}

// Password Toggle Functionality
function initializePasswordToggles() {
    const passwordToggles = document.querySelectorAll('[data-password-toggle]');
    
    passwordToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const type = input.type === 'password' ? 'text' : 'password';
            input.type = type;
            
            // Update toggle icon and label
            this.textContent = type === 'password' ? '👁️' : '🙈';
            this.setAttribute('aria-label', `${type === 'password' ? 'Show' : 'Hide'} password`);
        });
    });
}

// Modal Management
function initializeModals() {
    const modalTriggers = document.querySelectorAll('[data-modal-trigger]');
    const modalBackdrops = document.querySelectorAll('.modal-backdrop');
    
    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            const modalId = this.getAttribute('data-modal-trigger');
            const modal = document.getElementById(modalId);
            
            if (modal) {
                openModal(modal);
            }
        });
    });
    
    modalBackdrops.forEach(backdrop => {
        backdrop.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this);
            }
        });
        
        const closeBtn = backdrop.querySelector('[data-modal-close]');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                closeModal(backdrop);
            });
        }
    });
    
    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const openModal = document.querySelector('.modal-backdrop[style*="display: flex"]');
            if (openModal) {
                closeModal(openModal);
            }
        }
    });
}

function openModal(modal) {
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    // Focus first focusable element
    const firstFocusable = modal.querySelector('button, input, select, textarea, a[href]');
    if (firstFocusable) {
        firstFocusable.focus();
    }
    
    // Add animation class
    modal.classList.add('modal--open');
}

function closeModal(modal) {
    modal.style.display = 'none';
    document.body.style.overflow = '';
    modal.classList.remove('modal--open');
}

// File Input Enhancement
function initializeFileInputs() {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            const label = this.closest('.form__file-input')?.querySelector('.form__file-input-label');
            if (label && this.files.length > 0) {
                const file = this.files[0];
                const fileName = file.name;
                const fileSize = formatFileSize(file.size);
                
                label.innerHTML = `
                    <span>📎</span>
                    <div>
                        <div class="font-medium">${fileName}</div>
                        <div class="text-xs text-muted">${fileSize}</div>
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
    
    sortableTables.forEach(table => {
        const headers = table.querySelectorAll('th[data-sort]');
        
        headers.forEach(header => {
            header.addEventListener('click', function() {
                const column = this.getAttribute('data-sort');
                const tbody = table.querySelector('tbody');
                const rows = Array.from(tbody.querySelectorAll('tr'));
                const isAscending = this.classList.contains('sort--asc');
                
                // Remove sort classes from all headers
                headers.forEach(h => h.classList.remove('sort--asc', 'sort--desc'));
                
                // Add sort class to current header
                this.classList.add(isAscending ? 'sort--desc' : 'sort--asc');
                
                // Sort rows
                rows.sort((a, b) => {
                    const aValue = a.querySelector(`[data-${column}]`)?.getAttribute(`data-${column}`) || '';
                    const bValue = b.querySelector(`[data-${column}]`)?.getAttribute(`data-${column}`) || '';
                    
                    if (isAscending) {
                        return bValue.localeCompare(aValue);
                    } else {
                        return aValue.localeCompare(bValue);
                    }
                });
                
                // Reorder rows with animation
                rows.forEach((row, index) => {
                    row.style.transition = 'all 0.3s ease';
                    setTimeout(() => {
                        tbody.appendChild(row);
                    }, index * 50);
                });
            });
        });
    });
}

// Search and Filter
function initializeSearchAndFilter() {
    const searchInputs = document.querySelectorAll('[data-search]');
    
    searchInputs.forEach(input => {
        input.addEventListener('input', debounce(function() {
            const searchTerm = this.value.toLowerCase();
            const targetSelector = this.getAttribute('data-search');
            const items = document.querySelectorAll(targetSelector);
            
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                const shouldShow = text.includes(searchTerm);
                
                if (shouldShow) {
                    item.style.display = '';
                    item.style.opacity = '1';
                } else {
                    item.style.opacity = '0';
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 300);
                }
            });
        }, 300));
    });
}

// Progress Bar Animation
function initializeProgressBars() {
    const progressBars = document.querySelectorAll('.progress__bar');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const progressBar = entry.target;
                const width = progressBar.style.width;
                progressBar.style.width = '0%';
                
                setTimeout(() => {
                    progressBar.style.width = width;
                }, 100);
                
                observer.unobserve(progressBar);
            }
        });
    });
    
    progressBars.forEach(bar => observer.observe(bar));
}

// Accessibility Enhancements
function initializeAccessibility() {
    // Add skip link if not present
    if (!document.querySelector('.skip-link')) {
        const skipLink = document.createElement('a');
        skipLink.href = '#main-content';
        skipLink.className = 'skip-link';
        skipLink.textContent = 'Skip to main content';
        document.body.insertBefore(skipLink, document.body.firstChild);
    }
    
    // Add main content id if not present
    const mainContent = document.querySelector('main, .dashboard__main, .auth-card');
    if (mainContent && !mainContent.id) {
        mainContent.id = 'main-content';
    }
    
    // Add proper ARIA labels to interactive elements
    const buttons = document.querySelectorAll('button:not([aria-label])');
    buttons.forEach(button => {
        if (button.textContent.trim()) {
            button.setAttribute('aria-label', button.textContent.trim());
        }
    });
    
    // Add focus indicators
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Tab') {
            document.body.classList.add('keyboard-navigation');
        }
    });
    
    document.addEventListener('mousedown', function() {
        document.body.classList.remove('keyboard-navigation');
    });
}

// Form Validation
function initializeFormValidation() {
    const forms = document.querySelectorAll('form[data-validate]');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
                showFormErrors(this);
            }
        });
        
        // Real-time validation
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                clearFieldError(this);
            });
        });
    });
}

function validateForm(form) {
    let isValid = true;
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    
    inputs.forEach(input => {
        if (!validateField(input)) {
            isValid = false;
        }
    });
    
    return isValid;
}

function validateField(field) {
    const value = field.value.trim();
    const type = field.type;
    let isValid = true;
    let errorMessage = '';
    
    // Required field validation
    if (field.hasAttribute('required') && !value) {
        isValid = false;
        errorMessage = 'This field is required';
    }
    
    // Email validation
    if (type === 'email' && value && !isValidEmail(value)) {
        isValid = false;
        errorMessage = 'Please enter a valid email address';
    }
    
    // Password validation
    if (type === 'password' && value && value.length < 8) {
        isValid = false;
        errorMessage = 'Password must be at least 8 characters long';
    }
    
    if (!isValid) {
        showFieldError(field, errorMessage);
    } else {
        clearFieldError(field);
    }
    
    return isValid;
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function showFieldError(field, message) {
    field.classList.add('form__input--error');
    
    let errorElement = field.parentNode.querySelector('.form__error-text');
    if (!errorElement) {
        errorElement = document.createElement('div');
        errorElement.className = 'form__error-text';
        field.parentNode.appendChild(errorElement);
    }
    
    errorElement.textContent = message;
}

function clearFieldError(field) {
    field.classList.remove('form__input--error');
    
    const errorElement = field.parentNode.querySelector('.form__error-text');
    if (errorElement) {
        errorElement.remove();
    }
}

function showFormErrors(form) {
    const firstError = form.querySelector('.form__input--error');
    if (firstError) {
        firstError.focus();
    }
}

// Interactive Components
function initializeInteractiveComponents() {
    // Collapsible sections
    const collapsibles = document.querySelectorAll('[data-collapse]');
    collapsibles.forEach(collapsible => {
        const trigger = collapsible.querySelector('[data-collapse-trigger]');
        const content = collapsible.querySelector('[data-collapse-content]');
        
        if (trigger && content) {
            trigger.addEventListener('click', function() {
                const isExpanded = content.style.display !== 'none';
                
                if (isExpanded) {
                    content.style.display = 'none';
                    trigger.setAttribute('aria-expanded', 'false');
                } else {
                    content.style.display = 'block';
                    trigger.setAttribute('aria-expanded', 'true');
                }
            });
        }
    });
    
    // Tooltips
    const tooltips = document.querySelectorAll('[data-tooltip]');
    tooltips.forEach(element => {
        element.addEventListener('mouseenter', showTooltip);
        element.addEventListener('mouseleave', hideTooltip);
        element.addEventListener('focus', showTooltip);
        element.addEventListener('blur', hideTooltip);
    });
    
    // Copy to clipboard
    const copyButtons = document.querySelectorAll('[data-copy]');
    copyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const textToCopy = this.getAttribute('data-copy');
            navigator.clipboard.writeText(textToCopy).then(() => {
                showToast('Copied to clipboard!', 'success');
            });
        });
    });
}

// Tooltip functionality
function showTooltip(event) {
    const element = event.target;
    const tooltipText = element.getAttribute('data-tooltip');
    
    const tooltip = document.createElement('div');
    tooltip.className = 'tooltip';
    tooltip.textContent = tooltipText;
    tooltip.setAttribute('role', 'tooltip');
    
    document.body.appendChild(tooltip);
    
    const rect = element.getBoundingClientRect();
    tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
    tooltip.style.top = rect.top - tooltip.offsetHeight - 8 + 'px';
    
    element.tooltip = tooltip;
}

function hideTooltip(event) {
    const element = event.target;
    if (element.tooltip) {
        element.tooltip.remove();
        element.tooltip = null;
    }
}

// Toast notifications
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast toast--${type}`;
    toast.innerHTML = `
        <div class="toast__content">
            <span class="toast__message">${message}</span>
            <button class="toast__close" aria-label="Close notification">×</button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.classList.add('toast--show');
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        hideToast(toast);
    }, 5000);
    
    // Close button
    const closeBtn = toast.querySelector('.toast__close');
    closeBtn.addEventListener('click', () => hideToast(toast));
}

function hideToast(toast) {
    toast.classList.remove('toast--show');
    setTimeout(() => {
        toast.remove();
    }, 300);
}

// Utility functions
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

// Export functions for global use
window.LMS = {
    showToast,
    openModal,
    closeModal,
    validateForm,
    formatFileSize
};
