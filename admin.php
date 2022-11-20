<?php 
  $page = 'admin';
  include('method/checkIfAccountLoggedIn.php');
  $_SESSION['query'] = 'all';
  $_SESSION['from'] = null;
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Admin</title>

  <link rel="stylesheet" type="text/css" href="css/bootstrap.css">

</head>
<body class="bg-light">
<div class="container">
  <div class="row justify-content-center">
    <h1 class="font-weight-normal mt-5 mb-4"><?php echo $_SESSION['name'].'('.$_SESSION['accountType'].')';?></h1>
    <button class="btn btn-lg btn-primary col-12 mb-3" id="pos">POS</button>
    <button class="btn btn-lg btn-primary col-12 mb-3" id="orders">Orders</button>
    <button class="btn btn-lg btn-primary col-12 mb-3" id="ordersQueue">Orders Queue</button>
    <button class="btn btn-lg btn-primary col-12 mb-3" id="inventory">Inventory</button>
    <button class="btn btn-lg btn-primary col-12 mb-3" id="salesReport">Sales Report</button>
    <button class="btn btn-lg btn-primary col-12 mb-3" id="accountManagement">Account Management</button>
    <button class="btn btn-lg btn-dark col-12 mb-3" id="Logout">Logout</button>
    <script>
    document.getElementById("pos").onclick = function () {window.location.replace('adminPos.php'); };
    document.getElementById("orders").onclick = function () {window.location.replace('adminOrdersList.php'); };
    document.getElementById("orders").onclick = function () {window.location.replace('adminOrdersList.php'); };
    document.getElementById("ordersQueue").onclick = function () {window.location.replace('adminOrdersQueue.php'); };
    document.getElementById("inventory").onclick = function () {window.location.replace('adminInventory.php'); };
    document.getElementById("salesReport").onclick = function () {window.location.replace('adminSalesReport.php'); };
    document.getElementById("accountManagement").onclick = function () {window.location.replace('accountManagement.php'); };
    document.getElementById("Logout").onclick = function () {window.location.replace('Login.php'); 
    $.post(
        "method/logout.php",{
        }
    );}
    </script>

  </div>
</div>
  
</body>
</html>