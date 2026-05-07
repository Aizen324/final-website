<?php
  session_start();
  include('../../database/database.php');
  
  if (isset($_SESSION['user_name'])) {
    $name = $_SESSION['user_name'];
    $user_id = $_SESSION['user_id'];
  } else {
    header('Location: home.php');
    exit();
  }

  try {
    $get_query = 
        "SELECT pet_name, age, gender, type, breed, pet_desc, rehoming_status
        FROM rehoming_listings
        WHERE user_id = $user_id";

        $result = mysqli_query($connection, $get_query);
        $rehomingListings = [];

        if ($result && mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            $rehomingListings[] = $row;
          }
        }

    $_SESSION['rehomingListings'] = $rehomingListings;
  } catch(mysqli_sql_exception) {}

  if (!empty($_SESSION['rehomingListings'])) {
    $listings = $_SESSION['rehomingListings'];
  } else {
    $listings = [];
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
  <title>Rehome Your Pet</title>
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
        <p><b>Age:</b> <span class="modalAge"></span></p>
        <p><b>Gender:</b> <span class="modalGender"></span></p>
        <p><b>Type:</b> <span class="modalType"></span></p>
        <p><b>Breed:</b> <span class="modalBreed"></span></p>
        <p><b>Pet Description:</b> <span class="modalDesc"></span></p>
        <p><b>Rehoming Status:</b> <span class="modalStatus"></span></p>
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
    <section class="rehome-section-1">
      <h1>REHOME YOUR PET</h1>
      <p>We ensure that a bright future is ahead for your companions. By connecting pets with loving families, we provide the second chance they truly deserve.</p>
    </section>

    <section class="rehome-section-2">
      <div class="section-title-divider"><span>List of Pets</span></div>
      
      <div class="rehome-grid" id="rehome-grid">
        <?php if (!empty($_SESSION['rehomingListings'])): ?>
          <?php foreach($listings as $listing): ?>
            <div class="rehome-card">
              <div class="pet-card-img-placeholder">
                <img src="../../images/pet.png" alt="Pet placeholder image">
              </div>

              <div class="pet-card-body">
                <div class="pet-card-info">
                  <div>
                    <p><span class="label">Name:</span> <?php echo $listing['pet_name']; ?></p>
                    <p><span class="label">Age:</span> 
                      <?php 
                        if ($listing['age'] != "") { 
                          echo $listing['age'];
                        } else { echo "Unknown"; } ?>
                    </p>
                    <p><span class="label">Type:</span> <?php echo $listing['type']; ?></p>
                    <p>
                      <span class="label">Status:</span> 
                      <span class="<?php echo $listing['rehoming_status'] === 'Verified' ? 'status-verified' : 'status-unverified'; ?>">
                        <?php echo $listing['rehoming_status']?>
                      </span>
                    </p>
                  </div>
                  <div>
                    <span class="gender-icon <?php echo $listing['gender'] === 'male' ? 'male' : 'female'; ?>">
                      <?php 
                        if ($listing['gender'] == 'male') {
                          echo "♂";
                        } else {
                          echo "♀";
                        }
                      ?>  
                    </span>
                  </div>
                </div>

                <button class="btn-details"
                  data-name = "<?php echo htmlspecialchars($listing['pet_name']); ?>"
                  data-age = "<?php echo htmlspecialchars($listing['age']); ?>"
                  data-gender = "<?php echo htmlspecialchars($listing['gender']); ?>"
                  data-type = "<?php echo htmlspecialchars($listing['type']); ?>"
                  data-breed = "<?php echo htmlspecialchars($listing['breed']); ?>"
                  data-desc = "<?php echo htmlspecialchars($listing['pet_desc']); ?>"
                  data-status = "<?php echo htmlspecialchars($listing['rehoming_status']); ?>"
                >
                  Details
                </button>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif;?>

        <div class="add-card" id="add-card-btn">
          <div class="add-card-title">Add to List</div>
          <div class="add-plus">+</div>
        </div>
      </div>
    </section>

    <section class="section-3">
      <div class="wrapper">
        <img src="../../images/dog-house.png" alt="dog house" id="dog-house">
        <div class="text-container">
          <p>You can also meet and adopt pets!</p>
          <button class="adoption-btn">Go to Adopt Page &#8594;</button>
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

  <script defer>
    const btnDetails = document.querySelectorAll('.btn-details');
    const detailsOverlay = document.querySelector('.details-overlay');
    const closeBtn = document.querySelector('.close-btn');

    btnDetails.forEach(detail => {
      detail.addEventListener('click', () => {
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
  <script src="../scripts/navigation.js" defer></script>
</body>
</html>