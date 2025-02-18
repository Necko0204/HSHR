document.addEventListener("DOMContentLoaded", () => {
    function createFloatingShapes() {
        const container = document.createElement('div');
        container.classList.add('floating-container');
        document.body.appendChild(container);

        const colors = ["#6a86d8", "#4b6584", "#b3cde0", "#2e4053", "#5d6d7e"];
        const shapes = ["circle", "square", "triangle"];

        for (let i = 0; i < 40; i++) { // Balanced number for a clean, professional feel
            let shape = document.createElement('div');
            shape.classList.add('floating-shape');

            let randomShape = shapes[Math.floor(Math.random() * shapes.length)];
            shape.classList.add(randomShape);

            let color = colors[Math.floor(Math.random() * colors.length)];
            if (randomShape === "triangle") {
                shape.style.borderBottomColor = color;
            } else {
                shape.style.backgroundColor = color;
            }

            let size = Math.random() * 30 + 15;
            shape.style.width = size + "px";
            shape.style.height = size + "px";

            shape.style.top = Math.random() * 100 + "vh";
            shape.style.left = Math.random() * 100 + "vw";

            shape.style.animationDuration = (Math.random() * 4 + 4) + "s";
            shape.style.animationDelay = Math.random() * 2 + "s";

            container.appendChild(shape);
        }
    }

    createFloatingShapes();
});
