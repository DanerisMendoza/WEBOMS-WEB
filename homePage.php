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
    <head><title></title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script> 
        <script type="text/javascript" src="js/bootstrap.js"></script>
</head>
<body>
<div class="container text-center">
        <button type="button" class="btn btn-success col-sm-4" id="logout">Logout</button>
        <button type="button" class="btn btn-success col-sm-4" id="viewCart">View Cart</button>
        <div class="col-lg-12">
		<table class="table" border="10px">
			<tr>	
				<th scope="col">Dishes</th>
				<th scope="col">Price</th>
				<th scope="col">picture</th>
			</tr>
			<?php 
			include_once('connection.php');
			$sql = mysqli_query($conn,"select * from dishes_tb");  
			if (mysqli_num_rows($sql)) {  
			?>
			  	<?php while($rows = mysqli_fetch_assoc($sql)){ ?>
			    <tr>	   
					<td><?=$rows['dish']?></td>
					<td><?php echo '₱'.$rows['price']; ?></td>
					<td><?php $pic = $rows['picName']; echo "<img src='dishesPic/$pic' style=width:100px;height:100px>";?></td>
					<td><a href="?order=<?php echo $rows['dish'].",".$rows['price'].",".$rows['orderType']?>" >Add To Cart</a></td>
			    </tr>
			    <?php } ?>
			<?php } ?>	
			</table>
		</div>
</div>
</body>
</html>
<?php 
	if(isset($_GET['order'])){
        $order = explode(',',$_GET['order']);  
        $dish = $order[0];
        $price = $order[1];
		$orderType = $order[2];
        array_push($_SESSION['dishes'], $dish);
        array_push($_SESSION['price'], $price);
        array_push($_SESSION['orderType'], $orderType);
    }				
?>

<script>
	document.getElementById("logout").addEventListener("click",function(){
		$.post(
        "method/clearMethod.php");
        window.location.replace('login.php');
    });
	document.getElementById("viewCart").onclick = function () {window.location.replace('usercart.php'); };
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
</style>
