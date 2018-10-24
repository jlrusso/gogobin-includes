<?php
session_start();
if(isset($_POST['submit-pic'])){
  include_once "dbh-inc.php";
  $id = $_SESSION['user_id'];
  $file = $_FILES['file'];
  $fileName = $_FILES['file']['name'];
  $fileError = $_FILES['file']['error'];
  $fileType = $_FILES['file']['type'];
  $fileSize = $_FILES['file']['size'];
  $fileTmpName = $_FILES['file']['tmp_name'];
  $fileArr = explode(".", $fileName);
  $fileExt = strtolower(end($fileArr));
  $allowedExts = array("jpg", "jpeg", "png", "JPG", "JPEG", "PNG");
  $getUserData = "SELECT * FROM user_img WHERE user_id=? AND img_status IN (1, 0);";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $getUserData)){
    header("Location: ../profile?get-user-img-err");
    exit();
  } else {
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if(mysqli_num_rows($result) > 0){
      $resultData = mysqli_fetch_all($result, MYSQLI_ASSOC);
      if($resultData[0]['img_status'] === 0){
        $allFiles = glob("../uploads/profile".$id."*");
        unlink($allFiles[0]);
      }
      if(!in_array($fileExt, $allowedExts)){
        header("Location: ../profile?img-ext-err");
        exit();
      } else {
        if($fileError != 0){
          header("Location: ../profile?file-error");
          exit();
        } else {
          if($fileSize > 1000000){
            header("Location: ../profile?filesize-error");
            exit();
          } else {
            $randNum = mt_rand(0, 99999);
            $newFileName = "profile".$id. $randNum .".".$fileExt . "";
            $fileDestination = "../uploads/" . $newFileName;
            move_uploaded_file($fileTmpName, $fileDestination);
            $updateImg = "UPDATE user_img SET img_status=0, img_ext=?, img_rand=? WHERE user_id=?;";
            $stmt2 = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt2, $updateImg)){
              header("Location: ../profile?update-img-stmt-error");
              exit();
            } else {
              mysqli_stmt_bind_param($stmt2, "sss", $fileExt, $randNum, $id);
              mysqli_stmt_execute($stmt2);
              header("Location: ../profile?img-change-success");
              exit();
            }
          }
        }
      }
    } else {
      if(!in_array($fileExt, $allowedExts)){
        header("Location: ../profile.php?ext-error");
        exit();
      } else {
        if($fileError != 0){
          header("Location: ../profile.php?file-error");
          exit();
        } else {
          if($fileSize > 1000000){
            header("Location: ../profile.php?filesize-error");
            exit();
          } else {
            $randNum = mt_rand(0, 99999);
            $newFileName = "profile".$id. $randNum . ".".$fileExt . "";
            $fileDestination = "../uploads/" . $newFileName;
            move_uploaded_file($fileTmpName, $fileDestination);
            $updateImg = "UPDATE user_img SET img_status=0, img_rand=? WHERE user_id=?;";
            $stmt2 = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt2, $updateImg)){
              header("Location: ../profile.php?stmt2-error");
              exit();
            } else {
              mysqli_stmt_bind_param($stmt2, "ss", $randNum, $id);
              mysqli_stmt_execute($stmt2);
              header("Location: ../profile.php?img-success");
              exit();
            }
          }
        }
      }
    }
  }
} else {
  header("Location: ../profile.php?no-submit");
  exit();
}
