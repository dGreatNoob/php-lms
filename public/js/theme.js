document.addEventListener('DOMContentLoaded', function () {
  const themeToggle = document.querySelector('[data-theme-toggle]');
  const html = document.documentElement;
  
  // Load saved theme
  const savedTheme = localStorage.getItem('theme') || 'light';
  html.setAttribute('data-theme', savedTheme);
  
  if (themeToggle) {
    themeToggle.addEventListener('click', function () {
      const currentTheme = html.getAttribute('data-theme');
      const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
      
      html.setAttribute('data-theme', newTheme);
      localStorage.setItem('theme', newTheme);
      
      // Update toggle button text
      themeToggle.textContent = newTheme === 'dark' ? '☀️' : '🌙';
      themeToggle.setAttribute('aria-label', `Switch to ${newTheme === 'dark' ? 'light' : 'dark'} mode`);
    });
    
    // Set initial button state
    const currentTheme = html.getAttribute('data-theme');
    themeToggle.textContent = currentTheme === 'dark' ? '☀️' : '🌙';
    themeToggle.setAttribute('aria-label', `Switch to ${currentTheme === 'dark' ? 'light' : 'dark'} mode`);
  }
});

// Password toggle functionality
document.addEventListener('DOMContentLoaded', function () {
  const passwordToggles = document.querySelectorAll('[data-password-toggle]');
  
  passwordToggles.forEach(toggle => {
    toggle.addEventListener('click', function () {
      const input = this.previousElementSibling;
      const type = input.type === 'password' ? 'text' : 'password';
      input.type = type;
      
      // Update toggle icon
      this.textContent = type === 'password' ? '👁️' : '🙈';
      this.setAttribute('aria-label', `${type === 'password' ? 'Show' : 'Hide'} password`);
    });
  });
});

// Modal functionality
document.addEventListener('DOMContentLoaded', function () {
  const modalTriggers = document.querySelectorAll('[data-modal-trigger]');
  const modalBackdrops = document.querySelectorAll('.modal-backdrop');
  
  modalTriggers.forEach(trigger => {
    trigger.addEventListener('click', function (e) {
      e.preventDefault();
      const modalId = this.getAttribute('data-modal-trigger');
      const modal = document.getElementById(modalId);
      
      if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        
        // Focus first focusable element
        const firstFocusable = modal.querySelector('button, input, select, textarea, a[href]');
        if (firstFocusable) {
          firstFocusable.focus();
        }
      }
    });
  });
  
  modalBackdrops.forEach(backdrop => {
    backdrop.addEventListener('click', function (e) {
      if (e.target === this) {
        this.style.display = 'none';
        document.body.style.overflow = '';
      }
    });
    
    const closeBtn = backdrop.querySelector('[data-modal-close]');
    if (closeBtn) {
      closeBtn.addEventListener('click', function () {
        backdrop.style.display = 'none';
        document.body.style.overflow = '';
      });
    }
  });
  
  // Close modal on Escape key
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      const openModal = document.querySelector('.modal-backdrop[style*="display: flex"]');
      if (openModal) {
        openModal.style.display = 'none';
        document.body.style.overflow = '';
      }
    }
  });
});

// File input preview
document.addEventListener('DOMContentLoaded', function () {
  const fileInputs = document.querySelectorAll('input[type="file"]');
  
  fileInputs.forEach(input => {
    input.addEventListener('change', function () {
      const label = this.closest('.form__file-input')?.querySelector('.form__file-input-label');
      if (label && this.files.length > 0) {
        const fileName = this.files[0].name;
        label.innerHTML = `<span>📎</span><span>${fileName}</span>`;
      }
    });
  });
});

// Table sorting (basic implementation)
document.addEventListener('DOMContentLoaded', function () {
  const sortableTables = document.querySelectorAll('.table--sortable');
  
  sortableTables.forEach(table => {
    const headers = table.querySelectorAll('th[data-sort]');
    
    headers.forEach(header => {
      header.addEventListener('click', function () {
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
        
        // Reorder rows
        rows.forEach(row => tbody.appendChild(row));
      });
    });
  });
});

// Search and filter functionality
document.addEventListener('DOMContentLoaded', function () {
  const searchInputs = document.querySelectorAll('[data-search]');
  
  searchInputs.forEach(input => {
    input.addEventListener('input', function () {
      const searchTerm = this.value.toLowerCase();
      const targetSelector = this.getAttribute('data-search');
      const items = document.querySelectorAll(targetSelector);
      
      items.forEach(item => {
        const text = item.textContent.toLowerCase();
        const shouldShow = text.includes(searchTerm);
        item.style.display = shouldShow ? '' : 'none';
      });
    });
  });
});

// Progress bar animation
document.addEventListener('DOMContentLoaded', function () {
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
});

// Accessibility improvements
document.addEventListener('DOMContentLoaded', function () {
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
}); 