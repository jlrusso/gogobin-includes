<?php
session_start();
error_reporting(E_ALL ^ E_NOTICE);
if(isset($_POST['submit'])){
  include_once "dbh-inc.php";
  $id = $_SESSION['user_id'];
  $checkCurrOrders = "SELECT * FROM current_orders WHERE user_id=?;";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $checkCurrOrders)){
    header("Location: https://www.gogobin.com?stmt-error");
    exit();
  } else {
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $resultRows = mysqli_num_rows($result);
    if($resultRows > 0){
      header("Location: https://www.gogobin.com?one-order");
      exit();
    } else {
      $storeName = mysqli_real_escape_string($conn, $_POST['store_name']);
      $storeCity = mysqli_real_escape_string($conn, $_POST['store_city']);
      $storeAddress = mysqli_real_escape_string($conn, $_POST['store_address']);
      $deliveryTime = mysqli_real_escape_string($conn, $_POST['delivery_time']);
      $foodIds = mysqli_real_escape_string($conn, $_POST['food_ids']);
      $itemNames = mysqli_real_escape_string($conn, $_POST['item_names']);
      $item1specs = mysqli_real_escape_string($conn, $_POST['item_1_specs']);
      $item2specs = mysqli_real_escape_string($conn, $_POST['item_2_specs']);
      $item3specs = mysqli_real_escape_string($conn, $_POST['item_3_specs']);
      $item4specs = mysqli_real_escape_string($conn, $_POST['item_4_specs']);
      $item5specs = mysqli_real_escape_string($conn, $_POST['item_5_specs']);
      $item6specs = mysqli_real_escape_string($conn, $_POST['item_6_specs']);
      $item7specs = mysqli_real_escape_string($conn, $_POST['item_7_specs']);
      $item8specs = mysqli_real_escape_string($conn, $_POST['item_8_specs']);
      $item9specs = mysqli_real_escape_string($conn, $_POST['item_9_specs']);
      $item10specs = mysqli_real_escape_string($conn, $_POST['item_10_specs']);
      $item11specs = mysqli_real_escape_string($conn, $_POST['item_11_specs']);
      $item12specs = mysqli_real_escape_string($conn, $_POST['item_12_specs']);
      $item13specs = mysqli_real_escape_string($conn, $_POST['item_13_specs']);
      $item14specs = mysqli_real_escape_string($conn, $_POST['item_14_specs']);
      $item15specs = mysqli_real_escape_string($conn, $_POST['item_15_specs']);
      $item16specs = mysqli_real_escape_string($conn, $_POST['item_16_specs']);
      $item17specs = mysqli_real_escape_string($conn, $_POST['item_17_specs']);
      $item18specs = mysqli_real_escape_string($conn, $_POST['item_18_specs']);
      $item19specs = mysqli_real_escape_string($conn, $_POST['item_19_specs']);
      $item20specs = mysqli_real_escape_string($conn, $_POST['item_20_specs']);
      $item21specs = mysqli_real_escape_string($conn, $_POST['item_21_specs']);
      $item22specs = mysqli_real_escape_string($conn, $_POST['item_22_specs']);
      $item23specs = mysqli_real_escape_string($conn, $_POST['item_23_specs']);
      $item24specs = mysqli_real_escape_string($conn, $_POST['item_24_specs']);
      $item25specs = mysqli_real_escape_string($conn, $_POST['item_25_specs']);
      $item26specs = mysqli_real_escape_string($conn, $_POST['item_26_specs']);
      $item27specs = mysqli_real_escape_string($conn, $_POST['item_27_specs']);
      $item28specs = mysqli_real_escape_string($conn, $_POST['item_28_specs']);
      $item29specs = mysqli_real_escape_string($conn, $_POST['item_29_specs']);
      $item30specs = mysqli_real_escape_string($conn, $_POST['item_30_specs']);
      $item31specs = mysqli_real_escape_string($conn, $_POST['item_31_specs']);
      $item32specs = mysqli_real_escape_string($conn, $_POST['item_32_specs']);
      $item33specs = mysqli_real_escape_string($conn, $_POST['item_33_specs']);
      $item34specs = mysqli_real_escape_string($conn, $_POST['item_34_specs']);
      $item35specs = mysqli_real_escape_string($conn, $_POST['item_35_specs']);
      $item36specs = mysqli_real_escape_string($conn, $_POST['item_36_specs']);
      $item37specs = mysqli_real_escape_string($conn, $_POST['item_37_specs']);
      $item38specs = mysqli_real_escape_string($conn, $_POST['item_38_specs']);
      $item39specs = mysqli_real_escape_string($conn, $_POST['item_39_specs']);
      $item40specs = mysqli_real_escape_string($conn, $_POST['item_40_specs']);
      $orderStatus = 1;
      date_default_timezone_set("America/Los_Angeles");
      $orderDate = date("d-m-Y", time()) . " "  . date("h:i:s a");
      unset($_SESSION['order-requested-again']);
      $_SESSION['same-order-in-progress'] = "no";


      $insertCurrOrder = "INSERT INTO current_orders (user_id, store_name, store_city, store_address, delivery_time, food_ids, item_names, item_1_specs, item_2_specs, item_3_specs, item_4_specs, item_5_specs, item_6_specs, item_7_specs, item_8_specs, item_9_specs, item_10_specs, item_11_specs, item_12_specs, item_13_specs, item_14_specs, item_15_specs, item_16_specs, item_17_specs, item_18_specs, item_19_specs, item_20_specs, item_21_specs, item_22_specs, item_23_specs, item_24_specs, item_25_specs, item_26_specs, item_27_specs, item_28_specs, item_29_specs, item_30_specs, item_31_specs, item_32_specs, item_33_specs, item_34_specs, item_35_specs, item_36_specs, item_37_specs, item_38_specs, item_39_specs, item_40_specs, order_status, order_date) VALUES ($id, '$storeName', '$storeCity', '$storeAddress', '$deliveryTime', '$foodIds', '$itemNames', '$item1specs', '$item2specs', '$item3specs', '$item4specs', '$item5specs', '$item6specs', '$item7specs', '$item8specs', '$item9specs', '$item10specs', '$item11specs', '$item12specs', '$item13specs', '$item14specs', '$item15specs', '$item16specs', '$item17specs', '$item18specs', '$item19specs', '$item20specs', '$item21specs', '$item22specs', '$item23specs', '$item24specs', '$item25specs', '$item26specs', '$item27specs', '$item28specs', '$item29specs', '$item30specs', '$item31specs', '$item32specs', '$item33specs', '$item34specs', '$item35specs', '$item36specs', '$item27specs', '$item38specs', '$item39specs', '$item40specs', '$orderStatus', '$orderDate');";
      mysqli_query($conn, $insertCurrOrder);


      if($_SESSION['edit-in-progress'] == "yes"){
        $updatedEditOrder = "UPDATE edit_orders SET store_name='$storeName', store_city='$storeCity', store_address='$storeAddress', food_ids='$foodIds', delivery_time='$deliveryTime', item_names='$itemNames', item_1_specs='$item1specs', item_2_specs='$item2specs', item_3_specs='$item3specs', item_4_specs='$item4specs', item_5_specs='$item5specs', item_6_specs='$item6specs', item_7_specs='$item7specs', item_8_specs='$item8specs', item_9_specs='$item9specs', item_10_specs='$item10specs', item_11_specs='$item11specs', item_12_specs='$item12specs', item_13_specs='$item13specs', item_14_specs='$item14specs', item_15_specs='$item15specs', item_16_specs='$item16specs', item_17_specs='$item17specs', item_18_specs='$item18specs', item_19_specs='$item19specs', item_20_specs='$item20specs', item_21_specs='$item21specs', item_22_specs='$item22specs', item_23_specs='$item23specs', item_24_specs='$item24specs', item_25_specs='$item25specs', item_26_specs='$item26specs', item_27_specs='$item27specs', item_28_specs='$item28specs', item_29_specs='$item29specs', item_30_specs='$item30specs', item_31_specs='$item31specs', item_32_specs='$item32specs', item_33_specs='$item33specs', item_34_specs='$item34specs', item_35_specs='$item35specs', item_36_specs='$item36specs', item_37_specs='$item37specs', item_38_specs='$item38specs', item_39_specs='$item39specs', item_40_specs='$item40specs' WHERE user_id=$id;";
        mysqli_query($conn, $updatedEditOrder);
        $_SESSION["edit-in-progress"] == "no";
      } else {
        $insertEditOrder = "INSERT INTO edit_orders (user_id, store_name, store_city, store_address, delivery_time, food_ids, item_names, item_1_specs, item_2_specs, item_3_specs, item_4_specs, item_5_specs, item_6_specs, item_7_specs, item_8_specs, item_9_specs, item_10_specs, item_11_specs, item_12_specs, item_13_specs, item_14_specs, item_15_specs, item_16_specs, item_17_specs, item_18_specs, item_19_specs, item_20_specs, item_21_specs, item_22_specs, item_23_specs, item_24_specs, item_25_specs, item_26_specs, item_27_specs, item_28_specs, item_29_specs, item_30_specs, item_31_specs, item_32_specs, item_33_specs, item_34_specs, item_35_specs, item_36_specs, item_37_specs, item_38_specs, item_39_specs, item_40_specs) VALUES ($id, '$storeName', '$storeCity', '$storeAddress', '$deliveryTime', '$foodIds', '$itemNames', '$item1specs', '$item2specs', '$item3specs', '$item4specs', '$item5specs', '$item6specs', '$item7specs', '$item8specs', '$item9specs', '$item10specs', '$item11specs', '$item12specs', '$item13specs', '$item14specs', '$item15specs', '$item16specs', '$item17specs', '$item18specs', '$item19specs', '$item20specs', '$item21specs', '$item22specs', '$item23specs', '$item24specs', '$item25specs', '$item26specs', '$item27specs', '$item28specs', '$item29specs', '$item30specs', '$item31specs', '$item32specs', '$item33specs', '$item34specs', '$item35specs', '$item36specs', '$item27specs', '$item38specs', '$item39specs', '$item40specs');";
        mysqli_query($conn, $insertEditOrder);
        $_SESSION["edit-in-progress"] == "no";
      }

      $insertPrevOrder = "INSERT INTO previous_orders (user_id, store_name, store_city, store_address, delivery_time, food_ids, item_names, item_1_specs, item_2_specs, item_3_specs, item_4_specs, item_5_specs, item_6_specs, item_7_specs, item_8_specs, item_9_specs, item_10_specs, item_11_specs, item_12_specs, item_13_specs, item_14_specs, item_15_specs, item_16_specs, item_17_specs, item_18_specs, item_19_specs, item_20_specs, item_21_specs, item_22_specs, item_23_specs, item_24_specs, item_25_specs, item_26_specs, item_27_specs, item_28_specs, item_29_specs, item_30_specs, item_31_specs, item_32_specs, item_33_specs, item_34_specs, item_35_specs, item_36_specs, item_37_specs, item_38_specs, item_39_specs, item_40_specs, order_date) VALUES ($id, '$storeName', '$storeCity', '$storeAddress', '$deliveryTime', '$foodIds', '$itemNames', '$item1specs', '$item2specs', '$item3specs', '$item4specs', '$item5specs', '$item6specs', '$item7specs', '$item8specs', '$item9specs', '$item10specs', '$item11specs', '$item12specs', '$item13specs', '$item14specs', '$item15specs', '$item16specs', '$item17specs', '$item18specs', '$item19specs', '$item20specs', '$item21specs', '$item22specs', '$item23specs', '$item24specs', '$item25specs', '$item26specs', '$item27specs', '$item28specs', '$item29specs', '$item30specs', '$item31specs', '$item32specs', '$item33specs', '$item34specs', '$item35specs', '$item36specs', '$item27specs', '$item38specs', '$item39specs', '$item40specs', '$orderDate');";
      $_SESSION['order-in-progress'] = "yes";
      mysqli_query($conn, $insertPrevOrder);
      header("Location: ../profile.php?order=success");
      exit();
    }
  }
} else {
  header("Location: ../foodbin.php?order=error");
  exit();
}
