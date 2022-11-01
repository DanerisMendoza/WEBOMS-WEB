<?php 
    session_start();
    if(!isset($_SESSION["dishes"]) || !isset($_SESSION["price"]) || !isset($_SESSION["orderType"])){
    $_SESSION["dishes"] = array();
    $_SESSION["price"] = array(); 
    $_SESSION["orderType"] = array(); 
    }
?>
<!DOCTYPE html>
<html>
    <head>
    <title></title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script> 
    <script type="text/javascript" src="js/bootstrap.js"></script>

</head>
<body>
    <div class="container2 text-center">
        <h2> Customer </h1>
        <button class="btn" id="menu">Browse in Menu</button>
        <button class="btn" id="customerOrders">View your Orders</button>
        <button class="btn" id="logout">Logout</button>
    </div>
</body>
</html>


<script>
	document.getElementById("logout").addEventListener("click",function(){
		$.post(
        "method/clearMethod.php");
        window.location.replace('login.php');
    });
	document.getElementById("menu").onclick = function () {window.location.replace('customerMenu.php'); };
	document.getElementById("customerOrders").onclick = function () {window.location.replace('customerOrdersList.php'); };
</script>
<style>
  	body{
    background-image: url(settings/bg.jpg);
    background-size: cover;
    background-attachment: fixed;
    background-repeat: no-repeat;
    background-position: center;
    color: white;
    font-family: 'Josefin Sans',sans-serif;
    }
	.container{
     padding: 1%;
     margin-top: 2%;
     background: gray;
   }

   .container2{
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

   
</style>