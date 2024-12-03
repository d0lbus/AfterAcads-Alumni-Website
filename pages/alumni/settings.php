<?php
include '../../config/alumni/header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Settings</title>
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" />
    <link rel="stylesheet" href="../../style/alumni/settings.css" />
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-flex">
                <div class="brand-icon">
                    <a href="javascript:void(0)" id="sidebarToggle">
                        <span><img src="../../assets/bars1.png" width="24px" alt="bars" /></span>
                    </a>
                </div>
            </div>
        </div>
        <div class="sidebar-content">
            <div class="sidebar-user">
                <a href="../../pages/alumni/viewProfile.php">
                    <img src="../../assets/profileIcon.jpg" alt="Profile Picture" />
                </a>
                <div>
                    <h3><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h3>
                    <span><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
            </div>

            <div class="sidebar-menu">
            <ul>
          <li><a href="../../pages/alumni/shareExperience.php"><span><img src="../../assets/home1.png" width="20px" alt="Home" /></span>Home</a></li>
          <li><a href="../../pages/alumni/events.php"><span><img src="../../assets/event1.png" width="20px" alt="Events" /></span>Events</a></li>
          <li><a href="../../pages/alumni/opportunities.php"><span><img src="../../assets/opportunities.png" width="20px" alt="Opportunities" /></span>Opportunities</a></li>
          <li><a href="../../pages/alumni/notifications.php"><span><img src="../../assets/notification-removebg-preview.png" width="20px" alt="Notifications" /></span>Notifications</a></li>
          <li><a href="../../pages/alumni/settings.php"><span><img src="../../assets/setting1.png" width="20px" alt="Settings" /></span>Settings</a></li>
          <li>
            <form action="../../config/general/logout.php" method="post" onclick="confirmLogout()" style="margin: 0;">
                <button type="submit" class="logout-button">
                    <span>
                        <img src="../../assets/logout1.png" width="20px" alt="Logout" />
                    </span>Logout
                </button>
            </form>
          </li>
        </ul>
            </div>
        </div>
    </div>

    <div class="main-content">
        <header>
            <a href="../../pages/alumni/shareExperience.php">
                <img src="../../assets/logo.png" alt="logo" class="logo-header">
            </a>
        </header>
        <main>
            <h1>Settings</h1>
            <small>Update your account settings below</small>

            <!-- Settings Form -->
            <form id="settingsForm" class="settings-form" method="POST" action="../../config/alumni/update_settings_backend.php" enctype="multipart/form-data">
                
                <!-- Profile Picture -->
                <div class="form-group profile-picture-group">
                    <h2>Profile Picture</h2>
                    <div class="profile-picture-preview">
                        <img id="profile-picture-preview" src="../../assets/profileIcon.jpg" alt="Profile Picture">
                    </div>
                    <label for="profile-picture">Upload New Profile Picture</label>
                    <input type="file" name="profile-picture" id="profile-picture" accept="image/jpeg, image/png" onchange="previewImage(event)">
                    <small>Supported formats: JPG, PNG. Max size: 2MB.</small>
                </div>

                <!-- Name Fields -->
                <h2>Profile Settings</h2>
                <div class="form-group">
                    <label for="first-name">First Name</label>
                    <input type="text" id="first-name" name="first-name" required>
                </div>
                <div class="form-group">
                    <label for="middle-name">Middle Name</label>
                    <input type="text" id="middle-name" name="middle-name">
                </div>
                <div class="form-group">
                    <label for="last-name">Last Name</label>
                    <input type="text" id="last-name" name="last-name" required>
                </div>

                <!-- School and Course -->
                <h2>School and Course</h2>
                <div class="form-group">
                    <label for="school">School</label>
                    <select id="school" name="school" required>
                        <option value="">Select School</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="course">Course</label>
                    <select id="course" name="course" disabled required>
                        <option value="">Select Course</option>
                    </select>
                </div>

                <!-- Batch -->
                <div class="form-group">
                    <label for="batch">Batch</label>
                    <input type="text" id="batch" name="batch" placeholder="Type your batch (e.g., 223)" list="batch-list">
                    <datalist id="batch-list"></datalist>
                </div>

                <!-- Change Password -->
                <h2>Change Password</h2>
                <div class="form-group">
                    <label for="old-password">Old Password</label>
                    <input type="password" id="old-password" name="old-password">
                </div>
                <div class="form-group">
                    <label for="new-password">New Password</label>
                    <input type="password" id="new-password" name="new-password">
                </div>
                <div class="form-group">
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" id="confirm-password" name="confirm-password">
                </div>

                <!-- Submit -->
                <div class="button-container">
                    <button type="submit" class="button">Save Changes</button>
                </div>
            </form>
        </main>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sidebar = document.querySelector(".sidebar");
            const toggleButton = document.getElementById("sidebarToggle");

            toggleButton.addEventListener("click", function() {
                sidebar.classList.toggle("minimized");
            });
        });

       // Fetch settings options
       fetch('../../config/alumni/fetchSettingsOptions.php')
            .then(response => response.json())
            .then(data => {
                // Populate user fields
                const user = data.user;
                document.getElementById('profile-picture-preview').src = user.profile_picture || '../../assets/profileIcon.jpg';
                document.getElementById('first-name').value = user.first_name || '';
                document.getElementById('middle-name').value = user.middle_name || '';
                document.getElementById('last-name').value = user.last_name || '';

                // Populate school dropdown
                const schoolDropdown = document.getElementById('school');
                data.schools.forEach(school => {
                    const option = document.createElement('option');
                    option.value = school.id;
                    option.textContent = school.name;
                    if (user.school_id == school.id) option.selected = true;
                    schoolDropdown.appendChild(option);
                });

                // Populate course dropdown
                const courseDropdown = document.getElementById('course');
                data.courses.forEach(course => {
                    const option = document.createElement('option');
                    option.value = course.id;
                    option.textContent = course.name;
                    if (user.course_id == course.id) option.selected = true;
                    courseDropdown.appendChild(option);
                });

                // Populate batch suggestions
                const batchList = document.getElementById('batch-list');
                data.batches.forEach(batch => {
                    const option = document.createElement('option');
                    option.value = batch.batch_number;
                    batchList.appendChild(option);
                });
            });

        // Enable course dropdown based on school selection
        document.getElementById('school').addEventListener('change', function () {
            document.getElementById('course').disabled = !this.value;
        });

        // Preview profile picture
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = () => {
                document.getElementById('profile-picture-preview').src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        function confirmLogout() {
        if (confirm("Are you sure you want to logout?")) {
            window.location.href = "../../pages/alumni/loginpage.php";
            }
        }



    </script>
</body>

</html>
