<?php 
      include('../../method/query.php');
      $order_id = json_decode($_POST['order_id']);
      $query = "UPDATE weboms_order_tb SET status='serving' WHERE order_id='$order_id' ";     
      Query3($query)
?>