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
            <form action='admin-delete.php' method='post' onsubmit=\"return confirm('Delete user {$row['user_name']}?')\">
              <input type='hidden' value='{$row['user_id']}' name='user_id'>
              <button type='submit'class='delete-btn'>Delete</button>
            </form>
          </div>
        ";
      }
    }
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
            <form action='admin-delete.php' method='post' onsubmit=\"return confirm('Delete adoption listing?')\">
              <input type='hidden' value='{$row['adoption_id']}' name='adoption_id'>
              <button type='submit' class='delete-btn'>Delete</button>
            </form>
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
            <form action='admin-delete.php' method='post' onsubmit=\"return confirm('Delete rehoming listing?')\"'>
              <input type='hidden' value='{$row['rehoming_id']}' name='rehoming_id'>
              <button type='submit' class='delete-btn'>Delete</button>
            </form>
          </div>
        ";
      }
    }
  }

  // USER DELETE
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $delete_query = "DELETE FROM users WHERE user_id = '$user_id'";
    mysqli_query($connection, $delete_query);

    echo "<script>alert('User Deleted.');</script>";

    header('Location: admin-delete.php');
  }

  // ADOPTION DELETE
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['adoption_id'])) {
    $user_id = $_POST['adoption_id'];
    $delete_query = "DELETE FROM adoption_listings WHERE adoption_id = '$adoption_id'";
    mysqli_query($connection, $delete_query);

    echo "<script>alert('Adoption Listing Deleted.');</script>";

    header('Location: admin-delete.php');
  }

  // REHOMING DELETE
  if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['rehoming_id'])) {
    $rehoming_id = $_POST['rehoming_id'];
    $delete_query = "DELETE FROM rehoming_listings WHERE rehoming_id = $rehoming_id;";
    mysqli_query($connection, $delete_query);
    
    echo "<script>alert('Rehoming Listing Deleted.');</script>";
    
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
      <div class="user-wrapper">
        <h2>User Table</h2>
        <div class="table-scroll">
          <div class="user-table">
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

    <!-- ADOPTION TABLE -->
    <section class="section-2">
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
    <section class="section-3">
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
    </section>
  </main>

  <script src="../scripts/admin-navigation.js" defer></script>
</body>
</html>