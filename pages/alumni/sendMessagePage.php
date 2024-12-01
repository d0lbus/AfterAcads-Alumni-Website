<?php
include '../../config/alumni/header.php';
include '../../config/alumni/friendsManager.php';
include '../../config/alumni/connection.php';

$user = getAuthenticatedUser();

$friendsManager = new FriendsManager($conn);

$friends = $friendsManager->getFriends($user['id']);

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
    <link rel="stylesheet" href="../../style/alumni/sendMessage.css" />
    <link rel="stylesheet" href="../../style/alumni/friends-panel.css" />
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
          <li><a href="../../pages/alumni/shareExperience.php"><span><img src="../../assets/home1.png" width="20px" alt="Home" /></span>Home</a></li>
          <li><a href="../../pages/alumni/events.php"><span><img src="../../assets/event1.png" width="20px" alt="Events" /></span>Events</a></li>
          <li><a href="../../pages/alumni/opportunities.php"><span><img src="../../assets/opportunities.png" width="20px" alt="Opportunities" /></span>Opportunities</a></li>
          <li><a href="../../pages/alumni/notifications.php"><span><img src="../../assets/notification-removebg-preview.png" width="20px" alt="Notifications" /></span>Notifications</a></li>
          <li><a href="../../pages/alumni/settings.php"><span><img src="../../assets/setting1.png" width="20px" alt="Settings" /></span>Settings</a></li>
          <li><a href="javascript:void(0);" onclick="confirmLogout()"><span><img src="../../assets/logout1.png" width="20px" alt="Logout" /></span>Logout</a></li>
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
            <div class="message-container" id="messageContainer">
                <!-- Chat Header -->
                <div class="chat-header">
                    <?php if (!empty($friend['profile_picture'])): ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($friend['profile_picture']); ?>" 
                            alt="User Profile Picture" class="chat-user-icon">
                    <?php else: ?>
                        <img src="../../assets/profileIcon.jpg" 
                            alt="Default Avatar" class="chat-user-icon">
                    <?php endif; ?>
                    <span class="chat-username">
                        <?php echo htmlspecialchars($friend['first_name'] . ' ' . $friend['last_name']); ?>
                    </span>
            </div>

                <!-- Chat Box -->
                <div class="chat-box" id="chatBox">
                    <!-- Messages will be dynamically inserted here -->
                </div>

                <!-- Chat Input -->
                <div class="chat-input">
                    <input type="text" id="messageInput" placeholder="Type a message..." class="message-input">
                    <button class="send-button" id="sendButton">Send</button>
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
        <?php if (!empty($friends)): ?>
            <?php foreach ($friends as $friend): ?>
                <div class="friend-item">
                    <div class="friend-avatar">
                        <?php if (!empty($friend['profile_picture'])): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($friend['profile_picture']); ?>" 
                                alt="<?php echo htmlspecialchars($friend['first_name']); ?>'s Profile Picture" 
                                style="max-width: 50px; border-radius: 50%;">
                        <?php else: ?>
                            <img src="../../assets/profileIcon.jpg" 
                                alt="Default Avatar" 
                                style="max-width: 50px; border-radius: 50%;">
                        <?php endif; ?>
                    </div>
                    <div class="friend-info">
                        <h4><?php echo htmlspecialchars($friend['first_name'] . ' ' . $friend['last_name']); ?></h4>
                        <p><?php echo htmlspecialchars($friend['email']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No friends found.</p>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chatBox = document.getElementById('chatBox');
        const messageInput = document.getElementById('messageInput');
        const sendButton = document.getElementById('sendButton');
        const loggedInUserId = <?php echo json_encode($user['id']); ?>;
        const friendId = <?php echo json_encode($friend_id); ?>;

        // Fetch messages every 2 seconds
        function fetchMessages() {
            fetch(`../../config/alumni/fetchMessages.php?logged_in_user_id=${loggedInUserId}&friend_id=${friendId}`)
                .then(response => response.json())
                .then(messages => {
                    chatBox.innerHTML = ''; // Clear chatBox
                    messages.forEach(msg => {
                        const messageDiv = document.createElement('div');
                        messageDiv.classList.add('message', msg.sender_id == loggedInUserId ? 'sent' : 'received');
                        messageDiv.innerHTML = `
                            <p>${msg.message}</p>
                            <span class="timestamp">${new Date(msg.created_at).toLocaleTimeString()}</span>
                        `;
                        chatBox.appendChild(messageDiv);
                    });
                    chatBox.scrollTop = chatBox.scrollHeight; // Scroll to the bottom
                });
        }

        // Send message
        sendButton.addEventListener('click', function () {
            const message = messageInput.value.trim();
            if (message) {
                fetch('../../config/alumni/sendMessage.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `sender_id=${loggedInUserId}&receiver_id=${friendId}&message=${encodeURIComponent(message)}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        messageInput.value = ''; // Clear input
                        fetchMessages(); // Refresh chat
                    } else {
                        alert(data.error || 'Failed to send message.');
                    }
                });
            }
        });

        // Start fetching messages
        setInterval(fetchMessages, 2000);
        fetchMessages(); // Initial load

        // Handle Sidebar Toggle
        const sidebar = document.querySelector(".sidebar");
        const toggleButton = document.getElementById("sidebarToggle");
        if (sidebar && toggleButton) {
            toggleButton.addEventListener("click", function () {
                sidebar.classList.toggle("minimized");
            });
        }
    });

    
</script>
    
</body>
</html>