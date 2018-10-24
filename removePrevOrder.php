<?php
session_start();
if(isset($_POST['submit'])){
  include_once "dbh-inc.php";
  $id = $_SESSION['user_id'];
  $orderId = mysqli_real_escape_string($conn, $_POST['num']);
  $delPrevOrder = "DELETE FROM previous_orders WHERE order_id=? AND user_id=?;";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $delPrevOrder)){
    header("Location: ../profile?del-prev-order-stmt-error");
    exit();
  } else {
    mysqli_stmt_bind_param($stmt, "ss", $orderId, $id);
    mysqli_stmt_execute($stmt);
  }
  header("Location: ../profile?prev-order-removed");
  exit();
} else {
  header("Location: ../profile?prev-order-remove-error");
  exit();
}
