<?php
session_start();
include_once "dbh-inc.php";
$id = $_SESSION['user_id'];
$getAllNotis = "SELECT * FROM noti_system WHERE recipient_id=?;";
$stmt = mysqli_stmt_init($conn);
if(!mysqli_stmt_prepare($stmt, $getAllNotis)){
  header("Location: http://www.gogobin.com?stmt-error");
} else {
  mysqli_stmt_bind_param($stmt, "s", $id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $resultNum = mysqli_num_rows($result);
  if($resultNum > 0){
    echo "notis";
  }
}
