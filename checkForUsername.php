<?php
if(isset($_POST["username"])){
  include_once "dbh-inc.php";
  $userName = mysqli_real_escape_string($conn, $_POST["username"]);
}
$checkQuery = "SELECT * FROM users WHERE user_username=?;";
$stmt = mysqli_stmt_init($conn);
if(!mysqli_stmt_prepare($stmt, $checkQuery)){
  header("Location: ../forgotpwd?username-stmt-error");
  exit();
} else {
  mysqli_stmt_bind_param($stmt, "s", $userName);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $resultRows = mysqli_num_rows($result);
  if($resultRows > 0){
    echo $userName;
  }
}
