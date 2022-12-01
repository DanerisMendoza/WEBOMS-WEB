<?php 
    $page = 'customer';
    include('method/checkIfAccountLoggedIn.php');
    include('method/query.php');
    if(!isset($_SESSION["dishes"]) || !isset($_SESSION["price"]) || !isset($_SESSION["orderType"])){
    $_SESSION["dishes"] = array();
    $_SESSION["price"] = array(); 
    $_SESSION["orderType"] = array(); 
    }
    $query = "SELECT balance FROM `WEBOMS_userInfo_tb` where user_id = '$_SESSION[user_id]' ";
    $balance = getQueryOneVal($query,'balance');
    $balance = $balance == null ? 0 : $balance;
    $_SESSION['balance'] = $balance;
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
        <h2 class="font-weight-normal mt-3  mb-3 col-12">Customer, Hi <?php echo $_SESSION['name'];?>!</h1>  <!--mt for margin top and mb for margin bot -->
        <h2 class="font-weight-normal mb-3 col-12">Balance: ₱<?php echo $balance; ?></h1>
        <button class="btn btn-lg btn-primary col-12 mb-3" id="menu">Browse Menu</button>
        <button class="btn btn-lg btn-primary col-12 mb-3" id="topUp">Top-up</button>
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
        window.location.replace('Login.php');
    });
	document.getElementById("menu").onclick = function () {window.location.replace('customerMenu.php'); };
	document.getElementById("topUp").onclick = function () {window.location.replace('customerTopUp.php'); };
	document.getElementById("customerOrders").onclick = function () {window.location.replace('customerOrdersList.php'); };
</script>