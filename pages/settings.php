<?php include '../config/header.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Settings</title>
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" />
    <link rel="stylesheet" href="../style/settings.css" />
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-flex">
                <div class="brand-icon">
                    <a href="javascript:void(0)" id="sidebarToggle">
                        <span><img src="../assets/bars1.png" width="24px" alt="bars" /></span>
                    </a>
                </div>
            </div>
        </div>
        <div class="sidebar-content">
            <div class="sidebar-user">
                <a href="../pages/viewProfile.php">
                    <img src="../assets/profileIcon.jpg" alt="Profile Picture" />
                </a>
                <div>
                    <h3><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h3>
                    <span><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
            </div>

            <div class="sidebar-menu">
                <ul>
                <li><a href="../pages/shareExperience.php"><span><img src="../assets/home1.png" width="20px" alt="Home" /></span>Home</a></li>
                <li><a href="../pages/events.php"><span><img src="../assets/event1.png" width="20px" alt="Events" /></span>Events</a></li>
                <li><a href="../pages/opportunities.php"><span><img src="../assets/opportunities.png" width="20px" alt="Opportunities" /></span>Opportunities</a></li>
                <li><a href="../pages/settings.php"><span><img src="../assets/setting1.png" width="20px" alt="Settings" /></span>Settings</a></li>
                <li><a href="../pages/loginpage.php"><span><img src="../assets/logout1.png" width="20px" alt="Logout" /></span>Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="main-content">
        <header>
            <img src="../assets/logoBlue.png" alt="logo" class="logo-header" />
        </header>
        <main>
            <h1>Settings</h1>
            <small>Update your account settings below</small>
            
            <!-- Settings Form -->
            <form class="settings-form" method="post" action="../config/update_settings.php">
                <h2>Profile Settings</h2>
                <div class="form-group">
                    <label for="first-name">First Name</label>
                    <input type="text" name="first-name" id="first-name" placeholder="First Name" required value="<?= htmlspecialchars($user['first_name']) ?>" />
                </div>

                <div class="form-group">
                    <label for="middle-name">Middle Name</label>
                    <input type="text" name="middle-name" id="middle-name" placeholder="Middle Name" value="<?= htmlspecialchars($user['middle_name']) ?>" />
                </div>

                <div class="form-group">
                    <label for="last-name">Last Name</label>
                    <input type="text" name="last-name" id="last-name" placeholder="Last Name" required value="<?= htmlspecialchars($user['last_name']) ?>" />
                </div>

                <div class="form-group">
                    <label for="add-bio">Add Bio</label>
                    <textarea name="add-bio" id="add-bio" rows="4" placeholder="Tell us about yourself"><?= htmlspecialchars($user['bio']) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="change-address">Change Address</label>
                    <input type="text" name="change-address" id="change-address" placeholder="New Address" required value="<?= htmlspecialchars($user['address']) ?>" />
                </div>

                <div class="form-group">
                    <label for="batch-year">Batch Year</label>
                    <input type="number" name="batch-year" id="batch-year" placeholder="Batch Year" required min="1900" max="2100" />
                </div>

                <h2>Change Password</h2>
                <div class="form-group">
                    <label for="old-password">Old Password</label>
                    <input type="password" name="old-password" id="old-password" placeholder="Old Password" required />
                </div>
                
                <div class="form-group">
                    <label for="new-password">New Password</label>
                    <input type="password" name="new-password" id="new-password" placeholder="New Password" required />
                </div>
                
                <div class="form-group">
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" name="confirm-password" id="confirm-password" placeholder="Confirm Password" required />
                </div>

                <div class="form-group">
                    <label>Gender</label>
                    <div class="checkbox-group">
                        <label>
                            <input type="radio" name="employment-status" value="employed" required />
                            Male
                        </label>
                        <label>
                            <input type="radio" name="employment-status" value="unemployed" required />
                            Female
                        </label>
                        <label>
                            <input type="radio" name="employment-status" value="prefer-not-to-say" required />
                            Prefer not to say
                        </label>
                    </div>
                </div>                
            </form>

                <h2>Choose a School and Course</h2>
                <div class="form-group">
                    <label for="program">School</label>
                    <select name="program" id="program" required>
                        <option value="">Select School</option>
                        <option value="science">SAMCIS</option>
                        <option value="science">SEA</option>
                        <option value="engineering">SONAHBS</option>
                        <option value="science">SOL</option>
                        <option value="science">SOM</option>
                        <option value="arts">STELA</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="course">Course</label>
                    <select name="course" id="course" disabled required>
                        <option value="">Select Course</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Employment Status</label>
                    <div class="checkbox-group">
                        <label>
                            <input type="radio" name="employment-status" value="employed" required />
                            Employed
                        </label>
                        <label>
                            <input type="radio" name="employment-status" value="unemployed" required />
                            Unemployed
                        </label>
                        <label>
                            <input type="radio" name="employment-status" value="prefer-not-to-say" required />
                            Prefer not to say
                        </label>
                    </div>
                </div>                

                <div class="button-container">
                    <button type="submit" class="button">Save Changes</button>
                </div>
            </form>

            <!-- Display success or error messages -->
            <?php
            if (isset($_GET['success']) && $_GET['success'] === 'true') {
                echo '<p class="success-message">Settings updated successfully!</p>';
            } elseif (isset($_GET['error'])) {
                echo '<p class="error-message">Error: ' . htmlspecialchars($_GET['error']) . '</p>';
            }
            ?>
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
    </script>
</body>

</html>
