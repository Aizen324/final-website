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

  // Add adoption listing 
  // The admin can add stray animals from shelters that are ready for 
  if (isset($_POST['add'])) {
    try {
      $pet_name = filter_input(INPUT_POST, "pet_name", FILTER_SANITIZE_SPECIAL_CHARS);
      $age = filter_input(INPUT_POST, "age", FILTER_SANITIZE_SPECIAL_CHARS);
      $type = filter_input(INPUT_POST, "type", FILTER_SANITIZE_SPECIAL_CHARS);
      $breed = filter_input(INPUT_POST, "breed", FILTER_SANITIZE_SPECIAL_CHARS);
      $gender = filter_input(INPUT_POST, "gender", FILTER_SANITIZE_SPECIAL_CHARS);
      $pet_desc = filter_input(INPUT_POST, "pet_desc", FILTER_SANITIZE_SPECIAL_CHARS);
      $reason = filter_input(INPUT_POST, "reason", FILTER_SANITIZE_SPECIAL_CHARS);
      $user_id = $_SESSION['user_id'];
      $user_name = $_SESSION['user_name'];

      $imageName = $_FILES['pet_image']['name'];
      $imageTemp = $_FILES['pet_image']['tmp_name'];
      $newImageName = time() . '_' . $imageName;
      $uploadPath = '../../uploads/' . $newImageName;
      move_uploaded_file($imageTemp, $uploadPath);

      // INSERT PET
      $insert_query =
        "INSERT INTO pet_profile (user_id, pet_name, age, type, gender, breed, pet_image, pet_desc, status, reason)
        VALUES ('$user_id', '$pet_name', '$age', '$type', '$gender', '$breed', '$newImageName', '$pet_desc', 'For adoption', '$reason');
        ";
      mysqli_query($connection, $insert_query);

      // ADD TRANSACTION
      $pet_id = mysqli_insert_id($connection);

      $details = mysqli_real_escape_string($connection, "$user_name added pet $pet_name ($pet_id) to the adoption listing.");

      $transac_query = 
        "INSERT INTO transactions (user_id, pet_id, trans_type, trans_details, category)
        VALUES ('$user_id', '$pet_id', 'For adoption', '$details', 'Pet')";
        
      mysqli_query($connection, $transac_query);

      header('Location: admin-create.php');
    } catch (mysqli_sql_exception) {} 
  }


  function petList($connection) {
    $get_query = 
      "SELECT pet_id, pet_name, age, type, breed, gender, pet_desc, reason, status, created, updated, user_name
      FROM pet_profile p
      LEFT JOIN users u ON p.user_id = u.user_id";
    $result = mysqli_query($connection, $get_query);
    
    if ($result && mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        echo "
          <div class='row pet-record'>
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
          </div>
        ";
      }
    }
  }

  function transactionsList($connection) {
    $get_query = 
      "SELECT t.trans_id, u.user_name, p.pet_name, t.trans_type, t.trans_date, t.trans_details
       FROM transactions t
       JOIN users u ON t.user_id = u.user_id
       JOIN pet_profile p ON t.pet_id = p.pet_id
       ORDER BY t.trans_id ASC";
    $result = mysqli_query($connection, $get_query);
    
    if ($result && mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        echo "
          <div class='row transaction'>
            <p>{$row['trans_id']}</p>
            <p>{$row['user_name']}</p>
            <p>{$row['pet_name']}</p>
            <p>{$row['trans_type']}</p>
            <p>{$row['trans_date']}</p>
            <p>{$row['trans_details']}</p>
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
    <section class="section-1 create">
      <div class="form-container">
        <div class="header-text">
          <h2>Create Adoption Listing</h2>
        </div>
        <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" enctype="multipart/form-data">
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
            <select name="type" class="user-input" id="petType" required>
              <option value="" disabled selected>Select pet type</option>
              <option value="Dog">Dog</option>
              <option value="Cat">Cat</option>
              <option value="Bird">Bird</option>
              <option value="Rabbit">Rabbit</option>
            </select>
          </div>

          <div class="input-wrapper">
            <p>Breed: </p>
            <select name="breed" class="user-input" id="petBreed" required>
              <option value="" disabled selected>Select pet breed</option>
            </select>
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
            <p>Pet Image: </p>
            <input type="file" class="user-input" name="pet_image" id="pet_image" accept="image/*" required>
          </div>

          <div class="input-wrapper">
            <p>Pet Description: </p>
            <textarea name="pet_desc" required></textarea>
          </div>

          <div class="input-wrapper">
            <p>Reason: </p>
            <textarea name="reason" required></textarea>
          </div>

          <img src="../../images/two-paws.png" alt="paws" id="paw-img">
          <button type="submit" class="add-btn" name="add">Add</button>
        </form>
      </div>
      
    </section>

    <section class="section-2">
      <p class="line"><span></span>RECORDS<span></span></p>
      <div class="table-wrapper">
        <h2>Pet Table</h2>
        <div class="table-scroll">
          <div class="table">
            <div class="row pet-record">
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
            </div>
            <?php petList($connection); ?>
          </div>
        </div>
      </div>
    </section>

    <section class="section-3">
      <div class="table-wrapper">
        <h2>Transactions Table</h2>
        <div class="table-scroll">
          <div class="table">
            <div class="row transaction">
              <p>Transaction Number</p>
              <p>User</p>
              <p>Pet</p>
              <p>Transaction Type</p>
              <p>Transaction Date</p>
              <p>Transaction Details</p>
            </div>
            <?php transactionsList($connection); ?>
          </div>
        </div>
      </div>
    </section>
  </main>

  <script>
    const petType = document.getElementById('petType');
    const breedSelect = document.getElementById('petBreed');
    const breeds = {
      Dog: ['Unknown', 'German Shepherd', 'Siberian Husky', 'Aspin', 'Labrador', 'Golden Retriever', 'Pug', 'Chihuahua', 'Terrier', 'Shiba Inu', 'Bulldog'],
      Cat: ['Unknown', 'Maine Coon', 'British Shorthair', 'Siamese', 'Scottish Fold', 'Puspin'],
      Bird: ['Unknown', 'Parrot', 'Cockatiel', 'Lovebird', 'Dove', 'Macaw'],
      Rabbit: ['Unknown', 'Lionhead', 'Mini Rex', 'Netherland Dwarf', 'English Angora', 'Californian']
    }

    petType.addEventListener('change', () => {
      const selectedType = petType.value;
      
      breedSelect.innerHTML = '<option value="" disabled selected>Select pet breed</option>';

      breeds[selectedType].forEach(breed => {
        const option = document.createElement('option');

        option.value = breed;
        option.textContent = breed;

        breedSelect.appendChild(option);
      });
    });

    document.getElementById('pet_image').addEventListener('change', function() {
      const file = this.files[0];
      if (file && !file.type.startsWith('image/')) {
          alert("Please select an image file only.");
          this.value = '';
      }
    });
  </script>
  <script src="../scripts/admin-navigation.js" defer></script>
</body>
</html>