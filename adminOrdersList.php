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
            <table class="table table-striped" border="10">
            <tr>	
              <th scope="col">name</th>
              <th scope="col">Orders ID</th>
              <th scope="col">_______</th>
              <th scope="col">_______</th>
              <th scope="col">Approve status:</th>
              <th scope="col">Order Complete Status</th>
              <th scope="col">Order status:
                <form method="post">
                  <button class="btn" type="submit" name="showAll" style="font-size: 12px ;">Show/Unshow All</button>
                </form>
              </th>
                <th scope="col">Date:</th>
                <th scope="col">_______</th>
            </tr>
              <tbody>
                <?php
                  $transaction = new transaction();
                  include('method/Query.php');
                  if($_SESSION['query'] != 'all')
                    $resultSet =  $transaction -> getAllNotCompleteTransaction();
                  else
                    $resultSet =  $transaction -> getAllTransaction();
                  if($resultSet != null)
                    $transaction -> generateOrdersTableBody($resultSet);
                ?>
              </tbody>
            </table>
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
    $transaction =  transaction::withID($id);
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