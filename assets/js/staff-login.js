(() => {
    'use strict';

    const form = document.getElementById('loginForm');
    if (!form) return;

    const username = document.getElementById('username');
    const password = document.getElementById('password');
    const submitButton = form.querySelector('[data-login-button]');
    const buttonLabel = form.querySelector('[data-button-label]');
    const passwordToggle = form.querySelector('[data-password-toggle]');
    const rememberUsername = form.querySelector('[data-remember-username]');
    const alertBox = document.querySelector('[data-login-alert]');
    const capsLockNote = form.querySelector('[data-caps-lock]');
    const storageKey = 'hshr_staff_username';

    function safelyReadUsername() {
        try { return window.localStorage.getItem(storageKey) || ''; } catch (_) { return ''; }
    }

    function safelyRememberUsername(value) {
        try {
            if (rememberUsername.checked) window.localStorage.setItem(storageKey, value);
            else window.localStorage.removeItem(storageKey);
        } catch (_) {
            // Authentication remains available when browser storage is disabled.
        }
    }

    function showAlert(message, type = 'error') {
        alertBox.textContent = message;
        alertBox.className = `login-alert${type === 'success' ? ' is-success' : ''}`;
        alertBox.hidden = false;
    }

    function clearAlert() {
        alertBox.hidden = true;
        alertBox.textContent = '';
    }

    function setFieldError(field, message) {
        const group = field.closest('.field-group');
        const error = group.querySelector(`[data-field-error="${field.name}"]`);
        group.classList.toggle('has-error', Boolean(message));
        field.setAttribute('aria-invalid', message ? 'true' : 'false');
        error.textContent = message;
    }

    function validate() {
        const usernameEmpty = username.value.trim() === '';
        const passwordEmpty = password.value === '';
        setFieldError(username, usernameEmpty ? 'Enter your staff username.' : '');
        setFieldError(password, passwordEmpty ? 'Enter your password.' : '');
        if (usernameEmpty) username.focus();
        else if (passwordEmpty) password.focus();
        return !usernameEmpty && !passwordEmpty;
    }

    function setLoading(isLoading) {
        submitButton.disabled = isLoading;
        submitButton.classList.toggle('is-loading', isLoading);
        buttonLabel.textContent = isLoading ? 'Signing you in…' : 'Sign in to staff portal';
        form.setAttribute('aria-busy', isLoading ? 'true' : 'false');
    }

    async function parseLoginResponse(response) {
        const text = await response.text();
        let data;
        try { data = JSON.parse(text); } catch (_) {
            throw new Error('The sign-in service returned an invalid response. Please try again.');
        }
        return data;
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

    const remembered = safelyReadUsername();
    if (remembered) {
        username.value = remembered;
        rememberUsername.checked = true;
        if (window.matchMedia('(min-width: 761px)').matches) password.focus();
    } else if (window.matchMedia('(min-width: 761px)').matches) {
        username.focus();
    }

    form.addEventListener('submit', async event => {
        event.preventDefault();
        clearAlert();
        if (!validate()) return;

        setLoading(true);
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                credentials: 'same-origin',
                headers: { Accept: 'application/json' }
            });
            const result = await parseLoginResponse(response);

            if (!response.ok || result.status !== 'success') {
                throw new Error(result.message || 'Invalid username or password.');
            }

            safelyRememberUsername(username.value.trim());
            showAlert('Sign-in successful. Opening your workspace…', 'success');
            buttonLabel.textContent = 'Welcome back';
            window.setTimeout(() => {
                window.location.assign(result.redirect || 'dashboard.php');
            }, 650);
        } catch (error) {
            showAlert(error.message || 'Sign in is temporarily unavailable. Please try again.');
            password.select();
            setLoading(false);
        }
    });
})();
