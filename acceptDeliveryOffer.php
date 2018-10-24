<?php
session_start();
include_once "dbh-inc.php";
if(isset($_POST['submit'])){
  $id = $_SESSION['user_id'];
  $offererId = mysqli_real_escape_string($conn, $_POST['offerer-id']);
  $notiId = mysqli_real_escape_string($conn, $_POST['noti-id']);
  $firstName = mysqli_real_escape_string($conn, $_SESSION['user_first']);
  $lastName = mysqli_real_escape_string($conn, $_SESSION['user_last']);
  $lastInitial = $lastName[0];
  $notiContent = ucfirst($firstName) . " " . ucfirst($lastInitial) . ". has accepted your offer";
  date_default_timezone_set("America/Los_Angeles");
  $notiDate = date("d-m-Y", time()) . " "  . date("h:i:s a");
  $checkOrderStatus = "SELECT * FROM current_orders WHERE user_id=? AND order_status='1';";
  $checkStmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($checkStmt, $checkOrderStatus)){
    header("Location: https://www.gogobin.com?checkstmt-error");
    exit();
  } else {
    mysqli_stmt_bind_param($checkStmt, "s", $id);
    mysqli_stmt_execute($checkStmt);
    $result = mysqli_stmt_get_result($checkStmt);
    $resultRows = mysqli_num_rows($result);
    if($resultRows <= 0){
      header("Location: https://www.gogobin.com?delivery-inprogress");
      exit();
    } else {
      // delete delivery offer that was received
      $delNoti = "DELETE FROM noti_system WHERE recipient_id=? AND sender_id=? AND noti_id=?;";
      $delStmt = mysqli_stmt_init($conn);
      if(!mysqli_stmt_prepare($delStmt, $delNoti)){
        header("Location: https://www.gogobin.com?delstmt-error");
        exit();
      } else {
        mysqli_stmt_bind_param($delStmt, "sss", $id, $offererId, $notiId);
        mysqli_stmt_execute($delStmt);
        // insert notification where user accepts delivery offered
        $acceptNoti = "INSERT INTO noti_system (sender_id, recipient_id, noti_content, noti_type, noti_status, noti_date) VALUES (?, ?, ?, 'accept-offer', 'unread', ?);";
        $acceptStmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($acceptStmt, $acceptNoti)){
          header("Location: https://www.gogobin.com?acceptStmt-error");
          exit();
        } else {
          mysqli_stmt_bind_param($acceptStmt, "ssss", $id, $offererId, $notiContent, $notiDate);
          mysqli_stmt_execute($acceptStmt);
          $updateOrder = "UPDATE current_orders SET order_status='2', delivery_by=? WHERE user_id=?;";
          $updateStmt = mysqli_stmt_init($conn);
          if(!mysqli_stmt_prepare($updateStmt, $updateOrder)){
            header("Location: https://www.gogobin.com?updateStmt-error");
            exit();
          } else {
            mysqli_stmt_bind_param($updateStmt, "ss", $offererId, $id);
            mysqli_stmt_execute($updateStmt);
            header("Location: https://www.gogobin.com?offer-accepted");
            exit();
          }
        }
      }
    }
  }
} else {
  header("Location: https://www.gogobin.com?submit-error");
  exit();
}
