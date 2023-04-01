<?php 
    include('../../method/query.php');
    $user_id = json_decode($_POST['user_id']);
    $query = "select a.*,b.* from weboms_user_tb a inner join weboms_userInfo_tb b on a.user_id = b.user_id where a.user_id = '$user_id' ";
    $resultSet =  getQuery3($query);
    // id, name, picName, username, phone number, address, balance, email, gender
    $arr = array();
    if($resultSet!= null)
    foreach($resultSet as $row){ 
        // init
        array_push($arr,$row['id']);
        array_push($arr,$row['name']);
        array_push($arr,$row['picName']);
        array_push($arr,$row['username']);
        $g = $row['gender'];
        //gender process
        if($g == 'm'){
            array_push($arr,'male');
        }
        elseif($g == 'f'){
            array_push($arr,'female');
        }else{
            array_push($arr,'NA');
        }
        array_push($arr,$row['phoneNumber']);
        array_push($arr,$row['address']);
        array_push($arr,$row['balance']);
        array_push($arr,$row['email']);
    }
    echo json_encode($arr);
?>