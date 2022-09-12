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
      <button class="btn btn-success col-sm-4" id="orders">Orders</button>
        <button class="btn btn-success col-sm-4" id="admin">Admin</button>
        <script>
          document.getElementById("admin").onclick = function () {window.location.replace('admin.php'); };
          document.getElementById("orders").onclick = function () {window.location.replace('orders.php'); };
        </script> 
        <div class="col-lg-12">
            <?php 
            include_once('connection.php');
            $arr = explode(',',$_GET['idAndPic']);
            $id = $arr[0];
            $pic = $arr[1];
            // $total = $arr[2];
            // $sql = mysqli_query($conn,"select user_tb.name, orderList_tb.*  from user_tb inner join orderlist_tb on user_tb.linkid = orderlist_tb.linkid"); 
            // $sql = mysqli_query($conn,"select * from order_tb ");  
            // $sql = mysqli_query($conn,"select dishes_tb.cost, order_tb.* from dishes_tb, order_tb where ordersLinkId = '$id'");  
            // $sql = mysqli_query($conn,"select * from order_tb where ordersLinkId = '$id'");  
            // $sql = mysqli_query($conn,"select dishes_tb.*, order_tb.* from dishes_tb, order_tb where dishes_tb.orderType = order_tb.orderType and order_tb.ordersLinkId = '$id' ");  
            $sql = mysqli_query($conn,"select dishes_tb.*, order_tb.* from dishes_tb inner join order_tb where dishes_tb.orderType = order_tb.orderType and order_tb.ordersLinkId = '$id' ");  
            if (mysqli_num_rows($sql)) {  
            ?>
            <table class="table table-striped" border="10">
            <tr>	
            <!-- <th scope="col">price</th> -->
            <th scope="col">quantity</th>
            <th scope="col">name</th>
            <th scope="col">price</th>
            </tr>
              <tbody>
                <?php 
                $total = 0;
                while($rows = mysqli_fetch_assoc($sql)){ ?>
                <tr>	   
                <?php $price = ($rows['price']*$rows['quantity']);  $total += $price;?>
                <td><?php echo $rows['quantity']; ?></td>
                <td><?php echo $rows['dish']; ?></td>
                <td><?php echo $price?></td>
                </tr>
                <?php }?>
                <tr>
                  <td colspan="2">Total Amount:</td>
                  <td><?php echo $total?></td>
                </tr>
              </tbody>
            </table>
            <h1>Proof of Payment:</h1>
            <?php echo "<img src='payment/$pic' style=width:300px;height:500px>";?>
            <?php } ?>
          </div>
	    </div>
    </body>
</html>
