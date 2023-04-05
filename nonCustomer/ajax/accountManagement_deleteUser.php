<?php 
    include('../../method/query.php');
    $user_id = json_decode($_POST['user_id']);
    $query = "DELETE FROM weboms_user_tb WHERE user_id='$user_id' ";
    $query2 = "DELETE FROM weboms_userInfo_tb WHERE user_id='$user_id' ";
    Query3($query);
    Query3($query2);
?>