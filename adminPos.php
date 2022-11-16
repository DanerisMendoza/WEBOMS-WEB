<?php 
  session_start();

  if(!isset($_SESSION["dishes"]) && !isset($_SESSION["price"])){
    $_SESSION["dishes"] = array();
    $_SESSION["price"] = array(); 
  }
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin POS</title>
        
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
   
</head>
<body class="bg-light">
          
<div class="container text-center">
  <div class="row justify-content-center">
    <h1 class="font-weight-normal mt-5 mb-4">Point of Sales</h1>
    <button class="btn btn-lg btn-danger col-12 mb-3" id="admin">Admin</button>
    <button  type="button" class="btn btn-lg btn-success col-12 mb-4" id="viewCart" >View Cart</button>
              
    <script>document.getElementById("admin").onclick = function () {window.location.replace('admin.php'); };</script> 
    <script>document.getElementById("viewCart").onclick = function () {window.location.replace('adminCart.php'); };</script> 
              
    <div class="table-responsive col-lg-12">
            <?php 
                include_once('class/dishClass.php');
                include('method/Query.php');
                $dish = new dish();
                $resultSet =  $dish -> getAllDishes(); 
            ?>
      <table class="table table-striped table-bordered mb-5 col-lg-12">
        <thead class="table-dark">
          <tr>	
            <th scope="col">DISH</th>
            <th scope="col">PRICE</th>
            <th scope="col">IMAGE</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
                <?php
                  $dish->generateDishTableBodyMenu($resultSet);
                ?>
        </tbody>
      </table>
    </div>
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