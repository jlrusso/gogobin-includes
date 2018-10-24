<?php
session_start();
if(isset($_POST['sender'])){
  include_once "dbh-inc.php";
  $id = $_SESSION['user_id'];
  $senderId = mysqli_real_escape_string($conn, $_POST['sender']);
}
$getOtherImgExt = "SELECT * FROM user_img WHERE user_id=$senderId;";
$result = mysqli_query($conn, $getOtherImgExt);
$otherImgArr = mysqli_fetch_assoc($result);
$otherImgExt = $otherImgArr['img_ext'];
$randNum = $otherImgArr['img_rand'];
if($otherImgExt == null){
  $otherImage = "background-image: url(../img/user-icon.png)";
} else {
  $otherImage = "background-image: url(../uploads/profile.".$senderId.$randNum.".".$otherImgExt.")";
}
$getNewMsgs = "SELECT * FROM msg_system WHERE recipient_id='$id' AND sender_id='$senderId' AND msg_status='unread' ORDER BY msg_id ASC;";
$msgResult = mysqli_query($conn, $getNewMsgs);
$msgResultNum = mysqli_num_rows($msgResult);
if($msgResultNum > 0){
  $msgArr = mysqli_fetch_all($msgResult, MYSQLI_ASSOC);
  for($x = 0; $x < $msgResultNum; $x++){
    $msg = $msgArr[$x]['msg_content'];
    $msgId = $msgArr[$x]['msg_id'];
    echo "
      <div class='received-message-grid'>
        <div class='msg-img-wrap' data-for='img-wrap-".$senderId."'></div>
        <p class='rmsg-wrapper'>
          <span class='received-msg'>" . stripslashes($msg) . "</span>
        </p>
        <div></div>
      </div>
    ";
    $updateMsg = "UPDATE msg_system SET msg_status='read' WHERE msg_id=?;";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $updateMsg)){
      mysqli_stmt_bind_param($stmt, "s", $msgId);
      mysqli_stmt_execute($stmt);
    }
  }
}
