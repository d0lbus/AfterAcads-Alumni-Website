<?php
include '../../config/alumni/header.php';
include '../../config/alumni/friendsManager.php';
include '../../config/general/connection.php';

$user = getAuthenticatedUser();

$friendsManager = new FriendsManager($conn);

$friends = $friendsManager->getFriends($user['id']);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home</title>
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" />
    <link rel="stylesheet" href="../../style/alumni/shareExperience.css" />
    <link rel="stylesheet" href="../../style/alumni/friends-panel.css" />
</head>

<body>
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
          <li><a href="javascript:void(0);" onclick="confirmLogout()"><span><img src="../../assets/logout1.png" width="20px" alt="Logout" /></span>Logout</a></li>
        </ul>
            </div>
        </div>
    </div>

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

            <!-- Post Creation Section -->
            <div class="addPost">
                <div class="addPost-header">
                    <img src="../../assets/profileIcon.jpg" alt="Profile" class="profile-pic">
                    <textarea id="postContent" placeholder="What's on your mind?" class="post-input" rows="1"></textarea>
                </div>

                <!-- Dynamic Dropdowns -->
                <div class="dropdown-container">
                    <label for="modalSchool">School:</label>
                    <select id="modalSchool" class="dropdown"></select>

                    <label for="modalCourse">Course:</label>
                    <select id="modalCourse" class="dropdown"></select>

                    <label for="modalBatch">Batch:</label>
                    <select id="modalBatch" class="dropdown"></select>
                </div>

                <!-- Tag Input -->
                <div class="tag-input-container">
                    <label for="tags">Tags:</label>
                    <input type="text" id="tags" class="tag-input" placeholder="Add tags (e.g., #Experience, #Project)">
                </div>

                <!-- File Upload -->
                <div class="addPost-option">
                    <input type="file" id="postImage" accept="image/*">
                </div>

                <button id="postButton" class="post-button">Post</button>
            </div>

            
             <!-- Filters -->
             <div class="sort-filter-container">
                <div class="sort-dropdown">
                    <label for="sortOrder" class="sort-label">Sort by:</label>
                    <select id="sortOrder" class="sort-select">
                        <option value="latest">Latest</option>
                        <option value="oldest">Oldest</option>
                    </select>
                </div>
                <div class="filter-dropdown">
                    <label for="filterSchool" class="filter-label">School:</label>
                    <select id="filterSchool" class="filter-select"></select>

                    <label for="filterCourse" class="filter-label">Course:</label>
                    <select id="filterCourse" class="filter-select"></select>

                    <label for="filterBatch" class="filter-label">Batch:</label>
                    <select id="filterBatch" class="filter-select"></select>
                </div>
            </div>
            

            <!-- Posts Section -->
            <div id="postsContainer" class="posts-container">
                <!-- Posts will be dynamically inserted here -->
            </div>


            <!-- Modal for Creating a Post -->
            <div class="modal" id="postModal" style="display: none;">
            <div class="modal-content">
            <!-- Close Button -->
            <span class="close-modal">&times;</span>

            <!-- Modal Header -->
            <h2 class="modal-title">Create Post</h2>
            <div class="line"></div>

            <!-- User Info -->
            <div class="modal-header">
                <img src="../../assets/profileIcon.jpg" alt="Profile" class="profile-pic">
                <span><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></span>
            </div>

            <div class="modal-divider"></div>

        <!-- Post Content -->
        <textarea id="modalPostContent" placeholder="What's on your mind?" class="post-input" rows="3"></textarea>

        <!-- Tag Input -->
        <div class="tag-input-container">
            <label for="modalTags">Tags:</label>
            <input type="text" id="modalTags" class="tag-input" placeholder="Add tags (e.g., #Experience, #Project)">
        </div>

        <!-- Image Upload -->
        <div class="modal-add-option">
            <label for="modalPostImage">Upload an Image:</label>
            <input type="file" id="modalPostImage" accept="image/*">
        </div>

        <!-- Submit Button -->
        <button id="modalPostButton" class="post-button">Post</button>
        </div>
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
                                    <!-- Make the name a clickable link -->
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
    document.addEventListener("DOMContentLoaded", function () {

        const sidebar = document.querySelector(".sidebar");
        const toggleButton = document.getElementById("sidebarToggle");

        toggleButton.addEventListener("click", function() {
            sidebar.classList.toggle("minimized");
        });
        
        // Populate modal dropdowns
        function populateModalDropdown(endpoint, dropdownId, valueField, textField) {
            fetch(endpoint)
                .then((response) => response.json())
                .then((data) => {
                    const dropdown = document.getElementById(dropdownId);
                    dropdown.innerHTML = "<option value=''>Select an option</option>";
                    data.forEach((item) => {
                        const option = document.createElement("option");
                        option.value = item[valueField];
                        option.textContent = item[textField];
                        dropdown.appendChild(option);
                    });
                })
                .catch((error) => console.error(`Error populating ${dropdownId}:`, error));
        }

        // Populate schools for modal
        populateModalDropdown("../../config/alumni/fetchSchools.php", "modalSchool", "id", "name");

        // Populate batches for modal
        populateModalDropdown("../../config/alumni/fetchBatches.php", "modalBatch", "batch_number", "batch_number");

        // Populate courses dynamically based on selected school in modal
        document.getElementById("modalSchool").addEventListener("change", function () {
            const schoolId = this.value;
            populateModalDropdown(`../../config/alumni/fetchCourses.php?school_id=${schoolId}`, "modalCourse", "id", "name");
        });

        // Populate filter dropdowns
        function populateFilterDropdown(endpoint, dropdownId, valueField, textField) {
        fetch(endpoint)
            .then((response) => response.json())
            .then((data) => {
                const dropdown = document.getElementById(dropdownId);
                dropdown.innerHTML = "<option value=''>Select an option</option>";
                data.forEach((item) => {
                    const option = document.createElement("option");
                    option.value = item[valueField];
                    option.textContent = item[textField];
                    dropdown.appendChild(option);
                });
            })
            .catch((error) => console.error(`Error populating ${dropdownId}:`, error));
        }

        // Populate schools for filters
        populateFilterDropdown("../../config/alumni/fetchSchools.php", "filterSchool", "id", "name");

        // Populate batches for filters
        populateFilterDropdown("../../config/alumni/fetchBatches.php", "filterBatch", "batch_number", "batch_number");

        // Populate courses dynamically based on selected school in filters
        document.getElementById("filterSchool").addEventListener("change", function () {
            const schoolId = this.value;
            populateFilterDropdown(`../../config/alumni/fetchCourses.php?school_id=${schoolId}`, "filterCourse", "id", "name");
        });

        const sortOrderDropdown = document.getElementById("sortOrder");

        // Fetch and display posts
        function fetchPosts(schoolId = null, courseId = null, batch = null, sort = "latest") {
        let url = `../../config/alumni/fetch_posts.php?sort=${sort}`;
        if (schoolId) url += `&school_id=${schoolId}`;
        if (courseId) url += `&course_id=${courseId}`;
        if (batch) url += `&batch=${batch}`;

        fetch(url)
            .then((response) => response.json())
            .then((posts) => {
                const postsContainer = document.getElementById("postsContainer");
                postsContainer.innerHTML = ""; // Clear previous posts
                posts.forEach((post) => {
                    const postElement = document.createElement("div");
                    postElement.classList.add("post");
                    postElement.innerHTML = `
                        <div class="post-user">Posted by: <strong>${post.full_name}</strong></div>
                        <div class="post-school">School: ${post.school}</div>
                        <div class="post-course">Course: ${post.course}</div>
                        <div class="post-batch">Batch: ${post.batch}</div>
                        <div class="post-content">${post.content}</div>
                        ${
                            post.image
                                ? `<img src="data:image/jpeg;base64,${post.image}" alt="Post Image" />`
                                : ""
                        }
                        <div class="post-tags">Tags: ${post.tags?.join(", ") || "No tags"}</div>
                        <div class="post-date">${new Date(post.created_at).toLocaleString()}</div>
                    `;
                    postsContainer.appendChild(postElement);
                });
            })
            .catch((error) => console.error("Error fetching posts:", error));
        }

        // Fetch posts when sort order changes
        sortOrderDropdown.addEventListener("change", function () {
            const sortOrder = this.value;
            const schoolId = document.getElementById("filterSchool").value;
            const courseId = document.getElementById("filterCourse").value;
            const batch = document.getElementById("filterBatch").value;
            fetchPosts(schoolId, courseId, batch, sortOrder);
        });

        // Fetch posts when filters change
        document.getElementById("filterSchool").addEventListener("change", function () {
            const schoolId = this.value;
            const courseId = document.getElementById("filterCourse").value;
            const batch = document.getElementById("filterBatch").value;
            const sortOrder = document.getElementById("sortOrder").value;
            fetchPosts(schoolId, courseId, batch, sortOrder);
        });

        document.getElementById("filterCourse").addEventListener("change", function () {
            const schoolId = document.getElementById("filterSchool").value;
            const courseId = this.value;
            const batch = document.getElementById("filterBatch").value;
            const sortOrder = document.getElementById("sortOrder").value;
            fetchPosts(schoolId, courseId, batch, sortOrder);
        });

        document.getElementById("filterBatch").addEventListener("change", function () {
            const schoolId = document.getElementById("filterSchool").value;
            const courseId = document.getElementById("filterCourse").value;
            const batch = this.value;
            const sortOrder = document.getElementById("sortOrder").value;
            fetchPosts(schoolId, courseId, batch, sortOrder);
        });

        // Fetch posts when sort order changes
        document.getElementById("sortOrder").addEventListener("change", function () {
            const sortOrder = this.value;
            const schoolId = document.getElementById("filterSchool").value;
            const courseId = document.getElementById("filterCourse").value;
            const batch = document.getElementById("filterBatch").value;
            fetchPosts(schoolId, courseId, batch, sortOrder);
        });

        // Initial fetch of posts
        fetchPosts();
    });
    
    // Create Post (expand/minimized)
    document.addEventListener("DOMContentLoaded", function() {
        const addPost = document.querySelector(".addPost");
        const postContent = document.getElementById("postContent");

        // Initially minimize the addPost
        addPost.classList.add("minimized");

        // Expand addPost when the textarea is clicked
        postContent.addEventListener("click", function(event) {
            event.stopPropagation(); // Prevent the event from bubbling up to the document
            addPost.classList.remove("minimized");
            addPost.classList.add("expanded");
        });

        // Collapse addPost when clicking outside
        document.addEventListener("click", function(event) {
            if (!addPost.contains(event.target)) {
                addPost.classList.remove("expanded");
                addPost.classList.add("minimized");
            }
        });
    });

    function confirmLogout() {
        if (confirm("Are you sure you want to logout?")) {
            window.location.href = "../../pages/alumni/loginpage.php";
        }
    }

</script>

</body>

</html>