document.addEventListener('DOMContentLoaded', function () {
    const passwordInput = document.getElementById('password');
    const passwordFeedback = document.getElementById('password-feedback');
    const confirmInput = document.getElementById('password_confirmation');
    const confirmFeedback = document.getElementById('confirm-feedback');
    const submitBtn = document.getElementById('submit-btn') || document.querySelector('button[type="submit"]');

    if (!passwordInput || !confirmInput) return;

    function validatePasswords() {
        const passVal = passwordInput.value;
        const confirmVal = confirmInput.value;

        let isPasswordValid = false;
        let isConfirmValid = false;

        // 1. Validate Password Length
        if (passVal.length === 0) {
            passwordInput.classList.remove('is-invalid', 'is-valid');
            passwordFeedback.className = 'form-text mt-1';
            passwordFeedback.textContent = 'Minimum 8 characters required.';
        } else if (passVal.length < 8) {
            passwordInput.classList.add('is-invalid');
            passwordInput.classList.remove('is-valid');
            passwordFeedback.className = 'form-text text-danger fw-semibold mt-1';
            passwordFeedback.textContent = `❌ Minimum 8 characters required (current: ${passVal.length}).`;
        } else {
            passwordInput.classList.remove('is-invalid');
            passwordInput.classList.add('is-valid');
            passwordFeedback.className = 'form-text text-success fw-semibold mt-1';
            passwordFeedback.textContent = '✔ Password length OK!';
            isPasswordValid = true;
        }

        // 2. Validate Password Match
        if (confirmVal.length === 0) {
            confirmInput.classList.remove('is-invalid', 'is-valid');
            confirmFeedback.className = 'form-text mt-1';
            confirmFeedback.textContent = 'Passwords must match.';
        } else if (passVal !== confirmVal) {
            confirmInput.classList.add('is-invalid');
            confirmInput.classList.remove('is-valid');
            confirmFeedback.className = 'form-text text-danger fw-semibold mt-1';
            confirmFeedback.textContent = '❌ Passwords do not match.';
        } else {
            confirmInput.classList.remove('is-invalid');
            confirmInput.classList.add('is-valid');
            confirmFeedback.className = 'form-text text-success fw-semibold mt-1';
            confirmFeedback.textContent = '✔ Passwords match!';
            isConfirmValid = true;
        }

        // Disable submit button if either field is invalid
        if (submitBtn) {
            submitBtn.disabled = !(isPasswordValid && isConfirmValid);
        }
    }

    passwordInput.addEventListener('input', validatePasswords);
    confirmInput.addEventListener('input', validatePasswords);
});