<?php
session_start();
include_once "dbh-inc.php";
$id = $_SESSION["user_id"];
if(isset($_POST['convoIds'])){
  $convoIds = $_POST['convoIds'];
}
$getNewDistinctConvo = "SELECT * FROM msg_system WHERE recipient_id=? AND msg_status='unread' AND new_convo='yes';";
$stmt = mysqli_stmt_init($conn);
if(mysqli_stmt_prepare($stmt, $getNewDistinctConvo)){
  mysqli_stmt_bind_param($stmt, "s", $id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $resultRows = mysqli_num_rows($result);
  if($resultRows > 0){
    $resultData = mysqli_fetch_all($result, MYSQLI_ASSOC);
    for($x = 0; $x < $resultRows; $x++){
      $senderId = $resultData[$x]['sender_id'];
      $msgId = $resultData[$x]['msg_id'];
      $getUsers = "SELECT * FROM users WHERE id='$senderId';";
      $getUsersResult = mysqli_query($conn, $getUsers);
      $usersArr = mysqli_fetch_all($getUsersResult, MYSQLI_ASSOC);
      $otherFirst = ucfirst($usersArr[0]['user_first']);
      $otherLast = ucfirst($usersArr[0]['user_last']);
      $otherLastInitial = $otherLast[0] . ".";
      $otherFull = $otherFirst . " " . $otherLastInitial;
      $getSenderImg = "SELECT * FROM user_img WHERE user_id=$senderId;";
      $result2 = mysqli_query($conn, $getSenderImg);
      $senderImgArr = mysqli_fetch_assoc($result2);
      $senderImgExt = $senderImgArr['img_ext'];
      $randNum = $senderImgArr['img_rand'];
      if($senderImgExt == null){
        $senderImage = "../img/user-icon.png";
      } else {
        $senderImage = "../uploads/profile".$senderId.$randNum.".".$senderImgExt."";
      }
      echo "
        <div class='view-convo' sender='" . $senderId . "' id='" . $senderId . "-user'>
         <div class='convo-img-wrap' id='img-wrap-".$senderId."' style='background-image: url(".$senderImage.")'></div>
         <p class='other-name'>" . $otherFull . " &nbsp; <span class='new-msg-alert' id='" . $senderId ."-alert'>New Message</span></p>
         <input type='checkbox' id='checkbox-" . $senderId . "' class=' hidden convo-checkbox'/>
        </div>
      ";
    }
  }

  $getNewConvoMsgs = "SELECT * FROM msg_system WHERE recipient_id=? and new_convo='yes' ORDER BY msg_id ASC;";
  $stmt = mysqli_stmt_init($conn);
  if(mysqli_stmt_prepare($stmt, $getNewConvoMsgs)){
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $resultRows = mysqli_num_rows($result);
    $msgArr = mysqli_fetch_all($result, MYSQLI_ASSOC);
    if($resultRows > 0){
      echo "<div class='messages-outer'>";
        echo "
          <div class='msgs-header-wrapper'>
            <div class='msgs-header-content'>
              <span><i class='convos fa fa-arrow-left'></i></span>
              <span class='msgs-header-name'>" . $otherFirst . " " . $otherLast . "</span>
            </div>
          </div>
        ";
        echo "<div class='messages-inner' id='with-user-" . $senderId . "'>";
      for($counter = 0; $counter < $resultRows; $counter++){
        $msg = $msgArr[$counter]['msg_content'];
        echo "
          <div class='received-message-grid'>
            <div class='msg-img-wrap' style='background-image: url(".$senderImage.")'></div>
            <p class='msg-wrapper'>
              <span class='received-msg'>" . stripslashes($msg) . "</span>
            </p>
            <div></div>
          </div>
        ";
      }
      echo "</div>"; //end of 'messages-inner'
      echo "
        <form class='reply-msg-form'>
          <input type='number' class='hidden' name='other-id' value='" . $senderId. "' />
          <input type='text' name='message' placeholder='Message' autocomplete='off'/>
          <button type='button' name='submit' class='convo-send-submit'>
            <i class='convo fa fa-send-o' style='font-size: 20px'></i>
          </button>
        </form>
      ";
      echo "</div>"; //end of 'messages-outer'
    }
  }
  $updateMsg = "UPDATE msg_system SET new_convo='no', msg_status='read' WHERE msg_id=?;";
  $stmt = mysqli_stmt_init($conn);
  if(mysqli_stmt_prepare($stmt, $updateMsg)){
    mysqli_stmt_bind_param($stmt, "s", $msgId);
    mysqli_stmt_execute($stmt);
  }
}
