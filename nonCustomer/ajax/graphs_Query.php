<?php 
    include('../../method/query.php');
    $dishesArr = array();
    $qantityArr = array();
    $data = [['countOfSold'],['stockLeft'],['preparing'],['serving']];
    $countOfSold = $stockLeft = $preparing = $serving= 0;
    //getting count of sold
    $countOfSoldQuery = "select dish,quantity from weboms_ordersDetail_tb a inner join weboms_menu_tb b on a.orderType = b.orderType inner join weboms_order_tb c on a.order_id = c.order_id where c.status = 'complete'";
    $resultSet = getQuery3($countOfSoldQuery); 
    if($resultSet != null){
        foreach($resultSet as $row){
            //get sold stock
            $countOfSold += $row['quantity'];
            //merge dish quantity into 1 
            if(in_array($row['dish'], $dishesArr)){
                $index = array_search($row['dish'], $dishesArr);
                $newQuantity = $qantityArr[$index] + $row['quantity'];
                $qantityArr[$index] = $newQuantity;
            }
            else{
                array_push($dishesArr,$row['dish']);
                array_push($qantityArr,$row['quantity']);
            }
        }
        $data['countOfSold'] = $countOfSold;
    }
    // getting count of stock left
    $countOfStockLeftQuery = "select * from weboms_menu_tb;";
    $resultSet = getQuery3($countOfStockLeftQuery); 
    if($resultSet != null){
        foreach($resultSet as $row){
            $stockLeft += $row['stock'];
        }
        $data['stockLeft'] = $stockLeft;
    }
    // getting count of preparing
    $countOfPreparingQuery = "select dish,quantity,status from weboms_ordersDetail_tb a inner join weboms_menu_tb b on a.orderType = b.orderType inner join weboms_order_tb c on a.order_id = c.order_id and status = 'preparing'";
    $resultSet = getQuery3($countOfPreparingQuery); 
    if($resultSet != null){
        foreach($resultSet as $row){
            $preparing += $row['quantity'];
        }
        $data['preparing'] = $preparing;
    }
    // getting count of preparing
    $countOfServingQuery = "select dish,quantity,status from weboms_ordersDetail_tb a inner join weboms_menu_tb b on a.orderType = b.orderType inner join weboms_order_tb c on a.order_id = c.order_id and status = 'serving'";
    $resultSet = getQuery3($countOfServingQuery); 
    if($resultSet != null){
        foreach($resultSet as $row){
            $serving += $row['quantity'];
        }
        $data['serving'] = $serving;
    }
    echo json_encode($data);
?>

