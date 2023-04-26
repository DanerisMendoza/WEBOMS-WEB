<?php 
    include('../../method/query.php');
    $orders = [];
    $getServingOrder = "select order_id from weboms_userInfo_tb a right join weboms_order_tb b on a.user_id = b.user_id WHERE b.status = 'serving'and b.staffInCharge = 'online order' ORDER BY b.id asc; ";
    $resultSet = getQuery3($getServingOrder); 
    if($resultSet != null){
        foreach($resultSet as $row){
            array_push($orders,$row['order_id']);
        }
        echo json_encode($orders);
    }
    else{
        echo json_encode(null);
    }
?>