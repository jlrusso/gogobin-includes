<?php
  session_start();
  include_once "dbh-inc.php";
  $userFirst = $_SESSION['user_first'];
  $userLast = $_SESSION['user_last'];
  $userFull = ucfirst($userFirst) . " " . ucfirst($userLast[0]) . ".";
  $id = $_SESSION['user_id'];
  $getMyDeliveries = "SELECT * FROM current_orders WHERE delivery_by=? AND order_status='2';";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt, $getMyDeliveries)){
    header("Location: https://www.gogobin.com?stmt-error");
    exit();
  } else {
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
  }
  $resultNum = mysqli_num_rows($result);
  $rowArr = mysqli_fetch_assoc($result);
  if($resultNum > 0){
    echo "
      <div class='store-grid'>
        <span>Ordered by: " . getOrdererName($rowArr, $conn) . "</span>
        <span>" . $rowArr['store_name'] ."</span>
        <span>" . $rowArr['store_address'] ."</span>
        <span>Deliver by: " . $rowArr['delivery_time'] ."</span>
      </div>
    ";

    $foodIdsStr = $rowArr['food_ids'];
    $foodIdsArr = explode(" ", $foodIdsStr);
    array_pop($foodIdsArr);
    $foodIdsArrLength = count($foodIdsArr);
    echo "
      <div class='delivery-order-outer'>
        <div class='delivery-order-inner'>
    ";
    for($x = 0; $x < $foodIdsArrLength; $x++){
      $specStr = $rowArr['item_' . $foodIdsArr[$x] . '_specs'];
      $specsArr = explode(" | ", $specStr);
      echo "
        <div class='delivery-item'>
          <img src='../img/image". $foodIdsArr[$x] . ".jpg'/>
          <div class='item-details'>
            " . $specsArr[0] . "<br/>" . $specsArr[1] . "<br/>" . $specsArr[2] . "<br/>" . $specsArr[3] . "
          </div>
        </div>
      ";
    }
    echo "</div>"; //end of delivery order inner
    echo "</div>"; //end of delivery order outer
    echo "
      <div class='delivery-btns-container'>
        <button class='delivery-cancel-btn'>Cancel</button>
        <button class='delivery-completed-btn'>Completed</button>
      </div>
      <form class='cancel-delivery-form' method='POST' action='includes/cancelDelivery.php' hidden>
        <input type='text' name='from' value='home' class='hidden'/>
        <input type='text' name='user' value='" . $userFull ."'/>
        <input type='number' name='other-id' value='" . $rowArr['user_id'] . "'/>
        <input type='submit' name='submit' class='delivery-cancel-submit'/>
      </form>
      <form class='delivery-completed-form' method='POST' action='includes/isDeliveryCompleted.php' hidden>
        <input type='text' name='from' value='home' class='hidden'/>
        <input type='text' name='user' value='" . $userFull ."'/>
        <input type='number' name='other-id' value='" . $rowArr['user_id'] . "'/>
        <input type='submit' name='submit' class='offer-completed-submit'/>
      </form>
      <form class='delivery-msg-form'>
        <input type='number' name='other-id' value='" . $rowArr['user_id'] . "' class='hidden'/>
        <input type='text' name='message' placeholder='Message' autocomplete='off'/>
        <button type='button' name='submit' class='delivery-msg-submit'>
          <i class='delivery fa fa-send-o' style='font-size: 20px'></i>
        </button>
      </form>
    ";
  } else {
    echo "<p>You have no current deliveries.</p>";
  }

  function getOrdererName($rowArr, $conn){
    $person1 = $rowArr['user_id'];
    $getName = "SELECT * FROM users WHERE id=?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $getName)){
      header("Location: https://www.gogobin.com?stmt-error");
      exit();
    } else {
      mysqli_stmt_bind_param($stmt, "s", $person1);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $rowArr = mysqli_fetch_assoc($result);
      $firstName = $rowArr['user_first'];
      $lastInitial = $rowArr['user_last'][0] . ".";
      $fullName = ucfirst($firstName) . " " . ucfirst($lastInitial);
      return $fullName;
    }
  }
?>
