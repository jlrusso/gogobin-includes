<?php
session_start();
include_once "dbh-inc.php";
if(isset($_POST['other-id']) && isset($_POST['message'])){
  $otherId = mysqli_real_escape_string($conn, $_POST['other-id']);
  $msgContent = mysqli_real_escape_string($conn, $_POST['message']);
  date_default_timezone_set("America/Los_Angeles");
  $msgDate = date("d-m-Y", time()) . " "  . date("h:i:s a");
  $id = $_SESSION['user_id'];
  $getConvo = "SELECT * FROM msg_system WHERE (sender_id=? AND recipient_id=?) OR (recipient_id=? AND sender_id=?);";
  $stmt = mysqli_stmt_init($conn);
  if(mysqli_stmt_prepare($stmt, $getConvo)){
    mysqli_stmt_bind_param($stmt, "ssss", $otherId, $id, $otherId, $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $resultRows = mysqli_num_rows($result);
    if($resultRows > 0){
      $isNewConvo = "no";
    } else {
      $isNewConvo = "yes";
    }
  }
  $sendMsg = "INSERT INTO msg_system (sender_id, recipient_id, msg_content, msg_status, msg_date, new_convo) VALUES (?, ?, ?, 'unread', ?, ?);";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $sendMsg)){
    header("Location: https://www.gogobin.com?stmt-error");
    exit();
  } else {
    mysqli_stmt_bind_param($stmt, "sssss", $id, $otherId, $msgContent, $msgDate, $isNewConvo);
    mysqli_stmt_execute($stmt);
  }
} else {
  header("Location: https://www.gogobin.com?message-unsent");
  exit();
}
