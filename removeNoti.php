<?php
session_start();
if(isset($_POST['submit'])){
  include_once "dbh-inc.php";
  $notiId = mysqli_real_escape_string($conn, $_POST['noti-id']);
  $id = $_SESSION['user_id'];
  $delQuery = "DELETE FROM noti_system WHERE noti_id=? AND recipient_id=?;";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $delQuery)){
    header("Location: https://www.gogobin.com?remove-noti-stmt-err");
    exit();
  } else {
    mysqli_stmt_bind_param($stmt, "ss", $notiId, $id);
    mysqli_stmt_execute($stmt);
    header("Location: https://www.gogobin.com?noti-removed");
  }
} else {
  header("Location: https://www.gogobin.com?remove-noti-submit-err");
  exit();
}
