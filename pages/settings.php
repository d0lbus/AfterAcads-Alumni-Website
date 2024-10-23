<?php
include '../config/connection.php';

session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../pages/loginpage.php");
    exit();
}

$email = $_SESSION['email'];

// Fetch the logged-in user's details from the database
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['change-password'];
    $newEmail = $_POST['change-email'];
    $newBio = $_POST['add-bio'];
    $newAddress = $_POST['change-address'];

    // Validate email
    if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        header("Location: settings.php?error=Invalid email format");
        exit();
    }

    // Hash password
    $passwordHash = password_hash($newPassword, PASSWORD_BCRYPT);

    // Update user settings in the database
    $sql = "UPDATE users SET password_hash = ?, email = ?, bio = ?, address = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssss', $passwordHash, $newEmail, $newBio, $newAddress, $email);

    if ($stmt->execute()) {
        $_SESSION['email'] = $newEmail; // Update session email if changed
        header("Location: settings.php?success=true");
        exit();
    } else {
        header("Location: settings.php?error=" . urlencode($conn->error));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Settings</title>
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" />
    <link rel="stylesheet" href="../style/settings.css" />

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
                    <h3><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h3>
                    <span><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
            </div>

            <div class="sidebar-menu">
                <ul>
                    <li>
                        <a href="../pages/shareExperience.php">
                            <span>
                                <img
                                    src="../assets/home1.png"
                                    width="20px"
                                    alt="Home" />
                            </span>Home
                        </a>
                    </li>
                    <li><a href="../pages/events.php"><span><img
                                    src="../assets/event1.png"
                                    width="20px"
                                    alt="Events" /></span>Events</a>
                    </li>
                    <li><a href="../pages/settings.php"><span><img
                                    src="../assets/setting1.png"
                                    width="20px"
                                    alt="Settings" /></span>Settings</a>
                    </li>
                    <li><a href="../pages/loginpage.php"><span><img
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
            <h1>Settings</h1>
            <small>Update your account settings below</small>
            <!-- Settings Form -->
            <form class="settings-form" method="post" action="settings.php">
                <div class="form-group">
                    <label for="change-password">Change Password</label>
                    <input type="password" name="change-password" id="change-password" placeholder="New Password"/>
                </div>

                <div class="form-group">
                    <label for="change-email">Change Email</label>
                    <input type="email" name="change-email" id="change-email" placeholder="New Email" required value="<?= htmlspecialchars($user['email']) ?>" />
                </div>

                <div class="form-group">
                    <label for="add-bio">Add Bio</label>
                    <textarea name="add-bio" id="add-bio" rows="4" placeholder="Tell us about yourself"><?= htmlspecialchars($user['bio']) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="change-address">Change Address</label>
                    <input type="text" name="change-address" id="change-address" placeholder="New Address" required value="<?= htmlspecialchars($user['address']) ?>" />
                </div>

                <div class="button-container">
                    <button type="submit" class="button">Save Changes</button>
                </div>
            </form>

            <!-- Display success or error messages -->
            <?php
            if (isset($_GET['success']) && $_GET['success'] === 'true') {
                echo '<p class="success-message">Settings updated successfully!</p>';
            } elseif (isset($_GET['error'])) {
                echo '<p class="error-message">Error: ' . htmlspecialchars($_GET['error']) . '</p>';
            }
            ?>
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
</body>

</html>