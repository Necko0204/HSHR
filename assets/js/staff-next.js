(function () {
    'use strict';
    const csrfToken = document.documentElement.dataset.hshrCsrfToken || '';
    const nativeFetch = window.fetch.bind(window);

    function isSameOrigin(url) {
        try {
            return new URL(url, window.location.href).origin === window.location.origin;
        } catch (error) {
            return false;
        }
    }

    window.fetch = function (input, init = {}) {
        const requestUrl = input instanceof Request ? input.url : String(input);
        const method = String(init.method || (input instanceof Request ? input.method : 'GET')).toUpperCase();
        if (csrfToken && isSameOrigin(requestUrl) && !['GET', 'HEAD', 'OPTIONS'].includes(method)) {
            const headers = new Headers(input instanceof Request ? input.headers : undefined);
            new Headers(init.headers || {}).forEach((value, name) => headers.set(name, value));
            headers.set('X-CSRF-Token', csrfToken);
            init = { ...init, headers };
        }
        return nativeFetch(input, init);
    };

    const nativeXhrOpen = XMLHttpRequest.prototype.open;
    const nativeXhrSend = XMLHttpRequest.prototype.send;
    XMLHttpRequest.prototype.open = function (method, url, ...args) {
        this._hshrMethod = String(method).toUpperCase();
        this._hshrUrl = String(url);
        return nativeXhrOpen.call(this, method, url, ...args);
    };
    XMLHttpRequest.prototype.send = function (...args) {
        if (csrfToken && isSameOrigin(this._hshrUrl || '') && !['GET', 'HEAD', 'OPTIONS'].includes(this._hshrMethod || 'GET')) {
            this.setRequestHeader('X-CSRF-Token', csrfToken);
        }
        return nativeXhrSend.apply(this, args);
    };

    document.addEventListener('submit', (event) => {
        const form = event.target;
        if (!(form instanceof HTMLFormElement) || String(form.method).toUpperCase() !== 'POST' || !isSameOrigin(form.action)) return;
        let field = form.querySelector('input[name="_csrf"]');
        if (!field) {
            field = document.createElement('input');
            field.type = 'hidden';
            field.name = '_csrf';
            form.appendChild(field);
        }
        field.value = csrfToken;
    }, true);


    const body = document.body;
    const profileToggle = document.querySelector('[data-staff-profile-toggle]');
    const profilePanel = document.querySelector('[data-staff-profile-panel]');
    const notificationButton = document.querySelector('[data-staff-notifications]');
    const toast = document.querySelector('[data-staff-toast]');
    const themeButton = document.querySelector('[data-staff-theme]');
    const center = document.querySelector('[data-staff-message-center]');
    const overlay = document.querySelector('[data-staff-message-overlay]');
    const chat = document.querySelector('[data-staff-chat]');
    const form = document.querySelector('[data-staff-message-form]');
    const input = document.querySelector('[data-staff-message-input]');
    const search = document.querySelector('[data-staff-contact-search]');
    let activeContact = null;
    let refreshTimer = null;

    function setProfile(open) {
        if (!profilePanel) return;
        profilePanel.hidden = !open;
        profileToggle?.setAttribute('aria-expanded', String(open));
    }

    profileToggle?.addEventListener('click', (event) => {
        event.stopPropagation();
        setProfile(profilePanel?.hidden ?? false);
    });

    document.addEventListener('click', (event) => {
        if (!event.target.closest('.hshr-staff-profile-menu')) setProfile(false);
    });

    notificationButton?.addEventListener('click', () => {
        if (!toast) return;
        toast.hidden = false;
        toast.classList.add('show');
        window.setTimeout(() => {
            toast.classList.remove('show');
            window.setTimeout(() => { toast.hidden = true; }, 180);
        }, 2400);
    });

    function applyTheme(dark, persist) {
        body.classList.toggle('hshr-staff-dark', dark);
        const icon = themeButton?.querySelector('i');
        if (icon) icon.className = dark ? 'fa-regular fa-sun' : 'fa-regular fa-moon';
        if (persist) {
            fetch('dark_mode.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                body: new URLSearchParams({ dark_mode: dark ? 'true' : 'false' }),
                credentials: 'same-origin'
            }).catch(() => undefined);
        }
    }

    themeButton?.addEventListener('click', () => applyTheme(!body.classList.contains('hshr-staff-dark'), true));
    applyTheme(body.classList.contains('hshr-staff-dark'), false);

    function setCenter(open) {
        if (!center || !overlay) return;
        center.classList.toggle('open', open);
        center.setAttribute('aria-hidden', String(!open));
        overlay.hidden = !open;
        body.classList.toggle('hshr-staff-locked', open);
        if (open) {
            window.setTimeout(() => search?.focus(), 160);
            scheduleRefresh();
        } else if (refreshTimer) {
            window.clearTimeout(refreshTimer);
            refreshTimer = null;
        }
    }

    document.querySelectorAll('[data-staff-messages-open]').forEach((button) => button.addEventListener('click', () => setCenter(true)));
    document.querySelectorAll('[data-staff-messages-close]').forEach((button) => button.addEventListener('click', () => setCenter(false)));
    overlay?.addEventListener('click', () => setCenter(false));

    async function loadMessages(showLoading) {
        if (!activeContact || !chat) return;
        if (showLoading) chat.innerHTML = '<div class="hshr-staff-empty"><i class="fa-solid fa-circle-notch fa-spin"></i><p>Loading conversation…</p></div>';
        const params = new URLSearchParams({ receiver_id: activeContact.dataset.id || '', receiver_role: activeContact.dataset.role || '' });
        try {
            const response = await fetch(`includes/fetch_messages.php?${params}`, { credentials: 'same-origin' });
            if (!response.ok) throw new Error('Unable to load conversation');
            chat.innerHTML = await response.text();
            chat.scrollTop = chat.scrollHeight;
        } catch (error) {
            if (showLoading) chat.innerHTML = '<div class="hshr-staff-empty error"><i class="fa-solid fa-triangle-exclamation"></i><p>Conversation could not be loaded.</p></div>';
        }
    }

    function scheduleRefresh() {
        if (refreshTimer) window.clearTimeout(refreshTimer);
        if (!center?.classList.contains('open') || !activeContact) return;
        refreshTimer = window.setTimeout(async () => {
            if (!document.hidden) await loadMessages(false);
            scheduleRefresh();
        }, 8000);
    }

    document.querySelectorAll('[data-staff-contact]').forEach((contact) => {
        contact.addEventListener('click', async () => {
            document.querySelectorAll('[data-staff-contact]').forEach((item) => item.classList.remove('active'));
            contact.classList.add('active');
            activeContact = contact;
            const name = contact.querySelector('strong')?.textContent || 'Conversation';
            const role = contact.dataset.role || '';
            const nameNode = document.querySelector('[data-staff-conversation-name]');
            const roleNode = document.querySelector('[data-staff-conversation-role]');
            if (nameNode) nameNode.textContent = name;
            if (roleNode) roleNode.textContent = role;
            input?.removeAttribute('disabled');
            form?.querySelector('button')?.removeAttribute('disabled');
            await loadMessages(true);
            scheduleRefresh();
            input?.focus();
        });
    });

    search?.addEventListener('input', function () {
        const term = this.value.trim().toLowerCase();
        document.querySelectorAll('[data-staff-contact]').forEach((contact) => {
            contact.hidden = term !== '' && !(contact.dataset.search || '').includes(term);
        });
    });

    form?.addEventListener('submit', async (event) => {
        event.preventDefault();
        if (!activeContact || !input?.value.trim()) return;
        const button = form.querySelector('button');
        button?.setAttribute('disabled', 'disabled');
        const payload = new FormData();
        payload.set('receiver_id', activeContact.dataset.id || '');
        payload.set('receiver_role', activeContact.dataset.role || '');
        payload.set('message', input.value.trim());
        try {
            const response = await fetch('send_message.php', { method: 'POST', body: payload, credentials: 'same-origin' });
            if (!response.ok) throw new Error('Unable to send message');
            input.value = '';
            await loadMessages(false);
        } catch (error) {
            input.setCustomValidity('Message could not be sent. Please try again.');
            input.reportValidity();
            window.setTimeout(() => input.setCustomValidity(''), 2400);
        } finally {
            button?.removeAttribute('disabled');
            input?.focus();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') return;
        setProfile(false);
        setCenter(false);
    });
})();
