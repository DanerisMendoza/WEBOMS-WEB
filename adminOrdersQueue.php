<?php 
session_start();
$isSame = false;
include_once('orderClass.php');
$orderlist = new orderList();
$resultSet =  $orderlist -> getPrepairingOrder();
$count = 0;
//   if(isset($_SESSION['Queue'])){
//   foreach($resultSet as $str){
//     $count++;
//   }
//   if(count($_SESSION['Queue']) == $count){
//     $isSame = true;
//   }else{
//     $isSame = false;
//   }
//   }
// $Queue = 1;
// if(!isset($_SESSION['Queue'])){
//   $_SESSION['Queue'] = array();
//   foreach($resultSet as $str){
//     array_push($_SESSION['Queue'],$Queue);
//     $Queue++;
//   }
// }else if($isSame == false){
//   $last = count($_SESSION['Queue']);
//   $last-1;
//   $start = $_SESSION['Queue'][$last];
//   foreach($resultSet as $str){
//     array_push($_SESSION['Queue'],$start);
//     $start++;
//   }
// }
// $a = implode($_SESSION['Queue']);
// echo "<script>alert('$a');</script>";

?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
    <div class="container text-center">

        <button class="btn btn-success col-sm-4" id="admin">Admin</button>
        <script>
            document.getElementById("admin").onclick = function () {window.location.replace('admin.php'); };    
        </script> 
        
        <div class="col-lg-12">
            <table class="table table-striped" border="10">
            <tr>	
            <th scope="col">Orders ID</th>
            <th scope="col">Queue Number</th>
            </tr>
              <tbody>
                <?php
                $i = 1;
                if($resultSet != null)
                foreach($resultSet as $rows){ ?>
                <tr>	   
                <td><?php echo $rows['ordersLinkId']; ?></td>
                <td><?php echo $i;?></td>
                </tr>
                <?php $i++;} ?>
              </tbody>
            </table>
          </div>
	    </div>
    </body>
</html>