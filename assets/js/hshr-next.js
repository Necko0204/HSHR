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
    const sidebar = document.querySelector('[data-hshr-sidebar]') || document.getElementById('sidebar');
    const sidebarToggle = document.querySelector('[data-hshr-sidebar-toggle]') || document.getElementById('toggleButton');
    const mobileNavToggle = document.getElementById('mobileSidebarToggle');
    const sidebarBackdrop = document.getElementById('sidebarBackdrop');
    const desktopBreakpoint = window.matchMedia('(min-width: 1025px)');

    function postForm(url, values) {
        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams(values),
            credentials: 'same-origin'
        });
    }

    function setBodyScrollLocked(locked) {
        body.classList.toggle('hshr-scroll-locked', locked);
    }

    function setMobileSidebar(open) {
        if (!sidebar) return;
        sidebar.classList.toggle('mobile-open', open);
        sidebarBackdrop?.classList.toggle('show', open);
        mobileNavToggle?.setAttribute('aria-expanded', String(open));
        mobileNavToggle?.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
        setBodyScrollLocked(open);
    }

    function setDesktopSidebar(collapsed, persist) {
        if (!sidebar) return;
        sidebar.classList.toggle('minimized', collapsed);
        body.classList.toggle('hshr-sidebar-collapsed', collapsed);
        sidebarToggle?.setAttribute('aria-expanded', String(!collapsed));
        sidebarToggle?.setAttribute('aria-label', collapsed ? 'Expand navigation' : 'Collapse navigation');
        sidebarToggle?.setAttribute('title', collapsed ? 'Expand navigation' : 'Collapse navigation');

        const icon = sidebarToggle?.querySelector('i');
        if (icon) {
            icon.className = collapsed
                ? 'fa-solid fa-angles-right'
                : 'fa-solid fa-angles-left';
        }

        if (persist) {
            postForm('logics/update_sidebar_state.php', { sidebarOn: collapsed ? '0' : '1' })
                .catch(() => undefined);
        }
    }

    sidebarToggle?.addEventListener('click', function () {
        if (!sidebar) return;
        if (desktopBreakpoint.matches) {
            setDesktopSidebar(!sidebar.classList.contains('minimized'), true);
        } else {
            setMobileSidebar(false);
        }
    });

    mobileNavToggle?.addEventListener('click', function () {
        setMobileSidebar(!sidebar?.classList.contains('mobile-open'));
    });
    sidebarBackdrop?.addEventListener('click', () => setMobileSidebar(false));
    sidebar?.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            if (!desktopBreakpoint.matches) setMobileSidebar(false);
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && sidebar?.classList.contains('mobile-open')) {
            setMobileSidebar(false);
            mobileNavToggle?.focus();
        }
    });

    function syncSidebarForViewport() {
        if (!sidebar) return;
        if (desktopBreakpoint.matches) {
            setMobileSidebar(false);
            setDesktopSidebar(sidebar.classList.contains('minimized'), false);
        } else {
            body.classList.remove('hshr-sidebar-collapsed');
            setMobileSidebar(false);
        }
    }

    if (typeof desktopBreakpoint.addEventListener === 'function') {
        desktopBreakpoint.addEventListener('change', syncSidebarForViewport);
    } else if (typeof desktopBreakpoint.addListener === 'function') {
        desktopBreakpoint.addListener(syncSidebarForViewport);
    }
    syncSidebarForViewport();

    function closeMenus(except) {
        document.querySelectorAll('[data-hshr-menu-panel]').forEach((panel) => {
            if (panel !== except) {
                panel.hidden = true;
                const toggle = panel.closest('[data-hshr-menu]')?.querySelector('[data-hshr-menu-toggle]');
                toggle?.setAttribute('aria-expanded', 'false');
            }
        });
    }

    document.querySelectorAll('[data-hshr-menu-toggle]').forEach((toggle) => {
        const panel = toggle.closest('[data-hshr-menu]')?.querySelector('[data-hshr-menu-panel]');
        if (!panel) return;

        toggle.addEventListener('click', (event) => {
            event.stopPropagation();
            const opening = panel.hidden;
            closeMenus(opening ? panel : null);
            panel.hidden = !opening;
            toggle.setAttribute('aria-expanded', String(opening));
        });
    });

    document.addEventListener('click', (event) => {
        if (!event.target.closest('[data-hshr-menu-panel], [data-hshr-menu-toggle]')) {
            closeMenus();
        }
    });

    const themeToggle = document.getElementById('darkModeToggle');
    function updateThemeButton() {
        const dark = body.classList.contains('hshr-dark');
        themeToggle?.setAttribute('aria-label', dark ? 'Use light theme' : 'Use dark theme');
        themeToggle?.setAttribute('title', dark ? 'Use light theme' : 'Use dark theme');
        const icon = themeToggle?.querySelector('i');
        if (icon) icon.className = dark ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
    }

    themeToggle?.addEventListener('click', function () {
        const dark = !body.classList.contains('hshr-dark');
        body.classList.toggle('hshr-dark', dark);
        updateThemeButton();
        postForm('dark_mode.php', { dark_mode: dark ? '1' : '0' }).catch(() => undefined);
    });
    updateThemeButton();

    const messageCenter = document.querySelector('[data-message-center]');
    const messageOverlay = document.querySelector('[data-message-overlay]');
    const conversation = document.querySelector('[data-chat-body]');
    const composer = document.querySelector('[data-message-form]');
    const messageInput = composer?.querySelector('[data-message-input]');
    const contactSearch = document.querySelector('[data-contact-search]');
    let activeContact = null;
    let messageTimer = null;

    function setMessageCenter(open) {
        if (!messageCenter) return;
        messageCenter.classList.toggle('is-open', open);
        if (messageOverlay) {
            messageOverlay.hidden = !open;
            messageOverlay.classList.toggle('is-open', open);
        }
        messageCenter.setAttribute('aria-hidden', String(!open));
        setBodyScrollLocked(open || Boolean(sidebar?.classList.contains('mobile-open')));
        if (open) {
            window.setTimeout(() => contactSearch?.focus(), 180);
            scheduleMessageRefresh();
        } else if (messageTimer) {
            window.clearTimeout(messageTimer);
            messageTimer = null;
        }
    }

    document.querySelectorAll('[data-open-message-center]').forEach((button) => {
        button.addEventListener('click', () => setMessageCenter(true));
    });
    document.querySelectorAll('[data-close-message-center]').forEach((button) => {
        button.addEventListener('click', () => setMessageCenter(false));
    });
    messageOverlay?.addEventListener('click', () => setMessageCenter(false));

    async function loadMessages(showLoading) {
        if (!activeContact || !conversation) return;
        if (showLoading) {
            conversation.innerHTML = '<div class="hshr-conversation-state"><i class="fa-solid fa-circle-notch fa-spin"></i><p>Loading conversation…</p></div>';
        }

        const params = new URLSearchParams({
            receiver_id: activeContact.dataset.id || '',
            receiver_role: activeContact.dataset.role || ''
        });

        try {
            const response = await fetch(`includes/fetch_messages.php?${params}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin'
            });
            if (!response.ok) throw new Error('Unable to load messages');
            conversation.innerHTML = await response.text();
            conversation.scrollTop = conversation.scrollHeight;
        } catch (error) {
            if (showLoading) {
                conversation.innerHTML = '<div class="hshr-conversation-state hshr-error-state"><i class="fa-solid fa-triangle-exclamation"></i><p>Messages could not be loaded.</p></div>';
            }
        }
    }

    function scheduleMessageRefresh() {
        if (messageTimer) window.clearTimeout(messageTimer);
        if (!messageCenter?.classList.contains('is-open') || !activeContact) return;
        messageTimer = window.setTimeout(async () => {
            if (!document.hidden) await loadMessages(false);
            scheduleMessageRefresh();
        }, 7000);
    }

    document.querySelectorAll('[data-message-contact]').forEach((contact) => {
        contact.addEventListener('click', async () => {
            document.querySelectorAll('[data-message-contact]').forEach((item) => item.classList.remove('is-active'));
            contact.classList.add('is-active');
            activeContact = contact;

            const name = contact.querySelector('strong')?.textContent?.trim() || 'Conversation';
            const role = contact.dataset.role || '';
            const title = document.querySelector('[data-conversation-title]');
            const titleName = title?.querySelector('strong');
            const titleRole = title?.querySelector('small');
            if (titleName) titleName.textContent = name;
            if (titleRole) titleRole.textContent = role;
            if (messageInput) messageInput.disabled = false;
            composer?.querySelector('button[type="submit"]')?.removeAttribute('disabled');

            await loadMessages(true);
            scheduleMessageRefresh();
            messageInput?.focus();
        });
    });

    contactSearch?.addEventListener('input', function () {
        const term = this.value.trim().toLowerCase();
        document.querySelectorAll('[data-message-contact]').forEach((contact) => {
            const haystack = contact.dataset.search || contact.textContent.toLowerCase();
            contact.hidden = term !== '' && !haystack.includes(term);
        });
    });

    composer?.addEventListener('submit', async function (event) {
        event.preventDefault();
        if (!activeContact || !messageInput?.value.trim()) return;
        const submit = composer.querySelector('button[type="submit"]');
        submit?.setAttribute('disabled', 'disabled');

        try {
            const payload = new FormData();
            payload.set('sender_id', messageCenter.dataset.senderId || '');
            payload.set('sender_role', messageCenter.dataset.senderRole || '');
            payload.set('receiver_id', activeContact.dataset.id || '');
            payload.set('receiver_role', activeContact.dataset.role || '');
            payload.set('message', messageInput.value.trim());
            const response = await fetch('logics/send_message.php', {
                method: 'POST',
                body: payload,
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin'
            });
            if (!response.ok) throw new Error('Unable to send message');
            messageInput.value = '';
            await loadMessages(false);
        } catch (error) {
            messageInput?.setCustomValidity('Message could not be sent. Please try again.');
            messageInput?.reportValidity();
            window.setTimeout(() => messageInput?.setCustomValidity(''), 2500);
        } finally {
            submit?.removeAttribute('disabled');
            messageInput?.focus();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') return;
        closeMenus();
        setMobileSidebar(false);
        setMessageCenter(false);
    });

    desktopBreakpoint.addEventListener('change', (event) => {
        if (event.matches) setMobileSidebar(false);
    });
})();
