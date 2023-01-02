<?php 
  $page = 'customer';
  include('../method/checkIfAccountLoggedIn.php');
  include('../method/query.php');
  // company name
  $_SESSION['multiArr'] = array();
  $companyName = getQueryOneVal2('select name from weboms_company_tb','name');
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>View Orders</title>

  <link rel="stylesheet" type="text/css" href="../css/bootstrap 5/bootstrap.min.css"> 
  <link rel="stylesheet" type="text/css" href="../css/customer.css">
  <script type="text/javascript" src="../js/bootstrap 5/bootstrap.min.js"></script>
  <script type="text/javascript" src="../js/jquery-3.6.1.min.js"></script>
  <!-- online css bootsrap icon -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
  <!-- data table -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

</head>

<body style="background:#e0e0e0">

    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow">
      <div class="container py-3">
        <a class="navbar-brand fs-4" href="#"><?php echo $companyName;?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item me-2">
                    <a class="nav-link text-dark" href="#" id="customer"><i class="bi bi-house-door"></i> HOME</a>
                </li>
                <li class="nav-item me-2">
                    <a class="nav-link text-dark" href="#" id="customerProfile"><i class="bi bi-person-circle"></i> PROFILE</a>
                </li>
                <li class="nav-item me-2">
                    <a class="nav-link text-dark" href="#" id="menu"><i class="bi bi-book"></i> MENU</a>
                </li>
                <li class="nav-item me-2">
                    <a class="nav-link text-dark" href="#" id="topUp"><i class="bi bi-cash-stack"></i> TOP-UP</a>
                </li>
                <li class="nav-item me-2">
                    <a class="nav-link text-danger" href="#" id="customerOrder_details"><i class="bi bi-list"></i> VIEW ORDERS</a>
                </li>
            </ul>
            <form method="post">
                <button class="btn btn-danger" id="Logout" name="logout"><i class="bi bi-power"></i> LOGOUT</button>
            </form>
        </div>
      </div>
    </nav>
    
    <div class="container text-center bg-white shadow" style="margin-top:130px;">    
      <div class="row justify-content-center">
        <div class="table-responsive col-lg-12 p-5">
          <table class="table table-bordered table-hover col-lg-12" id="tb1">
            <thead class="table-dark">
              <tr>	
                <th scope="col">NAME</th>
                <th scope="col">ORDER NO.</th>
                <th scope="col">STATUS</th>
                <th scope="col">DATE & TIME(MM/dd/yyyy)</th>
                <th scope="col">FEEDBACK</th>
                <th scope="col">ORDER DETAILS</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $user_id = $_SESSION["user_id"];  
                $getCustomerOrders = "select a.name, a.email, b.* from weboms_userInfo_tb a inner join weboms_order_tb b on a.user_id = b.user_id where a.user_id = '$user_id' order by b.id desc;";
                $resultSet = getQuery2($getCustomerOrders);
                if($resultSet != null)
                foreach($resultSet as $row){ ?>
                <tr>	   
                <td><?php echo ucwords($row['name']); ?></td>
                <td><?php echo $row['order_id']; ?></td>
                <td><?php echo ucwords($row['status']); ?></td>
                <td><?php echo date('m/d/Y h:i:s a ', strtotime($row['date'])); ?></td>
                <td>
                  <?php 
                  $order_id = $row['order_id'];
                  $user_id = $row['user_id'];
                  $checkIfAlreadyFeedback = "SELECT * FROM weboms_feedback_tb WHERE order_id='$order_id' AND user_id = '$user_id' ";
                  $resultSet = getQuery2($checkIfAlreadyFeedback);
                  if($row['status'] == 'complete' && $resultSet == null){
                    ?>  <a class="btn btn-primary" href="customerFeedBack.php?ordersLinkIdAndUserLinkId=<?php echo $row['order_id'].','.$row['user_id']?>"><i class="bi bi-chat-square-text"></i></a>  <?php
                  }
                  elseif($row['status'] == 'complete'){
                    echo "Feedback already sent!";
                  }
                  elseif($row['status'] == 'prepairing' || $row['status'] == 'serving'){
                    echo "Please wait until order is complete!";
                  }
                  elseif($row['status'] == 'void'){
                    echo "Order is void!";
                  }
                ?>
                </td>
                <td><a class="btn btn-light" style="border:1px solid #cccccc;" href="customerOrder_details.php?id=<?php echo $row['order_id'];?>"><i class="bi bi-list"></i> View</a></td>
                </tr>
                <?php } ?>
            </tbody>
          </table>
        </div>
    </div>
  </div>
    
</body>
</html>

<script>
document.getElementById("menu").onclick = function() { window.location.replace('customerMenu.php'); };
document.getElementById("topUp").onclick = function() { window.location.replace('customerTopUp.php'); };
document.getElementById("customer").onclick = function() { window.location.replace('customer.php'); };
document.getElementById("customerProfile").onclick = function() { window.location.replace('customerProfile.php'); };
</script>

<?php 
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
          $updateQuery = "UPDATE weboms_menu_tb SET stock = (stock + '$dishesQuantity[$i]') WHERE dish= '$dishesArr[$i]' ";    
          Query2($updateQuery);    
        }
    }
    session_destroy();
    echo "<script>window.location.replace('../general/login.php');</script>";
  }
?>
<script>
    $(document).ready(function() {
        $('#tb1').DataTable();
    });
    $('#tb1').dataTable({
    "columnDefs": [
        { "targets": [5], "orderable": false }
    ]
    });
</script>