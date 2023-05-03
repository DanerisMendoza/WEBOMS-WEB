<?php 
    include('../../method/query.php');
    $arr = ['name'=>[],'feedback'=>[],'id'=>[]];
    $query = "select a.name, b.feedback, b.id from  weboms_userInfo_tb a inner join weboms_feedback_tb b on a.user_id = b.user_id";
    $resultSet = getQuery3($query); 
    if($resultSet != null){
        foreach($resultSet as $row){
            array_push($arr['name'],$row['name']);
            array_push($arr['feedback'],$row['feedback']);
            array_push($arr['id'],$row['id']);
        }
        echo json_encode($arr);
    }
    else{
        echo json_encode(null);
    }
?>