<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="../images/asdasdasd123123123123123.jpg">
    <title>Holy Spirit School of Imus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://unpkg.com/kursor/dist/kursor.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Add Particle Background -->
    <!-- <canvas id="particle-canvas" class="particle-canvas"></canvas> -->

    <!-- Add Mouse Trail Elements  -->
    <!-- <div id="mouse-trail"></div>  -->

    <div class="scroll-progress"></div>
    
    <!-- New Custom Cursor -->
    <div class="custom-cursor"></div>

    <!-- New Floating Elements -->
    <div class="floating-elements">
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
    </div>
    <!-- <div id="particles-js"></div> -->

<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="../images/landscape_logo.svg" alt="Logo" style="height: 60px; margin-right: 10px;">
            Holy Spirit School of Imus
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                <li class="nav-item"><a class="nav-link" href="#parallax-cta">Application</a></li>
                <li class="nav-item"><a class="nav-link" href="#footer">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>

    <!-- Hero Section -->
    <header class="hero">
        <div class="container">
            <div class="hero-content" data-aos="fade-up">
                <h1>Empowering Future Leaders</h1>
                <p class="lead text-black mb-4">Transform your future with world-class education and innovative learning experiences.</p>
                <a href="#about" class="btn btn-primary btn-lg">Discover More</a>
            </div>
        </div>
    </header>

    

<section class="parallax-cta position-relative" id="about">
    <div class="parallax-overlay"></div>
    <div class="container py-5">
        <div class="glass-card" data-aos="fade-up">
            <div class="card-content">
                <!-- Mission Side -->
                <div class="content-side">
                    <div class="icon-wrapper">
                        <i class="fas fa-rocket"></i>
                        <div class="icon-glow"></div>
                    </div>
                    <h2>Our Mission</h2>
                    <p>To empower individuals through innovative education, fostering a dynamic learning environment that prepares students for the challenges of tomorrow.</p>
                </div>
                
                <div class="divider"></div>
                
                <!-- Vision Side -->
                <div class="content-side">
                    <div class="icon-wrapper">
                        <i class="fas fa-lightbulb"></i>
                        <div class="icon-glow"></div>
                    </div>
                    <h2>Our Vision</h2>
                    <p>To be the leading institution in transformative education, where technology and traditional learning methods converge to create exceptional learning experiences.</p>
                </div>
            </div>
        </div>
    </div>
</section>



    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up"><strong>Why Choose Holy Spirit?</strong></h2>
            <div class="row">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon">🎓</div>
                        <h4>Expert Faculty</h4>
                        <p>Learn from industry experts and experienced educators who are passionate about student success.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon">🏫</div>
                        <h4>Modern Campus</h4>
                        <p>Experience learning in state-of-the-art facilities designed for the future of education.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon">📚</div>
                        <h4>Innovation Focus</h4>
                        <p>Stay ahead with our forward-thinking curriculum and innovative teaching methods.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <!-- Parallax Call-to-Action Section -->
    <section class="parallax-cta" id="parallax-cta">
        <div class="parallax-overlay"></div>
        <div class="container">
            <div class="cta-content" data-aos="zoom-in">
                <h2 class="cta-title">Ready to Start Your Journey?</h2>
                <p class="cta-description">Join us and be part of our innovative learning community.</p>
                <a href="../employment_application_page.php" 
                class="cta-button" 
                target="_blank"
                aria-label="Apply Now">
                    Apply Now
                </a>
            </div>
        </div>
    </section>

   
<section id="hero2" class="hero-section2">
<div class="hero-content2">
    <!-- Mouse/Logo Image -->
    <img
      src="../images/LOGO123-modified.png"
      alt="Mouse"
      class="mouse-image2"
    />

    <!-- Hero Text -->
    <h1 class="hero-text2">
      <span class="text-red2">Holy Spirit</span>
      <span class="text-gray2">School of Imus Inc.</span>
    </h1>
 
    <div class="scroll-text address-text">
      <div class="scroll-box">
        <i class="fas fa-map-marker-alt icon"></i>
        Imus, Cavite
      </div>
      <hr>
      <p>Visit us at our main campus located in the heart of Imus.</p>
      <!-- OpenStreetMap Embed -->
      <iframe 
        src="https://www.openstreetmap.org/export/embed.html?bbox=120.9367%2C14.4192%2C120.9467%2C14.4292&layer=mapnik" 
        style="border: 1px solid black; width: 350px; height: 350px; border-radius: 10px; pointer-events: auto; z-index: 10;" 
        allowfullscreen 
        loading="lazy">
      </iframe>
    </div>

    <div class="scroll-text social-media-text">
      <div class="scroll-box">
        <i class="fab fa-facebook icon"></i>
        Follow Us on Facebook
      </div>
      <hr>
      <p>Stay updated with our latest news and events!</p>
      <!-- Facebook Embed -->
    <iframe 
      src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2FHolySpiritSchoolofImus&tabs=timeline&width=350&height=350&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId" 
      style="border: 1px solid black; width: 350px; height: 350px; border-radius: 10px; pointer-events: auto; z-index: 10;" 
      allowfullscreen 
      loading="lazy">
    </iframe>
    </div>
    </div>
  </section>
    

    <!-- New Timeline Section with Parallax -->
    <section class="parallax-cta timeline-section">
        <div class="parallax-overlay"></div>
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">Our Journey</h2>
            <div class="timeline">
                <div class="timeline-item left" data-aos="fade-right">
                    <div class="timeline-content" style="color: black;">
                        <h3>2010</h3>
                        <p>Founded with a vision to transform education</p>
                    </div>
                </div>
                <div class="timeline-item right" data-aos="fade-left">
                    <div class="timeline-content" style="color: black;">
                        <h3>2015</h3>
                        <p>Expanded to multiple campuses</p>
                    </div>
                </div>
                <div class="timeline-item left" data-aos="fade-right">
                    <div class="timeline-content" style="color: black;">
                        <h3>2020</h3>
                        <p>Launched online learning platform</p>
                    </div>
                </div>
                <div class="timeline-item right" data-aos="fade-left">
                    <div class="timeline-content" style="color: black;">
                        <h3>2025</h3>
                        <p>Future ready education hub</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- general footer-->
