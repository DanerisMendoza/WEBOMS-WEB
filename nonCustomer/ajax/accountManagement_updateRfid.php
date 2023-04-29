<?php 
      include('../../method/query.php');
      $arr = json_decode($_POST['arr']);
      $updateQuery = "UPDATE weboms_userInfo_tb SET rfid = '$arr[0]' WHERE user_id= '$arr[1]' ";    
      Query3($updateQuery);
?>