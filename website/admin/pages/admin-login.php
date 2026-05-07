<?php
  session_start();
  include('../../database/database.php');

  try {
    if (isset($_POST['login'])) {
      $email = mysqli_real_escape_string($connection, $_POST['email']);
      $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

      $read_sql = "SELECT * FROM users WHERE role = 'admin';";
      $result = mysqli_query($connection, $read_sql);
      
      if ($result && mysqli_num_rows($result) > 0) {
        $admin_user = mysqli_fetch_assoc($result);

        if ($password === $admin_user['user_password']) {
          $_SESSION['role'] = $admin_user['role'];
          $_SESSION['user_id'] = $admin_user['user_id'];
          
          header('Location: admin-dashboard.php');
          exit();
        } else {
          echo "<script>alert('Invalid username or password.')</script>";
        }
      }
    }
  } catch (mysqli_sql_exception) {}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/login-styles.css">
  <link rel="stylesheet" href="../styles/general-styles.css">
  <link rel="icon" type="image/x-icon" href="../../images/logo.png">
  <title>Tailmates</title>
</head>
<body>
  <div class="login-signup-nav">
    <div class="left_section">
      <img src="../../icons/home-icon.svg" alt="home-icon" class="home-icon">
    </div>
    <div class="right_section">
      <div class="user-login">
        <img src="../../icons/person-icon.png" alt="person-icon" class="person-icon">
        <p>Login as User</p>
      </div>
    </div>
  </div>
  
  <main>
    <div class="box-container" id="box-container">
      <!-- Login Form -->
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
        <h1>Admin Login</h1>
        <div class="input-container">
          <input class="js-input" type="text" name="email" placeholder="Email">
          <input class="js-input" type="password" name="password" placeholder="Password">
          <p class="login-error err-message">Invalid username or password.</p>
        </div>
        <button class="js-login-submit" name="login">Login</button>
      </form>

    </div>
  </main>

  <script src="../scripts/admin-navigation.js" defer></script>
  <script src="../scripts/login-signup.js" defer></script>
</body>
</html>