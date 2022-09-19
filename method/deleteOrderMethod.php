<?php
    include_once('../connection.php');
    $idAndPicArr = explode(',',$_GET['idAndPicnameDelete']);
    $id = $idAndPicArr[0];
    $Pic = $idAndPicArr[1];
    $linkId = $idAndPicArr[2];
    $query1 = "DELETE FROM orderList_tb WHERE id='$id'";
    $query2 = "DELETE FROM order_tb WHERE ordersLinkId='$linkId'";
    $result = mysqli_query($conn, $query1) && mysqli_query($conn, $query2) ;
    if (!$result)
    echo "<script>alert('Delete data unsuccessfully'); window.location.replace('../orders.php');</script>";  
    
    unlink("../payment/".$Pic);
    echo "<script> window.location.replace('../orders.php'); alert('Delete data successfully'); </script>";  
?>