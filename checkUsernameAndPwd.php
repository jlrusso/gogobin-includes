<?php
session_start();
if(isset($_POST["username"]) && isset($_POST["pwd"])){
  include_once "dbh-inc.php";
  $username = mysqli_real_escape_string($conn, $_POST["username"]);
  $pwd = mysqli_real_escape_string($conn, $_POST["pwd"]);
  $checkUsername = "SELECT * FROM users WHERE user_username=?;";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $checkUsername)){
    header("Location: https://www.gogobin.com?stmt-error");
    exit();
  } else {
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $resultRows = mysqli_num_rows($result);
    if($resultRows > 0){
      $resultData = mysqli_fetch_all($result, MYSQLI_ASSOC);
      $pwdCheck = password_verify($pwd, $resultData[0]["user_pwd"]);
      if(!$pwdCheck){
        echo "incorrect";
      }
    } else {
      echo "incorrect";
    }
  }
} else {
  header("Location: https://www.gogobin.com?checkusername-pwd-submit-err");
  exit();
}
