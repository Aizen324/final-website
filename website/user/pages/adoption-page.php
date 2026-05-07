<?php
  session_start();
  
  if (isset($_SESSION['user_name'])) {
    $name = $_SESSION['user_name'];
  } else {
    header('Location: home.php');
    exit();
  }

  include('../../database/database.php');
  try {
    $pets = [];

    $get_query = 
      "SELECT a.pet_name, a.age, a.type, a.breed, a.gender, a.pet_desc, u.user_name 
      FROM adoption_listings a
      LEFT JOIN users u ON a.user_id = u.user_id
      WHERE adoption_status = 'Available';";
    $result = mysqli_query($connection, $get_query);

    if ($result && mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        $pets[] = $row;
      }
    }
  } catch (mysqli_sql_exception) {}

  function displayPets($pets) {
    if (empty($pets)) {
      echo "
        <p class='no-pet'></p>
        <p class='no-pet'>No pets available for adoption.</p>
        <p class='no-pet'></p>
      ";
    } else {
      foreach($pets as $pet) {
        $genderIcon = ($pet['gender'] == 'Male') ? '♂' : '♀';
        $genderClass = ($pet['gender'] == 'Male') ? 'Male' : 'Female';

        echo "
          <div class='pet-card'>
            <div class='pet-card-img-placeholder'>
              <img src='../../images/pet.png' alt='Pet image' />
            </div>

            <div class='pet-card-body'>
              <div class='pet-card-info'>

                <div>
                  <span class='gender-icon {$genderClass}'>
                    {$genderIcon}
                  </span>
                </div>

                <p><span class='label'>Name:</span> {$pet['pet_name']}</p>
                <p><span class='label'>Age:</span> {$pet['age']}</p>
                <p><span class='label'>Type:</span> {$pet['type']}</p>
                <p><span class='label'>Breed:</span> {$pet['breed']}</p>

              </div>

              <button class='btn-learn'>Learn More</button>
            </div>
          </div>
        ";
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../styles/general.css">
  <link rel="stylesheet" href="../styles/adoption-rehoming-style.css" />
  <link rel="icon" type="image/x-icon" href="../../images/logo.png">
  <title>Adopt a Pet</title>
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
    <section class="adoption-section-1">
      <div>
        <h1>ADOPT A PET</h1>
        <p>Find your new best friend and change a life forever.<br />Meet our adoptable pets.</p>
      </div>
    </section>
    
    <section class="adoption-section-2">
      <!-- SEARCH BAR -->
      <!-- <div class="search-bar-wrap">
        <div class="search-bar">
          <div class="search-input-wrap">
            <span class="search-icon">
              <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="6.5" cy="6.5" r="5" stroke="#666" stroke-width="1.5"/>
                <line x1="10.5" y1="10.5" x2="15" y2="15" stroke="#666" stroke-width="1.5" stroke-linecap="round"/>
              </svg>
            </span>
            <input type="text" id="adopt-search" placeholder="Search" />
          </div>
          <select class="filter-select" id="adopt-type-filter">
            <option value="">Type</option>
            <option value="dog">Dog</option>
            <option value="cat">Cat</option>
            <option value="bird">Bird</option>
            <option value="rabbit">Rabbit</option>
            <option value="snake">Snake</option>
            <option value="fish">Fish</option>
          </select>
        </div>
      </div> -->

      <div class="pet-grid" id="adopt-grid">
        <?php displayPets($pets); ?>
      </div>
    </section>

    <section class="section-3">
      <div class="wrapper">
        <img src="../../images/dog-house.png" alt="dog house" id="dog-house">
        <div class="text-container">
          <p>You can also rehome your pets!</p>
          <button class="rehoming-btn">Go to Rehome Page &#8594;</button>
        </div>
        <img src="../../images/running-dog.png" alt="running dog" id="running-dog">
      </div>
    </section>
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

  <script src="../scripts/navigation.js"></script>
</body>
</html>