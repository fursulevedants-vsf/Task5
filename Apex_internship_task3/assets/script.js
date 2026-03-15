document.addEventListener('DOMContentLoaded', () => {
    // 1. Mobile Navigation Toggle
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('nav-links');

    if (hamburger && navLinks) {
        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            hamburger.innerHTML = navLinks.classList.contains('active') ? '&#10005;' : '&#9776;';
        });
    }

    // 2. Comprehensive Client-side Validation (Day 3-5)
    const forms = document.querySelectorAll('form[novalidate]');
    
    const validationRules = {
        'username': { required: true, min: 3, max: 20, alphanumeric: true },
        'password': { required: true, min: 6 },
        'title': { required: true, min: 5, max: 100 },
        'content': { required: true, min: 10 }
    };

    forms.forEach(form => {
        const inputs = form.querySelectorAll('input, textarea');
        const submitBtn = form.querySelector('button[type="submit"]');

        const validateField = (input) => {
            const field = input.name;
            const rules = validationRules[field];
            if (!rules) return true;

            const value = input.value.trim();
            let error = null;

            if (rules.required && !value) error = `${field} is required.`;
            else if (rules.min && value.length < rules.min) error = `${field} must be at least ${rules.min} characters.`;
            else if (rules.max && value.length > rules.max) error = `${field} cannot exceed ${rules.max} characters.`;
            else if (rules.alphanumeric && !/^[a-zA-Z0-9]+$/.test(value)) error = `${field} must be alphanumeric.`;

            // Display error
            let errorElement = input.parentElement.querySelector('.field-error');
            if (error) {
                if (!errorElement) {
                    errorElement = document.createElement('small');
                    errorElement.className = 'field-error';
                    errorElement.style.color = 'var(--danger)';
                    input.parentElement.appendChild(errorElement);
                }
                errorElement.innerText = error;
                input.style.borderColor = 'var(--danger)';
                return false;
            } else {
                if (errorElement) errorElement.remove();
                input.style.borderColor = 'var(--glass-border)';
                return true;
            }
        };

        const updateFormState = () => {
            let isFormValid = true;
            inputs.forEach(input => {
                // We don't want to show errors on empty fields before they are touched
                // but we need to know if the form is valid overall
                const field = input.name;
                const rules = validationRules[field];
                if (rules && rules.required && !input.value.trim()) isFormValid = false;
                if (input.parentElement.querySelector('.field-error')) isFormValid = false;
            });
            if (submitBtn) submitBtn.disabled = !isFormValid;
        };

        inputs.forEach(input => {
            input.addEventListener('blur', () => {
                validateField(input);
                updateFormState();
            });
            input.addEventListener('input', () => {
                // Remove error as they type
                const errorElement = input.parentElement.querySelector('.field-error');
                if (errorElement) validateField(input);
                updateFormState();
            });
        });

        form.addEventListener('submit', (e) => {
            let isAllValid = true;
            inputs.forEach(input => {
                if (!validateField(input)) isAllValid = false;
            });

            if (!isAllValid) {
                e.preventDefault();
                showToast('Please fix the errors before submitting.', 'error');
            }
        });

        // Initialize state
        updateFormState();
    });

    // 3. Custom Confirm Modal logic
    window.confirmAction = function(message, onConfirm) {
        const overlay = document.createElement('div');
        overlay.className = 'modal-overlay';
        overlay.innerHTML = `
            <div class="glass-modal">
                <h3>${message}</h3>
                <div class="modal-actions">
                    <button class="btn btn-outline" id="modal-cancel">Cancel</button>
                    <button class="btn btn-danger" id="modal-confirm">Confirm</button>
                </div>
            </div>
        `;
        document.body.appendChild(overlay);
        
        // Use timeout to allow transition
        setTimeout(() => overlay.classList.add('active'), 10);
        
        const close = () => {
            overlay.classList.remove('active');
            setTimeout(() => overlay.remove(), 300);
        };
        
        overlay.querySelector('#modal-cancel').addEventListener('click', close);
        overlay.querySelector('#modal-confirm').addEventListener('click', () => {
            close();
            onConfirm();
        });
    };
});

// 4. Custom Toast Notification
function showToast(message, type = 'success') {
    let container = document.querySelector('.toast-container') || document.createElement('div');
    if (!container.parentElement) {
        container.className = 'toast-container';
        document.body.appendChild(container);
    }
    
    const toast = document.createElement('div');
    toast.className = 'glass-toast';
    if(type === 'error') {
        toast.style.background = 'rgba(239, 68, 68, 0.2)';
        toast.style.borderColor = 'rgba(239, 68, 68, 0.4)';
    }

    toast.innerHTML = `
        <span class="toast-icon">${type === 'success' ? '✅' : '❌'}</span>
        <span class="toast-message">${message}</span>
    `;
    
    container.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 10);
    setTimeout(() => {
        toast.classList.replace('show', 'hide');
        setTimeout(() => toast.remove(), 400); 
    }, 3000);
}

