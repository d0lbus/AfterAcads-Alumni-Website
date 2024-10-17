<?php
include '../config/connection.php';

$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;

// Fetch the event details from the database
$sql = "SELECT * FROM events WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $event = $result->fetch_assoc();
} else {
    echo "Event not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($event['title']); ?></title>
    <link
      rel="stylesheet"
      href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css"
    />
    <link rel="stylesheet" href="../style/view-events.css" />
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

    <div class="main-content">
        <header>
            <span class="las la-bars"></span>
            <div class="header-icons">
                <span class="las la-search"></span>
            </div>
        </header>
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
