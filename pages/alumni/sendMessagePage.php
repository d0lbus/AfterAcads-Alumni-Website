<?php
include '../../config/header.php';
include '../../config/connection.php';

$user = getAuthenticatedUser();

if (isset($_GET['chat_with']) && !empty($_GET['chat_with'])) {
    $friend_id = intval($_GET['chat_with']); // Get the friend's ID from the URL

    // Fetch friend's details
    $stmt = $conn->prepare("SELECT first_name, last_name, profile_picture FROM users WHERE id = ?");
    $stmt->bind_param("i", $friend_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $friend = $result->fetch_assoc(); // Fetch friend details
    } else {
        die("Friend not found.");
    }
} else {
    die("No friend selected for messaging.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" />
    <link rel="stylesheet" href="../../style/sendMessage.css" />
    <title>Message</title>
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
                    <h3>
                        <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                    </h3>
                    <span>
                        <?php echo htmlspecialchars($user['email']); ?>
                    </span>
                </div>
            </div>

            <div class="sidebar-menu">
                <ul>
                    <li>
                        <a href="../../pages/alumni/shareExperience.php">
                            <span><img src="../../assets/home1.png" width="20px" alt="Home" /></span>Home
                        </a>
                    </li>
                    <li>
                        <a href="../../pages/alumni/events.php">
                            <span><img src="../../assets/event1.png" width="20px" alt="Events" /></span>Events
                        </a>
                    </li>
                    <li><a href="../../pages/alumni/opportunities.php">
                            <span><img src="../../assets/opportunities.png" width="20px"
                                    alt="Opportunities" /></span>Opportunities</a></li>
                    <li>
                        <a href="../../pages/alumni/settings.php">
                            <span><img src="../../assets/setting1.png" width="20px" alt="Settings" /></span>Settings
                        </a>
                    </li>
                    <li>
                        <a href="../../pages/alumni/loginpage.php">
                            <span><img src="../../assets/logout1.png" width="20px" alt="Logout" /></span>Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="main-content">
        <header>
            <a href="../../pages/alumni/shareExperience.php">
                <img src="../../assets/logo.png" alt="logo" class="logo-header" />
            </a>
        </header>

        <main>

            <div class="header-search-bar">
                <input type="text" class="search-input" id="searchInput" placeholder="Search..." />
                <button class="search-button" id="searchButton" aria-label="Search">
                    <span><img src="../../assets/search1.png" width="20px" alt="search" /></span>
                </button>
            </div>
            
            <!-- Message Area -->
            <div class="message-container" id="messageContainer" style="display: none;">
                <div class="chat-header">
                    <img src="../../assets/profile.jpg" alt="User Profile Picture" class="chat-user-icon">
                    <span class="chat-username"><?php echo $friend; ?></span>
                </div>
                <div class="chat-box">
                    <div class="message received">
                        <p>Hey! How are you?</p>
                        <span class="timestamp">12:00 PM</span>
                    </div>
                    <div class="message sent">
                        <p>I'm good! How about you?</p>
                        <span class="timestamp">12:01 PM</span>
                    </div>
                </div>
                <div class="chat-input">
                    <input type="text" placeholder="Type a message..." class="message-input">
                    <button class="send-button">Send</button>
                </div>
            </div>
        </main>
    </div>

        </main>
    </div>

    <div class="right-panel">
        <h2>Friends</h2>
        <hr class="title-divider">
        <div class="friend-list">
            <div class="friend" onclick="openChat('Friend Name')">
                <img src="../../assets/profile.jpg" alt="Friend Profile Picture">
                <span>Friend Name 1</span>
            </div>
        </div>
    </div>

    <script>
        // open chat
        function openChat(friendName) {
            const messageContainer = document.getElementById('messageContainer');
            messageContainer.style.display = 'block';

            const chatUsername = document.querySelector('.chat-username');
            chatUsername.textContent = friendName;
        }
  </script>
    
</body>
</html>