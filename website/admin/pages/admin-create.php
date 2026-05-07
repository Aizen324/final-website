<?php
  if (session_status() === PHP_SESSION_NONE) {
      session_start();
  }
  
  if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
      session_unset();
      session_destroy();
      
      header('Location: ../../user/html-pages/login.php?error=unauthorized');
      exit();
  }

  include('../../database/database.php');
  
  if (isset($_POST['add'])) {
    try {
      $pet_name = filter_input(INPUT_POST, "pet_name", FILTER_SANITIZE_SPECIAL_CHARS);
      $age = filter_input(INPUT_POST, "age", FILTER_SANITIZE_SPECIAL_CHARS);
      $type = filter_input(INPUT_POST, "type", FILTER_SANITIZE_SPECIAL_CHARS);
      $breed = filter_input(INPUT_POST, "breed", FILTER_SANITIZE_SPECIAL_CHARS);
      $gender = filter_input(INPUT_POST, "gender", FILTER_SANITIZE_SPECIAL_CHARS);
      $pet_desc = filter_input(INPUT_POST, "pet_desc", FILTER_SANITIZE_SPECIAL_CHARS);
      $owner_name = filter_input(INPUT_POST, "owner_name", FILTER_SANITIZE_SPECIAL_CHARS);
      $contact_no = filter_input(INPUT_POST, "contact_no", FILTER_SANITIZE_SPECIAL_CHARS);
      $user_id = $_SESSION['user_id'];

      $insert_query =
      "INSERT INTO adoption_listings (pet_name, age, type, breed, gender, pet_desc, owner_name, contact_no, user_id)
      VALUES ('$pet_name', '$age', '$type', '$breed', '$gender', '$pet_desc', '$owner_name', '$contact_no', '$user_id');
      ";
      mysqli_query($connection, $insert_query);

      echo "<script>alert('New pet added to adoption listing.');</script>";

      header('Location: admin-create.php');
    } catch (mysqli_sql_exception) {} 
  }


  function adoptionList($connection) {
    $get_query = 
      "SELECT a.pet_name, a.age, a.type, a.breed, a.gender, a.pet_desc, a.owner_name, a.contact_no, a.adoption_status, a.date_listed, u.user_name 
      FROM adoption_listings a
      LEFT JOIN users u ON a.user_id = u.user_id";
    $result = mysqli_query($connection, $get_query);
    
    if ($result && mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        echo "
          <div class='row'>
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
          </div>
        ";
      }
    }
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
  <title>Create | Tailmates</title>
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
    <section class="section-1">
      <div class="form-container">
        <div class="header-text">
          <h2>Create Adoption Listing</h2>
        </div>
        <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
          <div class="input-wrapper">
            <p>Pet Name: </p>
            <input type="text" name="pet_name" required>
          </div>

          <div class="input-wrapper">
            <p>Age: </p>
            <input type="text" name="age">
          </div>

          <div class="input-wrapper">
            <p>Type: </p>
            <input type="text" placeholder="e.g. Dog" name="type" required>
          </div>

          <div class="input-wrapper">
            <p>Breed: </p>
            <input type="text" name="breed">
          </div>

          <div class="input-wrapper">
            <p>Gender: </p>
            <select name="gender">
              <option value="" disabled selected>Select gender</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select>
          </div>

          <div class="input-wrapper">
            <p>Pet Description: </p>
            <textarea name="pet_desc" required></textarea>
          </div>

          <div class="input-wrapper">
            <p>Owner Name: </p>
            <input type="text" name="owner_name" required>
          </div>

          <div class="input-wrapper">
            <p>Contact Number: </p>
            <input type="text" name="contact_no" required>
          </div>

          <img src="../../images/two-paws.png" alt="paws" id="paw-img">
          <button type="submit" class="add-btn" name="add">Add</button>
        </form>
      </div>
      
    </section>

    <section class="section-2">
      <div class="adoption-wrapper">
        <h2>Adoption Listings Table</h2>
        <div class="table-scroll">
          <div class="adoption-table">
            <div class="row">
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
            </div>
            <?php adoptionList($connection); ?>
          </div>
        </div>
      </div>
    </section>
  </main>

  <script src="../scripts/admin-navigation.js" defer></script>
</body>
</html>