<?php 
  include('allScript.php');
  session_start();
  if(!isset($_SESSION["dishes"]) && !isset($_SESSION["price"])){
  $_SESSION["dishes"] = array();
  $_SESSION["price"] = array(); 
  }
?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
    </head>
    <body>
    <div class="container text-center">
        <button class="btn btn-success col-sm-4" id="admin">Admin</button>
        <script>document.getElementById("admin").onclick = function () {window.location.replace('admin.php'); };</script> 
        <button  type="button" class="btn btn-success col-sm-4" id="viewCart" >View Cart</button>
        <script>document.getElementById("viewCart").onclick = function () {window.location.replace('cart.php'); };</script> 
        <div class="col-lg-12">
			<!-- order table -->
			<?php 
			include_once('connection.php');
			$sql = mysqli_query($conn,"select * from dishes_tb");  
			if (mysqli_num_rows($sql)) {  
			?>
			<table class="table table-striped" border="10">
			    <tr>	
          <th scope="col">Dishes</th>
					<th scope="col">Price</th>
					<th scope="col">picture</th>
			    </tr>
			  <tbody>
			  	<?php while($rows = mysqli_fetch_assoc($sql)){ ?>
			    <tr>	   
					<td><?=$rows['dish']?></td>
					<td><?php echo '₱'.$rows['price']; ?></td>
					<td><?php $pic = $rows['picName']; echo "<img src='dishesPic/$pic' style=width:100px;height:100px>";?></td>
					<td><a href="?order=<?php echo $rows['dish'].",".$rows['price']?>" >Add to Cart</a></td>
			    </tr>
			    <?php } ?>
			  </tbody>
			</table>
			<?php } ?>
    </div>
	</div>

    </body>
</html>

<?php 
    if(isset($_GET['order'])){
      $order = explode(',',$_GET['order']);  
      $dish = $order[0];
      $price = $order[1];
      array_push($_SESSION['dishes'], $dish);
      array_push($_SESSION['price'], $price);
    }
?>

<style>
    .btn{
      background-color: greenyellow;
    }
     
    body{
      background-image: url(settings/bg.jpg);
      background-size: cover;
      background-attachment: fixed;
      background-repeat: no-repeat;
      background-position: center;
      color: white;
      font-family: 'Josefin Sans',sans-serif;
    }

  
</style>

