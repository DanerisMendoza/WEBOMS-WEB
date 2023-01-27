<?php 
    include('../../method/query.php');
    //[dishes][prices][quantity][order type]
    $arr = json_decode($_POST['data']);
    for($i=0; $i<sizeof($arr[0]); $i++){
        $qty = $arr[2][$i];
        $dish = $arr[0][$i];
        $updateQuery = "UPDATE weboms_menu_tb SET stock = (stock + $qty) WHERE dish='$dish' ";    
        Query3($updateQuery);
    }
    print_r($arr[0][0]);
?>