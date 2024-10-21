<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['email'])) {
    header("Location: loginpage.php");
    exit();
}

include '../config/connection.php';

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

// Calculate the offset for the SQL query
$offset = ($page - 1) * $events_per_page;

// Count total number of events
$sql_count = "SELECT COUNT(*) AS total_events FROM events";
$result_count = $conn->query($sql_count);
$row_count = $result_count->fetch_assoc();
$total_events = $row_count['total_events'];

// Calculate the total number of pages
$total_pages = ceil($total_events / $events_per_page);

// Fetch events for the current page using LIMIT and OFFSET
$sql = "SELECT id, title, description, image_path, alt_text FROM events LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $events_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Events</title>
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" />
    <link rel="stylesheet" href="../style/view-events.css" />
</head>
<body>
<div class="sidebar-brand">
        <div class="brand-flex">
          <div class="brand-icon">
            <a href="javascript:void(0)" id="sidebarToggle">
              <span
                ><img src="../assets/bars1.png" width="24px" alt="bars"
              /></span>
            </a>
          </div>
          <img
            class="logocircle"
            src="../assets/alumnilogo.png"
            width="30px"
            alt=""
          />
        </div>
      </div>
      <div class="sidebar-content">
        <div class="sidebar-user">
          <a href="../pages/viewProfile.php">
            <img src="../assets/profile.jpg" alt="Profile Picture" />
          </a>
          <div>
            <h3>
              <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
            </h3>
            <span><?php echo htmlspecialchars($user['email']); ?></span>
          </div>
        </div>
        <div class="sidebar-menu">
          <div class="menu-head">
            <span>Dashboard</span>
          </div>
          <ul>
            <li>
              <a href="../pages/shareExperience.php"
                ><span
                  ><img
                    src="../assets/home1.png"
                    width="20px"
                    alt="Home" /></span
                >Home</a
              >
            </li>
            <li>
              <a href="../pages/events.php"
                ><span
                  ><img
                    src="../assets/event1.png"
                    width="20px"
                    alt="Events" /></span
                >Events</a
              >
            </li>
            <li>
              <a href="../pages/settings.php"
                ><span
                  ><img
                    src="../assets/setting1.png"
                    width="20px"
                    alt="Settings" /></span
                >Settings</a
              >
            </li>
            <li>
              <a href="../pages/loginpage.php"
                ><span
                  ><img
                    src="../assets/logout1.png"
                    width="20px"
                    alt="Logout" /></span
                >Logout</a
              >
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="main-content">
        <header>
            <div class="header-search-bar">
                <input type="text" class="search-input" placeholder="Search..." />
                <button class="search-button" aria-label="Search">
                    <span class="las la-search"></span>
                </button>
            </div>
        </header>
        
        <!-- The rest of the content appears below the search bar -->
        <main>
            <div class="page-header">
                <div class="content-container">
                    <div class="event-details">
                        <h1><?php echo htmlspecialchars($event['title']); ?></h1>
                        <p>
                            Held by: <?php echo htmlspecialchars($event['host']); ?><br />
                            When: <?php echo htmlspecialchars($event['date']); ?><br />
                            Where: <?php echo htmlspecialchars($event['location']); ?><br />
                            Time: <?php echo htmlspecialchars($event['time']); ?>
                        </p>
                    </div>
                    <div class="button-container">
                        <a href="interested.php?event_id=<?php echo $event_id; ?>" class="button">INTERESTED</a>
                        <a href="going.php?event_id=<?php echo $event_id; ?>" class="button">GOING</a>
                    </div>
                </div>
                <div class="background-image">
                    <img src="<?php echo $event['image_path']; ?>" alt="<?php echo htmlspecialchars($event['alt_text']); ?>" />
                </div>
            </div>
            <div class="event-description">
                <p><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
            </div>  
        </main>
    </div>
</body>
</html>
