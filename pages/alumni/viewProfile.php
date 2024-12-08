<?php
include '../../config/alumni/header.php'; 
include '../../config/general/connection.php';
include '../../config/alumni/friendsManager.php';

$user = getAuthenticatedUser();

if (!isset($user) || !is_array($user)) {
    die("User not authenticated. Please log in.");
}

$friendsManager = new FriendsManager($conn);
$logged_in_user_id = $user['id']; 

if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
    $profile_user_id = intval($_GET['user_id']); // Set profile_user_id for viewing another user's profile

    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $profile_user_id); // Use profile_user_id for the query
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $target_user = $result->fetch_assoc(); 
        $is_current_user = ($profile_user_id === $logged_in_user_id);
    } else {
        echo "User not found.";
        exit();
    }
} else {
    // Default to logged-in user's profile
    $profile_user_id = $logged_in_user_id; // Set profile_user_id to the logged-in user's ID
    $target_user = $user;
    $is_current_user = true;
}

$sql_posts = "SELECT content, created_at, image FROM posts WHERE user_id = ? ORDER BY created_at DESC";
$stmt_posts = $conn->prepare($sql_posts);
$stmt_posts->bind_param("i", $target_user['id']);
$stmt_posts->execute();
$result_posts = $stmt_posts->get_result();

// Determine friendship status
$friendStatus = $friendsManager->checkFriendStatus($logged_in_user_id, $profile_user_id);

// Set button text and action based on friendship status
if ($friendStatus === 'not_friends') {
    $buttonText = "Add Friend";
    $action = "../../config/alumni/addFriend.php";
} elseif ($friendStatus === 'request_sent') {
    $buttonText = "Cancel Friend Request";
    $action = "../../config/alumni/cancelFriendRequest.php";
} elseif ($friendStatus === 'pending_request') {
    $buttonText = "Accept Request";
    $action = "../../config/alumni/acceptFriend.php";
} elseif ($friendStatus === 'friends') {
    $buttonText = "Unfriend";
    $action = "../../config/alumni/unfriend.php";
}

