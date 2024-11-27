<?php
include '../config/header.php';
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
    <link rel="stylesheet" href="../style/shareExperience.css" />
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
                    <img src="../assets/profileIcon.jpg" alt="Profile Picture" />
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
                    <li>
                        <a href="../pages/shareExperience.php">
                            <span><img src="../assets/home1.png" width="20px" alt="Home" /></span>Home
                        </a>
                    </li>
                    <li>
                        <a href="../pages/events.php">
                            <span><img src="../assets/event1.png" width="20px" alt="Events" /></span>Events
                        </a>
                    </li>
                    <li><a href="../pages/opportunities.php">
                            <span><img src="../assets/opportunities.png" width="20px"
                                    alt="Opportunities" /></span>Opportunities</a></li>
                    <li>
                        <a href="../pages/settings.php">
                            <span><img src="../assets/setting1.png" width="20px" alt="Settings" /></span>Settings
                        </a>
                    </li>
                    <li>
                        <a href="../pages/loginpage.php">
                            <span><img src="../assets/logout1.png" width="20px" alt="Logout" /></span>Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="main-content">
        <header>
            <img src="../assets/logo.png" alt="logo" class="logo-header" />
        </header>

        <main>

            <div class="header-search-bar">
                <input type="text" class="search-input" id="searchInput" placeholder="Search..." />
                <button class="search-button" id="searchButton" aria-label="Search">
                    <span><img src="../assets/search1.png" width="20px" alt="search" /></span>
                </button>
            </div>
            <!-- Post creation section -->
            <div class="addPost">
                <div class="addPost-header">
                    <img src="../assets/profileIcon.jpg" alt="Profile" class="profile-pic" />
                    <textarea id="postContent"
                        placeholder="What's on your mind, <?php echo htmlspecialchars($user['first_name']); ?>?"
                        class="post-input" rows="1"></textarea>
                </div>

                <div class="tag-dropdown">
                    <label for="tags" class="tag-label">Select a Tag:</label>
                    <select id="tags" class="tag-select">
                        <option value="GENERAL">GENERAL</option>
                        <option value="SAMCIS">SAMCIS</option>
                        <option value="SOHNABS">SOHNABS</option>
                        <option value="STELA">STELA</option>
                        <option value="SEA">SEA</option>
                    </select>
                </div>

                <div class="addPost-option">
                    <input type="file" id="postImage" accept="image/*">
                </div>
                <button id="postButton" class="post-button">Post</button>
            </div>

            

            <div class="sort-filter-container">
                <div class="sort-dropdown">
                    <label for="sortOrder" class="sort-label">Sort by:</label>
                    <select id="sortOrder" class="sort-select">
                        <option value="latest">Latest</option>
                        <option value="oldest">Oldest</option>
                    </select>
                </div>
                <div class="filter-dropdown">
                    <label class="filter-label">Filter by:</label>
                    <select id="filterSchool" class="filter-select">
                        <option value="">School</option>
                        <option value="SAMCIS">SAMCIS</option>
                        <option value="SEA">SEA</option>
                        <option value="SOHNABS">SOHNABS</option>
                    </select>
                    <select id="filterCourse" class="filter-select">
                        <option value="">Course</option>
                        <option value="Computer Science">Computer Science</option>
                        <option value="Information Technology">Information Technology</option>
                        <option value="Nursing">Nursing</option>
                    </select>
                    <select id="filterBatch" class="filter-select">
                        <option value="">Batch</option>
                        <option value="223">223</option>
                        <option value="222">222</option>
                        <option value="221">221</option>
                    </select>
                </div>
            </div>
            


            <!-- Dynamic posts container -->
            <div id="postsContainer"></div>

            <!-- Modal for creating a post -->
            <div class="modal" id="postModal">
                <div class="modal-content">
                    <span class="close-modal">&times;</span>
                    <h2 class="modal-title">Create Post</h2>
                    <div class="line"></div>
                    <div class="modal-header">
                        <img src="../assets/profile.jpg" alt="Profile" class="profile-pic" />
                        <span>
                            <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                        </span>
                        <textarea id="modalPostContent" placeholder="What's on your mind?" class="post-input"
                            rows="3"></textarea>
                    </div>

                    <div class="modal-divider"></div>

                    <div class="tag-dropdown">
                        <label for="modal-tags" class="tag-label">Select a Tag:</label>
                        <select id="modal-tags" class="tag-select">
                            <option value="">Select a Tag</option>
                            <option value="SAMCIS">SAMCIS</option>
                            <option value="SOHNABS">SOHNABS</option>
                            <option value="STELA">STELA</option>
                            <option value="SEA">SEA</option>
                        </select>
                    </div>

                    <div class="modal-add-option">
                        <input type="file" id="modalPostImage" accept="image/*">
                    </div>
                    <button class="post-button">Post</button>
                </div>
            </div>


        </main>
    </div>

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

    <script>
        // Modal open/close functionality
        document.addEventListener("DOMContentLoaded", function () {
            const modal = document.getElementById("postModal");
            const openModalButton = document.getElementById("addPhotoVideoButton");
            const closeModalButton = document.querySelector(".close-modal");

            if (openModalButton) {
                openModalButton.onclick = function () {
                    modal.style.display = "block";
                };
            }

            if (closeModalButton) {
                closeModalButton.onclick = function () {
                    modal.style.display = "none";
                };
            }

            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            };

            // Handle creating a post
            document.getElementById("postButton").addEventListener("click", function (e) {
                e.preventDefault();

                const content = document.getElementById("postContent").value;
                const tag = document.getElementById("tags").value; // Selected tag
                const imageInput = document.getElementById("postImage");

                // Validate content
                if (!content.trim()) {
                    alert("Post content cannot be empty!");
                    return;
                }

                // Validate tag selection
                if (!tag) {
                    alert("Please select a tag before posting.");
                    return;
                }

                // Form data for sending the post
                const formData = new FormData();
                formData.append("content", content);
                formData.append("tag", tag);
                if (imageInput.files.length > 0) {
                    formData.append("image", imageInput.files[0]);
                }

                fetch("../config/create_posts.php", {
                    method: "POST",
                    body: formData,
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            alert("Post created successfully!");
                            location.reload(); // Reload to display new post
                        } else {
                            alert("Error creating post.");
                        }
                    })
                    .catch((error) => console.error("Error:", error));
            });

            // Fetch and display posts with optional filters (tag, search, sort)
            function fetchPosts(tag = null, search = null, sort = 'latest') {
                let url = `../config/fetch_posts.php?sort=${sort}`; // Add sorting to the URL
                if (tag) {
                    url += `&tag=${tag}`;
                }
                if (search) {
                    url += `&search=${search}`;
                }

                fetch(url)
                    .then((response) => response.json())
                    .then((posts) => {
                        const postsContainer = document.getElementById("postsContainer");
                        postsContainer.innerHTML = "";
                        posts.forEach((post) => {
                            const postElement = document.createElement("div");
                            postElement.classList.add("post");
                            postElement.innerHTML = `
                                <div class="post-user">${post.full_name}</div>
                                <div class="post-content">${post.content}</div>
                                ${post.image
                                    ? `<img src="data:image/jpeg;base64,${post.image}" alt="Post Image" />`
                                    : ""
                                }
                                <div class="post-tag">Tag: ${post.tag}</div>
                                <div class="post-date">${new Date(post.created_at).toLocaleString()}</div>
                            `;
                            postsContainer.appendChild(postElement);
                        });
                    })
                    .catch((error) => console.error("Error fetching posts:", error));
            }

            // Filter posts by tag
            document.getElementById("filterTag").addEventListener("change", function () {
                const selectedTag = this.value;
                const sortOrder = document.getElementById("sortOrder").value; // Get the selected sort order
                fetchPosts(selectedTag, null, sortOrder);
            });

            // Search posts by keyword
            document.getElementById("searchButton").addEventListener("click", function () {
                const searchQuery = document.getElementById("searchInput").value.trim();
                const sortOrder = document.getElementById("sortOrder").value; // Get the selected sort order
                if (searchQuery) {
                    fetchPosts(null, searchQuery, sortOrder);
                }
            });

            // Handle sorting by latest or oldest
            document.getElementById("sortOrder").addEventListener("change", function () {
                const sortOrder = this.value;
                const selectedTag = document.getElementById("filterTag").value; // Get selected tag
                const searchQuery = document.getElementById("searchInput").value.trim(); // Get search query
                fetchPosts(selectedTag, searchQuery, sortOrder);
            });

            // Initial fetch of posts (default to latest)
            fetchPosts();
        });

        // Responsive Sidebar
        document.addEventListener("DOMContentLoaded", function () {
            const sidebar = document.querySelector(".sidebar");
            const toggleButton = document.getElementById("sidebarToggle");

            toggleButton.addEventListener("click", function () {
                sidebar.classList.toggle("minimized");
            });
        });

        // Add Post Container
        document.addEventListener("DOMContentLoaded", function () {
            const addPost = document.querySelector(".addPost");
            const postContent = document.getElementById("postContent");

            // Initially minimize the addPost
            addPost.classList.add("minimized");

            // Expand addPost when the textarea is clicked
            postContent.addEventListener("click", function (event) {
                event.stopPropagation(); // Prevent the event from bubbling up to the document
                addPost.classList.remove("minimized");
                addPost.classList.add("expanded");
            });

            // Collapse addPost when clicking outside
            document.addEventListener("click", function (event) {
                if (!addPost.contains(event.target)) {
                    addPost.classList.remove("expanded");
                    addPost.classList.add("minimized");
                }
            });
        });
    </script>


</body>

</html>