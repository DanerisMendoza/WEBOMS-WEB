<?php 
    session_start();
    include('../../method/query.php');
    $multiArrCart = json_decode($_POST['multiArrCart']);
    $otherAttributes = json_decode($_POST['otherAttributes']);
     // init sessions
     $staff = $_SESSION['name'];
     $_SESSION['or_number'] = $or_number;
     $_SESSION['customerName'] = $customerName;
     $_SESSION['staffInCharge'] = 'POS';
     $_SESSION['date'] = $todayWithTime;
     $_SESSION['cash'] = $cash;
     $_SESSION['total'] = $total;
     $_SESSION['dishesArr'] = $dishesArr;
     $_SESSION['priceArr'] = $priceArr;
     $_SESSION['dishesQuantity'] = $dishesQuantity;
     $_SESSION['order_id'] = $order_id;
?>