<?php 
    include('../../method/query.php');
    $status = json_decode($_POST['status']);
    if($status != 'all')
        $query = "select b.ID from weboms_userInfo_tb a right join weboms_order_tb b on a.user_id = b.user_id where b.status = '$status'  order by b.id asc limit 1" ;
    else
        $query = "select b.ID from weboms_userInfo_tb a right join weboms_order_tb b on a.user_id = b.user_id order by b.id desc limit 1" ;
    $resultSet = getQuery3($query); 
    if($resultSet != null){
        foreach($resultSet as $row){
            echo json_encode($row['ID']);
        }
    }
    else{
        echo json_encode("null");
    }

?>

