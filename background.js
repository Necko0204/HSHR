document.addEventListener('DOMContentLoaded', function () {
    const isCompact = window.matchMedia('(max-width: 768px)').matches;
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const circleCount = reduceMotion ? 0 : (isCompact ? 5 : 10);
    const auroraCount = reduceMotion ? 0 : (isCompact ? 1 : 2);

    const circleContainer = document.createElement('div');
    circleContainer.className = 'background-circles';
    circleContainer.setAttribute('aria-hidden', 'true');

    const circleFragment = document.createDocumentFragment();
    for (let index = 0; index < circleCount; index += 1) {
        const circle = document.createElement('div');
        const size = Math.random() * 64 + 32;

        circle.className = 'circle';
        circle.style.width = `${size}px`;
        circle.style.height = `${size}px`;
        circle.style.left = `${Math.random() * 100}%`;
        circle.style.top = `${Math.random() * 100}%`;
        circle.style.animationDelay = `${Math.random() * 5}s`;
        circle.style.animationDuration = `${Math.random() * 7 + 8}s`;
        circleFragment.appendChild(circle);
    }
    circleContainer.appendChild(circleFragment);
    document.body.appendChild(circleContainer);

    const auroraContainer = document.createElement('div');
    auroraContainer.className = 'background-aurora';
    auroraContainer.setAttribute('aria-hidden', 'true');

    const auroraFragment = document.createDocumentFragment();
    for (let index = 0; index < auroraCount; index += 1) {
        const aurora = document.createElement('div');
        aurora.className = 'aurora';
        aurora.style.top = `${Math.random() * 100}%`;
        aurora.style.left = `${Math.random() * 100}%`;
        aurora.style.animationDuration = `${Math.random() * 10 + 18}s`;
        aurora.style.opacity = `${Math.random() * 0.15 + 0.18}`;
        auroraFragment.appendChild(aurora);
    }

    auroraContainer.appendChild(auroraFragment);
    document.body.appendChild(auroraContainer);
});
