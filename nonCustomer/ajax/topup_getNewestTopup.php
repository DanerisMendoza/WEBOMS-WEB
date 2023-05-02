<?php 
    include('../../method/query.php');
    $query = $query = "SELECT MAX(a.id) AS max_id FROM `weboms_topUp_tb` a INNER JOIN weboms_userInfo_tb b ON a.user_id = b.user_id";
    $resultSet = getQuery3($query); 
    if($resultSet != null){
        foreach($resultSet as $row){
            echo ($row['max_id']);
        }
    }
    else{
        echo json_encode(null);
    }
?>

