<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.html");
    exit();
}

include '../config/connection.php'; 

// Fetch user data based on the session email
$email = $_SESSION['email'];
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "Error: User not found.";
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../style/view-profile.css" />
</head>
<body>
    <div class="header-wrapper">
        <div class="header"></div> 
        <div class="cols-container">
            <div class="left-col">
                <div class="img-container">
                    <img src="/assets/display-photo.png" alt="Display Photo" />
                    <span></span>
                </div>
                <!-- Display user details dynamically -->
                <h2><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h2>
                <p><?php echo htmlspecialchars($user['email']); ?></p>

                <ul class="about">
                    <li><span>Joined <?php echo date('F d, Y', strtotime($user['created_at'])); ?></span></li>
                    <!-- TO DO: Display OTHER FIELDS DYNAMICALLY -->
                    <li><span>Lives in Baguio City</span></li>
                </ul>

                <div class="content">
                    <p>This is a bio section. You can personalize this later.</p>

                    <ul>
                        <li><i class="fa fa-twitter"></i></li>
                        <li><i class="fa fa-facebook"></i></li>
                        <li><i class="fa fa-instagram"></i></li>
                    </ul>
                </div>
            </div>
            <div class="right-col">
                <nav>
                    <ul>
                        <li><a href="#">Posts</a></li>
                        <li><a href="#">Photos</a></li>
                        <li><a href="#">Groups</a></li>
                        <li><a href="#">About</a></li>
                    </ul>
                    <button>Follow</button>
                </nav>

                <!-- TO DO: Display user's uploaded photos DYNAMICALLY  -->
                <div class="photos">
                    <img src="/assets/samcis-logo.jpg" alt="" />
                    <img src="/assets/slu-bakakeng.jpg" alt="" />
                    <img src="/assets/slu-lobby.jpg" alt="" />
                    <img src="/assets/profile.jpg" alt="" />
                </div>
            </div>
        </div>
    </div>
</body>
</html>
