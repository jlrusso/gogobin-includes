<?php
session_start();
if(isset($_POST['submit'])){
  include_once "dbh-inc.php";
  $notiId = mysqli_real_escape_string($conn, $_POST['noti-id']);
  $otherId = mysqli_real_escape_string($conn, $_POST['other-id']);
  $id = $_SESSION['user_id'];
  $first = $_SESSION['user_first'];
  $last = $_SESSION['user_last'];
  $lastInitial = ucfirst($last[0]. ".");
  $full = $first . " " . $lastInitial;
  $notiContent = $full . " said the order is not complete";
  date_default_timezone_set("America/Los_Angeles");
  $notiDate = date("d-m-Y", time()) . " "  . date("h:i:s a");
  $insert = "INSERT INTO noti_system (sender_id, recipient_id, noti_content, noti_type, noti_status, noti_date) VALUES (?, ?, ?, 'delivery-incomplete', 'unread', ?);";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $insert)){
    header("Location: https://www.gogobin.com?stmt-error");
    exit();
  } else {
    mysqli_stmt_bind_param($stmt, "ssss", $id, $otherId, $notiContent, $notiDate);
    mysqli_stmt_execute($stmt);
    $removeNoti = "DELETE FROM noti_system WHERE recipient_id=? AND noti_id=?;";
    $stmt2 = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt2, $removeNoti)){
      header("Location: https://www.gogobin.com?stmt2-error");
      exit();
    } else {
      mysqli_stmt_bind_param($stmt2, "ss", $id, $notiId);
      mysqli_stmt_execute($stmt2);
      header("Location: https://www.gogobin.com?order-incompleted");
      exit();
    }
  }
} else {
  header("Location: https://www.gogobin.com?submit-error");
  exit();
}
