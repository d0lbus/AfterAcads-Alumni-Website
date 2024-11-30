<?php
include '../../config/header.php'; 

$query = isset($_GET['query']) ? trim($_GET['query']) : '';

// Fetch results from the database (users, posts, etc.)
$searchResults = [];

if ($query) {
    $stmt = $conn->prepare("
        SELECT 'user' AS type, id, CONCAT(first_name, ' ', last_name) AS name, email 
        FROM users 
        WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ?
        UNION
        SELECT 'post' AS type, id, content AS name, '' AS email 
        FROM posts 
        WHERE content LIKE ?
    ");
    
    if ($stmt) {
        $likeQuery = "%{$query}%";
        $stmt->bind_param("ssss", $likeQuery, $likeQuery, $likeQuery, $likeQuery);
        $stmt->execute();
        $result = $stmt->get_result();
        $searchResults = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } else {
        echo "SQL Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Search Results</title>
    <link rel="stylesheet" href="../../style/searchResults.css" />
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" />
</head>

<body>
    <!-- Sidebar -->
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
                <a href="viewProfile.php">
                    <img src="../../assets/profileIcon.jpg" alt="Profile Picture" />
                </a>
                <div>
                    <h3>
                        <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                    </h3>
                    <span>
                        <?php echo htmlspecialchars($user['email']); ?>
                    </span>
                </div>
            </div>

            <div class="sidebar-menu">
            <ul>
          <li><a href="../../pages/alumni/shareExperience.php"><span><img src="../../assets/home1.png" width="20px" alt="Home" /></span>Home</a></li>
          <li><a href="../../pages/alumni/events.php"><span><img src="../../assets/event1.png" width="20px" alt="Events" /></span>Events</a></li>
          <li><a href="../../pages/alumni/opportunities.php"><span><img src="../../assets/opportunities.png" width="20px" alt="Opportunities" /></span>Opportunities</a></li>
          <li><a href="../../pages/alumni/notifications.php"><span><img src="../../assets/notification-removebg-preview.png" width="20px" alt="Notifications" /></span>Notifications</a></li>
          <li><a href="../../pages/alumni/settings.php"><span><img src="../../assets/setting1.png" width="20px" alt="Settings" /></span>Settings</a></li>
          <li><a href="../../pages/alumni/loginpage.php"><span><img src="../../assets/logout1.png" width="20px" alt="Logout" /></span>Logout</a></li>
        </ul>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <a href="shareExperience.php">
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
            <h1>Search Results for "<?php echo htmlspecialchars($query); ?>"</h1>
            <div class="results-container">
                <?php if (empty($searchResults)) : ?>
                    <p>No results found for your search.</p>
                <?php else : ?>
                    <?php foreach ($searchResults as $result) : ?>
                        <div class="result-item">
                            <?php if ($result['type'] === 'user') : ?>
                                <h3>
                                    <a href="viewProfile.php?user_id=<?php echo urlencode($result['id']); ?>">
                                        <?php echo htmlspecialchars($result['name']); ?>
                                    </a>
                                </h3>
                                <p>Email: <?php echo htmlspecialchars($result['email']); ?></p>
                            <?php elseif ($result['type'] === 'post') : ?>
                                <p><?php echo htmlspecialchars($result['name']); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <div class="right-panel">
        <h2>Friends</h2>
        <hr class="title-divider">
        <div class="friend-list">
            <div class="friend">
                <img src="../../assets/profile.jpg" alt="Friend Profile Picture">
                <span>Friend Name 1</span>
            </div>
        </div>
    </div>

    <script>
        // Responsive Sidebar
        document.addEventListener("DOMContentLoaded", function () {
            const sidebar = document.querySelector(".sidebar");
            const toggleButton = document.getElementById("sidebarToggle");

            toggleButton.addEventListener("click", function () {
                sidebar.classList.toggle("minimized");
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
                const searchInput = document.getElementById("searchInput");
                const searchButton = document.getElementById("searchButton");

                // Handle Enter Key Press
                searchInput.addEventListener("keypress", function (event) {
                    if (event.key === "Enter") {
                        event.preventDefault();
                        redirectToSearchResults(searchInput.value.trim());
                    }
                });

                // Handle Search Button Click
                searchButton.addEventListener("click", function () {
                    const searchQuery = searchInput.value.trim();
                    if (searchQuery) {
                        redirectToSearchResults(searchQuery);
                    }
                });

                // Redirect to Search Results Page
                function redirectToSearchResults(query) {
                    window.location.href = `searchResultsPage.php?query=${encodeURIComponent(query)}`;
                }
            });

    </script>
</body>

</html>