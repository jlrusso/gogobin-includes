<?php
session_start();
if(isset($_POST['submit'])){
  include_once "dbh-inc.php";
  unset($_SESSION['address']);
  header("Location: https://www.gogobin.com?address-unset");
  exit();
} else {
  header("Location: https://www.gogobin.com?address-unseterror");
  exit();
}
