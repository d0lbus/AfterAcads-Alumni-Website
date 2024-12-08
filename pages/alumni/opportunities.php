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

      <div class="page-header">
            <h1>Opportunities</h1>
                <small>Browse job opportunities and apply directly</small>

                <!-- Filters -->
                <div class="filters-container">
                    <select id="filter-school" name="school_id">
                        <option value="">Select School</option>
                    </select>

                    <select id="filter-course" name="course_id" disabled>
                        <option value="">Select Course</option>
                    </select>
                </div>

                <!-- Opportunities List -->
              <div class="opportunities-container" id="opportunitiesContainer">
                  <!-- Opportunities will be dynamically loaded here -->
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
                        <h4>
                          <!-- Make the name a clickable link -->
                          <a href="viewProfile.php?user_id=<?php echo $friend['id']; ?>">
                            <?php echo htmlspecialchars($friend['first_name'] . ' ' . $friend['last_name']); ?>
                          </a>
                        </h4>
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

    document.addEventListener("DOMContentLoaded", function () {
      const opportunitiesContainer = document.getElementById("opportunitiesContainer");
      const schoolFilter = document.getElementById("filter-school");
      const courseFilter = document.getElementById("filter-course");

      // Fetch schools and populate dropdown
      fetch('../../config/alumni/opportunities_controller.php?load=schools')
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            data.schools.forEach(school => {
              const option = document.createElement("option");
              option.value = school.id;
              option.textContent = school.name;
              schoolFilter.appendChild(option);
                        });
                    }
                })
                .catch(error => console.error("Error fetching schools:", error));

      // Fetch courses dynamically based on selected school
      schoolFilter.addEventListener("change", function () {
                const schoolId = schoolFilter.value;

                if (schoolId) {
                    fetch(`../../config/alumni/opportunities_controller.php?load=courses&school_id=${schoolId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                courseFilter.disabled = false;
                                courseFilter.innerHTML = "<option value=''>Select Course</option>";

                                data.courses.forEach(course => {
                                    const option = document.createElement("option");
                                    option.value = course.id;
                                    option.textContent = course.name;
                                    courseFilter.appendChild(option);
                                });
                            }
                        })
                        .catch(error => console.error("Error fetching courses:", error));
                } else {
                    courseFilter.disabled = true;
                    courseFilter.innerHTML = "<option value=''>Select Course</option>";
                }

                fetchOpportunities(schoolId, courseFilter.value);
            });

            // Fetch opportunities
            function fetchOpportunities(schoolId = null, courseId = null) {
                let url = `../../config/alumni/opportunities_controller.php?load=opportunities`;
                if (schoolId) url += `&school_id=${schoolId}`;
                if (courseId) url += `&course_id=${courseId}`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => renderOpportunities(data.opportunities))
                    .catch(error => console.error("Error fetching opportunities:", error));
            }

            // Render opportunities dynamically
            function renderOpportunities(opportunities) {
                opportunitiesContainer.innerHTML = "";

                if (!opportunities || opportunities.length === 0) {
                    opportunitiesContainer.innerHTML = "<p>No opportunities found.</p>";
                    return;
                }

                opportunities.forEach(opportunity => {
                    const opportunityCard = document.createElement("div");
                    opportunityCard.classList.add("opportunity-card");

                    opportunityCard.innerHTML = `
                        <div class="opportunity-details">
                            <h3>${opportunity.title}</h3>
                            <p>${opportunity.description}</p>
                            <p><strong>Company:</strong> ${opportunity.company_name}</p>
                            <p><strong>Location:</strong> ${opportunity.location}</p>
                            <p><strong>Posted on:</strong> ${opportunity.posted_date}</p>
                            <p><strong>Recommended related field:</strong> ${opportunity.course_name || "General"}</p>
                        </div>
                        <div class="opportunity-actions">
                            <a href="${opportunity.company_link}" target="_blank" class="apply-button">Apply</a>
                        </div>
                    `;
                    opportunitiesContainer.appendChild(opportunityCard);
                });
            }

            // Course filter listener
            courseFilter.addEventListener("change", function () {
                fetchOpportunities(schoolFilter.value, courseFilter.value);
            });

            // Initial fetch
            fetchOpportunities();
    });

    function confirmLogout() {
        if (confirm("Are you sure you want to logout?")) {
            window.location.href = "../../pages/alumni/loginpage.php";
        }
    }
  </script>
</body>

</html>
