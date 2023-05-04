<?php 
  $page = 'customer';
  include('../method/checkIfAccountLoggedIn.php');
  include('../method/query.php');
  $companyName = getQueryOneVal2('select name from weboms_company_tb','name');
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>View Orders - Order Details</title>
  
  <link rel="stylesheet" type="text/css" href="../css/bootstrap 5/bootstrap.min.css"> 
  <link rel="stylesheet" type="text/css" href="../css/customer.css">
  <!-- online css bootsrap icon -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
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
                        <a class="nav-link text-dark" href="customer.php"><i class="bi bi-house-door"></i> HOME</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link text-dark" href="customerProfile.php"><i class="bi bi-person-circle"></i> PROFILE</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link text-dark" href="customerMenu.php"><i class="bi bi-book"></i> MENU</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link text-dark" href="customerTopUp.php"><i class="bi bi-cash-stack"></i> TOP-UP</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link text-danger" href="customerOrders.php"><i class="bi bi-list"></i> VIEW ORDERS</a>
                    </li>
                    <li>
                        <form method="post">
                            <button class="btn btn-danger col-12" id="Logout" name="logout"><i class="bi bi-power"></i> LOGOUT</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
<div class="container text-center bg-white shadow p-5" style="margin-top:130px;">
  <div class="row justify-content-center">
    <div class="btn-group container-fluid" role="group" aria-label="Basic mixed styles example">
      <button class="btn btn-lg btn-dark col-6 mb-4" id="orderList"><i class="bi bi-arrow-left-short"></i> Back</button>
      <button class="btn btn-lg btn-danger col-6 mb-4" id="viewInPdf"><i class="bi bi-file-pdf"></i> PDF</button>
    </div>

    <!-- table -->
    <div class="table-responsive col-lg-12">
      <?php 
      
        $id =  $_GET['id'];
        $_SESSION['dishesArr'] = array();
        $_SESSION['priceArr'] = array();
        $_SESSION['dishesQuantity'] = array();

        $query = "select a.*, b.* from weboms_userInfo_tb a inner join weboms_order_tb b on a.user_id = b.user_id  where b.order_id = '$id' " ;
        $resultSet = getQuery2($query); 
        if($resultSet != null){
          foreach($resultSet as $row){ 
              //init
              $_SESSION['order_id'] = $row['order_id'];
              $_SESSION['or_number'] = $row['or_number'];
              $_SESSION['customerName'] = $row['name'];
              $_SESSION['date'] = $row['date'];
              $_SESSION['cash'] = $row['payment'];
              $_SESSION['total'] = $row['totalOrder'];
              $_SESSION['staffInCharge'] = $row['staffInCharge'];
          }
        }
        //company variables init
        $query = "select * from weboms_company_tb";
        $resultSet = getQuery2($query);
        if($resultSet!=null){
          foreach($resultSet as $row){
            $_SESSION['companyName'] = $row['name'];
            $_SESSION['companyAddress'] = $row['address'];
            $_SESSION['companyTel'] = $row['tel'];
          }
        }

        $query = "select weboms_menu_tb.*, weboms_ordersDetail_tb.* from weboms_menu_tb inner join weboms_ordersDetail_tb where weboms_menu_tb.orderType = weboms_ordersDetail_tb.orderType and weboms_ordersDetail_tb.order_id = '$id' ";
        $resultSet =  getQuery2($query); 
      ?>
            
      <table class="table table-hover table-bordered col-lg-12">
        <thead>
          <tr>	
            <th scope="col">DISH</th>
            <th scope="col">QUANTITY</th>
            <th scope="col">PRICE</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $total = 0;
          if($resultSet != null)
          foreach($resultSet as $row){ 
              array_push($_SESSION['dishesArr'],$row['dish']);
              array_push($_SESSION['priceArr'],$row['price']);
              array_push($_SESSION['dishesQuantity'],$row['quantity']);
          ?>
          <tr>	   
            <?php $price = ($row['price']*$row['quantity']);  $total += $price;?>
            <td><?php echo ucwords($row['dish']); ?></td>
            <td><?php echo $row['quantity']; ?></td>
            <td><?php echo '₱' . number_format($price,2);?></td>
          </tr>
          <?php }?>
          <tr>
            <td colspan="2"><b>Total Amount:</b></td>
            <td><b>₱<?php echo number_format($total,2);?></b></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
    
</body>
</html>

<script>
  document.getElementById("orderList").onclick = function () {window.location.replace('customerOrders.php'); };

  //order button (js)
  var viewInPdf = document.getElementById("viewInPdf");
  viewInPdf.addEventListener("click", () => {
          window.open("../pdf/receipt.php");
  });

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
