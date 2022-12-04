<?php 
  $page = 'admin';
  include('method/checkIfAccountLoggedIn.php');
  $_SESSION['query'] = 'all';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>

</head>

<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <h1 class="font-weight-normal mt-5 mb-4"><?php echo $_SESSION['name'].'('.$_SESSION['accountType'].')';?>
            </h1>
            <button class="btn btn-lg btn-primary col-12 mb-3" id="pos">POS</button>
            <button class="btn btn-lg btn-primary col-12 mb-3" id="orders">Orders</button>
            <button class="btn btn-lg btn-primary col-12 mb-3" id="ordersQueue">Orders Queue</button>
            <button class="btn btn-lg btn-primary col-12 mb-3" id="inventory">Inventory</button>
            <button class="btn btn-lg btn-primary col-12 mb-3" id="salesReport">Sales Report</button>
            <button class="btn btn-lg btn-primary col-12 mb-3" id="accountManagement">Account Management</button>
            <button class="btn btn-lg btn-primary col-12 mb-3" id="customerFeedback">Customer Feedback</button>
            <button class="btn btn-lg btn-primary col-12 mb-3" id="adminTopUp">topUp</button>
            <form method="POST">
                <button class="btn btn-lg btn-dark col-12 mb-3" id="Logout" name="logout">Logout</button>
            </form>
            <script>
            document.getElementById("pos").onclick = function() {
                window.location.replace('adminPos.php');
            };
            document.getElementById("orders").onclick = function() {
                window.location.replace('adminOrders.php');
            };
            document.getElementById("orders").onclick = function() {
                window.location.replace('adminOrders.php');
            };
            document.getElementById("ordersQueue").onclick = function() {
                window.location.replace('adminOrdersQueue.php');
            };
            document.getElementById("inventory").onclick = function() {
                window.location.replace('adminInventory.php');
            };
            document.getElementById("salesReport").onclick = function() {
                window.location.replace('adminSalesReport.php');
            };
            document.getElementById("accountManagement").onclick = function() {
                window.location.replace('accountManagement.php');
            };
            document.getElementById("customerFeedback").onclick = function() {
                window.location.replace('customerFeedbackList.php');
            };
            document.getElementById("adminTopUp").onclick = function() {
                window.location.replace('adminTopUp.php');
            };
            </script>

        </div>
    </div>

</body>

</html>
<?php 
  include('method/query.php');
  if(isset($_POST['logout'])){
    $dishesArr = array();
    $dishesQuantity = array();
    if(isset($_SESSION['dishes'])){
      for($i=0; $i<count($_SESSION['dishes']); $i++){
          if(in_array( $_SESSION['dishes'][$i],$dishesArr)){
            $index = array_search($_SESSION['dishes'][$i], $dishesArr);
          }
          else{
            array_push($dishesArr,$_SESSION['dishes'][$i]);
          }
      }
      foreach(array_count_values($_SESSION['dishes']) as $count){
        array_push($dishesQuantity,$count);
      }
      for($i=0; $i<count($dishesArr); $i++){ 
        $updateQuery = "UPDATE WEBOMS_menu_tb SET stock = (stock + '$dishesQuantity[$i]') WHERE dish= '$dishesArr[$i]' ";    
        Query($updateQuery);    
      }
    }
    session_destroy();
    echo "<script>window.location.replace('Login.php');</script>";
  }
?>