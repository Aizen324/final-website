<?php
  if (session_status() === PHP_SESSION_NONE) {
      session_start();
  }
  include('../../database/database.php');
  
  if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
      session_unset();
      session_destroy();
      
      header('Location: ../../user/html-pages/login.php?error=unauthorized');
      exit();
  }
  
  function adoptionList($connection) {
    $get_query = 
      "SELECT a.adoption_id, a.pet_name, a.age, a.type, a.breed, a.gender, a.pet_desc, a.owner_name, a.contact_no, a.adoption_status, a.date_listed, u.user_name 
      FROM adoption_listings a
      LEFT JOIN users u ON a.user_id = u.user_id";
    $result = mysqli_query($connection, $get_query);
    
    if ($result && mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        echo "
          <div class='row update'>
            <p>{$row['owner_name']}</p>
            <p>{$row['pet_name']}</p>
            <p>{$row['age']}</p>
            <p>{$row['type']}</p>
            <p>{$row['breed']}</p>
            <p>{$row['gender']}</p>
            <p class='pet_desc'>{$row['pet_desc']}</p>
            <p>{$row['contact_no']}</p>
            <p>{$row['adoption_status']}</p>
            <p>{$row['date_listed']}</p>
            <p>{$row['user_name']}</p>
            <button class='adopt-update-btn' 
              data-id='{$row['adoption_id']}'
              data-name='{$row['pet_name']}'
              data-age='{$row['age']}'
              data-type='{$row['type']}'
              data-breed='{$row['breed']}'
              data-gender='{$row['gender']}'
              data-desc=\"{$row['pet_desc']}\"
              data-contact='{$row['contact_no']}'
              data-status='{$row['adoption_status']}'>
                Update
            </button>
          </div>
        ";
      }
    }
  }

  function rehomingList($connection) {
    $get_query = "SELECT * FROM rehoming_listings";
    $result = mysqli_query($connection, $get_query);

    if ($result && mysqli_num_rows($result) >  0) {
      while ($row = mysqli_fetch_assoc($result)) {
        echo "
          <div class='row'>
            <p>{$row['pet_name']}</p>
            <p>{$row['age']}</p>
            <p>{$row['type']}</p>
            <p>{$row['gender']}</p>
            <p>{$row['breed']}</p>
            <p class='pet_desc'>{$row['pet_desc']}</p>
            <p>{$row['rehoming_status']}</p>
            <p>{$row['owner_name']}</p>
            <p>{$row['contact_no']}</p>
            <p class='reason'>{$row['reason']}</p>
            <p>{$row['time_listed']}</p>
            <button class='rehome-update-btn'
              data-id = '{$row['rehoming_id']}'
              data-name = '{$row['pet_name']}'
              data-age = '{$row['age']}'
              data-type = '{$row['type']}'
              data-gender = '{$row['gender']}'
              data-breed = '{$row['breed']}'
              data-status = '{$row['rehoming_status']}'>
              Update
            </button>
          </div>
        ";
      }
    }
  }

  // Adoption Update
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adopt-save-btn'])) {
    $adoption_id = $_POST['adoption_id'];
    $pet_name = filter_input(INPUT_POST, 'pet_name', FILTER_SANITIZE_SPECIAL_CHARS);
    $age = filter_input(INPUT_POST, 'age', FILTER_SANITIZE_SPECIAL_CHARS);
    $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_SPECIAL_CHARS);
    $breed = filter_input(INPUT_POST, 'breed', FILTER_SANITIZE_SPECIAL_CHARS);
    $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_SPECIAL_CHARS);
    $pet_desc = filter_input(INPUT_POST, 'pet_desc', FILTER_SANITIZE_SPECIAL_CHARS);
    $contact = filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_SPECIAL_CHARS);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS);

    $update_query = 
      "UPDATE adoption_listings 
       SET pet_name='$pet_name', age='$age', type='$type', breed='$breed', gender='$gender', pet_desc='$pet_desc', contact_no='$contact', adoption_status='$status'
       WHERE adoption_id = '$adoption_id';";
    mysqli_query($connection, $update_query);

    header('Location: admin-update.php');
    exit();
  }

  // Rehoming Update
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['rehome-save-btn'])) {
    $id = $_POST['rehoming_id'];
    $name = filter_input(INPUT_POST, 'name-rehome', FILTER_SANITIZE_SPECIAL_CHARS);
    $age = filter_input(INPUT_POST, 'age-rehome', FILTER_SANITIZE_SPECIAL_CHARS);
    $type = filter_input(INPUT_POST, 'type-rehome', FILTER_SANITIZE_SPECIAL_CHARS);
    $gender = filter_input(INPUT_POST, 'gender-rehome', FILTER_SANITIZE_SPECIAL_CHARS);
    $breed = filter_input(INPUT_POST, 'breed-rehome', FILTER_SANITIZE_SPECIAL_CHARS);
    $status = filter_input(INPUT_POST, 'status-rehome', FILTER_SANITIZE_SPECIAL_CHARS);

    $update_query = 
      "UPDATE rehoming_listings
       SET pet_name='$name', age='$age', type='$type', gender='$gender', breed='$breed', rehoming_status='$status'
       WHERE rehoming_id = '$id'"; 
    mysqli_query($connection, $update_query);

    header('Location: admin-update.php');
    exit();
  }
