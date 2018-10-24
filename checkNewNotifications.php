<?php
session_start();
include_once "dbh-inc.php";
$id = $_SESSION['user_id'];
$getNewNotis = "SELECT * FROM noti_system WHERE recipient_id=? AND noti_status='unread';";
$stmt = mysqli_stmt_init($conn);
if(!mysqli_stmt_prepare($stmt, $getNewNotis)){
  header("Location: https://www.gogobin.com?stmt-error");
  exit();
} else {
  mysqli_stmt_bind_param($stmt, "s", $id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $resultRows = mysqli_num_rows($result);
  if($resultRows > 0){
    echo 1;
  }
}
