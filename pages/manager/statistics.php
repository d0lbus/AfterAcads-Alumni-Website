<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Statistics</title>
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" />
    <link rel="stylesheet" href="../../style/manager/statistics.css" />
    <script src="script.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <body>
        <section>
            <header>
                <div><img class="logo" src="../../assets/alumnilogo.png" alt="afteracads" />
                    <img class="afteracadstext" src="../../assets/afteracadstext.png" alt="AfterAcads" />
                </div>
                <ul class="nav-links">
                    <li><a href="../../pages/manager/home.html">Home</a></li>
                    <li><a href="../../pages/manager/Registrants.html">Registrants</a></li>
                    <li><a href="../../pages/manager/approvePost.html">Posts</a></li>
                    <li><a href="../../pages/manager/upcomingEvents.html">Events</a></li>
                    <li><a href="#">Opportunities</a></li>
                    <li><a href="../../pages/manager/statistics.html">Statistics</a></li>
                </ul>
                <div class="profile">
                    <li><a href="viewProfile.php"><img class="profile-img" src="../../assets/display-photo.png"
                                alt="Profile" /></a></li>
                </div>
                </div>
                </div>
            </header>
        </section>
        <div class="container">
            <main>
                <div class="page-header">
                    <div>
                        <h1>Statistics Dashboard</h1>
                        <small>see how many percent of alumni are employed, unemployed,
                            underemployed, out of the country, how many are interested in an
                            event/opportunity</small>
                    </div>

                    <div class="header-actions">
                    </div>
                </div>
                <div class="cards">
                    <div class="card-single">
                        <div class="card-flex">
                            <div class="card-info">
                                <div class="card-head">
                                    <span>Total Users</span>
                                </div>
                                <div id="totalUsers" class="number"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-single">
                        <div class="card-flex">
                            <div class="card-info">
                                <div class="card-head">
                                    <span>Employed and Unemployed</span>
                                </div>
                                <div class="chart-container">
                                    <canvas id="employmentChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-single">
                        <div class="card-flex">
                            <div class="card-info">
                                <div class="card-head">
                                    <span>underemployed</span>
                                    <small>number of underemployed</small>
                                </div>
                                <h2>4,140</h2>
                                <small>34% of graduate students are underemployed</small>
                            </div>
                            <div class="card-chart">
                                <span class="las la-chart-line"></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-single">
                        <div class="card-flex">
                            <div class="card-info">
                                <div class="card-head">
                                    <span>Out of Country</span>
                                    <small>Number Out of the Country</small>
                                </div>
                                <h2>500</h2>
                                <small>5% of graduates are out of the country</small>
                            </div>
                            <div class="card-chart">
                                <span class="las la-chart-line"></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-single">
                        <div class="card-flex">
                            <div class="card-info">
                                <div class="card-head">
                                    <span>Interested in Events</span>
                                    <small>Number Interested</small>
                                </div>
                                <h2>1,800</h2>
                                <small>18% of graduates are interested in events</small>
                            </div>
                            <div class="card-chart">
                                <span class="las la-chart-line"></span>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        </div>

        </main>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const sidebar = document.querySelector(".sidebar");
                const toggleButton = document.getElementById("sidebarToggle");

                toggleButton.addEventListener("click", function () {
                    sidebar.classList.toggle("minimized");

                    // Add this to adjust the main content dynamically
                    const mainContent = document.querySelector(".main-content");
                    if (sidebar.classList.contains("minimized")) {
                        mainContent.style.marginLeft = "60px"; // Adjust for minimized sidebar
                    } else {
                        mainContent.style.marginLeft = "280px"; // Restore when sidebar is full
                    }
                });
            });

            // Fetch and display statistics
            async function fetchStatistics() {
            try {
                const response = await fetch('http://localhost:3000/api/users/statistics');
                const data = await response.json();

                // Display Total Users
                document.getElementById('totalUsers').textContent = data.totalUsers;

                // Employment Chart Data
                const employmentData = [data.employedUsers, data.unemployedUsers];

                // Employment PieChart
                const ctx = document.getElementById('employmentChart').getContext('2d');
                new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Employed', 'Unemployed'],
                    datasets: [{
                    label: 'Employment Status',
                    data: employmentData,
                    backgroundColor: ['#36A2EB', '#FF6384'], 
                    borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                    legend: {
                        position: 'top'
                    }
                    }
                }
                });
            } catch (error) {
                console.error('Error fetching statistics:', error);
            }
            }

            // Load statistics when the page loads
            fetchStatistics();
        </script>
    </body>

</html>