<?php 
    session_start();
    include('../../method/query.php');
    // [dishes][prices][quantity][order type]
    $multiArr = json_decode($_POST['multiArrCart']);
    // staff, customer, cash, total
    $otherAttributes = json_decode($_POST['otherAttributes']);
    date_default_timezone_set('Asia/Manila');
    $date = new DateTime();
    $today =  $date->format('Y-m-d'); 
    $_SESSION['date'] = $todayWithTime =  $date->format('Y-m-d H:i:s'); 
    $_SESSION['name'] = $staff = $otherAttributes[0];
    $customerName = $otherAttributes[1];
    $_SESSION['cash'] = $cash = $otherAttributes[2];
    $_SESSION['total'] = $total = $otherAttributes[3]; 
    $_SESSION['dishesArr'] = $multiArr[0];
    $_SESSION['priceArr'] = $multiArr[1];
    $_SESSION['dishesQuantity'] = $multiArr[2];
    $_SESSION['customerName'] = $otherAttributes[1];
    $_SESSION['staffInCharge'] = 'pos';

    //or number process
    $or_last = getQueryOneVal3("select or_number from weboms_order_tb WHERE id = (SELECT MAX(ID) from weboms_order_tb)","or_number");
    if($or_last == null){
        $or_last = 1;
    }
    else{
        $or_last = $or_last + 1;
    }
    $inputSize = strlen(strval($or_last));
    if($inputSize > 4)
        $str_length = $inputSize;
    else
        $str_length = 4;
    $temp = substr("0000{$or_last}", -$str_length);
    $_SESSION['or_number'] = $or_number = $temp;

    //get two user id from different table
    $lastUserIdOrder = getQueryOneVal3("SELECT MAX(user_id) from weboms_order_tb","MAX(user_id)");
    $lastUserIdUserInfo = getQueryOneVal3("SELECT MAX(user_id) from weboms_userInfo_tb","MAX(user_id)");
    //compare which user id is higher 
    if($lastUserIdOrder > $lastUserIdUserInfo)
        $user_id = $lastUserIdOrder;
    else
        $user_id = $lastUserIdUserInfo;   
    // increment user id
    $user_id++;

    //increment order id
    $lastOrderId = getQueryOneVal3("select order_id from weboms_order_tb WHERE order_id = (SELECT MAX(order_id) from weboms_order_tb)","order_id");
    if($lastOrderId == null){
        $lastOrderId = rand(1111,9999);
    }
    else{
        $lastOrderId = $lastOrderId + 1;
    }
    $_SESSION['order_id']  = $order_id = $lastOrderId;

    $query1 = "insert into weboms_order_tb(user_id, order_id, or_number, status, date, totalOrder, payment,  staffInCharge) values('$user_id', '$order_id', '$or_number', 'preparing', '$todayWithTime','$total','$cash', '$staff')";
    for($i=0; $i<count($multiArr[0]); $i++){
        $qty = $multiArr[2][$i];
        $ordType = $multiArr[3][$i];
        $query2 = "insert into weboms_ordersDetail_tb(order_id, quantity, orderType) values('$order_id',$qty,$ordType)";
        Query3($query2);
    }
    $query3 = "insert into weboms_userInfo_tb(name,user_id) values('$customerName','$user_id')";
    if($customerName != '')
        Query3($query3);
    Query3($query1);

?>