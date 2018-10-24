<?php
session_start();
include_once "dbh-inc.php";
if(isset($_POST['userId'])){
  $userId = mysqli_real_escape_string($conn, $_POST['userId']);
  $getUserImg = "SELECT * FROM user_img WHERE user_id=?;";
  $stmt = mysqli_stmt_init($conn);
  if(mysqli_stmt_prepare($stmt, $getUserImg)){
    mysqli_stmt_bind_param($stmt, "s", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $resultNum = mysqli_num_rows($result);
    $userImgArr = mysqli_fetch_all($result, MYSQLI_ASSOC);
    if($resultNum > 0){
      if($userImgArr[0]['img_ext'] == NULL){
        echo "url(../img/user-icon.png)";
      } else {
        $imgExt = $userImgArr[0]['img_ext'];
        $randNum = $userImgArr[0]['img_rand'];
        echo "url(../uploads/profile" . $userId . $randNum . "." . $imgExt .")";
      }
    }
  }
}
