<?php
    session_start();
    include('../../method/query.php');
    $mode = json_decode($_POST['mode']);
    $dateArr = json_decode($_POST['dateArr']);
    $sales = [['name'],['order_id'],['date'],['totalOrder'],['staffInCharge']];
    if($mode == 'showAll'){
        $query = "select a.name, b.* from weboms_userInfo_tb a right join weboms_order_tb b on a.user_id = b.user_id where b.status = 'complete' ORDER BY b.id asc;";
        unset($_SESSION['date1']);
        unset($_SESSION['date2']);
    }
    else if($mode == 'showByTwoDate'){
        $_SESSION['date1'] = date('m/d/Y h:i a ', strtotime($dateArr[0]));
        $_SESSION['date2'] = date('m/d/Y h:i a ', strtotime($dateArr[1]));
        $query = "select a.name, b.* from weboms_userInfo_tb a right join weboms_order_tb b on a.user_id = b.user_id where b.status = 'complete' and b.date between '$dateArr[0]' and '$dateArr[1]' ORDER BY b.id asc; ";
    }
    //insert to array
    $_SESSION['query'] = $query;
    $resultSet = getQuery3($query); 
    if($resultSet != null){
        $i = 0;
        foreach($resultSet as $row){
            $sales['name'][$i] = $row['name'];
            $sales['order_id'][$i] = $row['order_id'];
            $sales['date'][$i] = date('m/d/Y h:i a ', strtotime($row['date']));
            $sales['totalOrder'][$i] = $row['totalOrder'];
            $sales['staffInCharge'][$i] = $row['staffInCharge'];
            $i++;
        }
        echo json_encode($sales);
    }
    else{
        echo json_encode("null");
    }
?>

