<?php
include '../../config/alumni/header.php';
include '../../config/alumni/friendsManager.php';
include '../../config/general/connection.php';

$user = getAuthenticatedUser();

$friendsManager = new FriendsManager($conn);

$friends = $friendsManager->getFriends($user['id']);


$search = isset($_GET['search']) ? $_GET['search'] : '';
$school_id = isset($_GET['school_id']) ? $_GET['school_id'] : ''; 
$activeTab = isset($_GET['tab']) ? $_GET['tab'] : ''; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$total_pages = 0; 

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Events</title>
  <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" />
  <link rel="stylesheet" href="../../style/alumni/events.css" />
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
      
        <div class="header-actions-container">
          <form method="GET" action="events.php" class="header-search-bar">
            <input type="text" class="search-input" name="search" id="searchInput" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>" />
            <button type="submit" class="search-button" aria-label="Search"><span class="las la-search"></span></button>
            
            <!-- Dropdown container for search suggestions -->
            <div id="suggestions" class="suggestions-list"></div>
          </form>

          <form method="GET" action="events.php" class="school-dropdown">
            <select id="filter-events" name="school_id" onchange="this.form.submit()">
                <option value="">Select School</option>
                <?php
                $schools_query = "SELECT id, name FROM schools";
                $schools_result = $conn->query($schools_query);
                while ($school = $schools_result->fetch_assoc()):
                ?>
                    <option value="<?php echo $school['id']; ?>" <?php if ($school_id == $school['id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($school['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
          </form>
        </div>

        <h1>Events</h1>
        <small>See upcoming events and mark those you are interested in</small>

        <!-- Buttons for All Events and Interested Events -->
        <div class="event-buttons-container">
      
        </div>

        <div class="events-container" id="eventsContainer">
            <!-- Events will be dynamically loaded here -->
        </div>

        <div class="pagination" id="paginationContainer">
            <!-- Pagination buttons will be dynamically loaded here -->
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
    // Search functionality
    document.addEventListener("DOMContentLoaded", function() {
      const searchInput = document.getElementById("searchInput");
      const suggestionsList = document.getElementById("suggestions");

      // Example suggested search terms
      const suggestions = ["Upcoming events", "SAMCIS", "SEA", "General", "STELA", "SOHNABS"];
      
      searchInput.addEventListener("input", function() {
        const query = searchInput.value.toLowerCase();
        suggestionsList.innerHTML = ""; // Clear existing suggestions

        if (query.length > 0) {
          const filteredSuggestions = suggestions.filter(function(suggestion) {
            return suggestion.toLowerCase().includes(query);
          });

          filteredSuggestions.forEach(function(suggestion) {
            const suggestionElement = document.createElement("div");
            suggestionElement.classList.add("suggestion-item");
            suggestionElement.textContent = suggestion;
            suggestionElement.addEventListener("click", function() {
              searchInput.value = suggestion; // Set input field to selected suggestion
              suggestionsList.innerHTML = ""; // Clear suggestions after selection
            });
            suggestionsList.appendChild(suggestionElement);
          });
        }
      });

      // Hide suggestions when clicking outside
      document.addEventListener("click", function(e) {
        if (!e.target.closest('.header-search-bar')) {
          suggestionsList.innerHTML = ''; // Hide suggestions
        }
      });
    });

    // Responsive Sidebar
    document.addEventListener("DOMContentLoaded", function() {
      const sidebar = document.querySelector(".sidebar");
      const toggleButton = document.getElementById("sidebarToggle");

      toggleButton.addEventListener("click", function() {
          sidebar.classList.toggle("minimized");
      });
    });

    // Fetch Events Functionality
// Fetch Events Functionality
document.addEventListener("DOMContentLoaded", function () {
  const eventsContainer = document.getElementById("eventsContainer");
  const paginationContainer = document.getElementById("paginationContainer");
  const schoolFilter = document.getElementById("filter-events");
  const searchInput = document.getElementById("searchInput");

  // Track current filters
  let currentSchoolId = schoolFilter.value || null;
  let currentSearchQuery = searchInput.value || null;
  let currentPage = 1;

  function fetchEvents(page = 1, schoolId = null, search = null) {
    // Update state variables
    currentSchoolId = schoolId;
    currentSearchQuery = search;
    currentPage = page;

    let url = `../../config/alumni/events_controller.php?ajax=true&page=${page}`;
    if (schoolId) url += `&school_id=${schoolId}`;
    if (search) url += `&search=${encodeURIComponent(search)}`;

    fetch(url)
      .then((response) => response.json())
      .then((data) => {
        // Ignore stale responses
        if (schoolId !== currentSchoolId || search !== currentSearchQuery) {
          console.log("Stale fetch ignored.");
          return;
        }

        renderEvents(data.events);
        renderPagination(data.pagination, schoolId, search);
      })
      .catch((error) => console.error("Error fetching events:", error));
  }

  function renderEvents(events) {
    eventsContainer.innerHTML = ""; // Clear previous events
    if (events.length === 0) {
      eventsContainer.innerHTML = "<p>No events found.</p>";
      return;
    }

    events.forEach((event) => {
      const eventCard = document.createElement("div");
      eventCard.classList.add("event-card");

      eventCard.innerHTML = `
        <img src="${event.image_path}" alt="${event.alt_text}" class="event-image">
        <div class="event-details">
          <h3>${event.title}</h3>
          <p>${event.description}</p>
          <p><strong>Date:</strong> ${event.date}</p>
          <p><strong>Time:</strong> ${event.time}</p>
          <p><strong>Location:</strong> ${event.location}</p>
          <p><strong>School:</strong> ${event.school_name || "General"}</p>
        </div>
        <div class="event-participation">
          <button class="event-button going-button" data-event-id="${event.id}" data-status="going">
            Going (${event.going_count || 0})
          </button>
          <button class="event-button interested-button" data-event-id="${event.id}" data-status="interested">
            Interested (${event.interested_count || 0})
          </button>
        </div>
      `;
      eventsContainer.appendChild(eventCard);
    });

    // Attach event listeners after rendering
    document.querySelectorAll(".event-button").forEach((button) => {
      button.addEventListener("click", handleParticipation);
    });
  }

  function handleParticipation(event) {
    const button = event.target;
    const eventId = button.getAttribute("data-event-id");
    const status = button.getAttribute("data-status");

    // Toggle active state for the clicked button
    const parentContainer = button.parentNode;
    parentContainer.querySelectorAll(".event-button").forEach((btn) => {
      btn.classList.remove("active"); // Remove active class from all buttons in the same container
    });
    button.classList.add("active"); // Add active class to the clicked button

    // Send participation data to the backend
    fetch("../../config/alumni/participate_in_event.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ event_id: eventId, status: status }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert(`You are marked as '${status}' for this event!`);
          fetchEvents(currentPage, currentSchoolId, currentSearchQuery); // Refresh events to update counts
        } else {
          alert(data.message || "Failed to update participation status.");
        }
      })
      .catch((error) => console.error("Error updating participation:", error));
  }

  function renderPagination(pagination, schoolId, search) {
    paginationContainer.innerHTML = ""; // Clear previous pagination
    if (pagination.total_pages <= 1) return;

    for (let i = 1; i <= pagination.total_pages; i++) {
      const button = document.createElement("button");
      button.classList.add("pagination-button");
      button.textContent = i;
      if (i === pagination.current_page) button.classList.add("active");
      button.addEventListener("click", () => fetchEvents(i, schoolId, search));
      paginationContainer.appendChild(button);
    }
  }

  // Handle school filter change
  schoolFilter.addEventListener("change", function () {
    const schoolId = this.value || null;
    fetchEvents(1, schoolId, currentSearchQuery);
  });

  // Handle search input
  searchInput.addEventListener("input", function () {
    const searchQuery = this.value || null;
    fetchEvents(1, currentSchoolId, searchQuery);
  });

  // Initial fetch
  fetchEvents(currentPage, currentSchoolId, currentSearchQuery);
  });




    function confirmLogout() {
        if (confirm("Are you sure you want to logout?")) {
            window.location.href = "../../pages/alumni/loginpage.php";
        }
    }
  </script>
</body>

</html>
