<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['email'])) {
  header("Location: loginpage.php");
  exit();
}

include '../config/connection.php'; // Database connection

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

// Number of events per page
$events_per_page = 5;

// Get the current page number from the query string (default to 1 if not set)
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;

// Get the search and tag filter inputs
$search = isset($_GET['search']) ? $_GET['search'] : null;
$tag = isset($_GET['tag']) ? $_GET['tag'] : null;

// Calculate the offset for the SQL query
$offset = ($page - 1) * $events_per_page;

// Base SQL query
$sql = "SELECT id, title, description, image_path, alt_text, tag FROM events WHERE 1=1";

// Append tag filter if selected
if ($tag) {
    $sql .= " AND tag = ?";
}

// Append search filter if provided
if ($search) {
    $sql .= " AND (title LIKE ? OR description LIKE ?)";
}

// Count total number of events for pagination
$sql_count = str_replace("id, title, description, image_path, alt_text, tag", "COUNT(*) AS total_events", $sql);
$stmt_count = $conn->prepare($sql_count);
if ($tag && $search) {
    $search_term = '%' . $search . '%';
    $stmt_count->bind_param("sss", $tag, $search_term, $search_term);
} elseif ($tag) {
    $stmt_count->bind_param("s", $tag);
} elseif ($search) {
    $search_term = '%' . $search . '%';
    $stmt_count->bind_param("ss", $search_term, $search_term);
}
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$row_count = $result_count->fetch_assoc();
$total_events = $row_count['total_events'];

// Calculate the total number of pages
$total_pages = ceil($total_events / $events_per_page);

// Append pagination using LIMIT and OFFSET
$sql .= " LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
if ($tag && $search) {
    $stmt->bind_param('ssiii', $tag, $search_term, $search_term, $events_per_page, $offset);
} elseif ($tag) {
    $stmt->bind_param('sii', $tag, $events_per_page, $offset);
} elseif ($search) {
    $stmt->bind_param('ssii', $search_term, $search_term, $events_per_page, $offset);
} else {
    $stmt->bind_param('ii', $events_per_page, $offset);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Events</title>
  <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" />
  <link rel="stylesheet" href="../style/events.css" />
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
          <h3>
            <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
          </h3>
          <span><?php echo htmlspecialchars($user['email']); ?></span>
        </div>
      </div>
      <div class="sidebar-menu">
        <ul>
          <li>
            <a href="../pages/shareExperience.php"><span><img
                  src="../assets/home1.png"
                  width="20px"
                  alt="Home" /></span>Home</a>
          </li>
          <li>
            <a href="../pages/events.php"><span><img
                  src="../assets/event1.png"
                  width="20px"
                  alt="Events" /></span>Events</a>
          </li>
          <li>
            <a href="../pages/settings.php"><span><img
                  src="../assets/setting1.png"
                  width="20px"
                  alt="Settings" /></span>Settings</a>
          </li>
          <li>
            <a href="../pages/loginpage.php"><span><img
                  src="../assets/logout1.png"
                  width="20px"
                  alt="Logout" /></span>Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div class="main-content">
    <header>
        <img src="../assets/alumnilogo.png" alt="logo" class="logo-header" />
        <img src="../assets/afteracadstext.png" alt="AfterAcads" class="after-acads-text" />
    </header>

    <main>
        <div class="page-header">
            <div>
                <h1>Events</h1>
                <small>See upcoming events and mark those you are interested in</small>

                <!-- Container for Search Bar and Tag Dropdown -->
                <div class="header-actions-container">
                    <!-- Search Bar -->
                    <div class="header-search-bar">
                        <form method="GET" action="events.php">
                            <input type="text" class="search-input" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>" />
                            <button type="submit" class="search-button" aria-label="Search">
                                <span class="las la-search"></span>
                            </button>
                        </form>
                    </div>

                    <!-- Dropdown Filter by Tag -->
                    <div class="tag-dropdown">
                        <form method="GET" action="events.php">
                            <select id="filter-events" name="tag" onchange="this.form.submit()">
                                <option value="">All Tags</option>
                                <option value="GENERAL" <?php if ($tag === 'GENERAL') echo 'selected'; ?>>General</option>
                                <option value="SAMCIS" <?php if ($tag === 'SAMCIS') echo 'selected'; ?>>SAMCIS</option>
                                <option value="SOHNABS" <?php if ($tag === 'SOHNABS') echo 'selected'; ?>>SOHNABS</option>
                                <option value="STELA" <?php if ($tag === 'STELA') echo 'selected'; ?>>STELA</option>
                                <option value="SEA" <?php if ($tag === 'SEA') echo 'selected'; ?>>SEA</option>
                            </select>
                        </form>
                    </div>
                </div>

                <!-- Display Events -->
                <div class="card-container">
                    <?php while ($event = $result->fetch_assoc()): ?>
                        <div class="card">
                            <img src="<?php echo $event['image_path']; ?>" alt="<?php echo htmlspecialchars($event['alt_text']); ?>">
                            <div class="container">
                                <h2><b><?php echo htmlspecialchars($event['title']); ?></b></h2>
                                <p><?php echo htmlspecialchars($event['description']); ?></p>
                            </div>
                            <div class="button-container">
                                <a href="viewEvents.php?event_id=<?php echo $event['id']; ?>" class="button">VIEW</a>
                                <a href="interested.php?event_id=<?php echo $event['id']; ?>" class="button">INTERESTED</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- Pagination Section -->
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="events.php?page=<?php echo $page - 1; ?>&search=<?php echo htmlspecialchars($search); ?>&tag=<?php echo htmlspecialchars($tag); ?>" class="button">&laquo; Previous</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="events.php?page=<?php echo $i; ?>&search=<?php echo htmlspecialchars($search); ?>&tag=<?php echo htmlspecialchars($tag); ?>" class="button <?php if ($i == $page) echo 'active'; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="events.php?page=<?php echo $page + 1; ?>&search=<?php echo htmlspecialchars($search); ?>&tag=<?php echo htmlspecialchars($tag); ?>" class="button">Next &raquo;</a>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </main>
</div>



  <div class="right-panel">
    <h2>Friends</h2>
    <hr class="title-divider">
    <div class="friend-list">
      <div class="friend">
        <img src="../assets/profile.jpg" alt="Friend Profile Picture">
        <span>Friend Name 1</span>
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
  </div>
</body>

</html>