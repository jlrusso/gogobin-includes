<?php
if(isset($_POST["pwd"]) && isset($_POST["email"])){
  include_once "dbh-inc.php";
  $email = mysqli_real_escape_string($conn, $_POST["email"]);
  $pwd = mysqli_real_escape_string($conn, $_POST["pwd"]);
  $getUser = "SELECT * FROM users WHERE user_email=?";
  $stmt = mysqli_stmt_init($conn);
  if(mysqli_stmt_prepare($stmt, $getUser)){
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $resultRows = mysqli_num_rows($result);
    if($resultRows > 0){
      $data = mysqli_fetch_assoc($result);
      $unhashedpwd = password_verify($pwd, $data['user_pwd']);
      if($unhashedpwd){
        echo "yes";
      }
    } else {
      header("Location: https://www.gogobin.com?user-dne");
      exit();
    }
  }
}
