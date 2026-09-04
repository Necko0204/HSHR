(() => {
    'use strict';

    const form = document.getElementById('adminLoginForm');
    if (!form) return;

    const username = document.getElementById('username');
    const password = document.getElementById('password');
    const honeypot = document.getElementById('website');
    const submitButton = form.querySelector('[data-admin-login-button]');
    const buttonLabel = form.querySelector('[data-admin-button-label]');
    const passwordToggle = form.querySelector('[data-admin-password-toggle]');
    const rememberUsername = form.querySelector('[data-admin-remember]');
    const alertBox = document.querySelector('[data-admin-login-alert]');
    const capsLockNote = form.querySelector('[data-admin-caps-lock]');
    const recoveryButton = form.querySelector('[data-admin-recovery]');
    const googleButton = form.querySelector('[data-admin-google]');
    const storageKey = 'hshr_admin_username';

    function readRememberedUsername() {
        try { return window.localStorage.getItem(storageKey) || ''; } catch (_) { return ''; }
    }

    function updateRememberedUsername(value) {
        try {
            if (rememberUsername.checked) window.localStorage.setItem(storageKey, value);
            else window.localStorage.removeItem(storageKey);
        } catch (_) {
            // Sign-in remains available when browser storage is blocked.
        }
    }

    function showAlert(message, type = 'error') {
        alertBox.textContent = message;
        alertBox.className = `admin-login-alert${type === 'success' ? ' is-success' : type === 'info' ? ' is-info' : ''}`;
        alertBox.hidden = false;
    }

    function clearAlert() {
        alertBox.hidden = true;
        alertBox.textContent = '';
    }

    function setFieldError(field, message) {
        const group = field.closest('.admin-field');
        const error = group.querySelector(`[data-admin-field-error="${field.name}"]`);
        group.classList.toggle('has-error', Boolean(message));
        field.setAttribute('aria-invalid', message ? 'true' : 'false');
        error.textContent = message;
    }

    function validateForm() {
        const usernameMissing = username.value.trim() === '';
        const passwordMissing = password.value === '';
        setFieldError(username, usernameMissing ? 'Enter your administrator username.' : '');
        setFieldError(password, passwordMissing ? 'Enter your password.' : '');
        if (usernameMissing) username.focus();
        else if (passwordMissing) password.focus();
        return !usernameMissing && !passwordMissing;
    }

    function setLoading(isLoading) {
        submitButton.disabled = isLoading;
        submitButton.classList.toggle('is-loading', isLoading);
        buttonLabel.textContent = isLoading ? 'Verifying access…' : 'Continue to dashboard';
        form.setAttribute('aria-busy', isLoading ? 'true' : 'false');
    }

    async function parseResponse(response) {
        const text = await response.text();
        try { return JSON.parse(text); } catch (_) {
            throw new Error('The authentication service returned an invalid response. Please try again.');
        }
    }

    passwordToggle.addEventListener('click', () => {
        const reveal = password.type === 'password';
        password.type = reveal ? 'text' : 'password';
        passwordToggle.setAttribute('aria-label', reveal ? 'Hide password' : 'Show password');
        passwordToggle.setAttribute('aria-pressed', reveal ? 'true' : 'false');
        passwordToggle.querySelector('i').className = reveal ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
        password.focus({ preventScroll: true });
    });

    password.addEventListener('keyup', event => {
        capsLockNote.hidden = !event.getModifierState('CapsLock');
    });
    password.addEventListener('blur', () => { capsLockNote.hidden = true; });

    [username, password].forEach(field => field.addEventListener('input', () => {
        setFieldError(field, '');
        clearAlert();
    }));

    recoveryButton.addEventListener('click', () => {
        showAlert('Administrator self-service recovery is not configured in this local build. Contact the system owner to reset an admin password.', 'info');
    });

    googleButton.addEventListener('click', () => {
        showAlert('Google Workspace sign-in is not configured in this local build. Use your administrator username and password.', 'info');
    });

    const remembered = readRememberedUsername();
    if (remembered) {
        username.value = remembered;
        rememberUsername.checked = true;
        if (window.matchMedia('(min-width: 781px)').matches) password.focus();
    } else if (window.matchMedia('(min-width: 781px)').matches) {
        username.focus();
    }

    form.addEventListener('submit', async event => {
        event.preventDefault();
        clearAlert();
        if (honeypot.value !== '') {
            showAlert('Unable to process this request.');
            return;
        }
        if (!validateForm()) return;

        setLoading(true);
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                credentials: 'same-origin',
                headers: { Accept: 'application/json' }
            });
            const result = await parseResponse(response);
            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Invalid username or password.');
            }

            updateRememberedUsername(username.value.trim());
            showAlert('Access verified. Opening the administrator dashboard…', 'success');
            buttonLabel.textContent = 'Access granted';
            window.setTimeout(() => window.location.assign('dashboard.php'), 650);
        } catch (error) {
            showAlert(error.message || 'Sign in is temporarily unavailable. Please try again.');
            password.select();
            setLoading(false);
        }
    });
})();
