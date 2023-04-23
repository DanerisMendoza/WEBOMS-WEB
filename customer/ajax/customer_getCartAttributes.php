<?php 
    include('../../method/query.php');
    $user_id = json_decode($_POST['user_id']);
    $dishesArr = $priceArr = $quantityArr = $orderTypeArr = array();
    $query = "select a.*, b.* from weboms_menu_tb a inner join weboms_cart_tb b on a.orderType = b.orderType where b.user_id = '$user_id' ";
    $resultSet = getQuery3($query); 
    if($resultSet != null){
        foreach($resultSet as $row){
            array_push($dishesArr,$row['dish']);
            array_push($priceArr,$row['price']);
            array_push($quantityArr,$row['qty']);
            array_push($orderTypeArr,$row['orderType']);
        }
        $multiArr = array($dishesArr, $priceArr, $quantityArr, $orderTypeArr);
        echo json_encode($multiArr);
    }
    else{
        echo json_encode("null");
    }

?>

