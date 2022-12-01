<?php 
  $page = 'customer';
  include('method/checkIfAccountLoggedIn.php');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Costumer - View Your Orders</title>

  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
  <link rel="stylesheet" type="text/css" href="css/style.css">
    
</head>
<body class="bg-light">
    
<div class="container text-center mt-5">
  <div class="row justify-content-center">
    <button class="btn btn-lg btn-dark col-12 mb-4" id="customer">Customer</button>
        <script>
            document.getElementById("customer").onclick = function () {window.location.replace('customer.php'); };    
        </script> 
        
    <div class="table-responsive col-lg-12">
      <table class="table table-striped table-bordered col-lg-12">
        <thead class="table-dark">
          <tr>	
            <th scope="col">NAME</th>
            <th scope="col">STATUS</th>
            <th scope="col">EMAIL</th>
            <th scope="col"></th>
            <th scope="col">FEEDBACK</th>
            <th scope="col">DATE & TIME</th>
          </tr>
        </thead>
        <tbody>
              <?php
              include('method/query.php');
                $user_id = $_SESSION["user_id"];  
                $getCustomerOrders = "select WEBOMS_userInfo_tb.*, WEBOMS_order_tb.* from WEBOMS_userInfo_tb, WEBOMS_order_tb where WEBOMS_userInfo_tb.user_id = WEBOMS_order_tb.user_id and WEBOMS_userInfo_tb.user_id = '$user_id';";
                $resultSet = getQuery($getCustomerOrders);
                if($resultSet != null)
                foreach($resultSet as $rows){ ?>
                <tr>	   
                <td><?php echo $rows['name']; ?></td>
                <td><?php echo $rows['status']; ?></td>
                <td><?php echo $rows['email']; ?></td>
                <td><a class="btn btn-light border-dark" href="customerOrders.php?idAndPic=<?php echo $rows['order_id'].','.$rows['proofOfPayment']?>">View Order</a></td>
                <td><?php 
                  $order_id = $rows['order_id'];
                  $user_id = $rows['user_id'];
                  $checkIfAlreadyFeedback = "SELECT * FROM WEBOMS_feedback_tb WHERE order_id='$order_id' AND user_id = '$user_id' ";
                  $resultSet = getQuery($checkIfAlreadyFeedback);
                  if($rows['status'] == 'complete' && $resultSet == null){
                    ?>  <a class="btn btn-light border-dark" href="customerFeedBack.php?ordersLinkIdAndUserLinkId=<?php echo $rows['order_id'].','.$rows['user_id']?>">Feedback</a>  <?php
                  }
                  elseif($rows['status'] == 'complete'){
                    echo "You have already feedback!";
                  }
                  else{
                    echo "Please wait until order is Complete.";
                  }
                ?>
                </td>
                <td><?php echo date('m/d/Y h:i:s a ', strtotime($rows['date'])); ?></td>
                </tr>
                <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
    
</body>
</html>

