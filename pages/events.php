<?php
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
    <!-- Sidebar Section -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-flex">
                <img class="logocircle" src="../assets/alumnilogo.png" width="30px" alt="Alumni Logo" />
                <div class="brand-icon">
                    <span class="las la-bell"></span>
                    <span class="las la-user-circle"></span>
                </div>
            </div>
        </div>
        <div class="sidebar-main">
            <div class="sidebar-user">
                <img src="../assets/display-photo.png" alt="Profile Photo" />
                <div>
                    <h3>User Name</h3>
                    <span>user@example.com</span>
                </div>
            </div>
            <div class="sidebar-menu">
                <div class="menu-head"><span>DASHBOARD</span></div>
                <ul>
                    <li><a href="#"><span class="las la-calendar"></span> Calendar</a></li>
                    <li><a href="#"><span class="las la-phone"></span> Contact</a></li>
                    <li><a href="../pages/events.php"><span class="las la-sign"></span> Events</a></li>
                    <li><a href="../pages/shareExperience.php"><span class="las la-image"></span> Share</a></li>
                    <li><a href="../pages/settings.php"><span class="las la-tools"></span> Settings</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Main Content Section -->
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
                    <h1>Events</h1>
                    <small>See upcoming events and mark those you are interested in</small>
                    
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
