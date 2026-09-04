<?php
require_once __DIR__ . '/includes/admin_page.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

include 'includes/breadcrumb.php';
include 'helper.php';
include 'db_config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/asdasdasd123123123123123.jpg">
    <title>Holy Spirit Human Resource</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="admin-profile-page">
    <!-- Sidebar & Navbar in a separate container -->
    <div class="main-container">
        <?php include 'sidebar.php'; ?>
    </div>
        <div class="content-container">
            <?php include 'nav_header.php'; ?>
    </div>
    <main class="wrapper">
                <?php
                echo generateBreadcrumb();
                ?>
            <div class="profile-card text-center p-4 shadow-lg rounded border border-secondary" id="profileCard">
                    <div class="stars">
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                    </div>

                    <div class="sun-container">
                        <div class="sun theme-icon" id="sun-icon">☀️</div>
                    </div>

                    <div class="moon-container">
                        <div class="moon theme-icon" id="moon-icon">🌙</div>
                    </div>

                    <div class="cloud-container">
                        <div class="cloud">☁️</div>
                        <div class="cloud">☁️</div>
                        <div class="cloud">☁️</div>
                    </div>


                    <div class="flowers">
                        <div class="flower"></div>
                        <div class="flower"></div>
                        <div class="flower"></div>
                        <div class="flower"></div>
                        <div class="flower"></div>
                        <div class="flower"></div>
                        <div class="flower"></div>
                        <div class="flower"></div>
                        <div class="flower"></div>
                    </div>
        <div class="profile-header">
            <div class="profile-image-container2">
                <img src="<?= htmlspecialchars(hshr_profile_picture_url($userData['profile_picture'] ?? null), ENT_QUOTES, 'UTF-8') ?>"
                    alt="Profile Picture"
                    class="profile-picture2" onerror="this.onerror=null;this.src='images/image-not-found.jpg'">
            </div>
            <h3 style="margin: 20px 0;"><?php echo htmlspecialchars($userData['name']); ?> </h3>
        </div>
            <div class="profile-details-container">
                <div class="profile-detail full-width">
                    <strong style="display: block; text-align: center;">Bio</strong>
                    <p style="text-align: justify;"><?php echo htmlspecialchars($userData['bio']); ?></p>
                </div>
                <div class="profile-detail full-width">
                    <strong>Experience</strong>
                    <p><?php echo htmlspecialchars($userData['experiences'] ?? 'Not provided'); ?></p>
                </div>
                <div class="profile-detail">
                    <strong>Position</strong>
                    <span><?php echo htmlspecialchars($userData['position']); ?></span>
                </div>
                <div class="profile-detail">
                    <strong>Username</strong>
                    <span><?php echo htmlspecialchars($userData['username']); ?></span>
                </div>
                <div class="profile-detail">
                    <strong>Age</strong>
                    <span><?php echo htmlspecialchars($userData['age']); ?></span>
                </div>
                <div class="profile-detail">
                    <strong>Email</strong>
                    <span><?php echo htmlspecialchars($userData['email']); ?></span>
                </div>
                <div class="profile-detail">
                    <strong>Phone</strong>
                    <span><?php echo htmlspecialchars($userData['phone'] ?? 'N/A'); ?></span>
                </div>
                <div class="profile-detail">
                    <strong>Address</strong>
                    <span><?php echo htmlspecialchars($userData['address'] ?? 'N/A'); ?></span>
                </div>
            </div>
            <button class="btn2" id="editProfileBtn">Update Profile</button>
        </div>

    <div id="updateProfileCard" class="hidden">
        <form action="update_profile.php" method="POST" enctype="multipart/form-data">
                <div class="sun-container">
                        <div class="sun theme-icon" id="sun-icon">☀️</div>
                    </div>

                    <div class="moon-container">
                        <div class="moon theme-icon" id="moon-icon">🌙</div>
                    </div>

                       <div class="stars">
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                        <div class="star">⭐</div>
                    </div>
                       <div class="flowers">
                        <div class="flower"></div>
                        <div class="flower"></div>
                        <div class="flower"></div>
                        <div class="flower"></div>
                        <div class="flower"></div>
                        <div class="flower"></div>
                        <div class="flower"></div>
                        <div class="flower"></div>
                        <div class="flower"></div>
                    </div>

                    <div class="cloud-container">
                        <div class="cloud">☁️</div>
                        <div class="cloud">☁️</div>
                        <div class="cloud">☁️</div>
                        <div class="cloud">☁️</div>
                        <div class="cloud">☁️</div>
                        <div class="cloud">☁️</div>
                        <div class="cloud">☁️</div>
                    </div>

                    <!-- <div class="basketballs">
                        <div class="basketball"></div>
                        <div class="basketball"></div>
                        <div class="basketball"></div>
                        <div class="basketball"></div>
                        <div class="basketball"></div>
                        <div class="basketball"></div>
                        <div class="basketball"></div>
                        <div class="basketball"></div>
                        <div class="basketball"></div>
                    </div> -->
        <h3>EDIT PROFILE</h3>
            <input type="hidden" name="id" value="<?php echo $userData['id']; ?>">

            <!-- Profile Picture Section -->
            <div class="profile-picture-container2">
                <div class="profile-picture2">
                    <img id="profilePreview" src="<?= htmlspecialchars(hshr_profile_picture_url($userData['profile_picture'] ?? null), ENT_QUOTES, 'UTF-8') ?>" alt="Profile Picture" onerror="this.onerror=null;this.src='images/image-not-found.jpg'">
                    <input type="file" id="profilePicInput" name="profile_picture" accept="image/*" style="display: none;">
                </div>
                <label class="form-label">Profile Picture</label>
            </div>

            <!-- Form Fields Section -->
            <div class="form-container">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($userData['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Position</label>
                    <input type="text" class="form-control" name="position" value="<?php echo htmlspecialchars($userData['position']); ?>" required readonly>
                </div>

                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($userData['username']); ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Age</label>
                    <input type="number" class="form-control" name="age" value="<?php echo htmlspecialchars($userData['age']); ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Bio</label>
                    <textarea class="form-control" name="bio" required><?php echo htmlspecialchars($userData['bio']); ?></textarea>
                </div>

                <!-- New Fields -->
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($userData['phone']); ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Experiences</label>
                    <textarea class="form-control" name="experiences" required><?php echo htmlspecialchars($userData['experiences']); ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Address</label>
                    <textarea class="form-control" name="address" required><?php echo htmlspecialchars($userData['address']); ?></textarea>
                </div>
            </div>
            <!-- Buttons Section -->
            <div class="btn2-container">
                <button type="button" class="btn2 btn-secondary" id="cancelEdit">Cancel</button>
                <button type="submit" class="btn2 btn-success">Save Changes</button>
            </div>
        </form>
    </div>

    </main>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const profileCard = document.getElementById('profileCard');
    const updateProfileCard = document.getElementById('updateProfileCard');
    const editProfileButton = document.getElementById('editProfileBtn');
    const cancelEditButton = document.getElementById('cancelEdit');
    const profilePreview = document.getElementById('profilePreview');
    const profileInput = document.getElementById('profilePicInput');
    const form = updateProfileCard?.querySelector('form');

    function showStatus(message, type) {
        let status = document.getElementById('profileStatus');
        if (!status) {
            status = document.createElement('div');
            status.id = 'profileStatus';
            status.setAttribute('role', 'status');
            status.setAttribute('aria-live', 'polite');
            document.body.appendChild(status);
        }

        status.className = `profile-status ${type}`;
        status.textContent = message;
        status.hidden = false;
        window.clearTimeout(showStatus.timeoutId);
        showStatus.timeoutId = window.setTimeout(() => {
            status.hidden = true;
        }, 2500);
    }

    editProfileButton?.addEventListener('click', () => {
        profileCard?.classList.add('hidden');
        updateProfileCard?.classList.remove('hidden');
        updateProfileCard?.classList.add('show');
    });

    cancelEditButton?.addEventListener('click', () => {
        updateProfileCard?.classList.add('hidden');
        updateProfileCard?.classList.remove('show');
        profileCard?.classList.remove('hidden');
    });

    profilePreview?.addEventListener('click', () => profileInput?.click());

    profileInput?.addEventListener('change', (event) => {
        const file = event.target.files?.[0];
        if (!file) return;

        const reader = new FileReader();
        reader.addEventListener('load', (loadEvent) => {
            profilePreview.src = loadEvent.target.result;
        });
        reader.readAsDataURL(file);
    });

    form?.addEventListener('submit', async (event) => {
        event.preventDefault();
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton?.setAttribute('disabled', 'disabled');

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form)
            });
            const data = await response.json();

            if (!response.ok || data.status !== 'success') {
                throw new Error(data.message || 'Unable to update your profile.');
            }

            showStatus(data.message, 'success');
            window.setTimeout(() => window.location.reload(), 700);
        } catch (error) {
            showStatus(error.message || 'An error occurred while updating your profile.', 'error');
            submitButton?.removeAttribute('disabled');
        }
    });
});

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
