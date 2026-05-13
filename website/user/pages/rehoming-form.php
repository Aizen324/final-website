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
      // $ownerName = mysqli_real_escape_string($connection, $_POST["owner_name"]);
      // $contact   = mysqli_real_escape_string($connection, $_POST["contact_num"]);
      $reason    = mysqli_real_escape_string($connection, $_POST["reason"]);

      $imageName = $_FILES['pet_image']['name'];
      $imageTemp = $_FILES['pet_image']['tmp_name'];
      $newImageName = time() . '_' . $imageName;
      $uploadPath = '../../uploads/' . $newImageName;
      move_uploaded_file($imageTemp, $uploadPath);

      $insert_query = 
        "INSERT INTO pet_profile (pet_name, user_id, age, type, gender, breed, pet_image, pet_desc, reason) 
        VALUES 
          ('$petName', '$user_id', '$age', '$type', '$gender', '$breed', '$newImageName', '$petDesc', '$reason')";
      
      mysqli_query($connection, $insert_query);

      $pet_id = mysqli_insert_id($connection);
      $details = mysqli_real_escape_string($connection, "$name rehomed $petName ($pet_id)");

      $transac_query = 
        "INSERT INTO transactions (user_id, pet_id, trans_type, trans_details, category)
        VALUES ('$user_id', '$pet_id', 'For rehoming', '$details', 'Pet')";
        
      mysqli_query($connection, $transac_query);

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
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            
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
              <select name="type" class="user-input" id="petType" required>
                <option value="" disabled selected>Select pet type</option>
                <option value="Dog">Dog</option>
                <option value="Cat">Cat</option>
                <option value="Bird">Bird</option>
                <option value="Rabbit">Rabbit</option>
              </select>
            </div>
          </div>

          <div class="wrapper">
            <p>Breed <span>*</span></p>
            <div class="input-wrapper">
              <select name="breed" class="user-input" id="petBreed" required>
                <option value="" disabled selected>Select pet breed</option>
              </select>
            </div>
          </div>

          <div class="wrapper">
            <p>Image <span>*</span></p>
            <div class="input-wrapper">
              <input type="file" class="user-input" name="pet_image" id="pet_image" accept="image/*" required>
            </div>
          </div>

          <div class="wrapper">
            <p id="pet-description">Pet Description <span>*</span><img src="../../icons/help.png" alt="Help" id="help-icon"> <span class="tooltipText">You can include your pet's behavior, physical characteristics, and favorite food.</span></p>
            <textarea class="description-input user-input"  name="pet_desc" required></textarea>
          </div>

            <div class="wrapper">
              <p>Reason for Rehoming <span>*</span></p>
              <textarea class="description-input user-input"  name="reason" required></textarea>
            </div>
        </div>
        
        <!-- <div class="line"></div>

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
        </div> -->

        <div class="buttons">
          <button class="cancel-form-btn" name="cancel">Cancel</button>
          <button class="submit-form-btn" name="submit">Submit Form</button>
        </div>
      </form>
    </section>
  </main>
  
  <script defer>
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
  <script src="../scripts/navigation.js" defer></script>
  </body>
</html>