// Retrieve Profile Picture
$profile_picture = !$is_current_user 
    ? (!empty($target_user['profile_picture']) 
        ? 'data:image/' . ($target_user['profile_picture_type'] ?? 'jpeg') . ';base64,' . base64_encode($target_user['profile_picture']) 
        : '../../assets/profileIcon.jpg')
    : (!empty($user['profile_picture']) 
        ? 'data:image/' . ($user['profile_picture_type'] ?? 'jpeg') . ';base64,' . base64_encode($user['profile_picture']) 
        : '../../assets/profileIcon.jpg');

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Profile</title>
    <link rel="stylesheet" href="../../style/alumni/view-profile.css" />
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
        <div class="header-wrapper">
            <div class="header"></div>
            <div class="cols-container">
                <div class="left-col">
                <div class="img-container">
                    <img src="<?= $profile_picture; ?>" alt="Profile" id="profile-picture-preview" />
                    <span></span>
                </div>

                    <!-- Display user details dynamically -->
                    <h2><?php echo htmlspecialchars($target_user['first_name'] . ' ' . $target_user['last_name']); ?></h2>
                    <p><?php echo htmlspecialchars($target_user['email']); ?></p>

                    <ul class="about">
                        <li><span>Joined: <?php echo date('F d, Y', strtotime($target_user['created_at'])); ?></span></li>
                        <li><span>Address: <?php echo isset($target_user['user_address']) && !empty($target_user['user_address']) 
                            ? htmlspecialchars($target_user['user_address']) 
                            : '(Not provided)'; ?></span></li>
                        <li><span>Gender: <?php echo isset($target_user['gender']) && !empty($target_user['gender']) 
                            ? htmlspecialchars($target_user['gender']) 
                            : '(Not provided)'; ?></span></li>
                        <li><span>Employment Status: <?php echo isset($target_user['employment_status']) && !empty($target_user['employment_status']) 
                            ? htmlspecialchars($target_user['employment_status']) 
                            : '(Not provided)'; ?></span></li>
                        <li><span>Batch: <?php echo isset($target_user['batch_number']) ? htmlspecialchars($target_user['batch_number']) 
                            : '(Not provided)'; ?></span></li>
                        <li><span>School: <?php echo isset($target_user['school_name']) ? htmlspecialchars($target_user['school_name']) 
                            : '(Not provided)'; ?></span></li>
                        <li><span>Course: <?php echo isset($target_user['course_name']) ? htmlspecialchars($target_user['course_name']) 
                            : '(Not provided)'; ?></span></li>
                    </ul>
                    <div class="content">
                        <p><?php echo isset($target_user['bio']) ? htmlspecialchars($target_user['bio']) : 'Bio not provided'; ?></p>

                        <?php if (!$is_current_user): ?>
                        <div class="button-container">
                            <form method="POST" action="<?php echo $action; ?>">
                                <input type="hidden" name="friend_id" value="<?php echo htmlspecialchars($profile_user_id); ?>">
                                <button type="submit" class="friend-button"><?php echo $buttonText; ?></button>
                            </form>

                            <form method="POST" action="../../config/alumni/initiateChat.php">
                                <input type="hidden" name="friend_id" value="<?php echo htmlspecialchars($profile_user_id); ?>">
                                <button type="submit" class="message-button">Message</button>
                            </form>
                        </div>

                        <?php else: ?>
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
                    <h2 style="margin-bottom: 30px">PHOTOS</h2>
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
                                echo '<img src="data:image/jpeg;base64,' . base64_encode($photo['image']) . '" alt="User Photo" class="user-photo" />';
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
            <h3> <?php echo htmlspecialchars($target_user['first_name'] . ' ' . $target_user['last_name']); ?>'s Contact Details </h3>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($target_user['email']); ?> </p>
            <p><strong>Address:</strong> <?php echo isset($target_user['address']) ? htmlspecialchars($target_user['address']) : '(Not provided)'; ?> </p>

            <!-- <ul class="social-links">
                <li><img src="../../assets/twitter-icon.png" alt="twitter icon" width="20px" height="20px" /></li>
                <li><img src="../../assets/fb-icon.png" alt="fb icon" width="20px" height="20px" /></li>
                <li><img src="../../assets/instagram-icon.png" alt="ig icon" width="20px" height="20px"></li>
            </ul> -->
        </div>
    </div>


    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Handle Sidebar Toggle
        const sidebar = document.querySelector(".sidebar");
        const toggleButton = document.getElementById("sidebarToggle");
        if (sidebar && toggleButton) {
            toggleButton.addEventListener("click", function () {
                sidebar.classList.toggle("minimized");
            });
        }

        // Handle Contact Modal
        const modal = document.getElementById("contactModal");
        const closeButton = document.querySelector(".close-button");

        function showModal() {
            if (modal) modal.style.display = "block"; // Show modal
        }

        function closeModal() {
            if (modal) modal.style.display = "none"; // Hide modal
        }

        if (closeButton) {
            closeButton.addEventListener("click", closeModal); // Close when clicking the button
        }

        window.addEventListener("click", function (event) {
            if (event.target === modal) {
                closeModal(); // Close when clicking outside the modal
            }
        });

        // Expose showModal function globally (if required elsewhere)
        window.contactUser = showModal;

        // Handle Friend Button Action
        const friendButton = document.querySelector('.friend-button');
        if (friendButton) {
            friendButton.addEventListener('click', function (event) {
                event.preventDefault();

                const form = this.closest('form');
                const action = form?.getAttribute('action'); // Ensure form and action exist
                if (!action) {
                    console.error('Form action not found');
                    return;
                }

                const formData = new FormData(form);

                fetch(action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        friendButton.textContent = data.new_button_text; // Update button text
                    } else {
                        alert('Action failed: ' + data.message); // Show error message
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        }
    });

    function confirmLogout() {
        if (confirm("Are you sure you want to logout?")) {
            window.location.href = "../../pages/alumni/loginpage.php";
        }
    }
    </script>

</body>

</html>