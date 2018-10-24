<?php
  session_start();
  $id = $_SESSION['user_id'];
  $getNotis = "SELECT * FROM noti_system WHERE recipient_id='$id' AND noti_status='read';";
  $getResult = mysqli_query($conn, $getNotis);
  $resultNum = mysqli_num_rows($getResult);
  if($resultNum > 0){
    $getUsers = "SELECT * FROM users WHERE id!='$id';";
    $usersResult = mysqli_query($conn, $getUsers);
    $numOfUsers = mysqli_num_rows($usersResult);
    $usersArr = mysqli_fetch_all($usersResult, MYSQLI_ASSOC);
    for($x = 0; $x < $numOfUsers; $x++){
      $otherPersonId = $usersArr[$x]['id'];
      $notiWithUserX = "SELECT * FROM noti_system WHERE sender_id='$otherPersonId' AND recipient_id='$id' ORDER BY noti_date DESC;";
      $userNotiResult = mysqli_query($conn, $notiWithUserX);
      $numOfUserXnotis = mysqli_num_rows($userNotiResult);
      $notiArr = mysqli_fetch_all($userNotiResult, MYSQLI_ASSOC);
      for($x2 = 0; $x2 < $numOfUserXnotis; $x2++){
        if($notiArr[$x2]['noti_type'] == 'offer'){
          echo "
            <div class='user-notification'>
              <p class='accept-deny-message'>" . $notiArr[$x2]['noti_content'] . "</p>
              <div class='accept-deny-container'>
                <button class='accept-btn'>Accept</button>
                <button class='deny-btn'>Deny</button>
              </div>
              <form class='deny-form' method='POST' action='includes/denyDeliveryOffer.php' hidden>
                <input type='number' name='offerer-id' value='" . $notiArr[$x2]['sender_id'] . "'/>
                <input type='number' name='noti-id' value='" . $notiArr[$x2]['noti_id'] . "' />
                <input type='submit' name='submit' class='deny-offer-submit'/>
              </form>
              <form class='accept-form' method='POST' action='includes/acceptDeliveryOffer.php' hidden>
                <input type='number' name='offerer-id' value='" . $notiArr[$x2]['sender_id'] . "'/>
                <input type='number' name='noti-id' value='" . $notiArr[$x2]['noti_id'] . "' />
                <input type='submit' name='submit' class='accept-offer-submit'/>
              </form>
            </div>
          ";
        } else if ($notiArr[$x2]['noti_type'] == 'accept-offer'){
          echo "
           <div class='user-notification'>
              <p class='decision-message'>" . $notiArr[$x2]['noti_content'] . "</p>
              <div class='noti-btns-wrapper'>
                <span class='mail-icon' title='Send Message'><i class='fa fa-envelope'></i></span>
                <span class='remove-icon' title='Remove Notification'><i class='fa fa-close'></i></span>
              </div>
              <form class='remove-noti-form' method='POST' action='includes/removeNoti.php' hidden>
                <input type='number' name='noti-id' value='" . $notiArr[$x2]['noti_id'] . "'/>
                <input type='submit' name='submit' class='remove-noti-submit'/>
              </form>
            </div>
            <form class='send-message-form'>
              <div class='text-submit-container'>
                <input type='number' name='other-id' class='hidden' value='" . $notiArr[$x2]['sender_id'] . "'/>
                <input type='text' name='message' placeholder='Message' required='required' autocomplete='off' class='noti-msg'/>
                <button type='button' name='submit' class='noti-message-submit'>
                  <i class='noti fa fa-send-o' style='font-size: 20px'></i>
                </button>
              </div>
            </form>
          ";
        } else if ($notiArr[$x2]['noti_type'] == 'deny-offer'){
          echo "
           <div class='user-notification'>
              <p class='decision-message'>" . $notiArr[$x2]['noti_content'] . "</p>
              <div class='remove-noti-wrapper'>
                <span class='remove-icon' title='Remove Notification'><i class='fa fa-close'></i></span>
              </div>
              <form class='remove-noti-form' method='POST' action='includes/removeNoti.php' hidden>
                <input type='number' name='noti-id' value='" . $notiArr[$x2]['noti_id'] . "'/>
                <input type='submit' name='submit' class='remove-noti-submit'/>
              </form>
            </div>
          ";
        } else if ($notiArr[$x2]['noti_type'] == 'delivery-completed'){
          echo "
           <div class='user-notification'>
              <p class='decision-message'>" . $notiArr[$x2]['noti_content'] . "</p>
              <div class='yes-no-container'>
                <span class='yes-btn'>Yes</span>
                <span class='no-btn'>No</span>
              </div>
              <form class='delivery-check-form' method='POST' action='includes/deliveryIsComplete.php' hidden>
               <input type='number' name='noti-id' value='" . $notiArr[$x2]['noti_id'] . "' />
               <input type='submit' name='submit' class='delivery-complete-submit'/>
              </form>
              <form class='incomplete-delivery-form' action='includes/deliveryIncomplete.php' method='POST' hidden>
                <input type='number' name='noti-id' value='" . $notiArr[$x2]['noti_id'] . "' hidden/>
                <input type='number' name='other-id' value='" . $notiArr[$x2]['sender_id'] . "' hidden/>
                <input type='submit' name='submit' class='delivery-incomplete-submit' hidden/>
              </form>
           </div>
          ";
        } else if ($notiArr[$x2]['noti_type'] == 'delivery-incomplete'){
          echo "
           <div class='user-notification'>
              <p class='decision-message'>" . $notiArr[$x2]['noti_content'] . "</p>
              <div class='noti-btns-wrapper'>
                <span class='mail-icon' title='Send Message'><i class='fa fa-envelope'></i></span>
                <span class='remove-icon' title='Remove Notification'><i class='fa fa-close'></i></span>
              </div>
              <form class='remove-noti-form' method='POST' action='includes/removeNoti.php' hidden>
                <input type='number' name='noti-id' value='" . $notiArr[$x2]['noti_id'] . "'/>
                <input type='submit' name='submit' class='remove-noti-submit'/>
              </form>
            </div>
            <form class='send-message-form'>
              <div class='text-submit-container'>
                <input type='number' name='other-id' class='hidden' value='" . $notiArr[$x2]['sender_id'] . "'/>
                <input type='text' name='message' placeholder='Message' required='required' autocomplete='off' class='noti-msg'/>
                <button type='button' name='submit' class='noti-message-submit'>
                  <i class='noti fa fa-send-o' style='font-size: 20px'></i>
                </button>
              </div>
            </form>
          ";
        } else if ($notiArr[$x2]['noti_type'] == 'delivery-cancel'){
          echo "
           <div class='user-notification'>
              <p class='decision-message'>" . $notiArr[$x2]['noti_content'] . "</p>
              <div class='noti-btns-wrapper'>
                <span class='mail-icon' title='Send Message'><i class='fa fa-envelope'></i></span>
                <span class='remove-icon' title='Remove Notification'><i class='fa fa-close'></i></span>
              </div>
              <form class='remove-noti-form' method='POST' action='includes/removeNoti.php' hidden>
                <input type='number' name='noti-id' value='" . $notiArr[$x2]['noti_id'] . "'/>
                <input type='submit' name='submit' class='remove-noti-submit'/>
              </form>
            </div>
            <form class='send-message-form'>
              <div class='text-submit-container'>
                <input type='number' name='other-id' class='hidden' value='" . $notiArr[$x2]['sender_id'] . "'/>
                <input type='text' name='message' placeholder='Message' required='required' autocomplete='off' class='noti-msg'/>
                <button type='button' name='submit' class='noti-message-submit'>
                  <i class='noti fa fa-send-o' style='font-size: 20px'></i>
                </button>
              </div>
            </form>
          ";
        }
      }
    }
  }
