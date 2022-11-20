<?php 
    $page = 'cashier';
    include('method/checkIfAccountLoggedIn.php');
    include('method/query.php');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin POS - View Cart</title>
        
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
    <link rel="stylesheet" type="text/css" href="css/style.css">  
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script> 

</head>
<body class="bg-light">    
        
<div class="container text-center mt-5">
    <div class="row justify-content-center">
        <button class="btn btn-lg btn-dark col-12 mb-3" id="pos">POS</button>
    
        <div class="table-responsive col-lg-12">
            <table class="table table-striped table-bordered col-lg-12 mb-5">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">QUANTITY</th>
                        <th scope="col">DISH</th>
                        <th scope="col">COST</th>
                    </tr>
                </thead>
                    <?php 
                    $dishesArr = array();
                    $priceArr = array();
                    $dishesQuantity = array();
                    $orderType = array();

                    for($i=0; $i<count($_SESSION['dishes']); $i++){
                        if(in_array( $_SESSION['dishes'][$i],$dishesArr)){
                            $index = array_search($_SESSION['dishes'][$i], $dishesArr);
                            $newCost = $priceArr[$index] + $_SESSION['price'][$i];
						    $priceArr[$index] = $newCost;
                        }
                        else{
                            array_push($dishesArr,$_SESSION['dishes'][$i]);
                            array_push($priceArr,$_SESSION['price'][$i]);
                            array_push($orderType,$_SESSION['orderType'][$i]);
                        }
                    }
    
                    foreach(array_count_values($_SESSION['dishes']) as $count){
                        array_push($dishesQuantity,$count);
                    }
                       
                    $total = 0;
                    for($i=0; $i<count($priceArr); $i++){
                        $total += $priceArr[$i];
                    }
                    
                    for($i=0; $i<count($dishesArr); $i++){ ?>
                    <tr>  
                        <td><?php echo $dishesQuantity[$i];?></td>
                        <td><?php echo $dishesArr[$i];?></td>
                        <td><?php echo '₱'.$priceArr[$i];?></td>
                    </tr>
                    <?php }?>
                    <tr>
                        <td colspan="2"><b>TOTAL AMOUNT:</b></td>
                        <td><b>₱<?php echo $total; ?></b></td>
                    </tr>
            </table> 
       
            <form method="post">
                <input name="cash" placeholder="Cash Amount" type="number" class="form-control form-control-lg mb-3" required></input>
                <button type ="submit" class="btn btn-lg btn-success col-12 mb-3" name="order">Place Order</button>
            </form>
            <form method="post">
                <button type="submit" class="btn btn-lg btn-danger col-12 mb-5" name="clear">Clear Order</button>
            </form>
        </div>
    </div>
</div>
    
</body>
</html>
<script>document.getElementById("pos").onclick = function () {window.location.replace('adminPos.php'); }</script> 
<?php
    //clear button
    if(isset($_POST['clear'])){
        for($i=0; $i<count($dishesArr); $i++){ 
            $updateQuery = "UPDATE menu_tb SET stock = (stock + '$dishesQuantity[$i]') WHERE dish= '$dishesArr[$i]' ";    
            Query($updateQuery);    
        }
        $_SESSION["dishes"] = array();
        $_SESSION["price"] = array();
        $_SESSION["orderType"] = array(); 
        echo "<script>window.location.replace('adminCart.php');</script>";
    }

    //order button
    if(isset($_POST['order'])){
        $cash = $_POST['cash'];
        if($cash<$total)
            die ("<script>alert('Your Cash is less than your total Payment amount');</script>");
        date_default_timezone_set('Asia/Manila');
        $date = new DateTime();
        $today =  $date->format('Y-m-d'); 
        $todayWithTime =  $date->format('Y-m-d H:i:s'); 
        $_SESSION['date'] = $date = date("Y-m-d H:i:s"); 
        $_SESSION['cash'] = $cash;
        $_SESSION['total'] = $total;
        $_SESSION['dishesArr'] = $dishesArr;
        $_SESSION['priceArr'] = $priceArr;
        $_SESSION['dishesQuantity'] = $dishesQuantity;
        $staff = $_SESSION['name'].'('.$_SESSION['accountType'].')';
        $userLinkId = $_SESSION['userLinkId'];
        $ordersLinkId = uniqid();
        $query1 = "insert into order_tb(proofOfPayment, userlinkId, status, ordersLinkId, date, totalOrder, staffInCharge) values('null','$userLinkId','prepairing','$ordersLinkId','$todayWithTime','$total', '$staff')";
        for($i=0; $i<count($dishesArr); $i++){
            $query2 = "insert into ordersDetail_tb(orderslinkId, quantity, orderType) values('$ordersLinkId',$dishesQuantity[$i], $orderType[$i])";
            Query($query2);
        }
        if(Query($query1)){
            echo '<script>alert("Sucess Placing Order!");</script>';       
        }
        echo "<script>window.open('receipt.php', '_blank');</script>";
        echo "<script>window.location.replace('adminPos.php');</script>";
    }
?>

<!-- 
        add pdf page and size
        //AddPage [P(PORTRAIT),L(LANDSCAPE)],FORMAT(A4-A5-ETC)
        // $obj_pdf->AddPage('P','A5');
        you can see all possible values in this file: tcpdf/include/tcpdf_static.php
 -->
