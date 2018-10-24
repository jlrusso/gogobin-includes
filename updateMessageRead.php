<?php
session_start();
include_once "dbh-inc.php";
if(isset($_POST['submit'])){
  $senderId = mysqli_real_escape_string($conn, $_POST['sender-id']);
  $id = $_SESSION['user_id'];
  $updateQuery = "UPDATE noti_system SET message_read='Yes' WHERE recipient_id='$id' AND sender_id='$senderId';";
  mysqli_query($conn, $updateQuery);
}
