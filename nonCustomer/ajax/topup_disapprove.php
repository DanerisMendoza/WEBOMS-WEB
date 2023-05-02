<?php 
    include('../../method/query.php');
    $id = json_decode($_POST['paymentId']);
    $query = "UPDATE weboms_topUp_tb SET status='disapproved' WHERE id = '$id' "; 
    Query3($query);
?>