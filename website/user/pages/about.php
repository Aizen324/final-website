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
  <link rel="stylesheet" href="../styles/about.css">
  <link rel="icon" type="image/x-icon" href="../../images/logo.png">
  <title>About Us | Tailmates</title>
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
    <section class="section-1">
      <h1>About Us</h1>
    </section>
    
    <section class="section-2">
      <div class="box">
        <img src="../../images/orange-cat.png" alt="orange-cat" id="orange-cat">
        <p>At Tailmates, our mission is simple: to connect loving homes with animals in need. We believe every pet deserves safety, care, and a family that will cherish them for life. Whether you’re looking to adopt a loyal companion or help a rescued animal find a new beginning, Tailmates is here to make that journey easy, meaningful, and rewarding.</p>
        <img src="../../images/text-line-decoration.png" id="text-line-decoration">
      </div>
    </section>

    <section class="section-3">
      <div class="section-3-content">
        <div class="box2">
          <div class="text-content-one">
            We work with shelters, rescuers, and foster families to showcase pets who are ready for adoption. Our platform is designed to provide clear information, honest profiles, and a smooth adoption process — so you can focus on building a lifelong bond with your future best friend.
          </div>
          <img src="../../images/white-dog.png" alt="dog" id="dog">
        </div>
        <div class="box3">
          <img src="../../images/about-group-image.jpg" alt="group-image" id="group-image">
          <div class="text-content-two">
            Beyond adoption, Tail Mates is about community. We aim to promote responsible pet ownership, compassion for animals, and awareness about rescue efforts. Every adoption creates space for another animal in need, and together, we can make a real difference.
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
      <a href="#">Terms</a>
      <a href="#">Privacy</a>
      <a href="#">Compliances</a>
    </div>
  </footer>

  <script src="../scripts/navigation.js" defer></script>
</body>
</html>