document.addEventListener("DOMContentLoaded", function() {
    const circleContainer = document.createElement("div");
    circleContainer.classList.add("background-circles");
    document.body.appendChild(circleContainer);

    for (let i = 0; i < 20; i++) {
        let circle = document.createElement("div");
        circle.classList.add("circle");

        let size = Math.random() * 80 + 20; // Random size between 20px and 100px
        let posX = Math.random() * window.innerWidth;
        let posY = Math.random() * window.innerHeight;
        let delay = Math.random() * 2; // Random animation delay

        circle.style.width = `${size}px`;
        circle.style.height = `${size}px`;
        circle.style.left = `${posX}px`;
        circle.style.top = `${posY}px`;
        circle.style.animationDelay = `${delay}s`;

        circleContainer.appendChild(circle);
    }
});