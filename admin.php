<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
</head>
<body>
  <div class="container text-center">

    <button class="btn" id="pos">POS</button>
    <button class="btn" id="orders">Orders</button>
    <button class="btn" id="inventory">Inventory</button>
    <button class="btn" id="salesReport">Sales Report</button>
    <button class="btn" id="Logout">Logout</button>
    <script>
    document.getElementById("pos").onclick = function () {window.location.replace('pos.php'); };
    document.getElementById("orders").onclick = function () {window.location.replace('orders.php'); };
    document.getElementById("inventory").onclick = function () {window.location.replace('inventory.php'); };
    document.getElementById("salesReport").onclick = function () {window.location.replace('salesReport.php'); };
    document.getElementById("Logout").onclick = function () {window.location.replace('login.php'); 
    $.post(
        "method/clearSessionMethod.php", {
        }
    );};
    </script>
</body>
</html>

<style>
  body{
    background-color: black;
    color: white;
  }
  .btn{
    background-color: greenyellow;
  }

</style>


