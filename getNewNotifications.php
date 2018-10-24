<?php
session_start();
include_once "dbh-inc.php";
$id = $_SESSION['user_id'];
$getNotis = "SELECT * FROM noti_system WHERE recipient_id=$id AND noti_status='unread' ORDER BY noti_id DESC;";
$getResult = mysqli_query($conn, $getNotis);
$resultNum = mysqli_num_rows($getResult);
$notisArr = mysqli_fetch_all($getResult, MYSQLI_ASSOC);
if($resultNum > 0){
  for($counter = 0; $counter < $resultNum; $counter++){
    switch($notisArr[$counter]['noti_type']){
      case ("offer"):
        echo "
          <div class='user-notification'>
            <p class='accept-deny-message'>" . $notisArr[$counter]['noti_content'] . "</p>
            <div class='accept-deny-container'>
              <button class='accept-btn'>Accept</button>
              <button class='deny-btn'>Deny</button>
            </div>
            <form class='deny-form' method='POST' action='includes/denyDeliveryOffer.php' hidden>
              <input type='number' name='offerer-id' value='" . $notisArr[$counter]['sender_id'] . "'/>
              <input type='number' name='noti-id' value='" . $notisArr[$counter]['noti_id'] . "' />
              <input type='submit' name='submit' class='deny-offer-submit'/>
            </form>
            <form class='accept-form' method='POST' action='includes/acceptDeliveryOffer.php' hidden>
              <input type='number' name='offerer-id' value='" . $notisArr[$counter]['sender_id'] . "'/>
              <input type='number' name='noti-id' value='" . $notisArr[$counter]['noti_id'] . "' />
              <input type='submit' name='submit' class='accept-offer-submit'/>
            </form>
          </div>
        ";
        break;
      case ("accept-offer"):
        echo "
         <div class='user-notification'>
            <p class='decision-message'>" . $notisArr[$counter]['noti_content'] . "</p>
            <div class='noti-btns-wrapper'>
              <span class='mail-icon' title='Send Message'><i class='fa fa-envelope'></i></span>
              <span class='remove-icon' title='Remove Notification'><i class='fa fa-close'></i></span>
            </div>
            <form class='remove-noti-form' method='POST' action='includes/removeNoti.php' hidden>
              <input type='number' name='noti-id' value='" . $notisArr[$counter]['noti_id'] . "'/>
              <input type='submit' name='submit' class='remove-noti-submit'/>
            </form>
          </div>
          <form class='send-message-form'>
            <div class='text-submit-container'>
              <input type='number' name='other-id' class='hidden' value='" . $notisArr[$counter]['sender_id'] . "'/>
              <input type='text' name='message' placeholder='Message' required='required' autocomplete='off' class='noti-msg'/>
              <button type='button' name='submit' class='noti-message-submit'>
                <i class='fa fa-send-o' style='font-size: 20px'></i>
              </button>
            </div>
          </form>
        ";
        break;
      case ("deny-offer"):
        echo "
          <div class='user-notification'>
            <p class='decision-message'>" . $notisArr[$counter]['noti_content'] . "</p>
            <div class='remove-noti-wrapper'>
              <span class='remove-icon' title='Remove Notification'><i class='fa fa-close'></i></span>
            </div>
            <form class='remove-noti-form' method='POST' action='includes/removeNoti.php' hidden>
              <input type='number' name='noti-id' value='" . $notisArr[$counter]['noti_id'] . "'/>
              <input type='submit' name='submit' class='remove-noti-submit'/>
            </form>
          </div>
        ";
        break;
      case ("delivery-completed"):
        echo "
          <div class='user-notification'>
             <p class='decision-message'>" . $notisArr[$counter]['noti_content'] . "</p>
             <div class='yes-no-container'>
               <span class='yes-btn'>Yes</span>
               <span class='no-btn'>No</span>
             </div>
             <form class='delivery-check-form' method='POST' action='includes/deliveryIsComplete.php' hidden>
              <input type='number' name='noti-id' value='" . $notisArr[$counter]['noti_id'] . "' />
              <input type='submit' name='submit' class='delivery-complete-submit'/>
             </form>
             <form class='incomplete-delivery-form' action='includes/deliveryIncomplete.php' method='POST' hidden>
               <input type='number' name='noti-id' value='" . $notisArr[$counter]['noti_id'] . "' hidden/>
               <input type='number' name='other-id' value='" . $notisArr[$counter]['sender_id'] . "' hidden/>
               <input type='submit' name='submit' class='delivery-incomplete-submit' hidden/>
             </form>
           </div>
        ";
        break;
      case ("delivery-incomplete"):
        echo "
          <div class='user-notification'>
            <p class='decision-message'>" . $notisArr[$counter]['noti_content'] . "</p>
            <div class='noti-btns-wrapper'>
              <span class='mail-icon' title='Send Message'><i class='fa fa-envelope'></i></span>
              <span class='remove-icon' title='Remove Notification'><i class='fa fa-close'></i></span>
            </div>
            <form class='remove-noti-form' method='POST' action='includes/removeNoti.php' hidden>
              <input type='number' name='noti-id' value='" . $notisArr[$counter]['noti_id'] . "'/>
              <input type='submit' name='submit' class='remove-noti-submit'/>
            </form>
          </div>
          <form class='send-message-form'>
            <div class='text-submit-container'>
              <input type='number' name='other-id' class='hidden' value='" . $notisArr[$counter]['sender_id'] . "'/>
              <input type='text' name='message' placeholder='Message' required='required' autocomplete='off' class='noti-msg'/>
              <button type='button' name='submit' class='noti-message-submit'>
                <i class='fa fa-send-o' style='font-size: 20px'></i>
              </button>
            </div>
          </form>
        ";
        break;
      case ("delivery-cancel"):
        echo "
          <div class='user-notification'>
           <p class='decision-message'>" . $notisArr[$counter]['noti_content'] . "</p>
           <div class='noti-btns-wrapper'>
             <span class='mail-icon' title='Send Message'><i class='fa fa-envelope'></i></span>
             <span class='remove-icon' title='Remove Notification'><i class='fa fa-close'></i></span>
           </div>
           <form class='remove-noti-form' method='POST' action='includes/removeNoti.php' hidden>
             <input type='number' name='noti-id' value='" . $notisArr[$counter]['noti_id'] . "'/>
             <input type='submit' name='submit' class='remove-noti-submit'/>
           </form>
         </div>
         <form class='send-message-form'>
           <div class='text-submit-container'>
             <input type='number' name='other-id' class='hidden' value='" . $notisArr[$counter]['sender_id'] . "'/>
             <input type='text' name='message' placeholder='Message' required='required' autocomplete='off' class='noti-msg'/>
             <button type='button' name='submit' class='noti-message-submit'>
               <i class='fa fa-send-o' style='font-size: 20px'></i>
             </button>
           </div>
         </form>
        ";
        break;
    }
    $notiId = $notisArr[$counter]['noti_id'];
    $updateNotiStatus = "UPDATE noti_system SET noti_status='read' WHERE noti_id=?;";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $updateNotiStatus)){
      mysqli_stmt_bind_param($stmt, "s", $notiId);
      mysqli_stmt_execute($stmt);
    }
  }
}
