<?php
session_start();
if(isset($_POST['submit'])){
  include_once "dbh-inc.php";
  $id = $_SESSION['user_id'];
  $delEditOrder = "DELETE FROM edit_orders WHERE user_id=?;";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $delEditOrder)){
    header("Location: ../profile?del-edit-order-stmt-err");
    exit();
  } else {
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    unset($_SESSION['order-requested-again']);
  }
  $delCurrOrder = "DELETE FROM current_orders WHERE user_id=?;";
  $stmt2 = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt2, $delCurrOrder)){
    header("Location: ../profile?del-curr-order-stmt-err");
    exit();
  } else {
    mysqli_stmt_bind_param($stmt2, "s", $id);
    mysqli_stmt_execute($stmt2);
    $_SESSION['order-in-progress'] = "no";
    $_SESSION['same-order-in-progress'] = "no";
    $_SESSION['edit-in-progress'] = "no";
    header("Location: ../profile?deletion-success");
    exit();
  }
} else {
  header("Location ../profile?session=error");
  exit();
}
