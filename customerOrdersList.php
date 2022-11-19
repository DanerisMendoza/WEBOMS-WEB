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
    <!-- <h1 class="font-weight-normal mt-5 mb-4 text-center">View Your Orders</h1> -->
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
              $page = 'customer';
              include('method/checkIfAccountLoggedIn.php');
              include('method/Query.php');
                $userlinkId = $_SESSION["userLinkId"];  
                $getCustomerOrders = "select userInfo_tb.*, order_tb.* from userInfo_tb, order_tb where userInfo_tb.userLinkId = order_tb.userlinkId and userInfo_tb.userLinkId = '$userlinkId';";
                $resultSet = getQuery($getCustomerOrders);
                if($resultSet != null)
                foreach($resultSet as $rows){ ?>
                <tr>	   
                <td><?php echo $rows['name']; ?></td>
                <td><?php echo $rows['status']; ?></td>
                <td><?php echo $rows['email']; ?></td>
                <td><a class="btn btn-light border-dark" href="customerOrders.php?idAndPic=<?php echo $rows['ordersLinkId'].','.$rows['proofOfPayment']?>">View Order</a></td>
                <td><?php 
                  $ordersLinkId = $rows['ordersLinkId'];
                  $userLinkId = $rows['userLinkId'];
                  $checkIfAlreadyFeedback = "SELECT * FROM feedback_tb WHERE ordersLinkId='$ordersLinkId' AND userLinkId = '$userLinkId' ";
                  $resultSet = getQuery($checkIfAlreadyFeedback);
                  if($rows['status'] == 'complete' && $resultSet == null){
                    ?>  <a class="btn btn-light border-dark" href="customerFeedBack.php?ordersLinkIdAndUserLinkId=<?php echo $rows['ordersLinkId'].','.$rows['userLinkId']?>">Feedback</a>  <?php
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