?> 

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/crud-styles.css">
  <link rel="stylesheet" href="../styles/general-styles.css">
  <link rel="icon" type="image/x-icon" href="../../images/logo.png">
  <title>Update | Tailmates</title>
</head>
<body>
  <!-- Adoption Update Modal -->
  <div class="adoption-modal-overlay">
    <div class="update-modal">
      <div class="close-btn">&#10006;</div>
      <h3>Update Adoption List</h3>
      <div class="modal-info">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
          <input type="hidden" name="adoption_id" id="updateId">
          <div>
            <label>Pet Name <img src="../../icons/edit-icon.png" alt="edit-icon" class="edit-icon"></label>
            <input type="text" name="pet_name" id="updateName">
          </div>
          <div>
            <label>Age <img src="../../icons/edit-icon.png" alt="edit-icon" class="edit-icon"></label>
            <input type="text" name="age" id="updateAge">
          </div>
          <div>
            <label>Type <img src="../../icons/edit-icon.png" alt="edit-icon" class="edit-icon"></label>
            <input type="text" name="type" id="updateType">
          </div>
          <div>
            <label>Breed <img src="../../icons/edit-icon.png" alt="edit-icon" class="edit-icon"></label>
            <input type="text" name="breed" id="updateBreed">
          </div>
          <div>
            <label>Gender <img src="../../icons/edit-icon.png" alt="edit-icon" class="edit-icon"></label>
            <select name="gender" id="updateGender">
              <option value="" disabled selected>Select gender</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select>
          </div>
          <div>
            <label>Pet Description <img src="../../icons/edit-icon.png" alt="edit-icon" class="edit-icon"></label>
            <textarea name="pet_desc" id="updateDesc"></textarea>
          </div>
          <div>
            <label>Contact Number <img src="../../icons/edit-icon.png" alt="edit-icon" class="edit-icon"></label>
            <input type="text" name="contact" id="updateContact">
          </div>
          <div>
            <label>Status <img src="../../icons/edit-icon.png" alt="edit-icon" class="edit-icon"></label>
            <select name="status" id="updateStatus">
              <option value="" disabled selected>Select status</option>
              <option value="Available">Available</option>
              <option value="Adopted">Adopted</option>
            </select>
          </div>

          <div class="modal-action">
            <button type="submit" name="adopt-save-btn">Save Changes</button>
            <button type="button" class="cancel-btn">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Rehoming Update Modal -->
  <div class="rehome-modal-overlay">
    <div class="update-modal">
      <div class="close-btn">&#10006;</div>
      <h3>Update Rehoming List</h3>
      <div class="modal-info">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
          <input type="hidden" name="rehoming_id" id="rId">
          <div>
            <label>Pet Name <img src="../../icons/edit-icon.png" alt="edit-icon" class="edit-icon"></label>
            <input type="text" name="name-rehome" id="rName">
          </div>
          <div>
            <label>Age <img src="../../icons/edit-icon.png" alt="edit-icon" class="edit-icon"></label>
            <input type="text" name="age-rehome" id="rAge">
          </div>
          <div>
            <label>Type <img src="../../icons/edit-icon.png" alt="edit-icon" class="edit-icon"></label>
            <input type="text" name="type-rehome" id="rType">
          </div>
          <div>
            <label>Gender <img src="../../icons/edit-icon.png" alt="edit-icon" class="edit-icon"></label>
            <select name="gender-rehome" id="rGender">
              <option value="" disabled selected>Select gender</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
            </select>
          </div>
          <div>
            <label>Breed <img src="../../icons/edit-icon.png" alt="edit-icon" class="edit-icon"></label>
            <input type="text" name="breed-rehome" id="rBreed">
          </div>
          <div>
            <label>Status <img src="../../icons/edit-icon.png" alt="edit-icon" class="edit-icon"></label>
            <select name="status-rehome" id="rStatus">
              <option value="" disabled selected>Select status</option>
              <option value="Unverified">Unverified</option>
              <option value="Verified">Verified</option>
            </select>
          </div>

          <div class="modal-action">
            <button type="submit" name="rehome-save-btn">Save Changes</button>
            <button type="button" class="cancel-btn">Cancel</button>
          </div>
        </form>
      </div>

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
        <div><a href="admin-dashboard.php">Dashboard</a></div>
        <div><a href="admin-create.php">Create</a></div>
        <div><a href="admin-update.php">Update</a></div>
        <div><a href="admin-delete.php">Delete</a></div>
      </div>

      <div class="right-section">
        <img src="../../icons/notification.svg" alt="Notification Bell">

        <div class="profile-container">
          <p>Admin</p>
          <div class="user-profile"></div>
        </div>
      </div>  
    </div>
  </nav>

  <main>
    <!-- ADOPTION TABLE -->
    <section class="section-1">
      <div class="adoption-wrapper">
        <h2>Adoption Listings Table</h2>
        <div class="table-scroll">
          <div class="adoption-table">
            <div class="row update">
              <p>Owner</p>
              <p>Pet Name</p>
              <p>Age</p>
              <p>Type</p>
              <p>Breed</p>
              <p>Gender</p>
              <p>Pet Description</p>
              <p>Contact</p>
              <p>Status</p>
              <p>Date Listed</p>
              <p>Administrator</p>
              <p></p>
            </div>
              <?php adoptionList($connection); ?>
            </div>
          </div>
        </div>
    </section>

    <!-- REHOMING TABLE -->
    <section class="section-2">
      <div class="rehoming-wrapper">
        <h2>Rehoming Listings Table</h2>
        <div class="table-scroll">
          <div class="rehoming-table">
            <div class="row">
              <p>Pet Name</p>
              <p>Age</p>
              <p>Type</p>
              <p>Gender</p>
              <p>Breed</p>
              <p>Pet Description</p>
              <p>Rehoming Status</p>
              <p>Owner Name</p>
              <p>Contact Number</p>
              <p>Reason</p>
              <p>Time Listed</p>
              <p></p>
            </div>
            <?php rehomingList($connection) ?>
          </div>
        </div>
      </div>
    </section>
  </main>


  <script defer>
    const adoptionModal = document.querySelectorAll('.adopt-update-btn');
    const rehomingModal = document.querySelectorAll('.rehome-update-btn');
    const adoptionOverlay = document.querySelector('.adoption-modal-overlay');
    const rehomingOverlay = document.querySelector('.rehome-modal-overlay');
    const closeModal = document.querySelectorAll('.close-btn'); // x button
    const cancelBtn = document.querySelectorAll('.cancel-btn'); // cancel button

    // Inputs Data (Adoption)
    const id = document.getElementById('updateId');
    const name = document.getElementById('updateName');
    const age = document.getElementById('updateAge');
    const type = document.getElementById('updateType');
    const breed = document.getElementById('updateBreed');
    const gender = document.getElementById('updateGender');
    const desc = document.getElementById('updateDesc');
    const contact = document.getElementById('updateContact');
    const status = document.getElementById('updateStatus');
    
    // Inputs Data (Rehoming)
    const r_id = document.getElementById('rId');
    const r_name = document.getElementById('rName');
    const r_age = document.getElementById('rAge');
    const r_type = document.getElementById('rType');
    const r_gender = document.getElementById('rGender');
    const r_breed = document.getElementById('rBreed');
    const r_status = document.getElementById('rStatus');

    // Event Listeners
    adoptionModal.forEach(btn => {
      btn.addEventListener('click', () => {
        id.value = btn.dataset.id;
        name.value = btn.dataset.name;
        age.value = btn.dataset.age;
        type.value = btn.dataset.type;
        breed.value = btn.dataset.breed;
        gender.value = btn.dataset.gender;
        desc.value = btn.dataset.desc;
        contact.value = btn.dataset.contact;
        status.value = btn.dataset.status;

        adoptionOverlay.style.display = 'flex';
      });
    })
    rehomingModal.forEach(btn => {
      btn.addEventListener('click', () => {
        r_id.value = btn.dataset.id;
        r_name.value = btn.dataset.name;
        r_age.value = btn.dataset.age;
        r_type.value = btn.dataset.type;
        r_gender.value = btn.dataset.gender;
        r_breed.value = btn.dataset.breed;
        r_status.value = btn.dataset.status;

        rehomingOverlay.style.display = 'flex';
      });
    });

    closeModal.forEach(btn => {
      btn.addEventListener('click', () => {
        adoptionOverlay.style.display = 'none';
        rehomingOverlay.style.display = 'none';
      });
    })

    cancelBtn.forEach(btn => {
      btn.addEventListener('click', () => {
        adoptionOverlay.style.display = 'none';
        rehomingOverlay.style.display = 'none';
      });
    });

  </script>
  <script src="../scripts/admin-navigation.js" defer></script>
</body>
</html>