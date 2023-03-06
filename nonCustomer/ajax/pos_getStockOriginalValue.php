<?php 
    session_start();
    include('../../method/query.php');
    // user_id, orderType, qty
    $dish = $_POST['data'];
    $query = "select stock from weboms_menu_tb where dish='$dish' ";
    $result = getQueryOneVal3($query,'stock'); 
    echo $result;
?>