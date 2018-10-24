<?php
session_start();
if(isset($_POST['edit-submit'])){
  include_once "dbh-inc.php";
  $id = $_SESSION['user_id'];
  $first = mysqli_real_escape_string($conn, $_POST['first']);
  $last = mysqli_real_escape_string($conn, $_POST['last']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $city = mysqli_real_escape_string($conn, $_POST['city']);

  if(empty($first) || empty($last) || empty($email) || empty($city)){
    header("Location: ../profile?empty-field-err");
    exit();
  } else {
    $getUser = "SELECT * FROM users WHERE id!=? AND user_email=?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $getUser)){
      header("Location: ../profile?get-user-stmt-err");
      exit();
    } else {
      mysqli_stmt_bind_param($stmt, "ss", $id, $email);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $resultRows = mysqli_num_rows($result);
      if($resultRows > 0){
        header("Location: ../profile?email-taken-err");
        exit();
      } else {
        $updateUser = "UPDATE users SET user_first=?, user_last=?, user_email=?, user_city=? WHERE id=?;";
        $stmt2 = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt2, $updateUser)){
          header("Location: ../profile?update-user-stmt-err");
          exit();
        } else {
          mysqli_stmt_bind_param($stmt2, "sssss", $first, $last, $email, $city, $id);
          mysqli_stmt_execute($stmt2);
          $_SESSION['user_first'] = $first;
          $_SESSION['user_last'] = $last;
          $_SESSION['user_email'] = $email;
          $_SESSION['user_city'] = $city;
          header("Location: ../profile?edit-success");
          exit();
        }
      }
    }
  }
} else {
  header("Location ../profile.php?submit-error");
  exit();
}
