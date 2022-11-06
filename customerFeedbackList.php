<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
    <div class="container text-center">

        <button class="btn btn-success col-sm-4" id="customer">Back</button>
        <script>020
            document.getElementById("customer").onclick = function () {window.location.replace('customerOrdersList.php'); };    
        </script> 
        
        <div class="col-lg-12">
            <table class="table table-striped" border="10">
            <tr>	
            <th scope="col">name</th>
            <th scope="col">dish</th>
            <th scope="col">feedback</th>
            </tr>
              <tbody>
                <?php
                session_start();
                include_once('orderClass.php');
                $order = new orderList();
                $orderlist =  $order -> getOrderListByCustomer($_SESSION["username"]); 
                if($orderlist != null)
                foreach($orderlist as $rows){ ?>
                <tr>	   
                <!-- <td><?php echo $rows['name']; ?></td>
                <td><?php echo $rows['dish'];?></td>
                <td><?php echo $rows['feedback'];?></td> -->
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
	    </div>
    </body>
</html>
<?php 
  if(isset($_GET['status'])){
    $arr = explode(',',$_GET['status']);  
    $ordersLinkId = $arr[0];
    $email = $arr[1];
    $order = new order($ordersLinkId,$email);
    $order-> computeOrder(); 
    $order-> sendReceiptToEmail(); 
    $order-> approveOrder();
  }
?>