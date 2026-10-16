(function () {
    function initPhoneValidation() {
        const phoneInput = document.getElementById('phone');
        const feedback = document.getElementById('phone-feedback');
        const submitBtn = document.getElementById('submit-btn') || document.querySelector('button[type="submit"]');

        if (!phoneInput || !feedback) return;

        phoneInput.addEventListener('input', function () {
            // Strip non-numeric characters immediately (e.g., "qwe")
            this.value = this.value.replace(/[^0-9]/g, '');
            const val = this.value;

            // Reset state if field is empty
            if (val.length === 0) {
                phoneInput.classList.remove('is-invalid', 'is-valid');
                feedback.className = 'form-text mt-1';
                feedback.textContent = 'Format: Must start with 09 and be 11 digits.';
                if (submitBtn) submitBtn.disabled = false;
                return;
            }

            // Check prefix (Must start with 09)
            if (!val.startsWith('09')) {
                phoneInput.classList.add('is-invalid');
                phoneInput.classList.remove('is-valid');
                feedback.className = 'form-text text-danger fw-semibold mt-1';
                feedback.textContent = '❌ Must start with 09.';
                if (submitBtn) submitBtn.disabled = true;
                return;
            }

            // Check length (Must be 11 digits)
            if (val.length < 11) {
                phoneInput.classList.add('is-invalid');
                phoneInput.classList.remove('is-valid');
                feedback.className = 'form-text text-danger fw-semibold mt-1';
                feedback.textContent = `❌ Must be 11 digits (current: ${val.length}/11).`;
                if (submitBtn) submitBtn.disabled = true;
                return;
            }

            // Trigger AJAX check when exactly 11 digits starting with 09 are present
            if (val.length === 11 && val.startsWith('09')) {
                feedback.className = 'form-text text-muted mt-1';
                feedback.textContent = 'Checking availability...';

                const phoneCheckUrl = window.phoneCheckUrl || '/check-phone';
                const csrfToken = window.csrfToken || '';

                fetch(phoneCheckUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: JSON.stringify({ phone: val })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.exists) {
                        phoneInput.classList.add('is-invalid');
                        phoneInput.classList.remove('is-valid');
                        feedback.className = 'form-text text-danger fw-semibold mt-1';
                        feedback.textContent = '❌ Phone number is already registered.';
                        if (submitBtn) submitBtn.disabled = true;
                    } else {
                        phoneInput.classList.remove('is-invalid');
                        phoneInput.classList.add('is-valid');
                        feedback.className = 'form-text text-success fw-semibold mt-1';
                        feedback.textContent = '✔ Phone number is available!';
                        if (submitBtn) submitBtn.disabled = false;
                    }
                })
                .catch(() => {
                    phoneInput.classList.remove('is-invalid');
                    phoneInput.classList.add('is-valid');
                    feedback.className = 'form-text text-success fw-semibold mt-1';
                    feedback.textContent = '✔ Valid phone format (09+ 11 digits).';
                    if (submitBtn) submitBtn.disabled = false;
                });
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPhoneValidation);
    } else {
        initPhoneValidation();
    }
})();