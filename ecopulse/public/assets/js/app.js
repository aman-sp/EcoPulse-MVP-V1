document.addEventListener('DOMContentLoaded', () => {
    initTheme();
    initSidebar();
    initDropdowns();
    initModals();
    if (typeof lucide !== 'undefined') lucide.createIcons();
});

// Theme handling
function initTheme() {
    const toggle = document.getElementById('darkModeToggle');
    const icon = document.getElementById('darkModeIcon');
    const savedTheme = localStorage.getItem('theme');
    
    if (savedTheme === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
        if (icon) icon.setAttribute('data-lucide', 'sun');
    }

    if (toggle) {
        toggle.addEventListener('click', () => {
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            if (isDark) {
                document.documentElement.removeAttribute('data-theme');
                localStorage.setItem('theme', 'light');
                if (icon) icon.setAttribute('data-lucide', 'moon');
            } else {
                document.documentElement.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
                if (icon) icon.setAttribute('data-lucide', 'sun');
            }
            if (typeof lucide !== 'undefined') lucide.createIcons();
            
            // Dispatch event for charts to update
            window.dispatchEvent(new Event('themeChanged'));
        });
    }
}

// Sidebar handling
function initSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const mobileToggle = document.getElementById('mobileSidebarToggle');
    const desktopToggle = document.querySelector('.sidebar__toggle');
    
    if (localStorage.getItem('sidebarCollapsed') === 'true' && window.innerWidth > 1024) {
        sidebar?.classList.add('sidebar--collapsed');
    }

    desktopToggle?.addEventListener('click', () => {
        sidebar?.classList.toggle('sidebar--collapsed');
        localStorage.setItem('sidebarCollapsed', sidebar?.classList.contains('sidebar--collapsed'));
    });

    mobileToggle?.addEventListener('click', () => {
        sidebar?.classList.toggle('sidebar--open');
    });

    // Close mobile sidebar when clicking outside
    document.addEventListener('click', (e) => {
        if (window.innerWidth <= 768 && sidebar?.classList.contains('sidebar--open')) {
            if (!sidebar.contains(e.target) && !mobileToggle?.contains(e.target)) {
                sidebar.classList.remove('sidebar--open');
            }
        }
    });
}

// Dropdowns
function initDropdowns() {
    document.querySelectorAll('.dropdown__trigger').forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            const dropdown = trigger.closest('.dropdown');
            
            // Close others
            document.querySelectorAll('.dropdown').forEach(d => {
                if (d !== dropdown) d.classList.remove('active');
            });
            
            dropdown.classList.toggle('active');
        });
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('.dropdown').forEach(d => d.classList.remove('active'));
    });
}

// Modals
function initModals() {
    document.querySelectorAll('[data-modal-target]').forEach(trigger => {
        trigger.addEventListener('click', () => {
            const target = document.getElementById(trigger.getAttribute('data-modal-target'));
            target?.classList.add('modal--active');
        });
    });

    document.querySelectorAll('[data-modal-close]').forEach(trigger => {
        trigger.addEventListener('click', () => {
            trigger.closest('.modal')?.classList.remove('modal--active');
        });
    });
}

// Toast
window.showToast = function(message, type = 'success', duration = 5000) {
    const container = document.getElementById('toastContainer');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `toast toast--${type}`;
    
    let icon = 'check-circle';
    if (type === 'error') icon = 'alert-circle';
    if (type === 'warning') icon = 'alert-triangle';
    if (type === 'info') icon = 'info';

    toast.innerHTML = `
        <i data-lucide="${icon}"></i>
        <span>${message}</span>
    `;

    container.appendChild(toast);
    if (typeof lucide !== 'undefined') lucide.createIcons();

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => toast.remove(), 300);
    }, duration);
};

// Confirm Dialog
window.confirmAction = function(message, callback) {
    // Basic implementation using native confirm as fallback if custom modal is not present
    if (confirm(message)) {
        callback();
    }
};

// CSRF Header setup for fetch
window.fetchWithCsrf = function(url, options = {}) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!options.headers) options.headers = {};
    if (csrfToken) {
        options.headers['X-CSRF-TOKEN'] = csrfToken;
    }
    return fetch(url, options);
};
