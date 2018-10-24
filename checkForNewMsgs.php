<?php
session_start();
include_once "dbh-inc.php";
$id = mysqli_real_escape_string($conn, $_SESSION["user_id"]);
$getUnreadMsgSenders = "SELECT DISTINCT sender_id FROM msg_system WHERE recipient_id=? AND msg_status='unread';";
$stmt = mysqli_stmt_init($conn);
if(!mysqli_stmt_prepare($stmt, $getUnreadMsgSenders)){
  header("Location: https://www.gogobin.com?stmt-error");
  exit();
} else {
  mysqli_stmt_bind_param($stmt, "s", $id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $resultRows = mysqli_num_rows($result);
  if($resultRows > 0){
    $resultData = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $senderArr = [];
    for($row = 0; $row < $resultRows; $row++){
      array_push($senderArr, $resultData[$row]["sender_id"]);
    }
    echo json_encode($senderArr);
  }
}
