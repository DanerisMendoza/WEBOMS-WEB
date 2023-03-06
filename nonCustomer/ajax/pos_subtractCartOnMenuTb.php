<?php 
      include('../../method/query.php');
      // dish,price,qty,orderType
      $arr = json_decode($_POST['multiArrCart']);
      for($i=0; $i<sizeof($arr[0]); $i++){
        $dish = $arr[0][$i];
        $qty = $arr[2][$i];
        $updateQuery = "UPDATE weboms_menu_tb SET stock = (stock - $qty) WHERE dish= '$dish' ";    
        Query3($updateQuery);
      }
?>