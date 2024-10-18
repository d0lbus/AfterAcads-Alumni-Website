<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.html");
    exit();
}

include '../config/connection.php';

// Fetch user data based on the session email
$email = $_SESSION['email'];
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "Error: User not found.";
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../style/view-profile.css" />
</head>

<body>
<div class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-flex">
                
                <div class="brand-icon">
                    <a href="javascript:void(0)" id="sidebarToggle">
                        <span class="fa fa-bars"></span>
                    </a>
                </div>

                <img class="logocircle" src="../assets/alumnilogo.png" width="30px" alt="" />
            </div>
        </div>
        <div class="sidebar-content">
            <div class="sidebar-user">
            <a href="../pages/viewProfile.php">
                    <img src="../assets/profile.jpg" alt="Profile Picture" />
                </a>
                <div>
                    <h3><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h3>
                    <span><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
                </div>
                
                <div class="sidebar-menu">
                <div class="menu-head">
                    <span>Dashboard</span>
                </div>
                <ul>
                    <li><a href="../pages/shareExperience.php"><span class="las la-home"></span>Home</a></li>
                    <li><a href="../pages/events.php"><span class="las la-sign"></span>Events</a></li>
                    <li><a href="../pages/settings.php"><span class="las la-tools"></span>Settings</a></li>
                    <li><a href="../pages/loginpage.php"><span class="las la-sign-out-alt"></span>Logout</a></li>
                </ul>
            </div>
            </div>
        </div>
    

<div class="main-content">
    <div class="header-wrapper">
        <div class="header"></div>
        <div class="cols-container">
            <div class="left-col">
                <div class="img-container">
                    <img src="/assets/display-photo.png" alt="Display Photo" />
                    <span></span>
                </div>
                <!-- Display user details dynamically -->
                <h2><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h2>
                <p><?php echo htmlspecialchars($user['email']); ?></p>

                <ul class="about">
                    <li><span>Joined <?php echo date('F d, Y', strtotime($user['created_at'])); ?></span></li>
                    <!-- TO DO: Display OTHER FIELDS DYNAMICALLY -->
                    <li><span>Lives in Baguio City</span></li>
                </ul>

                <div class="content">
                    <p>This is a bio section. You can personalize this later.</p>

                    <ul class="social-logo">
                        <li><i class="fa fa-twitter"></i></li>
                        <li><i class="fa fa-facebook"></i></li>
                        <li><i class="fa fa-instagram"></i></li>
                    </ul>
                </div>
            </div>
            <div class="right-col">
                <nav>
                    <ul>
                        <li><a href="#posts">Posts</a></li>
                        <li><a href="#photos">Photos</a></li>
                        <li><a href="#about">About</a></li>
                    </ul>
                </nav>

                <div class="posts" id="posts">
                    <p>sample post</p>
                </div>

                <div class="line"></div>

                <!-- TO DO: Display user's uploaded photos DYNAMICALLY  -->
                <div class="photos" id="photos">
                    <img src="" alt="image" />
                    <img src="" alt="image" />
                    <img src="" alt="image" />
                    <img src="" alt="image" />
                    <img src="" alt="image" />
                    <img src="" alt="image" />
                </div>
            </div>
        </div>
    </div>
</div>

<script>
        document.addEventListener("DOMContentLoaded", function () {
            const sidebar = document.querySelector(".sidebar");
            const toggleButton = document.getElementById("sidebarToggle");

            toggleButton.addEventListener("click", function () {
                sidebar.classList.toggle("minimized");
            });
        });
    </script>
</body>
</html>