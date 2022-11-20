<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Admin Orders - View Orders</title>

  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 

</head>
<body class="bg-light">

<div class="container text-center mt-5">
  <div class="row justify-content-center">
    <div class="col-lg-12 cont2">
          <?php 
          $page = 'admin';
          include('method/checkIfAccountLoggedIn.php');
          if($_SESSION['from'] == 'orders'){ ?>
          <button class="btn btn-lg btn-dark col-12 mb-3" id="orderList">Order List</button>
          <?php }else{?>
          <button class="btn btn-lg btn-success col-12 mb-4" id="salesReport">Sales Report</button>
          <?php } ?>

          <div class="table-responsive col-lg-12">
            <?php 
              $arr = explode(',',$_GET['idAndPic']);
              $id = $arr[0];
              $pic = $arr[1];
              include('method/Query.php');
              $query = "select menu_tb.*, ordersDetail_tb.* from menu_tb inner join ordersDetail_tb where menu_tb.orderType = ordersDetail_tb.orderType and ordersDetail_tb.ordersLinkId = '$id' ";
              $resultSet = getQuery($query); 
            ?>
            <table class="table table-striped table-bordered border-dark col-lg-12">
              <thead class="table-dark">
                <tr>	
                  <th scope="col">QUANTITY</th>
                  <th scope="col">NAME</th>
                  <th scope="col">PRICE</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $total = 0;
                if($resultSet != null)
                foreach($resultSet as $rows){ ?>
                <tr>	   
                  <?php $price = ($rows['price']*$rows['quantity']);  $total += $price;?>
                  <td><?php echo $rows['quantity']; ?></td>
                  <td><?php echo $rows['dish']; ?></td>
                  <td><?php echo '₱'.$price?></td>
                </tr>
                <?php }?>
                <tr>
                  <td colspan="2"><b>TOTAL AMOUNT:</b></td>
                  <td><b>₱<?php echo $total?></b></td>
                </tr>
              </tbody>
            </table>
          </div>
          <?php if($pic!='null'){ ?>
          <div class="mb-5">
            <h1 class="font-weight-normal">PROOF OF PAYMENT:</h1>
            <?php  echo "<img src='payment/$pic' style=width:300px;height:500px>";?>
          </div>
          <?php }?>

	  </div>
	</div>
</div>

</body>
</html>

<script>document.getElementById("salesReport").onclick = function () {window.location.replace('adminSalesReport.php'); }</script> 
<script>document.getElementById("orderList").onclick = function () {window.location.replace('adminOrdersList.php'); }</script> 
