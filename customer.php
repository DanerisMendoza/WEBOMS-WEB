<?php 
    $page = 'customer';
    include('method/checkIfAccountLoggedIn.php');
    if(!isset($_SESSION["dishes"]) || !isset($_SESSION["price"]) || !isset($_SESSION["orderType"])){
    $_SESSION["dishes"] = array();
    $_SESSION["price"] = array(); 
    $_SESSION["orderType"] = array(); 
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Costumer</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script> 
    <script type="text/javascript" src="js/bootstrap.js"></script>

</head>
<body class="bg-light">

<div class="container text-center">
    <div class="row justify-content-center">
        <h2 class="font-weight-normal mt-5 mb-4">Customer, Hi <?php echo $_SESSION['name']?>!</h1>
        <button class="btn btn-lg btn-primary col-12 mb-3" id="menu">Browse Menu</button>
        <button class="btn btn-lg btn-primary col-12 mb-3" id="customerOrders">View Your Orders</button>
        <button class="btn btn-lg btn-dark col-12" id="logout">Logout</button>
    </div>
</div>

</body>
</html>


<script>
	document.getElementById("logout").addEventListener("click",function(){
		$.post(
        "method/logout.php");
        window.location.replace('login.php');
    });
	document.getElementById("menu").onclick = function () {window.location.replace('customerMenu.php'); };
	document.getElementById("customerOrders").onclick = function () {window.location.replace('customerOrdersList.php'); };
</script>