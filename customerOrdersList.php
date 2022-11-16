<!DOCTYPE html>
<html>
<head>
  <title>Costumer - View Your Orders</title>
        
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
  <link rel="stylesheet" type="text/css" href="css/style.css">
    
</head>
<body class="bg-light">
    
<div class="container text-center">
  <div class="row justify-content-center">
    <h1 class="font-weight-normal mt-5 mb-4 text-center">View Your Orders</h1>
    <button class="btn btn-lg btn-danger col-12 mb-4" id="customer">Customer</button>
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
                session_start();
                include_once('class/transactionClass.php');
                include_once('class/feedbackClass.php');
                include('method/Query.php');
                $transaction = transaction::withUserLinkId($_SESSION["userLinkId"]);  //Scope Resolution Operator (::) double colon = jump to search 
                $resultSet =  $transaction -> getOrderListByUserLinkId(); 
                if($resultSet != null)
                foreach($resultSet as $rows){ ?>
                <tr>	   
                <td><?php echo $rows['name']; ?></td>
                <td><?php echo ($rows['status'] == 1 ? "Approved": "Pending"); 
                ?></td>
                <td><?php echo $rows['email']; ?></td>
                <td><a class="btn btn-light border-dark" href="customerOrders.php?idAndPic=<?php echo $rows['ordersLinkId'].','.$rows['proofOfPayment']?>">View Order</a></td>
                <td><?php 
                  $transaction =  new feedback('',$rows['ordersLinkId'],$rows['userLinkId']);
                  if($rows['status'] == 1 && $transaction->CustomerFeedback() == null){
                    ?>  <a class="btn btn-light border-dark" href="customerFeedBack.php?ordersLinkIdAndUserLinkId=<?php echo $rows['ordersLinkId'].','.$rows['userLinkId']?>">Feedback</a>  <?php
                  }
                  elseif($rows['status'] == 1){
                    echo "You have already feedback!";
                  }
                  else{
                    echo "you cannot give feedback yet </br> please wait for approvation";
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

<?php 
  if(isset($_GET['status'])){
    $arr = explode(',',$_GET['status']);  
    $ordersLinkId = $arr[0];
    $email = $arr[1];
    $order = new order($ordersLinkId,$email);
    $order-> computeOrder(); 
    $order-> sendReceiptToEmail(); 
    $order-> approveOrder();
  }
?>