<footer class="footer" id="footer">
    <div class="footer-particles">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
    <div class="footer-container">
        <div class="footer-section">
        <h3>HSSI Inc.</h3>
        <p>Empowering students to achieve their dreams through quality education and innovation.</p>
        </div>
        <div class="footer-section">
        <h3>Quick Links</h3>
        <ul class="footer-links">
            <li><a href="#about">About Us</a></li>
            <li><a href="#features">Programs</a></li>
            <li><a href="#testimonials">Success Stories</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
        </div>
        <div class="footer-section">
        <h3>Contact Us</h3>
        <ul class="footer-links">
            <li>📍 123 Education Street</li>
            <li>📞 (555) 123-4567</li>
            <li>✉️ holy</li>
        </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2025 MySchool. All Rights Reserved.</p>
    </div>
</footer>

    <!-- Enhanced Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="script.js"></script>
    <script src="chatbot-responses.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
    <script src="https://unpkg.com/kursor"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fuse.js/6.6.2/fuse.min.js"></script>
    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script>
  document.addEventListener("DOMContentLoaded", () => {
    gsap.registerPlugin(ScrollTrigger);

    // Pin section and scrub
    ScrollTrigger.create({
      trigger: ".hero-section2",
      start: "top top",
      end: "+=2000",
      pin: true,
      scrub: true,
    });

    // Mouse spin
    gsap.to(".mouse-image2", {
      scrollTrigger: {
        trigger: ".hero-section2",
        start: "top top",
        end: "+=1200",
        scrub: 0.5,
      },
      rotationY: 360,
      ease: "none",
    });

    // Hero text moves left
    gsap.fromTo(
      ".hero-text2",
      { x: "100vw" },
      {
        scrollTrigger: {
          trigger: ".hero-section2",
          start: "top top",
          end: "+=1200",
          scrub: true,
        },
        x: "-200vw",
        ease: "none"
      }
    );

    // Show/hide address and date depending on scroll direction and progress
    ScrollTrigger.create({
      trigger: ".hero-section2",
      start: "top top",
      end: "+=1200",
      scrub: true,
      onUpdate: (self) => {
        // When scrolling down (direction > 0) and progress is nearly complete, show them.
        // Otherwise (including any upward scroll) hide them.
        if (self.direction > 0 && self.progress >= 0.99) {
          gsap.to(".address-text", {
            y: 0,
            opacity: 1,
            duration: 0.5,
            ease: "power2.out"
          });
          gsap.to(".social-media-text", {
            y: 0,
            opacity: 1,
            duration: 0.5,
            ease: "power2.out"
          });
        } else {
          gsap.to(".address-text", {
            y: -100,
            opacity: 0,
            duration: 0.3,
            ease: "power2.inOut"
          });
          gsap.to(".social-media-text", {
            y: 100,
            opacity: 0,
            duration: 0.3,
            ease: "power2.inOut"
          });
        }
      }
    });
  });
</script>

        <!-- Include Particles.js Script -->
