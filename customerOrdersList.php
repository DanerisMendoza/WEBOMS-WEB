<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
    <div class="container text-center">
        <button class="btn btn-success col-sm-4" id="customer">Customer</button>
        <button class="btn btn-success col-sm-4" id="customersFeedback">Customers FeedBack</button>
        <script>
            document.getElementById("customer").onclick = function () {window.location.replace('customer.php'); };    
            document.getElementById("customersFeedback").onclick = function () {window.location.replace('customerFeedbackList.php'); };    
        </script> 
        
        <div class="col-lg-12">
            <table class="table table-striped col-lg-12" border="10">
            <tr class="col-lg-12">	
            <th scope="col">name</th>
            <th scope="col">status</th>
            <th scope="col">email</th>
            <th scope="col"></th>
            <th scope="col">FeedBack</th>
            <th scope="col">Date</th>
            </tr>
              <tbody>
                <?php
                session_start();
                // $a = $_SESSION['userLinkId'];

                include_once('orderClass.php');
                $orderlist = orderList::withUserLinkId($_SESSION["userLinkId"]);  //Scope Resolution Operator (::) double colon = jump to search 
                $resultSet =  $orderlist -> getOrderListByUserLinkId(); 
                if($resultSet != null)
                foreach($resultSet as $rows){ ?>
                <tr>	   
                <td><?php echo $rows['name']; ?></td>
                <td><?php echo ($rows['status'] == 1 ? "Approved": "Pending"); 
                ?></td>
                <td><?php echo $rows['email']; ?></td>
                <td><a class="btn" style="background: white; padding:2px; border: 2px black solid; color:black;" href="customerOrders.php?idAndPic=<?php echo $rows['ordersLinkId'].','.$rows['proofOfPayment']?>">View Order</a></td>
                <td><?php 
                  $orderlist =  orderList::withUsersAndOrdersLinkId($rows['userlinkId'],$rows['ordersLinkId']);
                  if($rows['status'] == 1 && $orderlist->CustomerFeedback() == null){
                    ?>  <a class="btn" style="background: white; padding:2px; border: 2px black solid; color:black;" href="customerFeedBack.php?ordersLinkIdAndUserLinkId=<?php echo $rows['ordersLinkId'].','.$rows['userlinkId']?>">feedback</a>  <?php
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