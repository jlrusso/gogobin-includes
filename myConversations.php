<?php
$id = $_SESSION['user_id'];
$p2pMessage = "SELECT DISTINCT sender_id FROM msg_system WHERE recipient_id='$id';";
$p2pResult = mysqli_query($conn, $p2pMessage);
$p2pResultNum = mysqli_num_rows($p2pResult);
$p2pArr = mysqli_fetch_all($p2pResult, MYSQLI_ASSOC);
if($p2pResultNum < 1){
  echo "<p id='no-conversations'>No Conversations</p>";
} else {
  for($x = 0; $x < $p2pResultNum; $x++){
    $otherPersonId = $p2pArr[$x]['sender_id'];
    $getUsers = "SELECT * FROM users WHERE id='$otherPersonId';";
    $getUsersResult = mysqli_query($conn, $getUsers);
    $usersArr = mysqli_fetch_all($getUsersResult, MYSQLI_ASSOC);
    $otherFirst = ucfirst($usersArr[0]['user_first']);
    $otherLast = ucfirst($usersArr[0]['user_last']);
    $otherLastInitial = $otherLast[0] . ".";
    $otherFull = $otherFirst . " " . $otherLastInitial;
    $getOtherImgExt = "SELECT * FROM user_img WHERE user_id=$otherPersonId;";
    $result = mysqli_query($conn, $getOtherImgExt);
    $otherImgArr = mysqli_fetch_assoc($result);
    $otherImgExt = $otherImgArr['img_ext'];
    $randNum = $otherImgArr['img_rand'];
    $getUserImgExt = "SELECT * FROM user_img WHERE user_id=$id;";
    $result2 = mysqli_query($conn, $getUserImgExt);
    $userImgArr = mysqli_fetch_assoc($result2);
    $userImgExt = $userImgArr['img_ext'];
    if($otherImgExt == null){
      $otherImage = "../img/user-icon.png";
      echo "
        <div class='view-convo' sender='" . $otherPersonId . "' id='" . $otherPersonId . "-user'>
         <div class='convo-img-wrap' id='img-wrap-".$otherPersonId."' style='background-image: url(".$otherImage.")'></div>
         <p class='other-name'>" . $otherFull . " &nbsp; <span class='new-msg-alert' id='" . $otherPersonId ."-alert'>New Message</span></p>
         <input type='checkbox' id='checkbox-" . $otherPersonId . "' class=' hidden convo-checkbox'/>
        </div>
      ";
    } else {
      $otherImage = "../uploads/profile".$otherPersonId.$randNum.".".$otherImgExt."";
      echo "
        <div class='view-convo' sender='" . $otherPersonId . "' id='" . $otherPersonId . "-user'>
         <div class='convo-img-wrap' id='img-wrap-".$otherPersonId."' style='background-image: url(".$otherImage.")'></div>
         <p class='other-name'>" . $otherFull . " &nbsp; <span class='new-msg-alert' id='" . $otherPersonId ."-alert'>New Message</span></p>
         <input type='checkbox' id='checkbox-" . $otherPersonId . "' class=' hidden convo-checkbox'/>
        </div>
      ";
    }
    $getConvo = "SELECT * FROM msg_system WHERE (sender_id='$otherPersonId' AND recipient_id='$id' AND msg_status='read') OR (sender_id='$id' AND recipient_id='$otherPersonId' AND msg_status='read') ORDER BY msg_id ASC;";
    $getConvoResult = mysqli_query($conn, $getConvo);
    $convoResultNum = mysqli_num_rows($getConvoResult);
    $convoArr = mysqli_fetch_all($getConvoResult, MYSQLI_ASSOC);
    echo "<div class='messages-outer'>";
      echo "
        <div class='msgs-header-wrapper'>
          <div class='msgs-header-content'>
            <span><i class='convos fa fa-arrow-left'></i></span>
            <span class='msgs-header-name'>" . $otherFirst . " " . $otherLast . "</span>
          </div>
        </div>
      ";
      echo "<div class='messages-inner' id='with-user-" . $otherPersonId . "'>";
    for($x2 = 0; $x2 < $convoResultNum; $x2++){
      $msg = $convoArr[$x2]['msg_content'];
      if($convoArr[$x2]['recipient_id'] == $id){
        $otherId = $convoArr[$x2]['sender_id'];
        echo "
          <div class='received-message-grid'>
            <div class='msg-img-wrap' style='background-image: url(".$otherImage.")'></div>
            <p class='msg-wrapper'>
              <span class='received-msg'>" . stripslashes($msg) . "</span>
            </p>
            <div></div>
          </div>
        ";
      } else {
        $otherId = $convoArr[$x2]['recipient_id'];
        echo "
          <div class='sent-message-grid'>
            <div></div>
            <p><span class='sent-msg'>" . stripslashes($msg) . "</span></p>
          </div>
        ";
      }
    }
    echo "</div>"; //end of 'messages-inner'
    echo "
      <form class='reply-msg-form'>
        <input type='number' class='hidden' name='other-id' value='" . $otherPersonId. "' />
        <input type='text' name='message' placeholder='Message' autocomplete='off'/>
        <button type='button' name='submit' class='convo-send-submit'>
          <i class='convo fa fa-send-o' style='font-size: 20px'></i>
        </button>
      </form>
    ";
    echo "</div>"; //end of 'messages-outer'
  }
}
