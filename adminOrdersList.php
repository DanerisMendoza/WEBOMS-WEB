<?php 
  session_start();
  include_once('class/transactionClass.php');
  include_once('class/orderClass.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
    <div class="container text-center">

        <button class="btn btn-success col-sm-4" id="admin">Admin</button>
        <script>
            document.getElementById("admin").onclick = function () {window.location.replace('admin.php'); };    
        </script> 
        
        <div class="col-lg-12">
            <?php
              $transaction = new transaction();
              include('method/Query.php');
              if($_SESSION['query'] != 'all')
                $resultSet =  $transaction -> getAllNotCompleteTransaction();
              else
                $resultSet =  $transaction -> getAllTransaction();
              if($resultSet != null)
                $transaction -> generateOrdersTable($resultSet);
            ?>
          </div>
	    </div>
    </body>
</html>
<?php 
  //button to approve order
  if(isset($_GET['status'])){
    $arr = explode(',',$_GET['status']);  
    $ordersLinkId = $arr[0];
    $email = $arr[1];
    $order = new order($ordersLinkId,$email);
    $order-> computeOrder(); 
    $order-> sendReceiptToEmail(); 
    $order-> approveOrder();
  }
  //button to make transaction complete
  if(isset($_GET['orderComplete'])){
    $id = $_GET['orderComplete'];
    $transaction =  new transactionById($id);
    $transaction -> setOrderComplete();
  }
  //button to show even completed order or show pending orders only
  if(isset($_POST['showAll'])){
    if($_SESSION['query'] == 'all')
      $_SESSION['query'] = null;
    else
      $_SESSION['query'] = 'all';
    echo "<script>window.location.replace('adminOrdersList.php');</script>";
  }
?>