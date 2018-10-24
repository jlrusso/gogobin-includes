<?php
if(isset($_POST["submit"])){
  include_once "dbh-inc.php";
  $username = mysqli_real_escape_string($conn, $_POST["username"]);
  $email = mysqli_real_escape_string($conn, $_POST["confirm-email"]);
  $str = "abcdefghijklmnopqrstuvwxyz0123456789";
  $shuffledStr = str_shuffle($str);
  $token = substr($shuffledStr,0,9);
  $updateToken = "UPDATE users SET pwd_token=? WHERE user_username=? AND user_email=?;";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $updateToken)){
    header("Location: ../forgotpwd?pwd-token-stmt-err");
    exit();
  } else {
    mysqli_stmt_bind_param($stmt, "sss", $token, $username, $email);
    mysqli_stmt_execute($stmt);
    $emailLink = "www.gogobin.com/linkpage?token=" . $token . "&email=" . $email;
    $msg = "To change your password click on the link below\n\n" . $emailLink;
    $header = "From: adminjr@gogobin.com\r\n";
    mail($email, "Change Password", $msg, $header);
    header("Location: https://www.gogobin.com?email-sent");
    exit();
  }
} else {
  header("Location: https://www.gogobin.com?pwdchng-error");
  exit();
}
