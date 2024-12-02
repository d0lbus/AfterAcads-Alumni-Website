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
                    <!-- <li><a href="javascript:void(0);" onclick="confirmLogout()"><span><img src="../../assets/logout1.png" width="20px" alt="Logout" /></span>Logout</a></li> -->
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

                    <label for="batchDropdown">Batch:</label>
                    <select id="batchDropdown" class="dropdown"></select>

                </div>

                <!-- Tag Input -->
                <div class="tag-input-container">
                    <label for="tags">Tags:</label>
                    <input type="text" id="tags" class="tag-input" placeholder="Add tags (e.g., #Experience, #Project)">
                </div>

                <!-- File Upload -->
                <div class="addPost-option">
                    <label for="postImage">Upload Image:</label>
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
                    <select id="filterCourse" class="filter-select" disabled></select>

                    <label for="filterBatch" class="filter-label">Batch:</label>
                    <select id="filterBatch" class="filter-select"></select>
                </div>
            </div>
            

            <!-- Posts Section -->
            <div id="postsContainer" class="posts-container">
                <!-- Posts will be dynamically inserted here -->
            </div>

            <!-- Modal for Comments -->
            <div id="commentsModal" class="modal" style="display: none;">
                <div class="modal-content">
                    <span class="close-modal" onclick="document.getElementById('commentsModal').style.display='none'">&times;</span>
                    <h2 class="modal-title">Comments</h2>
                    <div class="comments-container" id="commentsContainer">
                        <!-- Comments will be dynamically loaded here -->
                    </div>
                    <div class="comment-input-section">
                        <textarea id="commentInput" class="comment-input" placeholder="Write a comment..."></textarea>
                        <button id="submitCommentButton" class="submit-comment-button">Submit</button>
                    </div>
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
                    dropdown.innerHTML = "<option value=''>None</option>";
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

        // Populate courses dynamically based on selected school in modal
        document.getElementById("modalSchool").addEventListener("change", function () {
            const schoolId = this.value;
            populateModalDropdown(`../../config/alumni/fetchCourses.php?school_id=${schoolId}`, "modalCourse", "id", "name");
        });

        function populateBatchDropdown() {
        fetch("../../config/alumni/fetchBatches.php")
        .then((response) => response.json())
        .then((data) => {
            const dropdown = document.getElementById("batchDropdown");
            dropdown.innerHTML = "<option value=''>None</option>";
            data.forEach((batch) => {
                const option = document.createElement("option");
                option.value = batch.id; 
                option.textContent = batch.batch_number; 
                dropdown.appendChild(option);
            });
        })
        .catch((error) => console.error("Error fetching batches:", error));
        }

        populateBatchDropdown();

        function populateBatchDropdownFilter() {
        fetch("../../config/alumni/fetchBatches.php")
        .then((response) => response.json())
        .then((data) => {
            const dropdown = document.getElementById("filterBatch");
            dropdown.innerHTML = "<option value=''>None</option>";
            data.forEach((batch) => {
                const option = document.createElement("option");
                option.value = batch.id; 
                option.textContent = batch.batch_number; 
                dropdown.appendChild(option);
            });
        })
        .catch((error) => console.error("Error fetching batches:", error));
        }

        populateBatchDropdownFilter();

        // Populate filter dropdowns
        function populateFilterDropdown(endpoint, dropdownId, valueField, textField) {
        fetch(endpoint)
            .then((response) => response.json())
            .then((data) => {
                const dropdown = document.getElementById(dropdownId);
                dropdown.innerHTML = "<option value=''>None</option>";
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

        // Populate courses dynamically based on selected school in filters
        document.getElementById("filterSchool").addEventListener("change", function () {
            const schoolId = this.value;
            populateFilterDropdown(`../../config/alumni/fetchCourses.php?school_id=${schoolId}`, "filterCourse", "id", "name");
        });

        const sortOrderDropdown = document.getElementById("sortOrder");

        const schoolDropdown = document.getElementById("filterSchool");
        const courseDropdown = document.getElementById("filterCourse");

        // Initially disable the course dropdown
        courseDropdown.disabled = true;

        // Enable course dropdown when a school is selected
        schoolDropdown.addEventListener("change", function () {
            if (schoolDropdown.value) {
                courseDropdown.disabled = false; 
            } else {
                courseDropdown.disabled = true; 
            }
        });

        // Show error message when trying to click the disabled course dropdown
        courseDropdown.addEventListener("click", function (event) {
            if (courseDropdown.disabled) {
                event.preventDefault(); 
                alert("Please select a school first."); 
            }
        });

        // Fetch and display posts
        function fetchPosts(schoolId = null, courseId = null, batchId = null, sort = "latest") {
            let url = `../../config/alumni/fetch_posts.php?sort=${sort}`;
            if (schoolId && schoolId !== "ALL") url += `&school_id=${schoolId}`;
            if (courseId && courseId !== "ALL") url += `&course_id=${courseId}`;
            if (batchId && batchId !== "ALL") url += `&batch_id=${batchId}`;

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
                            <div class="post-tags">Tags: ${
                                post.tags.length ? post.tags.join(", ") : "No tags"
                            }</div>
                            <div class="post-date">${new Date(post.created_at).toLocaleString()}</div>
                            <button class="view-comments-button" onclick="viewComments(${post.id})">View Comments</button>
                        `;
                        postsContainer.appendChild(postElement);
                    });
                })
                .catch((error) => console.error("Error fetching posts:", error));
        }

        // Fetch posts when filters change
        document.getElementById("filterSchool").addEventListener("change", function () {
            const schoolId = this.value;
            const courseId = document.getElementById("filterCourse").value;
            const batchId = document.getElementById("filterBatch").value;
            const sortOrder = document.getElementById("sortOrder").value;
            fetchPosts(schoolId, courseId, batchId, sortOrder);
        });

        document.getElementById("filterCourse").addEventListener("change", function () {
            const schoolId = document.getElementById("filterSchool").value;
            const courseId = this.value;
            const batchId = document.getElementById("filterBatch").value;
            const sortOrder = document.getElementById("sortOrder").value;
            fetchPosts(schoolId, courseId, batchId, sortOrder);
        });

        document.getElementById("filterBatch").addEventListener("change", function () {
            const batchId = this.value; 
            const schoolId = document.getElementById("filterSchool").value;
            const courseId = document.getElementById("filterCourse").value;
            const sortOrder = document.getElementById("sortOrder").value;

            const effectiveBatchId = batchId === '' ? 'ALL' : batchId;

            fetchPosts(schoolId, courseId, effectiveBatchId, sortOrder);
        });

        document.getElementById("sortOrder").addEventListener("change", function () {
            const sortOrder = this.value;
            const schoolId = document.getElementById("filterSchool").value;
            const courseId = document.getElementById("filterCourse").value;
            const batchId = document.getElementById("filterBatch").value;
            fetchPosts(schoolId, courseId, batchId, sortOrder);
        });

        // Initial fetch of posts
        fetchPosts();


        // Handle post creation
        document.getElementById("postButton").addEventListener("click", function (e) {
        e.preventDefault();

        const content = document.getElementById("postContent").value.trim();
        const schoolId = document.getElementById("modalSchool").value;
        const courseId = document.getElementById("modalCourse").value;
        const batchId = document.getElementById("batchDropdown").value;
        const tagInput = document.getElementById("tags").value.trim();
        const imageInput = document.getElementById("postImage");

        // Validation
        if (!content) {
            alert("Post content cannot be empty!");
            return;
        }
        if (!schoolId || !courseId || !batchId) {
            alert("Please select School, Course, and Batch!");
            return;
        }

        // Extract tags and validate them
        const extractedTags = tagInput
            .split(/\s+/) // Split by spaces
            .filter(tag => tag.startsWith('#') && tag.length > 1) // Ensure it starts with '#' and has content
            .map(tag => tag.substring(1)); // Remove the '#'

        if (extractedTags.length === 0 && tagInput.trim() !== "") {
            alert("All tags must start with # and cannot be empty!");
            return;
        }

        // Prepare form data
        const formData = new FormData();
        formData.append("content", content);
        formData.append("school_id", schoolId);
        formData.append("course_id", courseId);
        formData.append("batch_id", batchId);
        formData.append("tags", JSON.stringify(extractedTags));
        if (imageInput.files.length > 0) {
            formData.append("image", imageInput.files[0]);
        }

        // Send data to backend
        fetch("../../config/alumni/create_posts.php", {
            method: "POST",
            body: formData,
        })
        .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Post created successfully!");
                    location.reload(); // Reload to display the new post
                } else {
                    alert(data.message || "Failed to create post.");
                }
            })
                .catch(error => console.error("Error:", error));
            });
    });

    // Create Post Modal (expand/minimized)
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

    // View and Create Comments Modal
    function viewComments(postId) {
        const modal = document.getElementById("commentsModal");
        const commentsContainer = document.getElementById("commentsContainer");

        // Check if commentsContainer exists
        if (!commentsContainer) {
            console.error("commentsContainer element not found");
            return;
        }

        const commentInput = document.getElementById("commentInput");

        // Clear previous comments
        commentsContainer.innerHTML = "Loading comments...";

        // Fetch comments for the selected post
        fetch(`../../config/alumni/fetch_comments.php?post_id=${postId}`)
            .then((response) => response.json())
            .then((comments) => {
                commentsContainer.innerHTML = ""; // Clear loading text
                if (comments.length > 0) {
                    comments.forEach((comment) => {
                        const commentElement = document.createElement("div");
                        commentElement.classList.add("comment");
                        commentElement.innerHTML = `
                            <div class="comment-user">${comment.user_name}</div>
                            <div class="comment-content">${comment.comment}</div>
                            <div class="comment-date">${new Date(comment.created_at).toLocaleString()}</div>
                        `;
                        commentsContainer.appendChild(commentElement);
                    });
                } else {
                        commentsContainer.innerHTML = "<p>No comments yet.</p>";
                }
            })
            .catch((error) => {
                console.error("Error fetching comments:", error);
                commentsContainer.innerHTML = "<p>Error loading comments.</p>";
            });

            // Show the modal
            modal.style.display = "block";

            // Handle comment submission
            const submitButton = document.getElementById("submitCommentButton");
            submitButton.onclick = () => {
                const comment = commentInput.value.trim();
                if (!comment) {
                    alert("Comment cannot be empty!");
                    return;
                }

                // Send the comment to the server
                fetch("../../config/alumni/create_comment.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ post_id: postId, comment }),
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            alert("Comment added successfully!");
                            commentInput.value = ""; 
                            viewComments(postId); 
                        } else {
                            alert("Error adding comment.");
                        }
                    })
                    .catch((error) => {
                        console.error("Error adding comment:", error);
                        alert("Error adding comment.");
                    });
        };
    }   

    function confirmLogout() {
        if (confirm("Are you sure you want to logout?")) {
            window.location.href = "../../pages/alumni/loginpage.php";
        }
    }

</script>

</body>

</html>