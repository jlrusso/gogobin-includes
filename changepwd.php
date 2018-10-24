<?php
if(isset($_POST["submit"])){
  include_once "dbh-inc.php";
  $email = mysqli_real_escape_string($conn, $_POST["email"]);
  $token = mysqli_real_escape_string($conn, $_POST["token"]);
  $pwd = mysqli_real_escape_string($conn, $_POST["pwd-two"]);
  $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
  $updateUser = "UPDATE users SET user_pwd=? WHERE user_email=? AND pwd_token=?;";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $updateUser)){
    header("Location: ../linkpage?updated-pwd-stmt-err");
    exit();
  } else {
    mysqli_stmt_bind_param($stmt, "sss", $hashedPwd, $email, $token);
    mysqli_stmt_execute($stmt);
    $delToken = "UPDATE users SET pwd_token='' WHERE user_email=? AND pwd_token=?;";
    $stmt2 = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $delToken)){
      header("Location: ../linkpage?update-token-stmt-err");
      exit();
    } else {
      mysqli_stmt_bind_param($stmt, "ss", $email, $token);
      mysqli_stmt_execute($stmt);
      header("Location: https://www.gogobin.com?pwd-changed");
      exit();
    }
  }
}
