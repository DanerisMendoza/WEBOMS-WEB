<?php 
      include('../../method/query.php');
      $arr = json_decode($_POST['userIdAndTotal']);
      $updateQuery = "UPDATE weboms_userInfo_tb SET balance = (balance - '$arr[2]') where user_id = '$arr[1]' ";
      Query3($updateQuery);
      print_r($arr);
?>