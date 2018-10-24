<?php
session_start();
if(isset($_POST['submit'])){
  include_once "dbh-inc.php";
  $id = mysqli_real_escape_string($conn, $_SESSION['user_id']);
  $notiId = mysqli_real_escape_string($conn, $_POST['noti-id']);
  $updateOrder = "UPDATE current_orders SET order_status='3' WHERE user_id=?;";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $updateOrder)){
    header("Location: https://www.gogobin.com?stmt-error");
    exit();
  } else {
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
  }
  $removeNoti = "DELETE FROM noti_system WHERE recipient_id=? AND noti_id=?;";
  $stmt2 = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt2, $removeNoti)){
    header("Location: https://www.gogobin.com?stmt2-error");
    exit();
  } else {
    mysqli_stmt_bind_param($stmt2, "ss", $id, $notiId);
    mysqli_stmt_execute($stmt2);
    header("Location: https://www.gogobin.com?order-completed");
    exit();
  }
} else {
  header("Location: https://www.gogobin.com?submit-error");
  exit();
}
