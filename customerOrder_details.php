<?php 
  $page = 'customer';
  include('method/checkIfAccountLoggedIn.php');
  include('method/query.php');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Costumer - Orders</title>
  
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">

</head>
<body class="bg-light">
    
<div class="container text-center mt-5">
  <div class="row justify-content-center">
    <button class="btn btn-lg btn-dark col-12 mb-4" id="orderList"><i class="bi bi-arrow-left me-1"></i>Order List</button>
      <button class="btn btn-lg btn-dark col-12 mb-3" id="viewInPdf"><i class="bi bi-arrow-right me-1"></i>View In Pdf </button>

    <div class="table-responsive col-lg-12">
            <?php 
            
              $id =  $_GET['id'];
              $_SESSION['dishesArr'] = array();
              $_SESSION['priceArr'] = array();
              $_SESSION['dishesQuantity'] = array();

              $query = "select a.*, b.* from WEBOMS_userInfo_tb a inner join WEBOMS_order_tb b on a.user_id = b.user_id  where b.order_id = '$id' " ;
              $resultSet = getQuery($query); 
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
              $query = "select * from WEBOMS_company_tb";
              $resultSet = getQuery($query);
              if($resultSet!=null){
                foreach($resultSet as $row){
                  $_SESSION['companyName'] = $row['name'];
                  $_SESSION['companyAddress'] = $row['address'];
                  $_SESSION['companyTel'] = $row['tel'];
                }
              }


              $query = "select WEBOMS_menu_tb.*, WEBOMS_ordersDetail_tb.* from WEBOMS_menu_tb inner join WEBOMS_ordersDetail_tb where WEBOMS_menu_tb.orderType = WEBOMS_ordersDetail_tb.orderType and WEBOMS_ordersDetail_tb.order_id = '$id' ";
              $resultSet =  getQuery($query); 
            ?>
            
      <table class="table table-striped table-bordered col-lg-12 mb-4">
        <thead class="table-dark">
          <tr>	
            <th scope="col">QUANTITY</th>
            <th scope="col">DISH</th>
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
            <td><?php echo $row['quantity']; ?></td>
            <td><?php echo $row['dish']; ?></td>
            <td><?php echo '₱' .$price?></td>
          </tr>
          <?php }?>
          <tr>
            <td colspan="2"><b>TOTAL AMOUNT:</b></td>
            <td><b>₱<?php echo $total?></b></td>
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
          window.open("pdf/receipt.php");
  });

</script>