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

include '../config/connection.php'; // Database connection

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
    <title>Events</title>
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" />
    <link rel="stylesheet" href="../style/events.css" />
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-flex">
                <img class="logocircle" src="../assets/alumnilogo.png" width="30px" alt="" />
                <div class="brand-icon">
                    <a href="your-link-here.html">
                        <span class="las la-bars"></span>
                    </a>
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
                    <li><a href="../pages/shareExperience.php"><span class="las la-home"></span>Home</a></li>
                    <li><a href="../pages/events.php"><span class="las la-sign"></span>Events</a></li>
                    <li><a href="../pages/settings.php"><span class="las la-tools"></span>Settings</a></li>
                    <li><a href="../pages/loginpage.php"><span class="las la-sign-out-alt"></span>Logout</a></li>
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
        <main>
            <div class="page-header">
                <div>
                    <h1>Events</h1>
                    <small>See upcoming events and mark those you are interested in</small>

                    <!-- Dropdown Filter -->
                    <div class="dropdown-container">
                        <select id="filter-events" name="filter-events">
                            <option value="" disabled selected>Filter Events</option>
                            <option value="filter1">Placeholder Filter 1</option>
                            <option value="filter2">Placeholder Filter 2</option>
                            <option value="filter3">Placeholder Filter 3</option>
                        </select>
                    </div>                    

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
                            <a href="events.php?page=<?php echo $page - 1; ?>" class="button">&laquo; Previous</a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="events.php?page=<?php echo $i; ?>" class="button <?php if ($i == $page) echo 'active'; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <a href="events.php?page=<?php echo $page + 1; ?>" class="button">Next &raquo;</a>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </main>
    </div>
</body>
</html>
