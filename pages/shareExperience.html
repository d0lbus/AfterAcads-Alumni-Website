<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['email'])) {
    header("Location: loginpage.php");
    exit();
}

include '../config/connection.php'; // Include database connection

// Fetch the logged-in user's details from the database
$email = $_SESSION['email'];
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc(); // Fetch user data
} else {
    echo "Error: User not found.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home</title>
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" />
    <link rel="stylesheet" href="../style/shareExperience.css" />
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-flex">
                <img class="logocircle" src="../assets/alumnilogo.png" width="30px" alt="" />
                <div class="brand-icon">
                    <span class="las la-bell"></span>
                    <span class="las la-user-circle"></span>
                </div>
            </div>
        </div>
        <div class="sidebar-main">
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
                    <li><a href="../pages/shareExperience.html"><span class="las la-home"></span>Home</a></li>
                    <li><a href="../pages/ViewEvents.html"><span class="las la-sign"></span>Events</a></li>
                    <li><a href="#"><span class="las la-tools"></span>Settings</a></li>
                    <li><a href="../pages/loginpage.php"><span class="las la-sign-out-alt"></span>Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="main-content">
        <header>
            <span class="las la-bars"></span>
            <div class="header-icons">
                <span class="las la-search"></span>
            </div>
        </header>

        <main>
            <div class="page-header">
                <div>
                    <h1>Share your Experience</h1>
                    <small>Share your latest experiences</small>
                </div>
                <div class="header-actions"></div>
            </div>
            
            <div class="addPost">
                <div class="addPost-header">
                    <img src="../assets/profile.jpg" alt="Profile" class="profile-pic" />
                    <input type="text" id="postContent" placeholder="What's on your mind, <?php echo htmlspecialchars($user['first_name']); ?>?" class="post-input" />
                </div>

                <div class="tag-dropdown">
                    <label for="tags" class="tag-label">Select a Tag:</label>
                    <select id="tags" class="tag-select">
                        <option value="">Select a Tag</option>
                        <option value="SAMCIS">SAMCIS</option>
                        <option value="SOHNABS">SOHNABS</option>
                        <option value="STELA">STELA</option>
                        <option value="SEA">SEA</option>
                    </select>
                </div>

                <div class="addPost-option">
                    <button id="addPhotoVideoButton" class="post-option">
                        <span class="las la-image"></span> Photo
                    </button>
                </div>
                <button id="postButton" class="post-button">Post</button>
            </div>

            <div id="postsContainer"></div> <!-- Container to hold dynamic posts -->

            <div class="modal" id="postModal">
                <div class="modal-content">
                    <span class="close-modal">&times;</span>
                    <h2 class="modal-title">Create Post</h2>
                    <div class="line"></div>
                    <div class="modal-header">
                        <img src="../assets/profile.jpg" alt="Profile" class="profile-pic" />
                        <span><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></span>
                        <input type="text" placeholder="What's on your mind?" class="post-input" />
                    </div>
                    <div class="modal-divider"></div>

                    <div class="tag-dropdown">
                        <label for="modal-tags" class="tag-label">Select a Tag:</label>
                        <select id="modal-tags" class="tag-select">
                            <option value="">Select a Tag</option>
                            <option value="SAMCIS">SAMCIS</option>
                            <option value="SOHNABS">SOHNABS</option>
                            <option value="STELA">STELA</option>
                            <option value="SEA">SEA</option>
                        </select>
                    </div>

                    <div class="modal-add-option">
                        <button class="post-option">
                            <span class="las la-image"></span> Add Photo
                        </button>
                    </div>
                    <button class="post-button">Post</button>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Modal open/close functionality
        document.addEventListener("DOMContentLoaded", function () {
            const modal = document.getElementById("postModal");
            const openModalButton = document.getElementById("addPhotoVideoButton");
            const closeModalButton = document.querySelector(".close-modal");

            if (openModalButton) {
                openModalButton.onclick = function () {
                    modal.style.display = "block";
                };
            }

            if (closeModalButton) {
                closeModalButton.onclick = function () {
                    modal.style.display = "none";
                };
            }

            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            };

            // Post content handling
            document.getElementById('postButton').addEventListener('click', function() {
                const content = document.getElementById('postContent').value;

                if (content.trim() === '') {
                    alert('Post content cannot be empty!');
                    return;
                }

                // Send post content to PHP backend
                fetch('../config/create_posts.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ content: content })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Post created successfully!');
                        location.reload(); // Reload the page to show the new post
                    } else {
                        alert('Error creating post.');
                    }
                })
                .catch(error => console.error('Error:', error));
            });

            fetch('../config/fetch_posts.php')
                .then(response => response.json())
                .then(posts => {
                    const postsContainer = document.getElementById('postsContainer');

                    posts.forEach(post => {
                        const postElement = document.createElement('div');
                        postElement.classList.add('post');
                        postElement.innerHTML = `
                            <div class="post-user">${post.full_name}</div>
                            <div class="post-content">${post.content}</div>
                            <div class="post-date">${new Date(post.created_at).toLocaleString()}</div>
                        `;
                        postsContainer.appendChild(postElement);
                    });
                })
                .catch(error => console.error('Error fetching posts:', error));
        });
    </script>
</body>
</html>
