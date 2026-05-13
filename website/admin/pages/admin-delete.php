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

  function userList($connection) {
    $get_query = "SELECT * FROM users WHERE role = 'user';";
    $result = mysqli_query($connection, $get_query);

    if ($result && mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        echo "
          <div class='row user'>
            <p>{$row['user_name']}</p>
            <p>{$row['user_email']}</p>
            <p>{$row['role']}</p>
            <p>{$row['register_date']}</p>
            <form action='admin-delete.php' method='post' onsubmit=\"return confirm('This action is irreversible. Delete user {$row['user_name']}?')\">
              <input type='hidden' value='{$row['user_id']}' name='user_id'>
              <input type='hidden' value='{$row['user_name']}' name='user_name'>
              <button type='submit'class='delete-btn'>Delete</button>
            </form>
          </div>
        ";
      }
    }
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
            <form action='admin-delete.php' method='post' onsubmit=\"return confirm('This action is irreversible. Delete pet {$row['pet_name']}?')\">
              <input type='hidden' value='{$row['pet_id']}' name='pet_id'>
              <input type='hidden' value='{$row['pet_name']}' name='pet_name'>
              <button type='submit'class='delete-btn'>Delete</button>
            </form>
          </div>
        ";
      }
    }
  }
       

  // USER DELETE
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $user_name = $_POST['user_name'];
    $admin_id = $_SESSION['user_id'];
    $admin_name = $_SESSION['user_name'];
    
    // Delete User
    $delete_query = "DELETE FROM users WHERE user_id = '$user_id'";
    mysqli_query($connection, $delete_query);
    
    // Add Transaction (log)
    $details = mysqli_real_escape_string($connection, "$admin_name deleted user $user_name ($user_id).");

    $transac_query = 
      "INSERT INTO transactions (user_id, pet_id, trans_type, trans_details, category)
      VALUES ('$admin_id', NULL, 'User delete', '$details', 'User')";

    mysqli_query($connection, $transac_query);

    header('Location: admin-delete.php');
    exit();
  }

  // PET DELETE
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pet_id'])) {
    $pet_id = $_POST['pet_id'];
    $pet_name = $_POST['pet_name'];
    $admin_id = $_SESSION['user_id'];
    $admin_name = $_SESSION['user_name'];
    
    // Delete Pet
    $delete_query = "DELETE FROM pet_profile WHERE pet_id = '$pet_id'";
    mysqli_query($connection, $delete_query);

    // Add Transaction (log)
    $details = mysqli_real_escape_string($connection, "$admin_name deleted pet $pet_name ($pet_id).");

    $transac_query = 
      "INSERT INTO transactions (user_id, pet_id, trans_type, trans_details, category)
      VALUES ('$admin_id', '$pet_id', 'Pet delete', '$details', 'Pet')";
    
    mysqli_query($connection, $transac_query);

    header('Location: admin-delete.php');
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
  <title>Delete | Tailmates</title>
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
    <!-- USER TABLE -->
    <section class="section-1">
      <div class="table-wrapper">
        <h2>Customers Table</h2>
        <div class="table-scroll">
          <div class="table">
            <div class="row user">
              <p>Username</p>
              <p>Email</p>
              <p>Role</p>
              <p>Register Date</p>
              <p></p>
            </div>
            <?php userList($connection); ?>
          </div>
        </div>
      </div>
    </section>

    <!-- PET TABLE -->
    <section class="section-1">
      <div class="table-wrapper">
        <h2>Pet Table</h2>
        <div class="table-scroll">
          <div class="table">
            <div class="row pet-delete">
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
  </main>

  <script src="../scripts/admin-navigation.js" defer></script>
</body>
</html>