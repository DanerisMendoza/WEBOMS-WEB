<?php      
  $page = 'admin';
  include('method/checkIfAccountLoggedIn.php');
  include('method/query.php');
?>
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
          <button class="btn btn-lg btn-dark col-12 mb-3" id="back">Back</button>
          <div class="table-responsive col-lg-12">
            <?php 
              $arr = explode(',',$_GET['idAndPic']);
              $id = $arr[0];
              $query = "select a.*, b.* from WEBOMS_menu_tb a inner join WEBOMS_ordersDetail_tb b on a.orderType = b.orderType where b.order_id = '$id' ";
              $resultSet = getQuery($query); 
            ?>
            <table class="table table-striped table-bordered border-dark col-lg-12">
              <thead class="table-dark">
                <tr>	
                  <th scope="col">QUANTITY</th>
                  <th scope="col">DISH</th>
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
                <tr>
                  <td colspan="2"><b>Payment:</b></td>
                  <?php $payment = getQueryOneVal("SELECT a.payment FROM WEBOMS_order_tb a where a.order_id = '$id' ",'payment');?>
                  <td><b>₱<?php echo $payment; ?></b></td>
                </tr>
                <tr>
                  <td colspan="2"><b>Change:</b></td>
                  <td><b>₱<?php echo $payment-$total; ?></b></td>
                </tr>
              </tbody>
            </table>
          </div>
	  </div>
	</div>
</div>

</body>
</html>
<script>
  var from = "<?php echo $_SESSION['from'];?>";
  document.getElementById("back").onclick = function () {
  if(from == 'adminSalesReport')
    window.location.replace('adminSalesReport.php');
  else
    window.location.replace('adminOrders.php');
  }</script> 
