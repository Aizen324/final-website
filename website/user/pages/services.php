<?php
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }

  if (isset($_SESSION['user_name']) && $_SESSION['role'] == 'user') {
    $name = $_SESSION['user_name'];
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/general.css">
  <link rel="stylesheet" href="../styles/services.css">
  <link rel="icon" type="image/x-icon" href="../../images/logo.png">
  <title>Services | Tailmates</title>
</head>
<body>
  <!-- Profile Menu Sidebar -->
  <div class="sidebar-overlay">
    <div class="sidebar">
      <p class="exit-sidebar">x</p>

      <!-- sidebar-content -->
      <div class="sidebar-content">
        <div>
          <img src="../../icons/display-icon.png" alt="display-icon">
          <p>Display and Accessibility</p>
        </div>
        <div>
          <img src="../../icons/help-icon.png" alt="help-icon">
          <p>Help and Support</p>
        </div>
        <div>
          <img src="../../icons/settings-icon.png" alt="settings-icon">
          <p>Settings and Privacy</p>
        </div>
        <a href="../../database/logout.php" class="logout" name="logout">
          <img src="../../icons/logout-icon.png" alt="logout-icon">
          <p>Logout</p>
        </a>
      </div>
    </div>
  </div>

  <!-- Navigation -->
  <nav>
    <div class="navigation-wrapper">
      <div class="left-section">
        <div class="logo">
          <img src="../../images/logo.png" alt="logo">
          <h1>Tailmates</h1>
        </div>
      </div>

      <div class="middle-section">
        <div><a href="home.php">Home</a></div>
        <div><a href="services.php">Services</a></div>
        <div><a href="about.php">About</a></div>
        <div><a href="contacts.php">Contact Us</a></div>
      </div>

      <div class="right-section">
        <img src="../../icons/notification.svg" alt="Notification Bell">

        <?php if (isset($_SESSION['user_name'])):?>
        <div class="profile-container">
          <p><?php 
                if (isset($name)) echo htmlspecialchars($name);
              ?>
          </p>
          <div class="user-profile"></div>
        </div>
        <?php else: ?>
          <button class="sign-in-btn">Sign In</button>
        <?php endif; ?>
      </div>  
    </div>
  </nav>

  <main>
    <!-- Section 1 -->
    <section class="section-1">
      <h1>OUR SERVICES</h1>
      <img src="../../images/text-line-decoration.png" alt="line-decor" id="text-line-decor">
      <div>
        <p>Through our services, we aim to make the process of adopting or rehoming clear, respectful, and guided.
        Our platform is built to encourage transparency, responsibility, and care at every step.</p>
        <p>Whether you are searching for a new companion or seeking a safe home for your beloved pet,
        we are here to provide the support, structure, and information needed to make confident and compassionate choices.</p>
      <div>
    </section>

    <!-- Section 2 -->
    <section class="section-2">
      <div class="services-wrapper">   
        <!-- Card 1: Pet Adoption -->
        <div class="first-card">
          <img src="../../images/teal-paw.png" alt="Paw" id="teal-paw">
          <div class="services container">
            <h2>PET ADOPTION</h2>
            <?php if (isset($_SESSION['user_name'])): ?>
              <button class="adoption-btn">See More</button>
            <?php else: ?>
              <button class="no-user">See More</button>
            <?php endif; ?>
            <img src="../../images/gray-bunny.png" alt="Bunny" class="gray-bunny-img">
          </div>
        </div>

        <!-- Card 2: Pet Rehoming -->
        <div class="second-card">
          <img src="../../images/teal-paw.png" alt="Paw" id="teal-paw-2">
          <div class="services container">
            <h2>PET REHOMING</h2>
            <?php if (isset($_SESSION['user_name'])): ?>
              <button class="rehoming-btn">See More</button>
            <?php else: ?>
              <button class="no-user">See More</button>
            <?php endif; ?>
            <img src="../../images/gray-cat.png" alt="Cat" class="gray-cat-img">
          </div>
        </div>
      </div>

      <!-- Background Circles Design -->
      <div class="circles">
        <div class="circle-1">
          <div class="outside-circle">
            <div class="inner-circle"></div>
          </div>
        </div>

        <div class="circle-2"></div>

        <div class="circle-3">
          <div class="outside-circle">
            <div class="inner-circle"></div>
          </div>
        </div>
      </div>
    </section>

    <!-- End of Main Content -->
  </main>

   <!-- Footer -->
  <footer>
    <p>Copyright@2026 | All rights reserved</p>
    <div>
      <a href="">Terms</a>
      <a href="">Privacy</a>
      <a href="">Compliances</a>
    </div>
  </footer>

  <script src="../scripts/navigation.js" defer></script>
</body>
</html>