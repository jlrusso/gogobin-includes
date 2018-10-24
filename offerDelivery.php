<?php
session_start();
unset($_SESSION['address']);
if(isset($_POST['submit'])){
  include_once "dbh-inc.php";
  $ordererId = mysqli_real_escape_string($conn, $_POST['orderer-id']);
  date_default_timezone_set("America/Los_Angeles");
  $notiDate = date("d-m-Y", time()) . " "  . date("h:i:s a");
  $id = mysqli_real_escape_string($conn, $_SESSION['user_id']);
  $checkUserDelivery = "SELECT * FROM current_orders WHERE delivery_by=?;";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $checkUserDelivery)){
    header("Location: https://www.gogobin.com?stmt-error");
    exit();
  } else {
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $resultRows = mysqli_num_rows($result);
    if($resultRows > 0){
      header("Location: https://www.gogobin.com?one-delivery");
      exit();
    } else {
      //check if the user as already offered to delivery
      $checkForPreviousOffer = "SELECT offer_ids FROM current_orders WHERE user_id=?;";
      $stmt = mysqli_stmt_init($conn);
      if(!mysqli_stmt_prepare($stmt, $checkForPreviousOffer)){
        header("Location: https://www.gogobin.com?offer-stmt-err");
        exit();
      } else {
        mysqli_stmt_bind_param($stmt, "s", $ordererId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $resultNum = mysqli_num_rows($result);
        $offersArr = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $offersStr = $offersArr[0]['offer_ids'];
        $idToCheck = ",".$id.",";
        $offerExists = strpos($offersStr, $idToCheck);
        if($offerExists > -1){
          header("Location: https://www.gogobin.com?offer-already-made");
          exit();
        } else {
          #add offerers id to 'offer_ids' column with comma before and after to prevent multiple selection
          $getExistingOfferIds = "SELECT offer_ids FROM current_orders WHERE user_id=?;";
          $stmt = mysqli_stmt_init($conn);
          if(mysqli_stmt_prepare($stmt, $getExistingOfferIds)){
            mysqli_stmt_bind_param($stmt, "s", $ordererId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $resultData = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $offerIds = $resultData[0]['offer_ids'];
            $newOfferIds = $offerIds . "," . $id . ",";
            # insert the new value for offer_ids back into the current orderer
            $insertOffer = "UPDATE current_orders SET offer_ids=? WHERE user_id=?;";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $insertOffer)){
              header("Location: https://www.gogobin.com?insert-offer-id-err");
              exit();
            } else {
              mysqli_stmt_bind_param($stmt, "ss", $newOfferIds, $ordererId);
              mysqli_stmt_execute($stmt);
              $notiContent = mysqli_real_escape_string($conn, $_POST['message']);
              $insertNoti = "INSERT INTO noti_system (sender_id, recipient_id, noti_content, noti_type, noti_status, noti_date) VALUES (?, ?, ?, 'offer', 'unread', ?);";
              $stmt = mysqli_stmt_init($conn);
              if(!mysqli_stmt_prepare($stmt, $insertNoti)){
                header("Location: https://www.gogobin.com?stmt-error");
                exit();
              } else {
                mysqli_stmt_bind_param($stmt, "ssss", $id, $ordererId, $notiContent, $notiDate);
                mysqli_stmt_execute($stmt);
                header("Location: https://www.gogobin.com?offer-sent");
                exit();
              }
            }
          }
        }
      }
    }
  }
} else {
  header("Location: https://www.gogobin.com?offer-error");
  exit();
}
