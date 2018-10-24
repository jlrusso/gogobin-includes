<?php
session_start();
include_once "dbh-inc.php";
$id = $_SESSION["user_id"];
$checkCurrOrders = "SELECT * FROM current_orders WHERE user_id=?";
$stmt = mysqli_stmt_init($conn);
if(!mysqli_stmt_prepare($stmt, $checkCurrOrders)){
  header("Location: https://www.gogobin.com?stmt-error");
  exit();
} else {
  mysqli_stmt_bind_param($stmt, "s", $id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $resultRows = mysqli_num_rows($result);
  if($resultRows > 0){
    echo "order in progress";
  }
}
