// UI Animations and Interactions Module
class UIAnimations {
    constructor() {
        this.lastScroll = 0;
        this.initializeAOS();
        this.initializeNavbarScroll();
        this.initializeSmoothScroll();
        this.initializeCounterAnimation();
        this.initializeScrollProgress();
        this.initializeCustomCursor();
        this.initializeFloatingElements();
    }

    initializeAOS() {
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
    }

    initializeNavbarScroll() {
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar');
            const currentScroll = window.pageYOffset;

            if (currentScroll <= 0) {
                navbar.style.padding = '20px 0';
                navbar.style.boxShadow = 'none';
                navbar.style.transform = 'translateY(0)';
            } else if (currentScroll > this.lastScroll) {
                navbar.style.padding = '10px 0';
                navbar.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
                navbar.style.transform = 'translateY(-100%)';
            } else {
                navbar.style.transform = 'translateY(0)';
            }
            this.lastScroll = currentScroll;
        });
    }

    initializeSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    }

    initializeCounterAnimation() {
        const counterAnimation = () => {
            const counters = document.querySelectorAll('.counter-number');
            counters.forEach(counter => {
                const target = parseInt(counter.innerText);
                let count = 0;
                const speed = 2000 / target;
                
                const updateCount = () => {
                    if(count < target) {
                        count++;
                        counter.innerText = count + (counter.innerText.includes('+') ? '+' : '%');
                        setTimeout(updateCount, speed);
                    }
                };
                updateCount();
            });
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    counterAnimation();
                    observer.unobserve(entry.target);
                }
            });
        });

        document.querySelector('.stats-counter')?.querySelectorAll('.counter-number').forEach(counter => {
            observer.observe(counter);
        });
    }

    initializeScrollProgress() {
        window.addEventListener('scroll', () => {
            const scrollProgress = document.querySelector('.scroll-progress');
            if (scrollProgress) {
                const scrollPx = document.documentElement.scrollTop;
                const winHeightPx = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                const scrolled = `${(scrollPx / winHeightPx) * 100}%`;
                scrollProgress.style.width = scrolled;
            }
        });
    }

    initializeCustomCursor() {
        const cursor = document.querySelector('.custom-cursor');
        if (cursor) {
            document.addEventListener('mousemove', (e) => {
                cursor.style.left = e.clientX + 'px';
                cursor.style.top = e.clientY + 'px';
            });
        }
    }

    initializeFloatingElements() {
        const container = document.querySelector('.floating-elements');
        if (container) {
            for (let i = 0; i < 5; i++) {
                const element = document.createElement('div');
                element.className = 'floating-element';
                element.style.left = Math.random() * 100 + 'vw';
                element.style.top = Math.random() * 100 + 'vh';
                element.style.width = Math.random() * 100 + 50 + 'px';
                element.style.height = element.style.width;
                container.appendChild(element);
            }
        }
    }
}

// Initialize UI animations when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new UIAnimations();
});

document.addEventListener('scroll', function() {
    const parallaxImage = document.querySelector('.parallax-image');
    if (parallaxImage) {
        const scrolled = window.pageYOffset;
        parallaxImage.style.transform = `translate3d(0, ${scrolled * 0.4}px, 0) scale(1.1)`;
    }
});

// Add smooth scrolling
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

// Optional: Add parallax effect intensity
window.addEventListener('scroll', function() {
    const scrolled = window.pageYOffset;
    document.body.style.backgroundPositionY = -(scrolled * 0.5) + 'px';
});

 // Initialize Particle Background
 const canvas = document.getElementById('particle-canvas');
 const ctx = canvas.getContext('2d');
 let particles = [];

 function initParticles() {
     canvas.width = window.innerWidth;
     canvas.height = window.innerHeight;

     for (let i = 0; i < 100; i++) {
         particles.push({
             x: Math.random() * canvas.width,
             y: Math.random() * canvas.height,
             radius: Math.random() * 2,
             vx: Math.random() * 2 - 1,
             vy: Math.random() * 2 - 1
         });
     }
 }

 function animateParticles() {
     ctx.clearRect(0, 0, canvas.width, canvas.height);
     
     particles.forEach(particle => {
         particle.x += particle.vx;
         particle.y += particle.vy;

         if (particle.x < 0 || particle.x > canvas.width) particle.vx *= -1;
         if (particle.y < 0 || particle.y > canvas.height) particle.vy *= -1;

         ctx.beginPath();
         ctx.arc(particle.x, particle.y, particle.radius, 0, Math.PI * 2);
         ctx.fillStyle = 'rgba(255, 255, 255, 0.5)';
         ctx.fill();
     });

     requestAnimationFrame(animateParticles);
 }

 // Initialize Mouse Trail
 const trail = [];
 const trailLength = 20;

 for (let i = 0; i < trailLength; i++) {
     const dot = document.createElement('div');
     dot.className = 'mouse-trail';
     document.body.appendChild(dot);
     trail.push(dot);
 }

 window.addEventListener('mousemove', e => {
     trail.forEach((dot, index) => {
         setTimeout(() => {
             dot.style.left = e.pageX + 'px';
             dot.style.top = e.pageY + 'px';
             dot.style.transform = `scale(${1 - index / trailLength})`;
         }, index * 50);
     });
 });

 // Initialize everything
 document.addEventListener('DOMContentLoaded', () => {
     initParticles();
     animateParticles();
     
     // Initialize AOS
     AOS.init({
         duration: 1000,
         once: true
     });

     // Initialize Custom Cursor
     new kursor({
         type: 1,
         removeDefaultCursor: true,
         color: '#4ECDC4'
     });

     // Scroll Progress
     window.addEventListener('scroll', () => {
         const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
         const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
         const scrolled = (winScroll / height) * 100;
         document.querySelector('.scroll-progress').style.width = scrolled + '%';
     });

     // Magnetic Button Effect
     document.querySelectorAll('.magnetic-button').forEach(button => {
         button.addEventListener('mousemove', e => {
             const position = button.getBoundingClientRect();
             const x = e.pageX - position.left - position.width / 2;
             const y = e.pageY - position.top - position.height / 2;
             
             button.style.transform = `translate(${x * 0.3}px, ${y * 0.5}px)`;
         });

         button.addEventListener('mouseout', () => {
             button.style.transform = 'translate(0px, 0px)';
         });
     });
 });


 document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('.glass-card');
    
    cards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / card.clientWidth) * 100;
            const y = ((e.clientY - rect.top) / card.clientHeight) * 100;
            
            card.style.setProperty('--mouse-x', `${x}%`);
            card.style.setProperty('--mouse-y', `${y}%`);
        });
    });
});
