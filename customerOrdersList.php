<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
    <div class="container text-center">

        <button class="btn btn-success col-sm-4" id="customer">Customer</button>
        <script>020
            document.getElementById("customer").onclick = function () {window.location.replace('customer.php'); };    
        </script> 
        
        <div class="col-lg-12">
            <table class="table table-striped" border="10">
            <tr>	
            <th scope="col">name</th>
            <th scope="col">status</th>
            <th scope="col">email</th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col">FeedBack</th>
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
                <td><?php echo $rows['name']; ?></td>
                <td><?php echo ($rows['status'] == 1 ? "Approved": "Pending"); ?></td>
                <td><?php echo $rows['email']; ?></td>
                <td><a href="adminOrders.php?idAndPic=<?php echo $rows['ordersLinkId'].','.$rows['proofOfPayment']?>">View Order</a></td>
                <td><?php echo $rows['date']; ?></td>
                <td><?php 
                  if($rows['status'] == 1){
                    ?>  <a href="customerFeedBack.php">feedback</a>  <?php
                  }
                  else{
                    echo "you cannot give feedback yet </br> please wait for approvation";
                  }
                ?>
                </td>
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