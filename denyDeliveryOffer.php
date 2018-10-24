<?php
session_start();
include_once "dbh-inc.php";
if(isset($_POST['submit'])){
  $id = $_SESSION['user_id'];
  $firstName = $_SESSION['user_first'];
  $lastName = $_SESSION['user_last'];
  $lastInitial = $lastName[0];
  $notiId = mysqli_real_escape_string($conn, $_POST['noti-id']);
  $offererId = mysqli_real_escape_string($conn, $_POST['offerer-id']);
  date_default_timezone_set("America/Los_Angeles");
  $notiDate = date("d-m-Y", time()) . " "  . date("h:i:s a");
  $delQuery = "DELETE FROM noti_system WHERE sender_id='$offererId' AND recipient_id='$id' AND noti_id='$notiId';";
  mysqli_query($conn, $delQuery);
  $notiContent = ucfirst($firstName) . " " . ucfirst($lastInitial) . ". has denied your offer";
  $insertNoti = "INSERT INTO noti_system (sender_id, recipient_id, noti_content, noti_type, noti_status, noti_date) VALUES (?, ?, ?, 'deny-offer', 'unread', ?);";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $insertNoti)){
    header("Location: https://www.gogobin.com?stmt-error");
    exit();
  } else {
    mysqli_stmt_bind_param($stmt, "ssss", $id, $offererId, $notiContent, $notiDate);
    mysqli_stmt_execute($stmt);
    header("Location: ../profile?offer-denied");
    exit();
  }
} else {
  header("Location: ../profile?deny-submit-error");
  exit();
}
