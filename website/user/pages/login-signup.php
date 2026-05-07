<?php
  include("../../database/database.php");
  session_start();

  // Signup
  try {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup'])) {
      $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
      $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
      $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

      // Check if email already exists
      $check_sql = "SELECT * FROM users WHERE user_email = '$email'";
      $result = mysqli_query($connection, $check_sql);

      if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('User already exists.');</script>";
      } else {
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users(user_name, user_email, user_password) 
                VALUES ('$name', '$email', '$hashed_pass')";
        
        mysqli_query($connection, $sql);

        echo "<script>alert('Successfully registered.');</script>";
      }
    }
  } 
  catch (mysqli_sql_exception) {}
  catch (TypeError) {}

  // Login
  try {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
      $email = filter_input(INPUT_POST, "login_email", FILTER_SANITIZE_SPECIAL_CHARS);
      $password = filter_input(INPUT_POST, "login_password", FILTER_SANITIZE_SPECIAL_CHARS);
      
      $get_sql = "SELECT user_id, user_email, user_password, user_name, role
              FROM users 
              WHERE user_email = '$email' && role = 'user'";
      $result = mysqli_query($connection, $get_sql);

      if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['user_password'])) {
          $_SESSION['role'] = $user['role'];
          $_SESSION['user_name'] = $user['user_name'];
          $_SESSION['user_id'] = $user['user_id'];
          
          header("Location: home.php");
          exit();
        } else {
          echo "<script>alert('Invalid username/password.')</script>";
        }
      } else {
        echo "<script>alert('No user found.')</script>";
      }
    }
  } 
  catch (mysqli_sql_exception) {}
  catch (TypeError) {}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/login-signup.css">
  <link rel="stylesheet" href="../styles/general.css">
  <link rel="icon" type="image/x-icon" href="../../images/logo.png">
  <title>Tailmates</title>
</head>
<body>
  <div class="login-signup-nav">
    <div class="left_section">
      <img src="../../icons/home-icon.svg" alt="home-icon" class="home-icon">
    </div>
    <div class="right_section">
      <div class="admin-login">
        <img src="../../icons/person-icon.png" alt="person-icon" class="person-icon">
        <p>Login as Admin</p>
      </div>
    </div>
  </div>
  
  <main>
    <div class="box-container" id="box-container">
      <!-- Login Form -->
      <div class="form-container login">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
          <h1>Welcome Back!</h1>
          <div class="input-container">
            <input class="js-input" type="text" name="login_email" placeholder="Email">
            <input class="js-input" type="password" name="login_password" placeholder="Password">
            <p class="login-error err-message">Invalid username or password.</p>
          </div>
          <button class="js-login-submit" name="login">Login</button>
        </form>
      </div>

      <!-- Sign Up Form -->
      <div class="form-container sign-up">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
          <h1>Create an Account</h1>
          <div class="input-container">
            <input class="js-input" type="text" name="name" placeholder="Name">
            <p class="name-error err-message">Invalid username.</p>
          </div>
          <div class="input-container">
            <input class="js-input" type="text" name="email" placeholder="Email">
            <p class="email-error err-message">Invalid email address.</p>
          </div>
          <div class="input-container">
            <input class="js-input" type="password" name="password" placeholder="Password">
            <p class="password-error err-message">Invalid password.</p>
          </div>
          <button class="js-signup-submit" name="signup">Sign Up</button>
        </form>
      </div>

      <!-- Toggle Container -->
      <div class="overlay-container">
        <div class="overlay">
          <div class="overlay-panel overlay-left">
            <h1>Already have an account?</h1>
            <button class="js-loginBtn hidden">Login</button>
          </div>

          <div class="overlay-panel overlay-right">
            <h1>New here?</h1>
            <button class="js-signUpBtn hidden">Sign Up</button>
          </div>
        </div>
      </div>

    </div>
  </main>

  <script src="../scripts/navigation.js" defer></script>
  <script src="../scripts/login-signup.js" defer></script>
</body>
</html>

<?php
  try {
    mysqli_close($connection);
  } catch (TypeError) {}
?>