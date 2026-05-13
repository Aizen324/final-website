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
  <link rel="stylesheet" href="../styles/home.css">
  <link rel="icon" type="image/x-icon" href="../../images/logo.png">
  <title>Tailmates</title>
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
        <a href="adopted-animals.php">
          <img src="../../icons/cat-icon.png" alt="cat-icon">
          <p>See Adopted Pets</p>
        </a>
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
    <!-- Start of Main Content -->
    <!-- Section 1 -->
    <section class="section-1">
      <div class="headline-section">
        <h1>Find the Right Home. Find the Right Companion.</h1>
        <img src="../../images/paw.png" alt="Paw" id="paw">
        <button class="services-btn">Learn More</button>
      </div>
      
      <div class="image-section">
        <img src="../../images/dog.png" alt="Dog" id="dog">
        <img src="../../images/dog-treats.png" alt="Dog Treats" id="dog-treats">
        <div class="background-circle"></div>
      </div>
    </section>
    
    <!-- Section 2 -->
    <section class="section-2">
      <div class="mission-section">
        <img src="../../images/mission-image.png" alt="">
        <div>
            <h1>Our Mission</h1>
            <p>Our platform aims to help families adopt responsibly and support pet owners who need to rehome with care and compassion.</p>
        </div>
      </div>
      
      <div class="about-section">
        <img src="../../images/pet-footprints.png" alt="Footprints" id="footprints">
        <div class="intro-container">
          <h1>WHO ARE WE?</h1>
          <p>We are a group of students passionate about animal welfare and responsible pet ownership. This platform was created as part of our academic project.</p>
          <p>As students, we believe technology can be used to create positive change. Through this website, we aim to promote responsible adoption, easier rehoming, and awareness about the lifelong commitment of caring for a pet.</p>
          <button class="about-btn">Read More</button>
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