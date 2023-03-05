<?php 
    include('../../method/query.php');
    $dishesArr = $priceArr = $stockArr = $orderTypeArr = $aArray = array();
    $query = "SELECT * FROM `weboms_menu_tb`";
    $resultSet = getQuery3($query); 
    if($resultSet != null){
        foreach($resultSet as $row){
            array_push($dishesArr,$row['dish']);
            array_push($priceArr,$row['price']);
            array_push($stockArr,$row['stock']);
            array_push($orderTypeArr,$row['orderType']);
            $a = $row['dish'].",".$row['price'].",".$row['orderType'].",".$row['stock'];
            array_push($aArray,$a);
        }
        $multiArr = array($dishesArr, $priceArr, $stockArr, $orderTypeArr, $aArray);
        echo json_encode($multiArr);
    }
    else{
        echo json_encode("null");
    }

?>

