<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: loginpage.php");
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

$sql_posts = "SELECT content, created_at, tag, image FROM posts WHERE user_id = ? ORDER BY created_at DESC";
$stmt_posts = $conn->prepare($sql_posts);
$stmt_posts->bind_param("i", $user['id']);
$stmt_posts->execute();
$result_posts = $stmt_posts->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Profile</title>
    <link rel="stylesheet" href="../style/view-profile.css" />
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
                    <img src="../assets/display-photo.png" alt="Profile Picture" />
                </a>
                <div>
                    <h3><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h3>
                    <span><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
            </div>

            <div class="sidebar-menu">
                <ul>
                    <li>
                        <a href="../pages/shareExperience.php">
                            <span>
                                <img
                                    src="../assets/home1.png"
                                    width="20px"
                                    alt="Home" />
                            </span>Home
                        </a>
                    </li>
                    <li><a href="../pages/events.php"><span><img
                                    src="../assets/event1.png"
                                    width="20px"
                                    alt="Events" /></span>Events</a>
                    </li>
                    <li><a href="../pages/opportunities.php"><span><img src="../assets/opportunities.png" width="20px" alt="Opportunities" /></span>Opportunities</a></li>
                    <li><a href="../pages/settings.php"><span><img
                                    src="../assets/setting1.png"
                                    width="20px"
                                    alt="Settings" /></span>Settings</a>
                    </li>
                    <li><a href="../pages/loginpage.php"><span><img
                                    src="../assets/logout1.png"
                                    width="20px"
                                    alt="Logout" /></span>Logout</a>
                    </li>
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
                        <img src="../assets/display-photo.png" alt="Display Photo" />
                        <span></span>
                    </div>
                    <!-- Display user details dynamically -->
                    <h2><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h2>
                    <p><?php echo htmlspecialchars($user['email']); ?></p>

                    <ul class="about">
                        <li><span>Joined <?php echo date('F d, Y', strtotime($user['created_at'])); ?></span></li>
                        <li><span>Address: <?php echo isset($user['address']) ? htmlspecialchars($user['address']) : '(Not provided)'; ?></span></li>
                    </ul>

                    <div class="content">
                        <p><?php echo isset($user['bio']) ? htmlspecialchars($user['bio']) : 'Bio not provided'; ?></p>

                        <ul class="social-logo">
                            <li><img src="../assets/twitter-icon.png" alt="twitter icon" width="20px" height="20px" /></li>
                            <li><img src="../assets/fb-icon.png" alt="fb icon" width="20px" height="20px" /></li>
                            <li><img src="../assets/instagram-icon.png" alt="ig icon" width="20px" height="20px"></li>
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
                        <?php if ($result_posts->num_rows > 0): ?>
                            <?php while ($post = $result_posts->fetch_assoc()): ?>
                                <div class="post">
                                    <!-- Display post content -->
                                    <p><?php echo htmlspecialchars($post['content']); ?></p>

                                    <!-- Display tag if available -->
                                    <?php if (!empty($post['tag'])): ?>
                                        <small>Tag: <?php echo htmlspecialchars($post['tag']); ?></small>
                                    <?php endif; ?>

                                    <!-- Display image if available -->
                                    <?php if (!empty($post['image'])): ?>
                                        <div class="post-image">
                                            <img src="data:image/jpeg;base64,<?php echo base64_encode($post['image']); ?>" alt="Post Image" style="max-width:100%; height:auto;">
                                        </div>
                                    <?php endif; ?>

                                    <!-- Display the post date -->
                                    <small><?php echo date('F d, Y H:i', strtotime($post['created_at'])); ?></small>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>No posts yet.</p>
                        <?php endif; ?>
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