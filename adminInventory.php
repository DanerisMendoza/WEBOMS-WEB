<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Admin Inventory</title>
    
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>  
  <script type="text/javascript" src="js/bootstrap.min.js"></script>  

</head>
<body class="bg-light">
<div class="container text-center mt-5">
  <div class="row justify-content-center">
    <button class="btn btn-lg btn-dark col-6 mb-4" id="admin">Admin</button>
    <button id="addButton" type="button" class="btn btn-lg btn-success col-6 mb-4" data-toggle="modal" data-target="#loginModal">Add new dish</button>
    <script>document.getElementById("admin").onclick = function () {window.location.replace('admin.php'); };</script> 
    <div class="table-responsive col-lg-12 mb-5">
			<table class="table table-striped table-bordered col-lg-12">
			  <thead class="table-dark">
			    <tr>	
            <th scope="col">IMAGE</th>
			      <th scope="col">DISH</th>
			      <th scope="col">PRICE</th>
			      <th scope="col">STOCK</th>
			      <th scope="col"></th>
			      <th scope="col"></th>
			    </tr>
			  </thead>
			  <tbody>
			  	<?php 
            $page = 'admin';
            include('method/Query.php');
            include('method/checkIfAccountLoggedIn.php');
            $query = "select * from menu_tb";
            $resultSet = getQuery($query);
            if($resultSet != null){
              foreach($resultSet as $rows){?>
                <tr>	   
                <td><?php $pic = $rows['picName']; echo "<img src='dishesPic/$pic' style=width:100px;height:100px>";?></td>
                <td><?=$rows['dish']?></td>
                <td><?php echo '₱'.$rows['price']; ?></td>
                <td><?php echo $rows['stock']; ?></td>
                <td><a class="btn btn-danger border-dark" href="?idAndPicnameDelete=<?php echo $rows['orderType']." ".$rows['picName'] ?>">Delete</a></td>
                <td><a class="btn btn-warning border-dark" href="adminInventoryUpdate.php?idAndPicnameUpdate=<?php echo $rows['orderType'].",".$rows['dish'].",".$rows['price'].",".$rows['picName'].",".$rows['stock'] ?>"  >Update</a></td>
                </tr>
                <?php } 
            }
			  	 ?>
			  </tbody>
			</table>
		</div>
  </div>
</div>
  
<div class="modal fade" role="dialog" id="loginModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body ">
        <form method="post" class="form-group" enctype="multipart/form-data">
          <input type="text" class="form-control form-control-lg mb-3" name="dishes" placeholder="Enter Dish Name" required>
          <input type="number" class="form-control form-control-lg mb-3" name="price" placeholder="Enter Price" required>
          <input type="number" class="form-control form-control-lg mb-3" name="stock" placeholder="Enter Number of Stock" required>
          <input type="file" class="form-control form-control-lg mb-3" name="fileInput" required>
          <button type="submit" class="btn btn-lg btn-success col-12" name="insert">Insert</button>
        </form>
      </div>
    </div>
  </div>
</div>

</body>
</html>

<?php
  //delete 
  if (isset($_GET['idAndPicnameDelete'])){
    $arr = explode(' ',$_GET['idAndPicnameDelete']);
    $id = $arr[0];
    $pic = $arr[1];
    $query = "DELETE FROM menu_tb WHERE orderType='$id' ";
    if(Query($query)){
      unlink("dishespic/"."$pic");
      echo "<script> window.location.replace('adminInventory.php');</script>";
    }
  }

  //insert
  if(isset($_POST['insert'])){
  $dishes = $_POST['dishes'];
  $price = $_POST['price'];
  $file = $_FILES['fileInput'];
  $stock = $_POST['stock'];
  $fileName = $_FILES['fileInput']['name'];
  $fileTmpName = $_FILES['fileInput']['tmp_name'];
  $fileSize = $_FILES['fileInput']['size'];
  $fileError = $_FILES['fileInput']['error'];
  $fileType = $_FILES['fileInput']['type'];
  $fileExt = explode('.',$fileName);
  $fileActualExt = strtolower(end($fileExt));
  $allowed = array('jpg','jpeg','png');
  if(in_array($fileActualExt,$allowed)){
      if($fileError === 0){
          if($fileSize < 10000000){
              $fileNameNew = uniqid('',true).".".$fileActualExt;
              $fileDestination = 'dishesPic/'.$fileNameNew;
              move_uploaded_file($fileTmpName,$fileDestination);         
              $query = "insert into menu_tb(dish, price, picName, stock) values('$dishes','$price','$fileNameNew','$stock')";
              if(Query($query));
                echo "<script>window.location.replace('adminInventory.php')</script>";                                
          }
          else
              echo "your file is too big!";
      }
      else
          echo "there was an error uploading your file!";
  }
  else
      echo "you cannot upload files of this type";     
  }
?>