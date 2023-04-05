<?php 
    include('../../method/query.php');
    //username, name, email, accountType, rfid, user_id
    $user = [['username'],['name'],['email'],['accountType'],['rfid'],['user_id']];
    $selectAllUser = "select * from weboms_user_tb inner join weboms_userInfo_tb on weboms_user_tb.user_id = weboms_userInfo_tb.user_id";
    $resultSet = getQuery3($selectAllUser); 
    if($resultSet != null){
        $i = 0;
        foreach($resultSet as $row){
            $user['username'][$i] = $row['username'];
            $user['name'][$i] = $row['name'];
            $user['email'][$i] = $row['email'];
            $user['accountType'][$i] = $row['accountType'];
            $user['rfid'][$i] = $row['rfid'];
            $user['user_id'][$i] = $row['user_id'];
            $user['password'][$i] = $row['password'];
            $i++;
        }
        echo json_encode($user);
    }
    else{
        echo json_encode("null");
    }
?>

