<?php
session_start();
if(isset($_POST['submit'])){
 include_once "dbh-inc.php";
 $ordererId = mysqli_real_escape_string($conn, $_POST['other-id']);
 $user = mysqli_real_escape_string($conn, $_POST['user']);
 $id = $_SESSION['user_id'];
 $notiContent = "Has " . $user . " completed your order?";
 date_default_timezone_set("America/Los_Angeles");
 $notiDate = date("d-m-Y", time()) . " "  . date("h:i:s a");
 $insert = "INSERT INTO noti_system (sender_id, recipient_id, noti_content, noti_type, noti_status, noti_date) VALUES ($id, $ordererId, '$notiContent', 'delivery-completed', 'unread', '$notiDate');";
 $result = mysqli_query($conn, $insert);
 if($result){
   header("Location: https://www.gogobin.com?delivery-completed");
   exit();
 } else {
   header("Location: https://www.gogobin.com?query-error");
   exit();
 }
} else {
  header("Location: https://www.gogobin.com?submit-error");
  exit();
}
