document.addEventListener('DOMContentLoaded', () => {
    // Auto-generate username from hospital name
    const hospNameInput = document.getElementById('hospital_name');
    const usernameInput = document.getElementById('username');
    
    if (hospNameInput && usernameInput && !usernameInput.value) {
        hospNameInput.addEventListener('blur', () => {
            if (!usernameInput.value && hospNameInput.value) {
                const base = hospNameInput.value.toLowerCase().replace(/[^a-z0-9]/g, '');
                usernameInput.value = base + Math.floor(Math.random() * 1000);
            }
        });
    }

    // Password generator
    const generateBtn = document.getElementById('generatePassword');
    const passwordInput = document.getElementById('password');
    
    if (generateBtn && passwordInput) {
        generateBtn.addEventListener('click', () => {
            const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
            let pwd = "";
            for (let i = 0; i < 12; i++) {
                pwd += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            passwordInput.value = pwd;
            passwordInput.type = 'text'; // show it briefly
            setTimeout(() => passwordInput.type = 'password', 5000);
        });
    }

    // Prevent double submit
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', (e) => {
            const btn = form.querySelector('button[type="submit"]');
            if (btn && !form.classList.contains('no-disable')) {
                btn.disabled = true;
                btn.innerHTML = '<span class="loading-spinner" style="width:16px;height:16px;border-width:2px;display:inline-block;"></span> Processing...';
            }
        });
    });
});
