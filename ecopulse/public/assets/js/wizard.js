document.addEventListener('DOMContentLoaded', () => {
    const wizardForm = document.getElementById('wizardForm');
    if (!wizardForm) return;

    const totalSteps = 6;
    let currentStep = 1;

    // Check active step from DOM if available
    const activeStepEl = document.querySelector('.wizard__step--active');
    if (activeStepEl && activeStepEl.dataset.step) {
        currentStep = parseInt(activeStepEl.dataset.step);
    }

    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const autosaveIndicator = document.getElementById('autosaveIndicator');
    const stepTabs = document.querySelectorAll('.wizard__step');

    function updateStepUI(step) {
        currentStep = parseInt(step);

        // Show/hide content panels (#step-1, #step-2, ..., #step-6)
        for (let i = 1; i <= totalSteps; i++) {
            const panel = document.getElementById(`step-${i}`);
            if (panel) {
                panel.style.display = (i === currentStep) ? 'block' : 'none';
            }
        }

        // Update step header tabs styling
        stepTabs.forEach(tab => {
            const stepNum = parseInt(tab.dataset.step);
            tab.classList.remove('wizard__step--active', 'wizard__step--completed');
            
            const numberSpan = tab.querySelector('.wizard__step-number');
            const labelSpan = tab.querySelector('.wizard__step-label');

            if (stepNum === currentStep) {
                tab.classList.add('wizard__step--active');
                tab.style.borderBottomColor = '#0d9488';
                if (numberSpan) numberSpan.style.color = '#0f172a';
                if (labelSpan) labelSpan.style.color = '#475569';
            } else if (stepNum < currentStep) {
                tab.classList.add('wizard__step--completed');
                tab.style.borderBottomColor = '#10b981';
                if (numberSpan) numberSpan.style.color = '#0f172a';
                if (labelSpan) labelSpan.style.color = '#475569';
            } else {
                tab.style.borderBottomColor = '#e2e8f0';
                if (numberSpan) numberSpan.style.color = '#94a3b8';
                if (labelSpan) labelSpan.style.color = '#94a3b8';
            }
        });

        // Toggle action buttons
        if (prevBtn) {
            prevBtn.style.display = (currentStep > 1) ? 'inline-flex' : 'none';
        }

        if (nextBtn) {
            nextBtn.style.display = (currentStep < totalSteps) ? 'inline-flex' : 'none';
        }

        if (submitBtn) {
            submitBtn.style.display = (currentStep === totalSteps) ? 'inline-flex' : 'none';
        }

        // Re-initialize Lucide icons if present
        if (window.lucide && typeof window.lucide.createIcons === 'function') {
            window.lucide.createIcons();
        }
    }

    // Allow direct clicking on step headers
    stepTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const targetStep = parseInt(tab.dataset.step);
            if (targetStep && targetStep !== currentStep) {
                saveCurrentStepData(currentStep);
                updateStepUI(targetStep);
            }
        });
    });

    // Previous Button Click
    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            if (currentStep > 1) {
                saveCurrentStepData(currentStep);
                updateStepUI(currentStep - 1);
            }
        });
    }

    // Next Button Click
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            if (currentStep < totalSteps) {
                saveCurrentStepData(currentStep);
                updateStepUI(currentStep + 1);
            }
        });
    }

    // Save step data via AJAX
    function saveCurrentStepData(step) {
        if (typeof isReadonly !== 'undefined' && isReadonly) return;

        if (autosaveIndicator) {
            autosaveIndicator.style.display = 'inline-block';
            autosaveIndicator.textContent = 'Saving...';
        }

        const formData = new FormData(wizardForm);
        const actionUrl = wizardForm.action;
        const baseUrl = actionUrl.includes('/hospital/submission') ? actionUrl.split('/hospital/submission')[0] : '';

        fetch(`${baseUrl}/hospital/submission/save-step/${step}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (autosaveIndicator) {
                autosaveIndicator.textContent = 'Saved ✓';
                setTimeout(() => {
                    autosaveIndicator.style.display = 'none';
                }, 1500);
            }
        })
        .catch(err => {
            if (autosaveIndicator) {
                autosaveIndicator.textContent = 'Saved';
                setTimeout(() => {
                    autosaveIndicator.style.display = 'none';
                }, 1500);
            }
        });
    }

    // Initialize UI on page load
    updateStepUI(currentStep);
});
