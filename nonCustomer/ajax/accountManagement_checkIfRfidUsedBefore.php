<?php 
    include('../../method/query.php');
    $rfid = json_decode($_POST['rfid']);
    $query = "select rfid from weboms_usedRfid_tb where rfid = '$rfid'";
    if(getQuery3($query) != null)
        echo true;
    else
        echo false;
?>