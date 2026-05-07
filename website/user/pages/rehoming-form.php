<?php
  session_start();
  include("../../database/database.php");

  if (isset($_SESSION['user_name'])) {
    $name = $_SESSION['user_name'];
    $user_id = $_SESSION['user_id'];
  } else {
    header('Location: home.php');
    exit();
  }

  try {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
      $petName   = mysqli_real_escape_string($connection, $_POST["pet_name"]);
      $age       = mysqli_real_escape_string($connection, $_POST["age"]);
      $gender    = mysqli_real_escape_string($connection, $_POST["gender"]);
      $type      = mysqli_real_escape_string($connection, $_POST["type"]);
      $breed     = mysqli_real_escape_string($connection, $_POST["breed"]);
      $petDesc   = mysqli_real_escape_string($connection, $_POST["pet_desc"]);
      $ownerName = mysqli_real_escape_string($connection, $_POST["owner_name"]);
      $contact   = mysqli_real_escape_string($connection, $_POST["contact_num"]);
      $reason    = mysqli_real_escape_string($connection, $_POST["reason"]);
      
      $insert_query = 
        "INSERT INTO rehoming_listings (pet_name, age, type, gender, breed, pet_desc, owner_name, contact_no, reason, user_id) 
        VALUES 
          ('$petName', '$age', '$type', '$gender', '$breed', '$petDesc', '$ownerName', '$contact', '$reason', '$user_id')";
      
      mysqli_query($connection, $insert_query);
      header('Location: rehoming-page.php');
      exit();
    }
  } 
  catch(mysqli_sql_exception) {}
?>

<!-- HTML PAGE -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/general.css">
  <link rel="stylesheet" href="../styles/rehoming-form.css">
  <link rel="icon" type="image/x-icon" href="../../images/logo.png">
  <title>Rehoming Form</title>
</head>
<body>
  <!-- Main Section -->
  <main>
    <section class="section-1">
      <div class="header-container">
        <h1>REHOMING FORM</h1>
        <p>Please complete this form with as much detail as possible to help us find the most compatible home for your companion. Ensure that you fill in the input fields marked with an asterisk. Accurate information regarding your pet’s health and daily habits ensures a smooth transition and a successful long-term placement. Once submitted, our team will review the details and reach out to discuss the next steps in the rehoming process.</p>
      </div>
      <img src="../../images/teal-paw.png" alt="paw">
    </section>

    <section class="section-2">
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            
        <div class="line"></div>
        <div class="form1-container">
          <h2>Pet Information</h2>
            
          <div class="wrapper">
            <p>Pet Name <span>*</span></p>
            <div class="input-wrapper">
              <input type="text" class="user-input" name="pet_name" required>
            </div>
          </div>

          <div class="wrapper">
            <p>Age</p>
            <div class="input-wrapper">
              <input type="text" class="user-input" name="age">
            </div> 
          </div>

          <div class="wrapper">
            <p>Gender <span>*</span></p>
            <div class="input-wrapper">
              <select class="user-input" name="gender" required>
                <option value="" disabled selected>Select gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
              </select>
            </div>
          </div>

          <div class="wrapper">
            <p>Type <span>*</span></p>
            <div class="input-wrapper">
              <input type="text" placeholder="e.g. Cat" class="user-input" name="type" required>
            </div>
          </div>

          <div class="wrapper">
            <p>Breed <span>*</span></p>
            <div class="input-wrapper">
              <input type="text" class="user-input" name="breed" required>
            </div>
          </div>

          <div class="wrapper">
            <p>Description <span>*</span></p>
            <textarea class="description-input user-input" maxlength="700" name="pet_desc" required></textarea>
          </div>
        </div>
        
        <div class="line"></div>

        <div class="form2-container">
          <h2>Owner Information</h2>
          
            <div class="wrapper">
              <p>Pet Owner Name <span>*</span></p>
              <div class="input-wrapper">
                <input type="text" class="user-input" name="owner_name" required>
              </div>
            </div>

            <div class="wrapper">
              <p>Contact Number <span>*</span></p>
              <div class="input-wrapper">
                <input type="text" class="user-input" name="contact_num" required>
              </div>
            </div>

            <div class="wrapper">
              <p>Reason for Rehoming <span>*</span></p>
              <textarea class="description-input user-input" maxlength="700" name="reason" required></textarea>
            </div>
        </div>

        <div class="buttons">
          <button class="cancel-form-btn" name="cancel">Cancel</button>
          <button class="submit-form-btn" name="submit">Submit Form</button>
        </div>
      </form>
    </section>
  </main>

  <script src="../scripts/navigation.js" defer></script>
  </body>
</html>