(() => {
    'use strict';

    const header = document.querySelector('[data-header]');
    const menuToggle = document.querySelector('[data-menu-toggle]');
    const navigation = document.querySelector('[data-navigation]');
    const story = document.querySelector('[data-logo-story]');
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

    const setHeaderState = () => {
        header?.classList.toggle('is-scrolled', window.scrollY > 18);
    };

    const closeMenu = () => {
        if (!menuToggle || !navigation) return;
        menuToggle.setAttribute('aria-expanded', 'false');
        navigation.classList.remove('is-open');
        document.body.classList.remove('menu-open');
    };

    menuToggle?.addEventListener('click', () => {
        const opening = menuToggle.getAttribute('aria-expanded') !== 'true';
        menuToggle.setAttribute('aria-expanded', String(opening));
        navigation?.classList.toggle('is-open', opening);
        document.body.classList.toggle('menu-open', opening);
    });

    navigation?.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeMenu));

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') closeMenu();
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 1020) closeMenu();
    }, { passive: true });

    const revealElements = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window && !reducedMotion.matches) {
        const revealObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        }, { threshold: 0.14, rootMargin: '0px 0px -7% 0px' });
        revealElements.forEach((element) => revealObserver.observe(element));
    } else {
        revealElements.forEach((element) => element.classList.add('is-visible'));
    }

    let scheduled = false;

    const updateStory = () => {
        scheduled = false;
        if (!story) return;

        const rect = story.getBoundingClientRect();
        const scrollable = Math.max(1, story.offsetHeight - window.innerHeight);
        const progress = Math.min(1, Math.max(0, -rect.top / scrollable));
        const logo = story.querySelector('[data-story-logo]');
        const progressBar = story.querySelector('[data-story-progress]');
        const panels = story.querySelectorAll('[data-story-panel]');
        const isMobile = window.innerWidth <= 720;

        if (progressBar) {
            if (isMobile) {
                progressBar.style.width = `${progress * 100}%`;
                progressBar.style.height = '100%';
            } else {
                progressBar.style.height = `${progress * 100}%`;
                progressBar.style.width = '100%';
            }
        }

        if (!reducedMotion.matches && logo) {
            const rotation = -7 + (progress * 14);
            const scale = .94 + (Math.sin(progress * Math.PI) * .08);
            logo.style.setProperty('--logo-rotation', `${rotation.toFixed(2)}deg`);
            logo.style.setProperty('--logo-scale', scale.toFixed(3));
            story.style.setProperty('--ring-rotation', `${progress * 90}deg`);
            story.style.setProperty('--glow-scale', (1 + Math.sin(progress * Math.PI) * .18).toFixed(3));
        }

        const activePanel = Math.min(panels.length - 1, Math.floor(progress * panels.length));
        panels.forEach((panel, index) => panel.classList.toggle('is-active', index === activePanel));
    };

    const requestUpdate = () => {
        if (scheduled) return;
        scheduled = true;
        window.requestAnimationFrame(() => {
            setHeaderState();
            updateStory();
        });
    };

    window.addEventListener('scroll', requestUpdate, { passive: true });
    window.addEventListener('resize', requestUpdate, { passive: true });
    reducedMotion.addEventListener?.('change', requestUpdate);

    setHeaderState();
    updateStory();
})();
