<?php 
  include('method/checkIfAccountLoggedIn.php');
  include('method/Query.php');

?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin OQ</title>
    
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
  <link rel="stylesheet" type="text/css" href="css/style.css">
    
</head>
<body class="bg-light">

<div class="container text-center">
  <div class="row justify-content-center">
    <h1 class="font-weight-normal mt-5 mb-4 text-center">Orders Queue</h1>
    <button class="btn btn-lg btn-danger col-12 mb-3" id="admin">Admin</button>
        <script> document.getElementById("admin").onclick = function () {window.location.replace('admin.php'); };    </script> 
      
        <!-- serving table -->
      <?php   $getPrepairingOrder = "select customer_tb.name, order_tb.* from customer_tb inner join order_tb on customer_tb.userlinkId = order_tb.userlinkId  and status = 'serving' ORDER BY order_tb.id asc; ";
              $resultSet = getQuery($getPrepairingOrder);?>
      <div class="table-responsive col-lg-12">
        <table class="table table-striped table-bordered col-lg-12">
          <thead class="table-dark">
            <tr><h4 style="background: green; color: white;">Serving</h4>	
              <th scope="col">ORDERS ID</th>
            </tr>
          </thead>
          <tbody>
                <?php
                if($resultSet != null)
                foreach($resultSet as $rows){ ?>
                <tr>	   
                <td><strong style="font-size: 36px;"><?php echo $rows['ordersLinkId']; ?></strong></td>
                </tr>
                <?php } ?>
          </tbody>
        </table>
        <!-- prepairing table -->
      <?php   $getPrepairingOrder = "select customer_tb.name, order_tb.* from customer_tb inner join order_tb on customer_tb.userlinkId = order_tb.userlinkId  and status = 'prepairing' ORDER BY order_tb.id asc; ";
              $resultSet = getQuery($getPrepairingOrder);?>
      <div class="table-responsive col-lg-12">
        <table class="table table-striped table-bordered col-lg-12">
          <thead class="table-dark">
            <tr><h4 style="background: blue; color: white;">Prepairing</h4>	
              <th scope="col">ORDERS ID</th>
            </tr>
          </thead>
          <tbody>
                <?php
                if($resultSet != null)
                foreach($resultSet as $rows){ ?>
                <tr>	   
                <td><strong style="font-size: 36px;"><?php echo $rows['ordersLinkId']; ?></strong></td>
                </tr>
                <?php } ?>
          </tbody> 
        </table>
      </div>
	</div>
</div>

</body>
</html>