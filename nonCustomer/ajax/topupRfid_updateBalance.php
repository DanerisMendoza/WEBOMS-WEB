<?php 
    include('../../method/query.php');
    //update balance   
    $arr = json_decode($_POST['data']);
    $updateQuery = "UPDATE weboms_userInfo_tb SET balance = (balance + '$arr[0]') WHERE rfid= '$arr[1]' ";    
    Query3($updateQuery);
    // get attribute
    $query = "select a.*,b.* from weboms_user_tb a inner join weboms_userInfo_tb b on a.user_id = b.user_id where b.rfid = '$arr[1]' ";
    $resultSet =  getQuery3($query);
    $attributes = array();
    if($resultSet != null){
        foreach($resultSet as $row){ 
            array_push($attributes,$row['name']);
            array_push($attributes,$row['username']);
            array_push($attributes,$row['email']);
            array_push($attributes,$row['gender']);
            array_push($attributes,$row['phoneNumber']);
            array_push($attributes,$row['address']);
            array_push($attributes,$row['balance']);
            array_push($attributes,$row['picName']);
        }
    }
    echo implode(",",$attributes); 
?>