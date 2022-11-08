<?php 
session_start();
$_SESSION['query'] = null
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
</head>

<body>
    <div class="container text-center">
    <h2> ADMINISTRATOR MODE </h1>
    <button class="btn" id="pos">POS</button>
    <button class="btn" id="orders">Orders</button>
    <button class="btn" id="inventory">Inventory</button>
    <button class="btn" id="salesReport">Sales Report</button>
    <button class="btn" id="settings">Settings</button>
    <button class="btn" id="Logout">Logout</button>
    <script>
    document.getElementById("pos").onclick = function () {window.location.replace('adminPos.php'); };
    document.getElementById("orders").onclick = function () {window.location.replace('adminOrdersList.php'); };
    document.getElementById("inventory").onclick = function () {window.location.replace('adminInventory.php'); };
    document.getElementById("salesReport").onclick = function () {window.location.replace('adminSalesReport.php'); };
    document.getElementById("settings").onclick = function () {window.location.replace('adminSettings.php'); };
    document.getElementById("Logout").onclick = function () {window.location.replace('login.php'); 
    $.post(
        "method/clearSessionMethod.php", {
        }
    );};
    </script>
</body>
</html>

<style>
@import url('https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap');
  body{
    background-image: url(settings/bg.jpg);
    background-size: cover;
    background-attachment: fixed;
    background-repeat: no-repeat;
    background-position: center;
    color: white;
    font-family: 'Josefin Sans',sans-serif;
  }
  .btn{
    background-color: transparent;
    border: none;
    color: lightblue;
    text-align: center;
    font-size: 25px;
    margin: 20px;
    transition: 0.4s;
    border-radius: 10px;
    border: 1px solid lightblue;
  }
   .btn:hover{
     color: white;
     background-color: black;
   }
   .container{
    display: grid;
    text-align: right;
    background: gray;
    position: absolute;
    top: 48%;
    left: 50%;
    transform: translate(-50%, -50%);
    padding: 15px 50px 15px;
    height: auto;
    border-radius: 15px;
   }
  h1{
    padding-top: 150px;
    text-align: center;
    color: white;
    font-size: 50px;
  }
</style>
