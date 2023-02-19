<?php 
    session_start();
    include('../../method/query.php');
    // user_id, orderType, qty
    $arr = json_decode($_POST['data']);
    $updateQuery = "UPDATE weboms_cart_tb SET qty = $arr[2] WHERE orderType = '$arr[1]' and user_id= '$arr[0]'";    
    Query3($updateQuery);
?>