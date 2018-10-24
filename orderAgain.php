<?php
session_start();
if(isset($_POST['submit'])){
  include_once "dbh-inc.php";
  $id = mysqli_real_escape_string($conn, $_SESSION['user_id']);
  $orderId = mysqli_real_escape_string($conn, $_POST['num']);
  $getPrevOrder = "SELECT * FROM previous_orders WHERE user_id=? AND order_id=?;";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $getPrevOrder)){
    header("Location: https://www.gogobin.com?stmt-error");
    exit();
  } else {
    mysqli_stmt_bind_param($stmt, "ss", $id, $orderId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $resultRows = mysqli_num_rows($result);
    if($resultRows > 0){
      $_SESSION['order-requested-again'] = $orderId;
      $_SESSION['same-order-in-progress'] = "yes";
      $_SESSION['edit-in-progress'] = "no";
      header("Location: https://www.gogobin.com?orderagain=progress");
      exit();
    } else {
      header("Location: https://www.gogobin.com?noresults=error");
      exit();
    }
  }
} else {
  header("Location: ../profile.php?submit=error");
  exit();
}
