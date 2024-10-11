<?php
session_start();
include("../config/connection.php");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Profile</title>
    <link rel="stylesheet" href="../style/view-profile.css" />
</head>
<body>
    <div class="header-wrapper">
        <header></header>
        <div class="cols-container">
            <div class="left-col">
                <div class="img-container">
                    <img src="/assets/display-photo.png" alt="Display Photo" />
                    <span></span>
                </div>
                <h2 class="User"></h2>
                <p>pogi si daddy dolby</p>

                <ul class="about">
                    <li><span>Joined October 10, 2024</span></li>
                    <li><span>Lives in Baguio City</span></li>
                </ul>

                <div class="description">
                    <p>**write bio here** </p>

                    <ul>
                        <li><i class="fab fa-twitter"></i></li>
                        <li><i class="fab fa-facebook"></i></li>
                        <li><i class="fab fa-instagram"></i></li>
                    </ul>
                </div>
            </div>
            <div class="right-col">
                <nav>
                    <ul>
                        <li><a href="#">posts</a></li>
                        <li><a href="#">photos</a></li>
                        <li><a href="#">groups</a></li>
                        <li><a href="#">about</a></li>
                    </ul>
                    <button>Follow</button>
                </nav>

                <div class="photos">
                    <img src="" alt="" />
                    <img src="" alt="" />
                    <img src="" alt="" />
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>
