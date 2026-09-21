document.addEventListener('DOMContentLoaded', () => {
    const generateReportBtn = document.getElementById('generateReportBtn');
    
    if (generateReportBtn) {
        generateReportBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const btn = e.currentTarget;
            const originalText = btn.innerHTML;
            
            btn.disabled = true;
            btn.innerHTML = '<i data-lucide="loader" class="spin"></i> Generating...';
            lucide.createIcons();
            
            // Mock report generation delay
            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
                showToast('Report generated successfully! Download starting...', 'success');
                // Normally you would trigger window.location = downloadUrl here
            }, 2000);
        });
    }
});
