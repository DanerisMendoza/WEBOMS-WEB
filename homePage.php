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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
</head>
<body>
<div class="container text-center">
        <button type="button" class="btn btn-success col-sm-4" id="logout">Logout</button>
        <button type="button" class="btn btn-success col-sm-4" id="viewCart">View Cart</button>
        <div class="col-lg-12">
		<table class="table table-striped" border="10">
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
					<td><?php echo '₱'.$rows['cost']; ?></td>
					<td><?php $pic = $rows['picName']; echo "<img src='dishesPic/$pic' style=width:100px;height:100px>";?></td>
					<td><a href="?order=<?php echo $rows['dish'].",".$rows['cost'].",".$rows['orderType']?>" >Add to Cart</a></td>
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
     .btn{
    background-color: greenyellow;
  }
</style>