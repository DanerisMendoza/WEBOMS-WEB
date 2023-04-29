<?php 
      include('../../method/query.php');
      $rfid = json_decode($_POST['rfid']);
      $query = "insert into weboms_usedRfid_tb(rfid) values('$rfid')";
      Query3($query);
?>