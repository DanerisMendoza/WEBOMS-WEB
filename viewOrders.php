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
        <div class="col-lg-12 cont2">
          <button class="btn btn-success col-sm-4" id="orders">Orders</button>
          <button class="btn btn-success col-sm-4" id="admin">Admin</button>
            <?php 
            include_once('connection.php');
            $arr = explode(',',$_GET['idAndPic']);
            $id = $arr[0];
            $pic = $arr[1];
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
            <?php } ?>
          </div>
          <h1>Proof of Payment:</h1>
            <?php echo "<img src='payment/$pic' style=width:300px;height:500px>";?>
	    </div>
    </body>
</html>

<style>
    body{
    background-image: url(settings/bg.jpg);
    background-size: cover;
    background-attachment: fixed;
    background-repeat: no-repeat;
    background-position: center;
    /* background-color: #ED212D; */
    color: white;
    font-family: 'Josefin Sans',sans-serif;
  }

	.cont2{
     padding: 1%;
     margin-top: 2%;
     background: gray;
   }
</style>

<script>
  document.getElementById("admin").onclick = function () {window.location.replace('admin.php'); };
  document.getElementById("orders").onclick = function () {window.location.replace('orders.php'); };
</script> 