<?php
session_start();
include_once "dbh-inc.php";
$fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$subject = mysqli_real_escape_string($conn, $_POST['subject']);
$message = mysqli_real_escape_string($conn, $_POST['message']);
date_default_timezone_set("America/Los_Angeles");
$msg_date = date("d-m-Y", time()) . " "  . date("h:i:s a");

if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
  header("Location: https://www.gogobin.com?invalid=email");
  exit();
} else {
  $insertContact = "INSERT INTO user_contact (user_full, user_email, user_subject, user_msg, msg_date) VALUES (?, ?, ?, ?, ?);";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $insertContact)){
    header("Location: https://www.gogobin.com?stmt-error");
    exit();
  } else {
    mysqli_stmt_bind_param($stmt, "sssss", $fullname, $email, $subject, $message, $msg_date);
    mysqli_stmt_execute($stmt);
  }
}
