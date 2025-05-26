document.addEventListener("DOMContentLoaded", function() {
    const circleContainer = document.createElement("div");
    circleContainer.classList.add("background-circles");
    document.body.appendChild(circleContainer);

    for (let i = 0; i < 25; i++) {
        let circle = document.createElement("div");
        circle.classList.add("circle");

        let size = Math.random() * 80 + 40; // Random size between 40px and 120px
        let posX = Math.random() * window.innerWidth;
        let posY = Math.random() * window.innerHeight;
        let delay = Math.random() * 3; // Random animation delay
        let duration = Math.random() * 4 + 3; // Random duration between 3s and 7s

        circle.style.width = `${size}px`;
        circle.style.height = `${size}px`;
        circle.style.left = `${posX}px`;
        circle.style.top = `${posY}px`;
        circle.style.animationDelay = `${delay}s`;
        circle.style.animationDuration = `${duration}s`;

        circleContainer.appendChild(circle);
    }
});

document.addEventListener("DOMContentLoaded", function() {
    const auroraContainer = document.createElement("div");
    auroraContainer.classList.add("background-aurora");
    document.body.appendChild(auroraContainer);

    // Create aurora layers
    for (let i = 0; i < 3; i++) {
        let aurora = document.createElement("div");
        aurora.classList.add("aurora");
        aurora.style.top = `${Math.random() * 100}%`;
        aurora.style.left = `${Math.random() * 100}%`;
        aurora.style.animationDuration = `${Math.random() * 8 + 6}s`;
        aurora.style.opacity = `${Math.random() * 0.3 + 0.3}`;
        auroraContainer.appendChild(aurora);
    }

    // Create soft particles
    for (let i = 0; i < 40; i++) {
        let particle = document.createElement("div");
        particle.classList.add("particle");

        let size = Math.random() * 8 + 4;
        let posX = Math.random() * window.innerWidth;
        let posY = Math.random() * window.innerHeight;
        let delay = Math.random() * 4;
        let duration = Math.random() * 5 + 3;

        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;
        particle.style.left = `${posX}px`;
        particle.style.top = `${posY}px`;
        particle.style.animationDelay = `${delay}s`;
        particle.style.animationDuration = `${duration}s`;

        auroraContainer.appendChild(particle);
    }

    // Interactive effect
    document.addEventListener("mousemove", (e) => {
        auroraContainer.style.setProperty("--mouse-x", `${e.clientX}px`);
        auroraContainer.style.setProperty("--mouse-y", `${e.clientY}px`);
    });
});

