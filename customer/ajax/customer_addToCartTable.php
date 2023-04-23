<?php 
    session_start();
    include('../../method/query.php');
    // user_id, orderType, qty
    $arr = json_decode($_POST['data']);
    $query = "select a.orderType from weboms_menu_tb a inner join weboms_cart_tb b on a.orderType = b.orderType where b.user_id = '$arr[0]' and a.orderType = '$arr[1]' ";
    $resultSet = getQuery3($query); 
    if($resultSet != null){
        $updateQuery = "UPDATE weboms_cart_tb SET qty = (qty + $arr[2]) WHERE orderType = '$arr[1]' and user_id= '$arr[0]'";    
        Query3($updateQuery);
    }
    else{
        $query3 = "insert into weboms_cart_tb(user_id, orderType, qty) values('$arr[0]','$arr[1]', $arr[2])";
        Query3($query3);
    }
?>