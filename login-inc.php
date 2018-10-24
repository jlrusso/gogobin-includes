<?php
if(isset($_POST['submit'])){
  include_once "dbh-inc.php";
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $pwd = mysqli_real_escape_string($conn, $_POST['pwd']);
  if(empty($username) || empty($pwd)){
    header("Location: https://www.gogobin.com?login-empty");
    exit();
  } else {
    $getUser = "SELECT * FROM users WHERE user_username=?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $getUser)){
      header("Location: https://www.gogobin.com?stmt-error");
      exit();
    } else {
      mysqli_stmt_bind_param($stmt, "s", $username);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $resultRows = mysqli_num_rows($result);
      if($resultRows > 0){
        $row = mysqli_fetch_assoc($result);
        $unhashedpwd = password_verify($pwd, $row['user_pwd']);
        if(!$unhashedpwd) {
          header("Location: https://www.gogobin.com?pwd-error");
          exit();
        } else {
          session_start();
          $_SESSION['user_id'] = $row['id'];
          $_SESSION['user_first'] = $row['user_first'];
          $_SESSION['user_last'] = $row['user_last'];
          $_SESSION['user_email'] = $row['user_email'];
          $_SESSION['user_username'] = $row['user_username'];
          $_SESSION['user_city'] = $row['user_city'];
          header("Location: https://www.gogobin.com?logged-in");
          exit();
        }
      } else {
        header("Location: ../signup?user-dne");
        exit();
      }
    }
  }
} else {
  header("Location: https://www.gogobin.com?login-submit-err");
  exit();
}
