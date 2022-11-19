<?php 
  $page = 'admin';
  include('method/checkIfAccountLoggedIn.php');
  include('method/Query.php');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Admin OQ</title>
    
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
  <link rel="stylesheet" type="text/css" href="css/style.css">
    
</head>
<body class="bg-light">

<div class="container text-center mt-5">
  <div class="row justify-content-center">
    <!-- <h1 class="font-weight-normal mt-5 mb-4 text-center">Orders Queue</h1> -->
    <button class="btn btn-lg btn-dark col-12 mb-4" id="admin">Admin</button>
    <script> document.getElementById("admin").onclick = function () {window.location.replace('admin.php'); };    </script> 
      
    <!-- serving table -->
    <?php   $getPrepairingOrder = "select userInfo_tb.name, order_tb.* from userInfo_tb inner join order_tb on userInfo_tb.userlinkId = order_tb.userlinkId  and status = 'serving' ORDER BY order_tb.id asc; ";
            $resultSet = getQuery($getPrepairingOrder);?>
    <div class="table-responsive col-lg-6">
      <table class="table table-striped table-bordered col-lg-12">
        <thead class="table-dark">
          <tr>
            <h4 style="background: green; color: white;">Serving</h4>	
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

    <!-- prepairing table -->
    <?php   $getPrepairingOrder = "select userInfo_tb.name, order_tb.* from userInfo_tb inner join order_tb on userInfo_tb.userlinkId = order_tb.userlinkId  and status = 'prepairing' ORDER BY order_tb.id asc; ";
            $resultSet = getQuery($getPrepairingOrder);?>
    <div class="table-responsive col-lg-6">
      <table class="table table-striped table-bordered col-lg-12">
        <thead class="table-dark">
          <tr>
            <h4 style="background: red; color: white;">Prepairing</h4>	
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