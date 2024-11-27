<?php
include '../../config/header.php';

if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
    $viewing_user_id = intval($_GET['user_id']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $viewing_user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $target_user = $result->fetch_assoc(); 
        $is_current_user = ($viewing_user_id === $user['id']); 
    } else {
        echo "User not found.";
        exit;
    }
} else {
    // Default to logged-in user's profile
    $target_user = $user;
    $is_current_user = true; 
}


$sql_posts = "SELECT content, created_at, tag, image FROM posts WHERE user_id = ? ORDER BY created_at DESC";
$stmt_posts = $conn->prepare($sql_posts);
$stmt_posts->bind_param("i", $target_user['id']);
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
    <link rel="stylesheet" href="../../style/view-profile.css" />
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
                <a href="viewProfile.php">
                    <img src="../../assets/profileIcon.jpg" alt="Profile Picture" />
                </a>
                <div>
                    <h3><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h3>
                    <span><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
            </div>

            <div class="sidebar-menu">
                <ul>
                    <li>
                        <a href="shareExperience.php">
                            <span>
                                <img
                                    src="../../assets/home1.png"
                                    width="20px"
                                    alt="Home" />
                            </span>Home</a>
                    </li>
                    <li><a href="events.php"><span><img
                                    src="../../assets/event1.png"
                                    width="20px"
                                    alt="Events" /></span>Events</a>
                    </li>
                    <li><a href="opportunities.php"><span><img src="../../assets/opportunities.png" width="20px" alt="Opportunities" /></span>Opportunities</a></li>
                    <li><a href="settings.php"><span><img
                                    src="../../assets/setting1.png"
                                    width="20px"
                                    alt="Settings" /></span>Settings</a>
                    </li>
                    <li><a href="loginpage.php"><span><img
                                    src="../../assets/logout1.png"
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
                        <img src="../../assets/profileIcon.jpg" alt="Display Photo" />
                        <span></span>
                    </div>
                    <!-- Display user details dynamically -->
                    <h2><?php echo htmlspecialchars($target_user['first_name'] . ' ' . $target_user['last_name']); ?></h2>
                    <p><?php echo htmlspecialchars($target_user['email']); ?></p>

                    <ul class="about">
                        <li><span>Joined <?php echo date('F d, Y', strtotime($target_user['created_at'])); ?></span></li>
                        <li><span>Address: <?php echo isset($target_user['address']) ? htmlspecialchars($target_user['address']) : '(Not provided)'; ?></span></li>
                    </ul>

                    <div class="content">
                        <p><?php echo isset($target_user['bio']) ? htmlspecialchars($target_user['bio']) : 'Bio not provided'; ?></p>

                        <?php if (!$is_current_user): ?>
                        <!-- Show Add Friend button if viewing another user's profile -->
                        <form method="POST" action="addFriend.php">
                            <input type="hidden" name="friend_id" value="<?php echo htmlspecialchars($target_user['id']); ?>">
                            <button type="submit" class="add-friend-button">Add Friend</button>
                        </form>
                        <?php else: ?>
                            <!-- Show edit profile button for current user -->
                            <a href="settings.php" class="edit-profile-button">Edit Profile</a>
                        <?php endif; ?>

                        <div class="contact-button">
                            <button onclick="contactUser()">Contact Details</button>
                        </div>
                    </div>
                </div>
                <div class="right-col">
                    <nav>
                        <ul>
                            <li><a href="#posts">Posts</a></li>
                            <li><a href="#photos">Photos</a></li>
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
                        <?php
                        // Fetch all user images from the database
                        $sql_photos = "SELECT image FROM posts WHERE user_id = ? AND image IS NOT NULL ORDER BY created_at DESC";
                        $stmt_photos = $conn->prepare($sql_photos);
                        $stmt_photos->bind_param("i", $target_user['id']);
                        $stmt_photos->execute();
                        $result_photos = $stmt_photos->get_result();

                        if ($result_photos->num_rows > 0):
                            while ($photo = $result_photos->fetch_assoc()):
                                // Display each image
                                echo '<div class="photo-item">';
                                echo '<img src="data:image/jpeg;base64,' . base64_encode($photo['image']) . '" alt="User Photo" style="max-width: 100%; height: auto; margin-bottom: 10px;" />';
                                echo '</div>';
                            endwhile;
                        else:
                            echo "<p>No photos to display.</p>";
                        endif;

                        $stmt_photos->close();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Details Modal -->
    <div id="contactModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h3> User's Contact Details</h3>
            <p><strong>Email:</strong> useremail@gmail.com</p>
            <p><strong>Address:</strong> user address baguio city</p>

            <ul class="social-links">
                <li><img src="../../assets/twitter-icon.png" alt="twitter icon" width="20px" height="20px" /></li>
                <li><img src="../../assets/fb-icon.png" alt="fb icon" width="20px" height="20px" /></li>
                <li><img src="../../assets/instagram-icon.png" alt="ig icon" width="20px" height="20px"></li>
            </ul>
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

        function contactUser() {
            const modal = document.getElementById("contactModal");
            modal.style.display = "block"; // Show the modal
        }

        document.addEventListener("DOMContentLoaded", function () {
            const modal = document.getElementById("contactModal");
            const closeButton = document.querySelector(".close-button");

            // Close the modal when the close button is clicked
            closeButton.addEventListener("click", function () {
                modal.style.display = "none";
            });

            // Close the modal when clicking outside of the modal content
            window.addEventListener("click", function (event) {
                if (event.target === modal) {
                    modal.style.display = "none";
                }
            });
        });
    </script>
</body>

</html>