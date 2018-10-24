<?php
session_start();
if(isset($_POST['submit'])){
 include_once "dbh-inc.php";
 $otherId = mysqli_real_escape_string($conn, $_POST['other-id']);
 $user = mysqli_real_escape_string($conn, $_POST['user']);
 $id = $_SESSION['user_id'];
 $notiContent = $user . " could not complete your order";
 date_default_timezone_set("America/Los_Angeles");
 $notiDate = date("d-m-Y", time()) . " "  . date("h:i:s a");

 $updateCurrOrder = "UPDATE current_orders SET order_status='1', delivery_by=NULL WHERE user_id=?;";
 $stmt = mysqli_stmt_init($conn);
 if(!mysqli_stmt_prepare($stmt, $updateCurrOrder)){
   header("Location: https://www.gogobin.com?stmt-error");
   exit();
 } else {
   mysqli_stmt_bind_param($stmt, "s", $otherId);
   mysqli_stmt_execute($stmt);
 }
 $insertNoti = "INSERT INTO noti_system (sender_id, recipient_id, noti_content, noti_type, noti_status, noti_date) VALUES (?, ?, ?, 'delivery-cancel', 'unread', ?);";
 $stmt2 = mysqli_stmt_init($conn);
 if(!mysqli_stmt_prepare($stmt2, $insertNoti)){
   header("Location: https://www.gogobin.com?stmt2-error");
   exit();
 } else {
   mysqli_stmt_bind_param($stmt2, "ssss", $id, $otherId, $notiContent, $notiDate);
   mysqli_stmt_execute($stmt2);
   header("Location: https://www.gogobin.com?cancel-success");
   exit();
 }
} else {
  header("Location: https://www.gogobin.com?cancel-delivery-error");
  exit();
}
