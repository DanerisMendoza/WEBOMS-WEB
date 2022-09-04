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
</head>
<body>
<div class="container text-center">
        <button type="button" class="btn btn-success col-sm-4" id="logout">Logout</button>
        <button type="button" class="btn btn-success col-sm-4" id="logout">View Cart</button>
        <div class="col-lg-12">
			<!-- order table -->
			<?php 
			include_once('connection.php');
			$sql = mysqli_query($conn,"select * from dishes_tb");  
			if (mysqli_num_rows($sql)) {  
			?>
			<table class="table table-striped" border="10">
			  <thead>
			    <tr>	
			      	<th scope="col">Dishes</th>
					<th scope="col">cost</th>
					<th scope="col">picture</th>
			    </tr>
			  </thead>
			  <tbody>
			  	<?php 
			  	   $i = 0;
			  	   while($rows = mysqli_fetch_assoc($sql)){
			  	   $i++;
			  	 ?>
				 
			    <tr>	   
					<td><?=$rows['dish']?></td>
					<td><?php echo '₱'.$rows['cost']; ?></td>
					<td><?php $pic = $rows['picName']; echo "<img src='dishesPic/$pic' style=width:100px;height:100px>";?></td>
					<td><a href="?dish=<?php echo $rows['dish']." ".$rows['cost']?>">Add to Cart</a></td>
			    </tr>
			    <?php } ?>
			  </tbody>
			</table>
			<?php } ?>
			
			<!-- cart table -->
			<div class="modal fade" id="otpModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
			<div class="modal-dialog">
				<div class="modal-content container">
					<div class="modal-body">
						<form method="post">
							<h3>Cart</h3>
							<input data-dismiss="modal" type="submit" value="Cancel" name="Cancel">
		
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
<?php 
    $username = $_GET['username'];
    echo "<script>alert('hello $username!');</script>";
?>
<script>
        document.getElementById("logout").addEventListener("click",function(){
        window.location.replace('login.php');
    });
</script>
<style>
     .btn{
    background-color: greenyellow;
  }
</style>