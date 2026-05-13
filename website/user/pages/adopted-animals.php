<?php
  session_start();
  
  if (isset($_SESSION['user_name'])) {
    $name = $_SESSION['user_name'];
    $user_id = $_SESSION['user_id'];
  } else {
    header('Location: home.php');
    exit();
  }

  include('../../database/database.php');
  try {
    $pets = [];

    $get_query = 
      "SELECT pet_id, pet_name, age, type, breed, gender, pet_desc, pet_image, status
      FROM pet_profile
      WHERE status = 'Adopted' AND user_id = '$user_id'";
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
        <p class='no-pet'>You have not adopted any pets.</p>
        <p class='no-pet'></p>
      ";
    } else {
      foreach($pets as $pet) {
        $genderIcon = ($pet['gender'] == 'Male') ? '♂' : '♀';
        $genderClass = ($pet['gender'] == 'Male') ? 'Male' : 'Female';

        echo "
          <div class='pet-card'>
            <div class='pet-card-img-placeholder'>
              <img src='../../uploads/{$pet['pet_image']}' alt='Pet image' />
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
                <p><span class='label'>Gender:</span> {$pet['gender']}</p>
                <p><span class='label'>Type:</span> {$pet['type']}</p>
                <p><span class='label'>Breed:</span> {$pet['breed']}</p>
                <p><span class='label'>Status:</span> {$pet['status']}</p>

              </div>

              <button class='btn-learn'
                data-id = '{$pet['pet_id']}'
                data-name = '{$pet['pet_name']}'
                data-age = '{$pet['age']}'
                data-gender = '{$pet['gender']}'
                data-type = '{$pet['type']}'
                data-breed = '{$pet['breed']}'
                data-desc = '{$pet['pet_desc']}'
                data-status = '{$pet['status']}'>
                    Learn More
              </button>
            </div>
          </div>
        ";
      }
    }
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rehome-submit'])) {
    $pet_id = (int) $_POST['pet_id'];
    $pet_name = $_POST['pet_name'];
    $user_id = (int) $_SESSION['user_id'];
    
    // Updates the status of the pet
    $update_query = "UPDATE pet_profile SET status = 'For rehoming' WHERE pet_id = $pet_id";

    mysqli_query($connection, $update_query);

    // Add Transaction log
    $details = mysqli_real_escape_string($connection, "$name rehomed $pet_name ($pet_id)");

    $transac_query = 
      "INSERT INTO transactions (user_id, pet_id, trans_type, trans_details, category)
      VALUES ('$user_id', '$pet_id', 'For rehoming', '$details', 'Pet')";
      
    mysqli_query($connection, $transac_query);

    header("Location: rehoming-page.php");
    exit();
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
  <title>Adopted Pets | Tailmates</title>
</head>

<body>
  <!-- Pet Details Overlay Modal -->
  <div class="details-overlay">
    <div class="details-modal">
      <div class="close-btn">&#10006;</div>
      <div class="modal-name-container">
        <h2 class="modalName"></h2>
        <img src="../../images/text-line-decoration.png" alt="text-line-decor" id="line-decor">
      </div>
      <div class="modal-info">
        <div>
          <p><b>Age:</b> <span class="modalAge"></span></p>
          <p><b>Gender:</b> <span class="modalGender"></span></p>
          <p><b>Type:</b> <span class="modalType"></span></p>
          <p><b>Breed:</b> <span class="modalBreed"></span></p>
          <p><b>Pet Description:</b> <span class="modalDesc"></span></p>
          <p><b>Status:</b> <span class="modalStatus"></span></p>
        </div>
        <div>
          <form action="adopted-animals.php" method="post" onsubmit="return confirm('Are you sure you want to rehome this pet? This action cannot be undone.')">
            <input type="hidden" name="pet_id" class="modalPetId">
            <input type="hidden" name="pet_name" class="modal_PetName">
            <button type="submit" name='rehome-submit' class="rehome-btn">Rehome this Pet</button>
          </form>
        </div>
      </div>
      <img src="../../images/teal-paw.png" alt="paw" id="teal-paw">
      <img src="../../images/paws-side-pattern.png" alt="paw pattern" id="paws-side-pattern">
    </div>
  </div>

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
    <section class="adoption-section-1">
      <div>
        <h1>YOUR ADOPTED PETS</h1>
      </div>
    </section>
    
    <section class="adoption-section-2">
      <div class="pet-grid" id="adopt-grid">
        <?php displayPets($pets); ?>
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
  
  <script defer>
      const btnLearn = document.querySelectorAll('.btn-learn');
      const detailsOverlay = document.querySelector('.details-overlay');
      const closeBtn = document.querySelector('.close-btn');

      btnLearn.forEach(detail => {
        detail.addEventListener('click', () => {
          document.querySelector('.modalPetId').value = detail.dataset.id;
          document.querySelector('.modal_PetName').value = detail.dataset.name;
          document.querySelector('.modalName').textContent = detail.dataset.name;
          document.querySelector('.modalAge').textContent = detail.dataset.age;
          document.querySelector('.modalGender').textContent = detail.dataset.gender;
          document.querySelector('.modalType').textContent = detail.dataset.type;
          document.querySelector('.modalBreed').textContent = detail.dataset.breed;
          document.querySelector('.modalDesc').textContent = detail.dataset.desc;
          document.querySelector('.modalStatus').textContent = detail.dataset.status;

          detailsOverlay.style.display = "flex";
        });
      });

      closeBtn.addEventListener('click', () => {
        detailsOverlay.style.display = "none";
      });
  </script>
  <script src="../scripts/navigation.js"></script>
</body>
</html>