<?php 
include('../method/query.php');
session_start();
$dishesArr = array();
$dishesQuantity = array();

for($i=0; $i<count($_SESSION['dishes']); $i++){
    if(in_array( $_SESSION['dishes'][$i],$dishesArr)){
        $index = array_search($_SESSION['dishes'][$i], $dishesArr);
    }
    else{
        array_push($dishesArr,$_SESSION['dishes'][$i]);
    }
}

foreach(array_count_values($_SESSION['dishes']) as $count){
    array_push($dishesQuantity,$count);
}

for($i=0; $i<count($dishesArr); $i++){ 
    $updateQuery = "UPDATE weboms_menu_tb SET stock = (stock + '$dishesQuantity[$i]') WHERE dish= '$dishesArr[$i]' ";    
    Query($updateQuery);    
}

session_destroy();
?>