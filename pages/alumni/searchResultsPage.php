<?php
include '../../config/alumni/header.php';
include '../../config/alumni/friendsManager.php';
include '../../config/general/connection.php';

$user = getAuthenticatedUser();

$friendsManager = new FriendsManager($conn);

$friends = $friendsManager->getFriends($user['id']);

$query = isset($_GET['query']) ? trim($_GET['query']) : '';

// Fetch results from the database (users, posts, etc.)
$searchResults = [];

if ($query) {
    // Search for users and their details
    $stmt = $conn->prepare("
        SELECT 
            'user' AS type, 
            users.id, 
            CONCAT(users.first_name, ' ', users.last_name) AS name, 
            users.email, 
            NULL AS school, 
            NULL AS course, 
            NULL AS batch, 
            NULL AS content, 
            NULL AS tags, 
            NULL AS image, 
            NULL AS created_at
        FROM users 
        WHERE users.first_name LIKE ? OR users.last_name LIKE ? OR users.email LIKE ?
        
        UNION
        
        SELECT 
            'post' AS type, 
            posts.id, 
            CONCAT(users.first_name, ' ', users.last_name) AS name, 
            users.email,
            schools.name AS school, 
            courses.name AS course, 
            batches.batch_number AS batch, 
            posts.content AS content, 
            COALESCE(GROUP_CONCAT(DISTINCT tags.name), '') AS tags, 
            NULL AS image, 
            posts.created_at
        FROM posts
        LEFT JOIN users ON posts.user_id = users.id
        LEFT JOIN schools ON posts.school_id = schools.id
        LEFT JOIN courses ON posts.course_id = courses.id
        LEFT JOIN batches ON posts.batch_id = batches.id
        LEFT JOIN post_tags ON posts.id = post_tags.post_id
        LEFT JOIN tags ON post_tags.tag_id = tags.id
        WHERE users.first_name LIKE ? 
            OR users.last_name LIKE ? 
            OR posts.content LIKE ? 
            OR tags.name LIKE ? 
            OR schools.name LIKE ? 
            OR courses.name LIKE ?
        GROUP BY posts.id

        UNION

        SELECT 
            'event' AS type,
            events.id,
            events.title AS name,
            NULL AS email,
            NULL AS school,
            NULL AS course,
            NULL AS batch,
            events.description AS content,
            NULL AS tags,
            events.image_path AS image,
            events.created_at
        FROM events
        WHERE events.title LIKE ? 
            OR events.description LIKE ? 
            OR events.location LIKE ?
    ");
    
    if ($stmt) {
        $likeQuery = "%{$query}%";
        $stmt->bind_param("ssssssssssss", $likeQuery, $likeQuery, $likeQuery, $likeQuery, $likeQuery, $likeQuery, $likeQuery, $likeQuery, $likeQuery, $likeQuery, $likeQuery, $likeQuery);
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
    <link rel="stylesheet" href="../../style/alumni/searchResults.css" />
    <link rel="stylesheet" href="../../style/alumni/friends-panel.css" />
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
                                
                                <h1>USERS</h1>
                                <!-- Display User Information -->
                                <h3>
                                    <a href="viewProfile.php?user_id=<?php echo urlencode($result['id']); ?>">
                                        <?php echo htmlspecialchars($result['name']); ?>
                                    </a>
                                </h3>
                                <p>Email: <?php echo htmlspecialchars($result['email']); ?></p>
                            
                            <?php elseif ($result['type'] === 'post') : ?>
                                <h1>POSTS</h1>
                                <!-- Display Post Information -->
                                <h3>Posted by: <?php echo htmlspecialchars($result['name']); ?></h3>
                                <p>School: <?php echo htmlspecialchars($result['school']); ?></p>
                                <p>Course: <?php echo htmlspecialchars($result['course']); ?></p>
                                <p>Batch: <?php echo htmlspecialchars($result['batch']); ?></p>
                                <p><?php echo htmlspecialchars($result['content']); ?></p>

                                <!-- Display Tags -->
                                <?php if (!empty($result['tags'])) : ?>
                                    <p>Tags: <?php echo htmlspecialchars($result['tags']); ?></p>
                                <?php endif; ?>

                                <!-- Highlight Matching Words in Tags, Content, and Other Fields -->
                                <?php 
                                $highlightedContent = preg_replace('/(' . preg_quote($query, '/') . ')/i', '<span class="highlight">$1</span>', $result['content']);
                                $highlightedTags = preg_replace('/(' . preg_quote($query, '/') . ')/i', '<span class="highlight">$1</span>', $result['tags']);
                                $highlightedSchool = preg_replace('/(' . preg_quote($query, '/') . ')/i', '<span class="highlight">$1</span>', $result['school']);
                                $highlightedCourse = preg_replace('/(' . preg_quote($query, '/') . ')/i', '<span class="highlight">$1</span>', $result['course']);
                                ?>

                                <p>Content: <?php echo $highlightedContent; ?></p>
                                <p>Tags: <?php echo $highlightedTags; ?></p>
                                <p>School: <?php echo $highlightedSchool; ?></p>
                                <p>Course: <?php echo $highlightedCourse; ?></p>

                                <!-- Display Image -->
                                <!-- <p><?php echo htmlspecialchars($result['name']); ?></p> -->
                                <?php if (!empty($result['image'])) : ?>
                                    <img src="data:image/jpeg;base64,<?php echo $result['image']; ?>" alt="Post Image" class="post-image">
                                <?php endif; ?>

                                <!-- Display Timestamp -->
                                <p><?php echo htmlspecialchars($result['created_at']); ?></p>

                                <?php elseif ($result['type'] === 'event') : ?>
                            <h1>EVENTS</h1>
                            <!-- Display Event Information -->
                            <h3>
                                <a href="viewEvent.php?event_id=<?php echo urlencode($result['id']); ?>">
                                    <?php echo htmlspecialchars($result['name']); ?>
                                </a>
                            </h3>
                            <p>Description: <?php echo htmlspecialchars($result['content']); ?></p>

                            <!-- Display Event Image -->
                            <?php if (!empty($result['image'])) : ?>
                                <img src="../../uploads/<?php echo htmlspecialchars($result['image']); ?>" alt="Event Image" class="event-image">
                            <?php endif; ?>

                            <!-- Display Timestamp -->
                            <p>Posted on: <?php echo htmlspecialchars($result['created_at']); ?></p>
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

            function confirmLogout() {
        if (confirm("Are you sure you want to logout?")) {
            window.location.href = "../../pages/alumni/loginpage.php";
        }
    }

    </script>
</body>

</html>