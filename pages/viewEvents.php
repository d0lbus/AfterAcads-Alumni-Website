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

// Fetch the specific event details based on event_id
$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Event</title>
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link rel="stylesheet" href="../style/view-events.css">
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-flex">
                <div class="brand-icon">
                    <a href="javascript:void(0)" id="sidebarToggle">
                        <span><img src="../assets/bars1.png" width="24px" alt="bars"></span>
                    </a>
                </div>
            </div>
        </div>
        <div class="sidebar-content">
            <div class="sidebar-user">
                <a href="../pages/viewProfile.php">
                    <img src="../assets/display-photo.png" alt="Profile Picture">
                </a>
                <div>
                    <h3><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h3>
                    <span><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
            </div>
            <div class="sidebar-menu">
                <ul>
                <li><a href="../pages/shareExperience.php"><span><img src="../assets/home1.png" width="20px" alt="Home" /></span>Home</a></li>
                <li><a href="../pages/events.php"><span><img src="../assets/event1.png" width="20px" alt="Events" /></span>Events</a></li>
                <li><a href="../pages/opportunities.php"><span><img src="../assets/opportunities.png" width="20px" alt="Opportunities" /></span>Opportunities</a></li>
                <li><a href="../pages/settings.php"><span><img src="../assets/setting1.png" width="20px" alt="Settings" /></span>Settings</a></li>
                <li><a href="../pages/loginpage.php"><span><img src="../assets/logout1.png" width="20px" alt="Logout" /></span>Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="main-content">
        <header>
            <a href="../pages/shareExperience.php">
                <img src="../assets/logo.png" alt="logo" class="logo-header" />
            </a>
        </header>

        <main>
            <div class="page-header">
                <div class="content-container">
                    <div class="event-details">
                        <h1><?php echo htmlspecialchars($event['title']); ?></h1>
                        <p>
                            Hosted by: <?php echo htmlspecialchars($event['host']); ?><br>
                            Date: <?php echo htmlspecialchars($event['date']); ?><br>
                            Location: <?php echo htmlspecialchars($event['location']); ?><br>
                            Time: <?php echo htmlspecialchars($event['time']); ?>
                        </p>
                    </div>
                    <div class="button-container">
                        <a href="interested.php?event_id=<?php echo $event_id; ?>" class="button">INTERESTED</a>
                        <a href="going.php?event_id=<?php echo $event_id; ?>" class="button">GOING</a>
                    </div>
                </div>
                <div class="background-image">
                    <img src="<?php echo $event['image_path']; ?>" alt="<?php echo htmlspecialchars($event['alt_text']); ?>">
                </div>
            </div>
            <div class="event-description">
                <p><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
            </div>
        </main>
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
</body>
</html>
