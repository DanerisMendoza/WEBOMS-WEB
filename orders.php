<?php  include('allScript.php'); ?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
    </head>
    <body>
    <div class="container text-center">

        <button class="btn btn-success col-sm-4" id="admin">Admin</button>
        <script>
            document.getElementById("admin").onclick = function () {window.location.replace('admin.php'); };    
        </script> 
        
        <div class="col-lg-12">
            <?php 
            include_once('connection.php');
            $sql = mysqli_query($conn,"select user_tb.name, orderlist_tb.* from user_tb, orderlist_tb where user_tb.userlinkId = orderlist_tb.userlinkId ORDER BY orderlist_tb.id asc; ");  
            if (mysqli_num_rows($sql)) {  
            ?>
            <table class="table table-striped" border="10">
            <tr>	
            <th scope="col">name</th>
            <th scope="col">status</th>
            </tr>
              <tbody>
                <?php while($rows = mysqli_fetch_assoc($sql)){ ?>
                <tr>	   
                <td><?php echo $rows['name']; ?></td>
                <td><?php echo ($rows['status'] == 1 ? "Approved": "Pending"); ?></td>
                <td><a href="viewOrders.php?idAndPic=<?php echo $rows['ordersLinkId'].','.$rows['proofOfPayment']?>">View Order</a></td>
                <td><a href="?status=<?php echo $rows['ordersLinkId'] ?>">Approve</a></td>
                <td><a href="method/deleteOrderMethod.php?idAndPicnameDelete=<?php echo $rows['ID'].','.$rows['proofOfPayment'].','.$rows['ordersLinkId'] ?>">Delete</a></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
            <?php } ?>
          </div>
	    </div>
    </body>
</html>
<?php 
  if(isset($_GET['status'])){
     $ordersLinkId = $_GET['status'];
     include_once('orderClass.php');
     $order = new order($ordersLinkId);
     $order-> sendReceiptToEmail(); 
     $updateQuery = "UPDATE orderList_tb SET status=true WHERE ordersLinkId='$ordersLinkId'";     
     $result = mysqli_query($conn, $updateQuery);
     if (!$result)
     echo "<script>alert('update data unsuccessfully'); window.location.replace('orders.php');</script>";  
     echo "<script>alert('Approve Success'); window.location.replace('orders.php');</script>";
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