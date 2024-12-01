<?php
include '../../config/alumni/header.php';
include '../../config/alumni/friendsManager.php';
include '../../config/general/connection.php';

$user = getAuthenticatedUser();

$friendsManager = new FriendsManager($conn);

$friends = $friendsManager->getFriends($user['id']);


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Notifications</title>
  <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" />
  <link rel="stylesheet" href="../../style/alumni/notifications.css" />
  <link rel="stylesheet" href="../../style/alumni/friends-panel.css" />
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
      <a href="../pages/alumni/shareExperience.php">
          <img src="../../assets/logo.png" alt="logo" class="logo-header" />
      </a>
    </header>

    <main>
      <div class="page-header">
        <h1>Notifications</h1>
        <small>View your recent notifications</small>
      </div>

      <div class="notification-container">
        <!-- Sample notification card -->
        <div class="notification-card">
          <div class="notification-content">
            <h3>Sample Notification Title</h3>
            <p>This is an example of a notification message. It provides the details for this notification.</p>
          </div>
          <span class="notification-timestamp">2024-11-30 14:30</span>
        </div>
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
    // Responsive Sidebar
    document.addEventListener("DOMContentLoaded", function() {
      const sidebar = document.querySelector(".sidebar");
      const toggleButton = document.getElementById("sidebarToggle");

      toggleButton.addEventListener("click", function() {
          sidebar.classList.toggle("minimized");
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
