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
  <title>Opportunities</title>
  <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" />
  <link rel="stylesheet" href="../../style/alumni/opportunities.css" />
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
          <li><a href="../../pages/alumni/loginpage.php"><span><img src="../../assets/logout1.png" width="20px" alt="Logout" /></span>Logout</a></li>
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

      <form action="searchResultsPage.php" method="get" style="display: flex; width: 100%;">
          <input type="text" class="search-input" name="query" placeholder="Search..." required />
          <button class="search-button" id="searchButton" aria-label="Search">
              <span><img src="../../assets/search1.png" width="20px" alt="search" /></span>
          </button>
      </form>

      </div>

        <h1>Opportunities</h1>
        <small>Find available job openings and internships provided by our partners</small>

        <div class="card-container">
          <div class="card">
            <div class="company-logo">
              <img src="../../assets/company-logo.png" alt="Company Logo">
            </div>
            <div class="container">
              <h2><b>{Job Title}</b></h2>
              <p><strong>Company:</strong> {Company Name}</p>
              <p><strong>Location:</strong> {Location}</p>
              <p>{Description of the opportunity goes here. This is a brief summary of what the job entails and any key details.}</p>
            </div>
            <div class="button-container">
              <a href="../../pages/alumni/viewOpportunities.php" class="button">View</a>
              <a href="apply.php?job_id=1" class="button">Apply</a>
            </div>
          </div>
          <!-- Additional job cards would go here -->
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
    document.addEventListener("DOMContentLoaded", function() {
      const sidebar = document.querySelector(".sidebar");
      const toggleButton = document.getElementById("sidebarToggle");

      toggleButton.addEventListener("click", function() {
        sidebar.classList.toggle("minimized");
      });

      const searchInput = document.getElementById('search-input');
      const dropdownContainer = document.getElementById('dropdown-container');

      // Sample data for suggestions
      const suggestions = [
        'Architecture and Urban Design',
        'Sustainable Development',
        'Eco-friendly Tourism',
        'Green Spaces',
        'Waste Management',
        'Public Transportation'
      ];

      // Show dropdown based on input
      searchInput.addEventListener('input', function() {
        const query = searchInput.value.trim().toLowerCase();
        dropdownContainer.innerHTML = ''; // Clear current suggestions
        
        if (query) {
          const filteredSuggestions = suggestions.filter(item =>
            item.toLowerCase().includes(query)
          );

          filteredSuggestions.forEach(suggestion => {
            const div = document.createElement('div');
            div.classList.add('dropdown-item');
            div.textContent = suggestion;
            div.onclick = () => {
              searchInput.value = suggestion;
              dropdownContainer.innerHTML = ''; // Hide dropdown on selection
            };
            dropdownContainer.appendChild(div);
          });

          dropdownContainer.style.display = 'block';
        } else {
          dropdownContainer.style.display = 'none';
        }
      });

      // Hide dropdown when clicking outside
      document.addEventListener('click', function(e) {
        if (!e.target.closest('.header-search-bar')) {
          dropdownContainer.style.display = 'none';
        }
      });
    });
  </script>
</body>

</html>
