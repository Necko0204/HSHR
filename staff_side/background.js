document.addEventListener('DOMContentLoaded', () => {
    const container = document.querySelector('.background-container');

    function createBox() {
        const box = document.createElement('div');
        box.classList.add('box');
        container.appendChild(box);

        const size = Math.floor(Math.random() * 40) + 10;
        const left = Math.random() * 100;
        const duration = Math.random() * 10 + 10;

        box.style.width = `${size}px`;
        box.style.height = `${size}px`;
        box.style.left = `${left}%`;
        box.style.animationDuration = `${duration}s`;

        box.animate([
            { transform: 'translateY(120vh) translateX(0) rotate(0)', opacity: 0 },
            { opacity: 0.5, offset: 0.1 },
            { opacity: 0.5, offset: 0.9 },
            { transform: 'translateY(-10vh) translateX(0) rotate(360deg)', opacity: 0 }
        ], {
            duration: duration * 1000,
            iterations: Infinity,
            easing: 'linear'
        });
    }

    for (let i = 0; i < 10; i++) {
        createBox();
    }
});