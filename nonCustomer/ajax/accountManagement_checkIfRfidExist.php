<?php 
    include('../../method/query.php');
    $arr = json_decode($_POST['arr']);
    $query = "select rfid from weboms_userInfo_tb where rfid = '$arr[0]'";
    if(getQuery3($query) != null)
        echo true;
    else
        echo false;
?>