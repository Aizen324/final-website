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
  <link rel="stylesheet" href="../styles/contacts.css">
  <link rel="icon" type="image/x-icon" href="../../images/logo.png">
  <title>Contact Us | Tailmates</title>
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
    <!-- FOLLOW US SECTION -->
    <section class="section-1">
      <div class="Socials-wrapper">
        <h1>Follow Us</h1>
        <div class="social-icons">
          <div class="social-item">
              <!-- Instagram -->
              <a href="#">
                  <svg class="social-icon" viewBox="0 0 24 24" fill="none" stroke="#008080" stroke-width="1.8">
                      <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                      <circle cx="12" cy="12" r="5"/>
                      <circle cx="17.5" cy="6.5" r="1.2" fill="#008080" stroke="none"/>
                  </svg>
              </a>
              <span class="social-label">TailMates</span>
          </div>

          <div class="social-item">
              <!-- Facebook -->
              <a href="#">
                  <svg class="social-icon" viewBox="0 0 24 24" fill="#008080">
                      <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                  </svg>
              </a>
              <span class="social-label">TailMates</span>
          </div>

          <div class="social-item">
              <!-- Tiktok -->
              <a href="#">
                  <svg class="social-icon" viewBox="0 0 24 24" fill="#008080">
                      <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.75a4.85 4.85 0 0 1-1.01-.06z"/>
                  </svg>
              </a>
              <span class="social-label">TailMates</span>
          </div>
        </div>
      </div>
    </section>

 
    <!-- DARK CONTACT SECTION / FORMS SECTION -->
    <section class="section-2">
      <div class="container">
        <div class="contact-form-column">
          <h1>Contact Information</h1>

          <div class="input-group">
            <label>Name</label>
            <input type="text">
          </div>

          <div class="input-group">
            <label>Email</label>
            <input type="email">
          </div>

          <div class="input-group">
            <label>Subject</label>
            <input type="text">
          </div>

          <div class="input-group">
            <label>Message</label>
            <textarea rows="6"></textarea>
          </div>

          <button class="send-btn">Send Now</button>
        </div>

        <div class="contact-info-column">
          <div class="info-group">
            <h2>Email Us</h2>
            <a href="#">tailmates@gmail.com</a>
          </div>
          
          <div class="info-group">
            <h2>Phone Number</h2>
            <p>+631234567890</p>
          </div>
        </div>
      </div>
    </section>
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