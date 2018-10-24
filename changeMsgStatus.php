<?php
session_start();
if(isset($_POST['sender'])){
  include_once "dbh-inc.php";
  $sender = mysqli_real_escape_string($_POST['sender']);
  $id = mysqli_real_escape_string($_SESSION['user_id']);

  $getUnreadMsgs = "SELECT * FROM msg_system WHERE recipient_id=? AND sender_id=? AND msg_status='unread';";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $getUnreadMsgs)){
    header("Location: https://www.gogobin.com?stmt-error")
    exit();
  } else {
    mysqli_stmt_bind_param($stmt, "ss", $id, $sender);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $msgResultNum = mysqli_num_rows($result);
    $unreadMsgArr = mysqli_fetch_assoc($result);
    if($msgResultNum > 0){
      for($x = 0; $x < $msgResultNum; $x++){
        $msgId = $unreadMsgArr[$x]['msg_id'];
        $changeToRead = "UPDATE msg_system SET msg_status='read' WHERE msg_id=?;";
        $stmt2 = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt2, $changeToRead)){
          header("Location: https://www.gogobin.com?stmt2-error");
          exit();
        } else {
          mysqli_stmt_bind_param($stmt2, "s", $msgId);
          mysqli_stmt_execute($stmt2);
        }
      }
    }
  }
} else {
  header("Location: https://www.gogobin.com?no-sender");
  exit();
}
