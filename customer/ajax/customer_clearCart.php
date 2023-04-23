<?php 
      include('../../method/query.php');
      $user_id = json_decode($_POST['data']);
      $query = "DELETE FROM weboms_cart_tb where user_id='$user_id' ";
      Query3($query);
?>