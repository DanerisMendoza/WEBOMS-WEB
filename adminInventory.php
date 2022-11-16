<!DOCTYPE html>
<html>
<head>
  <title>Admin Inventory</title>
    
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>  
  <script type="text/javascript" src="js/bootstrap.min.js"></script>  

</head>
<body class="bg-light">

<div class="container text-center">
  <div class="row justify-content-center">
    <h1 class="font-weight-normal mt-5 mb-4 text-center">Inventory</h1>
    <button class="btn btn-lg btn-danger col-12 mb-3" id="admin">Admin</button>
    <button id="addButton" type="button" class="btn btn-lg btn-success col-12 mb-4" data-toggle="modal" data-target="#loginModal">Add new dish</button>
    <script>document.getElementById("admin").onclick = function () {window.location.replace('admin.php'); };</script> 

    <div class="table-responsive col-lg-12 mb-5">
			<table class="table table-striped table-bordered col-lg-12">
			  <thead class="table-dark">
			    <tr>	
            <th scope="col">IMAGE</th>
			      <th scope="col">DISH</th>
			      <th scope="col">PRICE</th>
			      <th scope="col">COST</th>
			      <th scope="col">STOCK</th>
			      <th scope="col"></th>
			      <th scope="col"></th>
			    </tr>
			  </thead>
			  <tbody>
			  	<?php 
            include('class/dishClass.php');
            include('method/Query.php');
            $dish = new dish();
            $resultSet = $dish -> getAllDishes();
            if($resultSet != null)
              $dish->generateDishTableBodyInventory($resultSet);
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
          <input type="number" class="form-control form-control-lg mb-3" name="cost" placeholder="Enter Cost" required>
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
    $dish = new dish($id, $pic);
    $dish ->deleteDishOnDatabase();
  }

  //insert
  if(isset($_POST['insert'])){
  $dishes = $_POST['dishes'];
  $price = $_POST['price'];
  $cost = $_POST['cost'];
  $file = $_FILES['fileInput'];
  $stock = $_POST['stock'];
  if($price < $cost){
    echo "<script>alert('Cost should be less than price!'); window.location.replace('adminInventory.php');</script>";
    return;
  }
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
              $dish = new dish($dishes, $price, $fileNameNew, $cost, $stock);
              $dish->insertNewDishToDatabase();        
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