<?php 
      include('../../method/query.php');
      $arr = json_decode($_POST['data']);
      $updateQuery = "UPDATE weboms_menu_tb SET stock = (stock - $arr[1]) WHERE dish= '$arr[0]' ";    
      Query3($updateQuery);
?>