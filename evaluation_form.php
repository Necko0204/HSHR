<?php
function createRatingInput($name, $criterion) {
    return <<<HTML
    <tr class="hover:bg-purple-50 transition-colors">
        <td class="p-4">$criterion</td>
        <td class="p-4">
            <div class="flex items-center justify-center">
                <div class="rating-group" data-name="$name">
                    <div class="score-display">Score: <span class="current-score">0</span>/10</div>
                    <input type="hidden" name="$name" value="0">
                    <div class="flex flex-row-reverse justify-end gap-1">
                        <span class="rating__star" data-value="10" data-color="#FF4500">★</span>
                        <span class="rating__star" data-value="8" data-color="#FF6347">★</span>
                        <span class="rating__star" data-value="6" data-color="#FF8C00">★</span>
                        <span class="rating__star" data-value="4" data-color="#FFA500">★</span>
                        <span class="rating__star" data-value="2" data-color="#FFD700">★</span>
                    </div>
                </div>
                <div class="emoji-feedback ml-4">😶</div>
            </div>
        </td>
    </tr>
    HTML;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/asdasdasd123123123123123.jpg">
    <title>Teacher Evaluation Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
.rating__star {
        font-size: 2em;
        color: #ddd;
        cursor: pointer;
        transition: all 0.3s ease-in-out;
        padding: 0 5px;
        position: relative;
    }

    .rating__star::before {
        content: '★';
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        opacity: 0;
        transition: all 0.3s ease;
        filter: blur(4px);
    }

    .rating__star.active {
        animation: starPulse 1.5s ease-in-out infinite;
    }

    .rating__star.active::before {
        opacity: 0.6;
        animation: starGlow 1.5s ease-in-out infinite;
    }

    @keyframes starPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    @keyframes starGlow {
        0%, 100% { 
            transform: translate(-50%, -50%) scale(1.2);
            filter: blur(4px);
        }
        50% { 
            transform: translate(-50%, -50%) scale(1.5);
            filter: blur(6px);
        }
    }

    .rating-group[data-rating="10"] .rating__star.active { color: #FFD700; text-shadow: 0 0 20px #FFD700; }
    .rating-group[data-rating="8"] .rating__star.active { color: #FFA500; text-shadow: 0 0 20px #FFA500; }
    .rating-group[data-rating="6"] .rating__star.active { color: #FF8C00; text-shadow: 0 0 20px #FF8C00; }
    .rating-group[data-rating="4"] .rating__star.active { color: #FF6347; text-shadow: 0 0 20px #FF6347; }
    .rating-group[data-rating="2"] .rating__star.active { color: #FF4500; text-shadow: 0 0 20px #FF4500; }

        .score-display {
            position: absolute;
            top: -25px;
            left: 50%;
            transform: translateX(-50%);
            background: #a855f7;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .rating-group:hover .score-display {
            opacity: 1;
        }

        .emoji-feedback {
            font-size: 1.5em;
            transition: all 0.3s ease;
        }

        .progress-container {
            width: 100%;
            height: 6px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            margin: 20px 0;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(to right, #ffd700, #a855f7);
            width: 0%;
            transition: width 0.5s ease;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(168, 85, 247, 0.5);
        }

        .section-header {
            background: linear-gradient(to right, #a855f7, #ec4899);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-weight: bold;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-purple-500 via-pink-500 to-red-500 p-4 md:p-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl p-6 md:p-8">
            <h1 class="text-3xl font-bold text-center mb-8 section-header">
                Teacher Evaluation Form
            </h1>

            <div class="progress-container">
                <div class="progress-bar"></div>
            </div>

            <form id="evaluationForm" class="space-y-8">
                <!-- Teaching Competency Section -->
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold section-header">
                        I. Teaching Competency (60%)
                    </h2>
                    <table class="w-full">
                        <tbody>
                            <?php
                            $teachingCriteria = [
                                "Selects content and prepares appropriate instructional materials/teaching aid",
                                "Selects teaching methods / strategies",
                                "Relates new lessons with previous knowledge skills",
                                "Provides appropriate motivation",
                                "Presents and develops lessons",
                                "Conveys ideas properly",
                                "Utilizes the art of questioning to develop higher level of thinking",
                                "Ensures students participation",
                                "Addresses individual differences",
                                "Shows mastery of the subject matter"
                            ];

                            foreach ($teachingCriteria as $index => $criterion) {
                                echo createRatingInput("teaching_$index", $criterion);
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Professional Characteristics Section -->
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold section-header">
                        II. Professional and Personal Characteristics (30%)
                    </h2>
                    <table class="w-full">
                        <tbody>
                            <?php
                            $professionalCriteria = [
                                "Submits works on time",
                                "Accepts task willingly",
                                "Follows instructions promptly",
                                "Cooperates with co-worker",
                                "Follows rules and regulations in the workplace",
                                "Proper attire/Good grooming",
                                "Initiative/Resourcefulness",
                                "Ensures students participation",
                                "Dedication/commitment",
                                "Fairness/Justice"
                            ];

                            foreach ($professionalCriteria as $index => $criterion) {
                                echo createRatingInput("professional_$index", $criterion);
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Attendance Section -->
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold section-header">
                        III. Punctuality and Attendance (10%)
                    </h2>
                    <table class="w-full">
                        <tbody>
                            <?php
                            $attendanceCriteria = [
                                "Punctuality – number of times tardy during the rating period",
                                "Attendance – number of days during the rating period"
                            ];

                            foreach ($attendanceCriteria as $index => $criterion) {
                                echo createRatingInput("attendance_$index", $criterion);
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Remarks Section -->
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold section-header">Remarks</h2>
                    <textarea 
                        name="remarks"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
                        rows="4"
                        placeholder="Enter your remarks here..."></textarea>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" 
                            class="inline-flex items-center px-8 py-3 rounded-full bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold shadow-lg transform transition-all hover:-translate-y-1 hover:shadow-xl">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Submit Evaluation
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const ratingGroups = document.querySelectorAll('.rating-group');
        const totalQuestions = ratingGroups.length;
        let answeredQuestions = 0;

        ratingGroups.forEach(group => {
            const stars = group.querySelectorAll('.rating__star');
            const hiddenInput = group.querySelector('input[type="hidden"]');
            const scoreDisplay = group.querySelector('.current-score');
            const emojiContainer = group.closest('tr').querySelector('.emoji-feedback');

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const value = this.dataset.value;
                    hiddenInput.value = value;
                    scoreDisplay.textContent = value;
                    group.style.setProperty('--rating', stars.length - Array.from(stars).indexOf(this));
                    
                    updateEmoji(value, emojiContainer);
                    if (hiddenInput.value === "0") answeredQuestions++;
                    updateProgress();
                    createCelebrationEffect(group);
                });
            });
        });

        function updateEmoji(value, container) {
            const emojis = {
                '10': '🌟',
                '8': '😊',
                '6': '😐',
                '4': '😕',
                '2': '😢'
            };
            container.textContent = emojis[value] || '😶';
            container.style.transform = 'scale(1.3)';
            setTimeout(() => container.style.transform = 'scale(1)', 300);
        }

        function updateProgress() {
            const progress = (answeredQuestions / totalQuestions) * 100;
            document.querySelector('.progress-bar').style.width = `${progress}%`;
        }

        function createCelebrationEffect(element) {
            const colors = ['#FFD700', '#FF69B4', '#4B0082', '#00FF00'];
            for (let i = 0; i < 20; i++) {
                const particle = document.createElement('div');
                particle.style.cssText = `
                    position: absolute;
                    width: 8px;
                    height: 8px;
                    background: ${colors[Math.floor(Math.random() * colors.length)]};
                    border-radius: 50%;
                    pointer-events: none;
                    z-index: 1000;
                `;
                document.body.appendChild(particle);

                const rect = element.getBoundingClientRect();
                const startX = rect.left + rect.width / 2;
                const startY = rect.top + rect.height / 2;
                
                const angle = Math.random() * Math.PI * 2;
                const velocity = 1 + Math.random() * 2;
                const destinationX = startX + Math.cos(angle) * 100;
                const destinationY = startY + Math.sin(angle) * 100;

                particle.style.left = `${startX}px`;
                particle.style.top = `${startY}px`;

                particle.animate([
                    { transform: 'translate(0, 0) scale(1)', opacity: 1 },
                    { transform: `translate(${destinationX - startX}px, ${destinationY - startY}px) scale(0)`, opacity: 0 }
                ], {
                    duration: 1000,
                    easing: 'cubic-bezier(0.4, 0, 0.2, 1)',
                    fill: 'forwards'
                }).onfinish = () => particle.remove();
            }
        }

        document.getElementById('evaluationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            btn.innerHTML = '<i class="fas fa-check mr-2"></i>Thank You!';
            btn.classList.add('bg-green-500');
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Submit Evaluation';
                btn.classList.remove('bg-green-500');
            }, 3000);
        });
    });
    document.querySelectorAll('.rating__star').forEach(star => {
        star.addEventListener('click', function() {
            const group = this.closest('.rating-group');
            const value = this.dataset.value;
            const color = this.dataset.color;
            
            // Remove active class from all stars
            group.querySelectorAll('.rating__star').forEach(s => s.classList.remove('active'));
            
            // Add active class to selected stars
            let current = this;
            while (current) {
                current.classList.add('active');
                current = current.nextElementSibling;
            }
            
            // Set the rating value as a data attribute for CSS targeting
            group.dataset.rating = value;
            
            // Apply color-specific glow effect
            group.querySelectorAll('.rating__star.active').forEach(activeStar => {
                activeStar.style.setProperty('--star-color', color);
            });
        });
    });
    </script>
</body>
</html>
</qodoArtifact>

