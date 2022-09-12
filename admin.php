<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
</head>
<body>
  <div class="container text-center">
    <button id="addButton" type="button" class="btn" data-toggle="modal" data-target="#loginModal">Add new dish</button>
    <button class="btn" id="pos">POS</button>
    <button class="btn" id="orders">Orders</button>
    <button class="btn" id="inventory">Inventory</button>
    <button class="btn" id="Logout">Logout</button>
    <script>
    document.getElementById("pos").onclick = function () {window.location.replace('pos.php'); };
    document.getElementById("orders").onclick = function () {window.location.replace('orders.php'); };
    document.getElementById("inventory").onclick = function () {window.location.replace('inventory.php'); };
    document.getElementById("Logout").onclick = function () {window.location.replace('login.php'); 
    $.post(
        "method/clearMethod.php", {
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


