<?php 
      include('../../method/query.php');
      $arr = json_decode($_POST['data']);
      $query = "DELETE FROM weboms_cart_tb WHERE orderType='$arr[1]' and user_id='$arr[0]' ";
      Query3($query);
?>