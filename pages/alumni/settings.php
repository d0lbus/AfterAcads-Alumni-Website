<?php
include '../../config/alumni/header.php';
include '../../config/general/connection.php';

$user = getAuthenticatedUser();
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
                <img src="<?= !empty($user['profile_picture']) 
                            ? 'data:image/jpeg;base64,' . base64_encode($user['profile_picture']) 
                            : '../../assets/profileIcon.jpg'; ?>" 
                            alt="Profile" 
                            id="profile-picture-preview" />
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
            <form id="settings-form" method="post" action="../../config/alumni/update_settings.php" enctype="multipart/form-data">
                <!-- Profile Picture -->
                <div class="profile-picture-group">
                    <h2>Profile Picture</h2>
                    <div class="profile-picture-preview">
                        <img src="<?= !empty($user['profile_picture']) 
                            ? 'data:image/jpeg;base64,' . base64_encode($user['profile_picture']) 
                            : '../../assets/profileIcon.jpg'; ?>" 
                            alt="Profile" 
                            id="profile-picture-preview" />
                    </div>
                    <label for="profile-picture">Upload New Profile Picture</label>
                    <input type="file" id="profile-picture" name="profile-picture" accept="image/jpeg, image/png" onchange="previewImage(event)">
                </div>

                <!-- Personal Information -->
                <h2>Profile Settings</h2>
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first-name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last-name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Middle Name</label>
                    <input type="text" name="middle-name" value="<?= htmlspecialchars($user['middle_name']) ?>">
                </div>
                <div class="form-group">
                    <label>Bio</label>
                    <textarea name="bio" id = "bio" rows="4"><?= htmlspecialchars($user['bio']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address" id ="address" value="<?= htmlspecialchars($user['user_address']) ?>">
                </div>

                <!-- School, Course, and Batch -->
                <h2>School and Course</h2>
                <div class="form-group">
                    <label>School</label>
                    <select id="school" name="school" required>
                        <option value="">Select School</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Course</label>
                    <select id="course" name="course" disabled required>
                        <option value="">Select Course</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Batch</label>
                    <input type="text" id="batch" name="batch" list="batch-list" value="<?= htmlspecialchars($user['batch_number'] ?? '') ?>" placeholder="Type your batch (e.g., 223)" />
                    <datalist id="batch-list"></datalist>
                </div>

                <!-- Employment Status -->
                <h2>Employment Status</h2>
                <div class="form-group">
                    <label for="employment-status">Employment Status</label>
                    <select id="employment-status" name="employment-status" required>
                        <option value="">Select Employment Status</option>
                        <option value="Employed" <?= ($user['employment_status'] === 'Employed' ? 'selected' : '') ?>>Employed</option>
                        <option value="Unemployed" <?= ($user['employment_status'] === 'Unemployed' ? 'selected' : '') ?>>Unemployed</option>
                        <option value="Not Looking For Work" <?= ($user['employment_status'] === 'Not Looking For Work' ? 'selected' : '') ?>>Not Looking For Work</option>
                        <option value="Retired" <?= ($user['employment_status'] === 'Retired' ? 'selected' : '') ?>>Retired</option>
                        <option value="Studying" <?= ($user['employment_status'] === 'Studying' ? 'selected' : '') ?>>Studying</option>
                    </select>
                </div>

                <!-- Password -->
                <h2>Change Password</h2>
                <div class="form-group">
                    <label>Old Password</label>
                    <input type="password" name="old-password">
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new-password">
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm-password">
                </div>

                <button type="submit">Save Changes</button>
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

        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const output = document.getElementById('profile-picture-preview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        // Fetch settings options
        document.addEventListener('DOMContentLoaded', () => {
            fetch("../../config/alumni/fetchSettingsOptions.php")
                .then(response => response.json())
                .then(data => {
                    // Populate schools
                    const schoolSelect = document.getElementById('school');
                    data.schools.forEach(school => {
                        const option = document.createElement('option');
                        option.value = school.id;
                        option.textContent = school.name;
                        if (school.id == <?= $user['school_id'] ?? 'null' ?>) option.selected = true;
                        schoolSelect.appendChild(option);
                    });

                    // Pre-fetch courses if a school is selected
                    const selectedSchoolId = <?= $user['school_id'] ?? 'null' ?>;
                    const selectedCourseId = <?= $user['course_id'] ?? 'null' ?>;
                    if (selectedSchoolId) {
                        fetch(`../../config/alumni/fetchCourses.php?school_id=${selectedSchoolId}`)
                            .then(response => response.json())
                            .then(courses => {
                                const courseSelect = document.getElementById('course');
                                courses.forEach(course => {
                                    const option = document.createElement('option');
                                    option.value = course.id;
                                    option.textContent = course.name;
                                    if (course.id == selectedCourseId) option.selected = true;
                                    courseSelect.appendChild(option);
                                });
                                courseSelect.disabled = false;
                            });
                    }

                    // Populate batches
                    const batchList = document.getElementById('batch-list');
                    data.batches.forEach(batch => {
                        const option = document.createElement('option');
                        option.value = batch.batch_number;
                        batchList.appendChild(option);
                    });

                    // Set the batch input value
                    const batchInput = document.getElementById('batch');
                    batchInput.value = data.selectedBatchNumber || '';
                });

            // Fetch courses dynamically
            document.getElementById('school').addEventListener('change', function () {
                const schoolId = this.value;
                const courseSelect = document.getElementById('course');
                courseSelect.innerHTML = '<option value="">Select Course</option>';
                if (schoolId) {
                    fetch(`../../config/alumni/fetchCourses.php?school_id=${schoolId}`)
                        .then(response => response.json())
                        .then(courses => {
                            courses.forEach(course => {
                                const option = document.createElement('option');
                                option.value = course.id;
                                option.textContent = course.name;
                                courseSelect.appendChild(option);
                            });
                            courseSelect.disabled = false;
                        });
                } else {
                    courseSelect.disabled = true;
                }
            });
        });

        function confirmLogout() {
        if (confirm("Are you sure you want to logout?")) {
            window.location.href = "../../pages/alumni/loginpage.php";
            }
        }



    </script>
</body>

</html>
