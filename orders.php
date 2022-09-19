<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
    </head>
    <body>
    <div class="container text-center">

        <button class="btn btn-success col-sm-4" id="admin">Admin</button>
        <script>
            document.getElementById("admin").onclick = function () {window.location.replace('admin.php'); };    
        </script> 
        
        <div class="col-lg-12">
            <!-- order table -->
            <?php 
            include_once('connection.php');
            // $sql = mysqli_query($conn,"select * from orderList_tb");  
            // $sql = mysqli_query($conn,"select user_tb.name, orderList_tb.*,  order_tb.* from user_tb inner join orderlist_tb on user_tb.linkid = orderlist_tb.linkid inner join order_tb on orderlist_tb.linkid = order_tb.linkid;");  
            // $sql = mysqli_query($conn,"select user_tb.name, orderList_tb.*  from user_tb left join orderlist_tb on user_tb.linkid = orderlist_tb.id;");  
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
                <td><a href="?status=<?php echo $rows['ID'] ?>">Approve</a></td>
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
     $id = $_GET['status'];
     $updateQuery = "UPDATE orderList_tb SET status=true WHERE ID='$id'";     
     $result = mysqli_query($conn, $updateQuery);
     if (!$result)
     echo "<script>alert('update data unsuccessfully'); window.location.replace('orders.php');</script>";  
     echo "<script>alert('Approve Success'); window.location.replace('orders.php');</script>";
  }
?>
