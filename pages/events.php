<?php
// Include controller for backend logic
include '../config/events_controller.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Events</title>
  <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" />
  <link rel="stylesheet" href="../style/events.css" />
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
          <li><a href="../pages/shareExperience.php"><span><img src="../assets/home1.png" width="20px" alt="Home" /></span>Home</a></li>
          <li><a href="../pages/events.php"><span><img src="../assets/event1.png" width="20px" alt="Events" /></span>Events</a></li>
          <li><a href="../pages/opportunities.php"><span><img src="../assets/opportunities.png" width="20px" alt="Opportunities" /></span>Opportunities</a></li>
          <li><a href="../pages/settings.php"><span><img src="../assets/setting1.png" width="20px" alt="Settings" /></span>Settings</a></li>
          <li><a href="../pages/loginpage.php"><span><img src="../assets/logout1.png" width="20px" alt="Logout" /></span>Logout</a></li>
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
      <div class="page-header">
        <h1>Events</h1>
        <small>See upcoming events and mark those you are interested in</small>

        <div class="header-actions-container">
          <form method="GET" action="events.php" class="header-search-bar">
            <input type="text" class="search-input" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>" />
            <button type="submit" class="search-button" aria-label="Search"><span class="las la-search"></span></button>
          </form>

          <form method="GET" action="events.php" class="tag-dropdown">
            <select id="filter-events" name="tag" onchange="this.form.submit()">
              <option value="">All Tags</option>
              <option value="GENERAL" <?php if ($tag === 'GENERAL') echo 'selected'; ?>>General</option>
              <option value="SAMCIS" <?php if ($tag === 'SAMCIS') echo 'selected'; ?>>SAMCIS</option>
              <option value="SOHNABS" <?php if ($tag === 'SOHNABS') echo 'selected'; ?>>SOHNABS</option>
              <option value="STELA" <?php if ($tag === 'STELA') echo 'selected'; ?>>STELA</option>
              <option value="SEA" <?php if ($tag === 'SEA') echo 'selected'; ?>>SEA</option>
            </select>
          </form>
        </div>

        <div class="card-container">
          <?php while ($event = $result->fetch_assoc()): ?>
            <div class="card">
              <img src="<?php echo $event['image_path']; ?>" alt="<?php echo htmlspecialchars($event['alt_text']); ?>">
              <div class="container">
                <h2><b><?php echo htmlspecialchars($event['title']); ?></b></h2>
                <p><?php echo htmlspecialchars($event['description']); ?></p>
              </div>
              <div class="button-container">
                <a href="viewEvents.php?event_id=<?php echo $event['id']; ?>" class="button">VIEW</a>
                <a href="interested.php?event_id=<?php echo $event['id']; ?>" class="button">INTERESTED</a>
              </div>
            </div>
          <?php endwhile; ?>
        </div>

        <div class="pagination">
          <?php if ($page > 1): ?>
            <a href="events.php?page=<?php echo $page - 1; ?>&search=<?php echo htmlspecialchars($search); ?>&tag=<?php echo htmlspecialchars($tag); ?>" class="button">&laquo; Previous</a>
          <?php endif; ?>
          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="events.php?page=<?php echo $i; ?>&search=<?php echo htmlspecialchars($search); ?>&tag=<?php echo htmlspecialchars($tag); ?>" class="button <?php if ($i == $page) echo 'active'; ?>">
              <?php echo $i; ?>
            </a>
          <?php endfor; ?>
          <?php if ($page < $total_pages): ?>
            <a href="events.php?page=<?php echo $page + 1; ?>&search=<?php echo htmlspecialchars($search); ?>&tag=<?php echo htmlspecialchars($tag); ?>" class="button">Next &raquo;</a>
          <?php endif; ?>
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
    document.addEventListener("DOMContentLoaded", function() {
      const sidebar = document.querySelector(".sidebar");
      const toggleButton = document.getElementById("sidebarToggle");

      toggleButton.addEventListener("click", function() {
        sidebar.classList.toggle("minimized");
      });
    });
  </script>
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
</body>

</html>
