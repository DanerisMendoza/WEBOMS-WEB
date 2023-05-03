<?php 
    include('../../method/query.php');
    $query = "CHECKSUM TABLE weboms_order_tb;";
    $resultSet = getQuery3($query); 
    if($resultSet != null){
        foreach($resultSet as $row){
            echo json_encode($row['Checksum']);
        }
    }
    else{
        echo json_encode("null");
    }

?>

