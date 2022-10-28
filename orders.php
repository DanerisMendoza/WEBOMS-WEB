<?php  include('allScript.php'); ?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
    </head>
    <body>
    <div class="container text-center">

        <button class="btn btn-success col-sm-4" id="admin">Admin</button>
        <script>020
            document.getElementById("admin").onclick = function () {window.location.replace('admin.php'); };    
        </script> 
        
        <div class="col-lg-12">
            <table class="table table-striped" border="10">
            <tr>	
            <th scope="col">name</th>
            <th scope="col">status</th>
            <th scope="col">email</th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col">date</th>
            </tr>
              <tbody>
                <?php
                include_once('orderClass.php');
                $order = new orderList();
                $orderlist =  $order -> getOrderList(); 
                foreach($orderlist as $rows){ ?>
                <tr>	   
                <td><?php echo $rows['name']; ?></td>
                <td><?php echo ($rows['status'] == 1 ? "Approved": "Pending"); ?></td>
                <td><?php echo $rows['email']; ?></td>
                <td><a href="viewOrders.php?idAndPic=<?php echo $rows['ordersLinkId'].','.$rows['proofOfPayment']?>">View Order</a></td>
                <td><a href="?status=<?php echo $rows['ordersLinkId'].','.$rows['email']; ?>">Approve</a></td>
                <td><a href="method/deleteOrderMethod.php?idAndPicnameDelete=<?php echo $rows['ID'].','.$rows['proofOfPayment'].','.$rows['ordersLinkId'] ?>">Delete</a></td>
                <td><?php echo $rows['date']; ?></td>
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
<style>
    body{
    background-image: url(settings/bg.jpg);
    background-size: cover;
    background-attachment: fixed;
    background-repeat: no-repeat;
    background-position: center;
    color: white;
    font-family: 'Josefin Sans',sans-serif;
  }

	.container{
     padding: 1%;
     margin-top: 2%;
     background: gray;
   }
</style>