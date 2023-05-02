<?php 
    include('../../method/query.php');
    $id = json_decode($_POST['paymentId']);
    $user_id = json_decode($_POST['userId']);
    $amount = json_decode($_POST['amount']);
    $query = "UPDATE weboms_topUp_tb SET status='approved' WHERE id='$id' ";     
    if(Query3($query)){
        $query = "UPDATE weboms_userInfo_tb SET balance = (balance + '$amount') where user_id = '$user_id' ";     
        Query3($query);
    }
?>