<?php 
    session_start();
    include('../../method/query.php');
    // user_id, orderType, qty
    $arr = json_decode($_POST['data']);
    $updateQuery = "UPDATE weboms_cart_tb SET qty = (qty - $arr[2]) WHERE orderType = '$arr[1]' and user_id= '$arr[0]'";    
    Query3($updateQuery);
    $query = "select qty from weboms_cart_tb where user_id='$arr[0]' and orderType = '$arr[1]' ";
    $result = getQueryOneVal3($query,'qty'); 
    if($result == 0){
        $query = "DELETE FROM weboms_cart_tb WHERE orderType='$arr[1]' and user_id='$arr[0]' ";
        Query3($query);
    }
?>