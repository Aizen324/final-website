<?php   
  $db_server = getenv('DB_HOST');
  $db_user = getenv('DB_USER');
  $db_password = getenv('DB_PASS');
  $db_name = getenv('DB_NAME');
  $connection = null;

  try {
    $connection = mysqli_connect($db_server, $db_user, $db_password, $db_name);
  } catch (mysqli_sql_exception) {
    echo "<script>alert('Database Connection Failed');</script>";
  }

?>