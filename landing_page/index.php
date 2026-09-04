<?php
declare(strict_types=1);

$currentYear = date('Y');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Holy Spirit School of Imus — a learning community committed to faith, character, and academic excellence.">
    <meta name="theme-color" content="#8f1838">
    <title>Holy Spirit School of Imus</title>
    <link rel="icon" href="../images/LOGO123-modified.png" type="image/png">
    <link rel="stylesheet" href="style.css?v=<?= rawurlencode((string) filemtime(__DIR__ . '/style.css')) ?>">
    <script src="script.js?v=<?= rawurlencode((string) filemtime(__DIR__ . '/script.js')) ?>" defer></script>
</head>
<body>
    <a class="skip-link" href="#main-content">Skip to content</a>

    <header class="site-header" data-header>
        <div class="header-shell">
            <a class="brand" href="#home" aria-label="Holy Spirit School of Imus home">
                <img src="../images/LOGO123-modified.png" alt="" width="52" height="52">
                <span>
                    <strong>Holy Spirit School</strong>
                    <small>of Imus, Inc.</small>
                </span>
            </a>

            <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-navigation" data-menu-toggle>
                <span></span><span></span><span></span>
                <span class="sr-only">Open navigation</span>
            </button>

            <nav class="primary-navigation" id="primary-navigation" aria-label="Primary navigation" data-navigation>
                <a href="#about">About</a>
                <a href="#experience">Why HSSI</a>
                <a href="#journey">Our identity</a>
                <a href="#contact">Contact</a>
            </nav>
        </div>
    </header>

    <main id="main-content">
        <section class="hero" id="home">
            <div class="hero-glow hero-glow-one" aria-hidden="true"></div>
            <div class="hero-glow hero-glow-two" aria-hidden="true"></div>
            <div class="hero-shell">
                <div class="hero-copy reveal">
                    <p class="eyebrow"><span></span> Nurturing minds. Forming character.</p>
                    <h1>A brighter future begins with <em>purpose.</em></h1>
                    <p class="hero-lead">Holy Spirit School of Imus brings together academic excellence, values-centered formation, and a caring community where every learner can thrive.</p>
                    <div class="hero-actions">
                        <a class="button button-primary" href="../employment_application_page.php">Start an application <span aria-hidden="true">→</span></a>
                        <a class="button button-quiet" href="#about">Discover our school</a>
                    </div>
                    <div class="hero-proof" aria-label="School highlights">
                        <div><strong>2000</strong><span>Established</span></div>
                        <div><strong>Imus</strong><span>Proudly local</span></div>
                        <div><strong>Whole child</strong><span>Always the focus</span></div>
                    </div>
                </div>

                <div class="hero-visual reveal" aria-label="Holy Spirit School of Imus crest">
                    <div class="visual-orbit orbit-one" aria-hidden="true"></div>
                    <div class="visual-orbit orbit-two" aria-hidden="true"></div>
                    <div class="crest-card">
                        <div class="crest-halo" aria-hidden="true"></div>
                        <img src="../images/LOGO123-modified.png" alt="Holy Spirit School of Imus crest" width="620" height="620">
                    </div>
                    <div class="visual-note note-top"><span>✦</span> Faith-led learning</div>
                    <div class="visual-note note-bottom"><span>✓</span> Student-centered</div>
                </div>
            </div>
            <a class="scroll-cue" href="#about"><span>Scroll to explore</span><i aria-hidden="true"></i></a>
        </section>

        <section class="intro section-shell" id="about">
            <div class="section-heading reveal">
                <p class="eyebrow"><span></span> Who we are</p>
                <h2>Education that reaches beyond the classroom.</h2>
            </div>
            <div class="intro-grid">
                <article class="statement-card statement-card-dark reveal">
                    <span class="card-number">01</span>
                    <div>
                        <p class="card-kicker">Our mission</p>
                        <h3>Empower learners to lead with knowledge, compassion, and integrity.</h3>
                        <p>We create meaningful learning experiences that develop capable, grounded, and socially responsible individuals.</p>
                    </div>
                </article>
                <article class="statement-card reveal">
                    <span class="card-number">02</span>
                    <div>
                        <p class="card-kicker">Our vision</p>
                        <h3>A trusted community where potential becomes purpose.</h3>
                        <p>We envision graduates who think deeply, act courageously, and contribute positively wherever life takes them.</p>
                    </div>
                </article>
            </div>
        </section>

        <section class="experience" id="experience">
            <div class="section-shell">
                <div class="experience-heading reveal">
                    <div>
                        <p class="eyebrow eyebrow-light"><span></span> The HSSI experience</p>
                        <h2>Built for every dimension of growth.</h2>
                    </div>
                    <p>Strong foundations matter. Our school experience connects rigorous learning with confidence, values, and real belonging.</p>
                </div>

                <div class="feature-grid">
                    <article class="feature-card reveal">
                        <span class="feature-icon" aria-hidden="true">01</span>
                        <h3>Academic confidence</h3>
                        <p>Thoughtful instruction and supportive guidance help learners master fundamentals and stay curious.</p>
                    </article>
                    <article class="feature-card reveal">
                        <span class="feature-icon" aria-hidden="true">02</span>
                        <h3>Values in action</h3>
                        <p>Faith, discipline, service, and respect are lived every day—not simply written on a wall.</p>
                    </article>
                    <article class="feature-card reveal">
                        <span class="feature-icon" aria-hidden="true">03</span>
                        <h3>A caring community</h3>
                        <p>Students grow best when they feel known. We build meaningful partnerships among home, school, and community.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="logo-story" id="journey" aria-labelledby="journey-title" data-logo-story>
            <div class="logo-stage">
                <div class="story-progress" aria-hidden="true"><span data-story-progress></span></div>
                <div class="story-grid">
                    <div class="story-copy" data-story-copy>
                        <p class="eyebrow"><span></span> Our identity</p>
                        <div class="story-panel is-active" data-story-panel="0">
                            <p class="story-step">01 / 03</p>
                            <h2 id="journey-title">Rooted in faith.</h2>
                            <p>Our crest is more than an emblem. It reflects a community guided by faith, dignity, and love of learning.</p>
                        </div>
                        <div class="story-panel" data-story-panel="1">
                            <p class="story-step">02 / 03</p>
                            <h2>Growing in wisdom.</h2>
                            <p>Knowledge opens doors. Character gives students the courage and judgment to walk through them well.</p>
                        </div>
                        <div class="story-panel" data-story-panel="2">
                            <p class="story-step">03 / 03</p>
                            <h2>Ready to serve.</h2>
                            <p>We prepare young people to use their gifts generously and help shape a more hopeful world.</p>
                        </div>
                    </div>

                    <div class="story-mark" aria-hidden="true">
                        <div class="mark-ring mark-ring-outer"></div>
                        <div class="mark-ring mark-ring-inner"></div>
                        <div class="mark-glow"></div>
                        <img src="../images/LOGO123-modified.png" alt="" width="700" height="700" data-story-logo>
                        <span class="mark-label mark-label-top">Holy Spirit</span>
                        <span class="mark-label mark-label-bottom">Imus · Philippines</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="pathways section-shell">
            <div class="pathways-card reveal">
                <div>
                    <p class="eyebrow eyebrow-light"><span></span> Take the next step</p>
                    <h2>Your place in our community starts here.</h2>
                    <p>Explore opportunities to join Holy Spirit School of Imus and help us shape meaningful learning experiences.</p>
                </div>
                <div class="pathway-actions">
                    <a class="button button-white" href="../employment_application_page.php">Apply with us <span aria-hidden="true">→</span></a>
                    <a class="text-link" href="#contact">Get in touch</a>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer" id="contact">
        <div class="footer-shell">
            <div class="footer-brand">
                <img src="../images/LOGO123-modified.png" alt="" width="58" height="58">
                <div><strong>Holy Spirit School of Imus, Inc.</strong><span>Learning with faith. Leading with purpose.</span></div>
            </div>
            <div class="footer-column">
                <p>Visit</p>
                <span>Imus, Cavite, Philippines</span>
            </div>
            <div class="footer-column">
                <p>Portals</p>
                <a href="../index.php">Administrator login</a>
                <a href="../staff_side/">Staff login</a>
            </div>
            <div class="footer-column">
                <p>Connect</p>
                <a href="https://www.facebook.com/HolySpiritSchoolofImus" target="_blank" rel="noopener noreferrer">Facebook <span aria-hidden="true">↗</span></a>
                <a href="../employment_application_page.php">Applications</a>
            </div>
        </div>
        <div class="footer-bottom">
            <span>© <?= htmlspecialchars($currentYear, ENT_QUOTES, 'UTF-8') ?> Holy Spirit School of Imus, Inc.</span>
            <a href="#home">Back to top ↑</a>
        </div>
    </footer>
</body>
</html>