<!-- <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<script>
    particlesJS("particles-js", {
        "particles": {
            "number": {
                "value": 80,
                "density": {
                    "enable": true,
                    "value_area": 800
                }
            },
            "color": {
                "value": "#ffffff"
            },
            "shape": {
                "type": "circle",
                "stroke": {
                    "width": 0,
                    "color": "#000000"
                },
                "polygon": {
                    "nb_sides": 5
                }
            },
            "opacity": {
                "value": 0.5,
                "random": false,
                "anim": {
                    "enable": false,
                    "speed": 1,
                    "opacity_min": 0.1,
                    "sync": false
                }
            },
            "size": {
                "value": 3,
                "random": true,
                "anim": {
                    "enable": false,
                    "speed": 40,
                    "size_min": 0.1,
                    "sync": false
                }
            },
            "line_linked": {
                "enable": true,
                "distance": 150,
                "color": "#ffffff",
                "opacity": 0.4,
                "width": 1
            },
            "move": {
                "enable": true,
                "speed": 6,
                "direction": "none",
                "random": false,
                "straight": false,
                "out_mode": "out",
                "bounce": false,
                "attract": {
                    "enable": false,
                    "rotateX": 600,
                    "rotateY": 1200
                }
            }
        },
        "interactivity": {
            "detect_on": "canvas",
            "events": {
                "onhover": {
                    "enable": true,
                    "mode": "repulse"
                },
                "onclick": {
                    "enable": true,
                    "mode": "push"
                },
                "resize": true
            },
            "modes": {
                "grab": {
                    "distance": 400,
                    "line_linked": {
                        "opacity": 1
                    }
                },
                "bubble": {
                    "distance": 400,
                    "size": 40,
                    "duration": 2,
                    "opacity": 8,
                    "speed": 3
                },
                "repulse": {
                    "distance": 200,
                    "duration": 0.4
                },
                "push": {
                    "particles_nb": 4
                },
                "remove": {
                    "particles_nb": 2
                }
            }
        },
        "retina_detect": true
    });
</script> -->
   <!-- Add this before closing body tag -->
   <div class="chatbot-container">
        <button class="chat-button" onclick="toggleChat()" aria-label="Toggle chat">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
        </button>

        <div class="chat-window" id="chatWindow">
            <div class="chat-header">
                <h3>HSSI Assistant</h3>
                <button class="close-chat" onclick="toggleChat()">×</button>
            </div>
            <div class="chat-messages" id="chatMessages">
                <div class="message bot-message">
                    Hello! 👋 I'm your HSSI virtual assistant. How can I help you today?
                </div>
                <div class="quick-suggestions">
                    <button class="suggestion-btn" onclick="sendSuggestion('Tell me about employment opportunities')">Employment Opportunities</button>
                    <button class="suggestion-btn" onclick="sendSuggestion('How can I enroll?')">Enrollment Process</button>
                    <button class="suggestion-btn" onclick="sendSuggestion('How do I apply?')">How to Apply</button>
                </div>
                <div class="typing-indicator" id="typingIndicator">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
            <div class="chat-input">
                <input type="text" id="userInput" placeholder="Type your message..." onkeypress="handleKeyPress(event)">
                <button onclick="sendMessage()">Send</button>
            </div>
        </div>
    </div>

    <script>
let isOpen = false; // Fixed undeclared variable

function toggleChat() {
    const chatWindow = document.getElementById('chatWindow');
    isOpen = !isOpen;
    chatWindow.style.display = isOpen ? 'flex' : 'none';
}

function handleKeyPress(event) {
    if (event.key === 'Enter') {
        sendMessage();
    }
}

function sendMessage() {
    const input = document.getElementById('userInput');
    const message = input.value.trim();

    if (message) {
        addMessage(message, 'user-message');
        input.value = '';
        showTypingIndicator();

        // Simulate AI processing
        setTimeout(() => {
            hideTypingIndicator();
            const response = getEnhancedResponse(message.toLowerCase()); // Direct function call
            addMessage(response, 'bot-message');
        }, 1000);
    }
}

function showTypingIndicator() {
    document.getElementById('typingIndicator').style.display = 'block';
}

function hideTypingIndicator() {
    document.getElementById('typingIndicator').style.display = 'none';
}

function addMessage(text, className) {
    const messages = document.getElementById('chatMessages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${className}`;
    messageDiv.textContent = text;
    messages.appendChild(messageDiv);
    messages.scrollTop = messages.scrollHeight;
}

function getRandomResponse(responses) {
    return responses[Math.floor(Math.random() * responses.length)];
}

// Levenshtein Distance Function
function levenshteinDistance(a, b) {
    const dp = Array(a.length + 1)
        .fill(null)
        .map(() => Array(b.length + 1).fill(null));

    for (let i = 0; i <= a.length; i++) dp[i][0] = i;
    for (let j = 0; j <= b.length; j++) dp[0][j] = j;

    for (let i = 1; i <= a.length; i++) {
        for (let j = 1; j <= b.length; j++) {
            const cost = a[i - 1] === b[j - 1] ? 0 : 1;
            dp[i][j] = Math.min(
                dp[i - 1][j] + 1, // Deletion
                dp[i][j - 1] + 1, // Insertion
                dp[i - 1][j - 1] + cost // Substitution
            );
        }
    }

    return dp[a.length][b.length];
}

// Function to find the closest matching response
function findBestMatch(input, responses) {
    let bestMatch = null;
    let lowestDistance = Infinity;
    const threshold = 3; // Allow minor typos

    for (const key of Object.keys(responses)) {
        const distance = levenshteinDistance(input, key);
        if (distance < lowestDistance && distance <= threshold) {
            lowestDistance = distance;
            bestMatch = key;
        }
    }

    return bestMatch;
}

function sendSuggestion(text) {
        document.getElementById('userInput').value = text;
        sendMessage();
    }
</script>
</body>
</html>