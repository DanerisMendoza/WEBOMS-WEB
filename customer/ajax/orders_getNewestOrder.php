<?php 
    include('../../method/query.php');
    $user_id = json_decode($_POST['user_id']);
    $query = "select MAX(b.id) AS max_id from weboms_userInfo_tb a inner join weboms_order_tb b on a.user_id = b.user_id where a.user_id = '$user_id' order by b.id desc;";
    $resultSet = getQuery3($query); 
    if($resultSet != null){
        foreach($resultSet as $row){
            echo json_encode($row['max_id']);
        }
    }
    else{
        echo json_encode("null");
    }

?>

