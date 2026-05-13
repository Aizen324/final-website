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

  
  function petList($connection) {
    $get_query = 
      "SELECT pet_id, pet_name, age, type, breed, gender, pet_desc, reason, status, created, updated, user_name
      FROM pet_profile p
      LEFT JOIN users u ON p.user_id = u.user_id
      ORDER BY p.status DESC";
    $result = mysqli_query($connection, $get_query);
    
    if ($result && mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        echo "
          <div class='row pet-update'>
            <p>{$row['status']}</p>
            <p>{$row['user_name']}</p>
            <p>{$row['pet_id']}</p>
            <p>{$row['pet_name']}</p>
            <p>{$row['age']}</p>
            <p>{$row['type']}</p>
            <p>{$row['breed']}</p>
            <p>{$row['gender']}</p>
            <p class='pet_desc'>{$row['pet_desc']}</p>
            <p class='pet_desc'>{$row['reason']}</p>
            <p>{$row['created']}</p>
            <p>{$row['updated']}</p>
            <button class='update-btn'
              data-id = '{$row['pet_id']}'
              data-name = '{$row['pet_name']}'
              data-age = '{$row['age']}'
              data-type = '{$row['type']}'
              data-gender = '{$row['gender']}'
              data-breed = '{$row['breed']}'
              data-status = '{$row['status']}'
              data-desc = '{$row['pet_desc']}'
              data-reason = '{$row['reason']}'>
               Update
             </button>
          </div>
        ";
      }
    }
  }

  // Pet Update
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save-btn'])) {
    $id = $_POST['pet_id'];
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $age = filter_input(INPUT_POST, 'age', FILTER_SANITIZE_SPECIAL_CHARS);
    $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_SPECIAL_CHARS);
    $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_SPECIAL_CHARS);
    $breed = filter_input(INPUT_POST, 'breed', FILTER_SANITIZE_SPECIAL_CHARS);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
    $pet_desc = $_POST['pet_desc'];
    $reason = $_POST['reason'];
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];

    $update_query = 
      "UPDATE pet_profile
       SET pet_name='$name', age='$age', type='$type', gender='$gender', breed='$breed', status='$status', pet_desc='$pet_desc', reason='$reason'
       WHERE pet_id = '$id'"; 
    mysqli_query($connection, $update_query);

    // ADD TRANSACTION
    $details = mysqli_real_escape_string($connection, "$user_name updated the information for pet $name ($id).");

    $transac_query = 
      "INSERT INTO transactions (user_id, pet_id, trans_type, trans_details, category)
      VALUES ('$user_id', '$id', 'Pet update', '$details', 'Pet')";
      
    mysqli_query($connection, $transac_query);

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
  <!-- Pet Update Modal -->
  <div class="modal-overlay">
    <div class="update-modal">
      <div class="close-btn">&#10006;</div>
      <h3>Update Pet Profile</h3>
      <div class="modal-info">
        <form action="" method="post">
          <input type="hidden" name="pet_id" id="id">
          <div>
            <label>Pet Name <img src="../../icons/edit-icon.png" alt="edit-icon" class="edit-icon"></label>
            <input type="text" name="name" id="name">
          </div>
          <div>
            <label>Age <img src="../../icons/edit-icon.png" alt="edit-icon" class="edit-icon"></label>
            <input type="text" name="age" id="age">
          </div>
          <div>
            <label>Type <img src="../../icons/edit-icon.png" alt="edit-icon" class="edit-icon"></label>
            <input type="text" name="type" id="type">
          </div>
          <div>
            <label>Gender <img src="../../icons/edit-icon.png" alt="edit-icon" class="edit-icon"></label>
            <select name="gender" id="gender">
              <option value="" disabled selected>Select gender</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
            </select>
          </div>
          <div>
            <label>Breed <img src="../../icons/edit-icon.png" alt="edit-icon" class="edit-icon"></label>
            <input type="text" name="breed" id="breed">
          </div>
          <div>
            <label>Status <img src="../../icons/edit-icon.png" alt="edit-icon" class="edit-icon"></label>
            <select name="status" id="status">
              <option value="" disabled selected>Select status</option>
              <option value="For rehoming">For rehoming</option>
              <option value="For adoption">For adoption</option>
              <option value="Adopted">Adopted</option>
              <option value="Deceased">Deceased</option>
            </select>
          </div>
          <div>
            <label>Pet Description <img src="../../icons/edit-icon.png" alt="edit-icon" class="edit-icon"></label>
            <textarea name="pet_desc" id="desc"></textarea>
          </div>
          <div>
            <label>Reason <img src="../../icons/edit-icon.png" alt="edit-icon" class="edit-icon"></label>
            <textarea name="reason" id="reason"></textarea>
          </div>

          <div class="modal-action">
            <button type="submit" name="save-btn">Save Changes</button>
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
    <!-- PET TABLE -->
    <section class="section-1">
      <div class="table-wrapper">
        <h2>Pet Table</h2>
        <div class="table-scroll">
          <div class="table">
            <div class="row pet-update">
              <p>Status</p>
              <p>Owner</p>
              <p>Pet Number</p>
              <p>Pet Name</p>
              <p>Age</p>
              <p>Type</p>
              <p>Breed</p>
              <p>Gender</p>
              <p>Pet Description</p>
              <p>Reason</p>
              <p>Date Created</p>
              <p>Date Updated</p>
              <p></p>
            </div>
            <?php petList($connection); ?>
          </div>
        </div>
      </div>
    </section>

    <!-- REHOMING TABLE
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
            <?php // rehomingList($connection) ?>
          </div>
        </div>
      </div>
    </section> -->
  </main>


  <script defer>
    const modal = document.querySelectorAll('.update-btn');
    const overlay = document.querySelector('.modal-overlay');
    const closeModal = document.querySelectorAll('.close-btn'); // x button
    const cancelBtn = document.querySelectorAll('.cancel-btn'); // cancel button

    // Pet Data (Adoption)
    const id = document.getElementById('id');
    const name = document.getElementById('name');
    const age = document.getElementById('age');
    const type = document.getElementById('type');
    const gender = document.getElementById('gender');
    const breed = document.getElementById('breed');
    const status = document.getElementById('status');
    const desc = document.getElementById('desc');
    const reason = document.getElementById('reason');
    
    // Inputs Data (Rehoming)
    // const r_id = document.getElementById('rId');
    // const r_name = document.getElementById('rName');
    // const r_age = document.getElementById('rAge');
    // const r_type = document.getElementById('rType');
    // const r_gender = document.getElementById('rGender');
    // const r_breed = document.getElementById('rBreed');
    // const r_status = document.getElementById('rStatus');

    // Event Listeners
    modal.forEach(btn => {
      btn.addEventListener('click', () => {
        id.value = btn.dataset.id;
        name.value = btn.dataset.name;
        age.value = btn.dataset.age;
        type.value = btn.dataset.type;
        breed.value = btn.dataset.breed;
        gender.value = btn.dataset.gender;
        status.value = btn.dataset.status;
        desc.value = btn.dataset.desc;
        reason.value = btn.dataset.reason;

        overlay.style.display = 'flex';
      });
    })
    // rehomingModal.forEach(btn => {
    //   btn.addEventListener('click', () => {
    //     r_id.value = btn.dataset.id;
    //     r_name.value = btn.dataset.name;
    //     r_age.value = btn.dataset.age;
    //     r_type.value = btn.dataset.type;
    //     r_gender.value = btn.dataset.gender;
    //     r_breed.value = btn.dataset.breed;
    //     r_status.value = btn.dataset.status;

    //     rehomingOverlay.style.display = 'flex';
    //   });
    // });

    closeModal.forEach(btn => {
      btn.addEventListener('click', () => {
        overlay.style.display = 'none';
      });
    })

    cancelBtn.forEach(btn => {
      btn.addEventListener('click', () => {
        overlay.style.display = 'none';
      });
    });

  </script>
  <script src="../scripts/admin-navigation.js" defer></script>
</body>
</html>