<?php
session_start();
include_once "dbh-inc.php";
if(isset($_POST['submit'])){
  $id = $_SESSION["user_id"];
  $getCurrOrderId = "SELECT id FROM current_orders WHERE user_id=?";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $getCurrOrderId)){
    header("Location: https://www.gogobin.com?stmt-error");
    exit();
  } else {
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $resultRows = mysqli_num_rows($result);
    if($resultRows > 0){
      $currData = mysqli_fetch_all($result, MYSQLI_ASSOC);
      $orderId = $currData[0]['id'];
      $delFromPrev = "DELETE FROM previous_orders WHERE user_id=? AND order_id=?";
      $stmt = mysqli_stmt_init($conn);
      if(!mysqli_stmt_prepare($stmt, $delFromPrev)){
        header("Location: https://www.gogobin.com?stmt-error");
        exit();
      } else {
        mysqli_stmt_bind_param($stmt, "ss", $id, $orderId);
        mysqli_stmt_execute($stmt);
        $delFromEdit = "DELETE FROM edit_orders WHERE user_id=?;";
        $stmt2 = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt2, $delFromEdit)){
          header("Location: https://www.gogobin.com?stmt2-error");
          exit();
        } else {
          mysqli_stmt_bind_param($stmt2, "s", $id);
          mysqli_stmt_execute($stmt2);
          $delFromCur = "DELETE FROM current_orders WHERE user_id=?;";
          $stmt3 = mysqli_stmt_init($conn);
          if(!mysqli_stmt_prepare($stmt3, $delFromCur)){
            header("Location: https://www.gogobin.com?stmt3-error");
            exit();
          } else {
            mysqli_stmt_bind_param($stmt3, "s", $id);
            mysqli_stmt_execute($stmt3);
            $_SESSION['order-in-progress'] = "no";
            $_SESSION['same-order-in-progress'] = "no";
            $_SESSION['edit-in-progress'] = "no";
            header("Location: ../profile?order-cancelled");
            exit();
          }
        }
      }
    } else {
      header("Location: ../profile?zero-rows");
      exit();
    }
  }
} else {
  header("Location: ../profile?cancel-order-err");
  exit();
}
