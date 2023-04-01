<?php 
      include('../../method/query.php');
      $order_id = json_decode($_POST['order_id']);
      $user_id = json_decode($_POST['user_id']);
      $totalOrder = json_decode($_POST['totalOrder']);
      
      $query = "UPDATE weboms_order_tb SET status='void' WHERE order_id='$order_id' ";     
      $query2 = "UPDATE weboms_userInfo_tb SET balance = (balance + '$totalOrder') WHERE user_id= '$user_id' ";    
      if(Query3($query)){
          if(Query3($query2)){
              echo "<SCRIPT>  window.location.replace('adminOrders.php'); alert('SUCCESS!');</SCRIPT>";
          }
      }


      $dishesArr = array();
      $dishesQuantity = array();

      $query = "select a.*, b.* from weboms_menu_tb a inner join weboms_ordersDetail_tb b on a.orderType = b.orderType where b.order_id = '$order_id' ";
      $resultSet = getQuery3($query); 

      foreach($resultSet as $row){
          array_push($dishesArr,$row['dish']);
          array_push($dishesQuantity,$row['quantity']);
      }
          
      for($i=0; $i<count($dishesArr); $i++){ 
          $updateQuery = "UPDATE weboms_menu_tb SET stock = (stock + '$dishesQuantity[$i]') WHERE dish= '$dishesArr[$i]' ";    
          Query3($updateQuery);    
      }
?>