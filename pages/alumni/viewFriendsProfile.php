

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Profile</title>
    <link rel="stylesheet" href="../../style/view-friends-profile.css" />
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
                <a href="#">
                    <img src="../assets/display-photo.png" alt="Profile Picture" />
                </a>
                <div>
                    <h3>John Doe</h3>
                    <span>john.doe@example.com</span>
                </div>
            </div>

            <div class="sidebar-menu">
                <ul>
                    <li><a href="#"><span><img src="../assets/home1.png" width="20px" alt="Home" /></span>Home</a></li>
                    <li><a href="#"><span><img src="../assets/event1.png" width="20px" alt="Events" /></span>Events</a></li>
                    <li><a href="#"><span><img src="../assets/opportunities.png" width="20px" alt="Opportunities" /></span>Opportunities</a></li>
                    <li><a href="#"><span><img src="../assets/setting1.png" width="20px" alt="Settings" /></span>Settings</a></li>
                    <li><a href="#"><span><img src="../assets/logout1.png" width="20px" alt="Logout" /></span>Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="header-wrapper">
            <div class="header"></div>
            <div class="cols-container">
                <div class="left-col">
                    <div class="img-container">
                        <img src="../assets/display-photo.png" alt="Display Photo" />
                        <span></span>
                    </div>
                    <h2>John Doe</h2>
                    <p>john.doe@example.com</p>

                    <ul class="about">
                        <li><span>Joined January 15, 2023</span></li>
                        <li><span>Address: Not Provided</span></li>
                    </ul>

                    <div class="action-buttons">
                        <button class="btn-connect">Connect</button>
                        <button class="btn-message">Message</button>
                    </div>
                </div>

                <div class="right-col">
                    <nav>
                        <ul>
                            <li><a href="#posts">Posts</a></li>
                            <li><a href="#photos">Photos</a></li>
                            <li><a href="#about">About</a></li>
                        </ul>
                    </nav>
                    <div class="posts" id="posts">
                        <p>No posts yet.</p>
                    </div>
                </div>
            </div>
        </div>
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
