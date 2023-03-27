<?php 
    $page = 'customer';
    include('../method/checkIfAccountLoggedIn.php');
    include('../method/query.php');
    date_default_timezone_set('Asia/Manila');
    $date = new DateTime();
    $today =  $date->format('Y-m-d'); 
    $todayWithTime =  $date->format('Y-m-d H:i:s'); 
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    $_SESSION['multiArr'] = array();
    $companyName = getQueryOneVal2('select name from weboms_company_tb','name');
?>  

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cart</title>

    <link rel="stylesheet" type="text/css" href="../css/bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/customer.css">
    <script type="text/javascript" src="../js/jquery-3.6.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
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
                        <a class="nav-link text-dark" href="#" id="customer"><i class="bi bi-house-door"></i> HOME</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link text-dark" href="#" id="customerProfile"><i class="bi bi-person-circle"></i> PROFILE</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link text-danger" href="#"><i class="bi bi-book"></i> MENU</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link text-dark" href="#" id="topUp"><i class="bi bi-cash-stack"></i> TOP-UP</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link text-dark" href="#" id="customerOrder_details"><i class="bi bi-list"></i> VIEW ORDERS</a>
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
            <button class="btn btn-lg btn-dark col-12 mb-4" id="back"><i class="bi bi-arrow-left-short"></i> Back</button>
            <!-- <input id="dateTime" type="datetime-local" class="form-control form-control-lg mb-4 bg-light text-center" name="date" min="<?php //echo $todayWithTime;?>" value="<?php //echo $todayWithTime;?>" /> -->
            <h1 class="form-control form-control-lg mb-4 bg-light text-center"><?php echo $todayWithTime; ?></h1>

            <!-- table -->
            <div class="table-responsive col-lg-12">
                <table id="tbl" class="table table-hover table-bordered col-lg-12 mb-4">
                    <thead>
                        <tr>
                            <th scope="col">DISH</th>
                            <th scope="col" colspan="2">QUANTITY</th>
                            <th scope="col">PRICE</th>
                        </tr>
                    </thead>
                    <?php 
                    $dishesArr = array();
                    $priceArr = array();
                    $dishesQuantity = array();
                    $orderType = array();
      
                    //merge repeating order into 1 
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
                    //push order quantity into arrray
                    foreach(array_count_values($_SESSION['dishes']) as $count){
                        array_push($dishesQuantity,$count);
                    }
                    
                    //merge 3 array into 1 multi dimensional
                    for($i=0; $i<count($dishesArr); $i++){ 
                        $arr = array('dish'=> $dishesArr[$i], 'price' => $priceArr[$i], 'quantity' => $dishesQuantity[$i], 'orderType' => $orderType[$i]);
                        array_push($_SESSION['multiArr'],$arr);
                    }
                    //sort multi dimensional
                    sort($_SESSION['multiArr']);
                    $total = 0;
                    for($i=0; $i<count($priceArr); $i++){
                        $total += $priceArr[$i];
                    }

                    //create a table using the multi dimensional array
                    foreach($_SESSION['multiArr'] as $arr){ ?>
                    <tr>
                        <td><?php echo ucwords($arr['dish']);?></td>
                        <td><?php echo $arr['quantity'];?></td>
                        <td>
                            <!-- check stock -->
                            <?php if(getQueryOneVal2("select stock from weboms_menu_tb where dish = '$arr[dish]' ",'stock') > 0) { ?>
                            <a class="btn btn-success" href="?add=<?php echo $arr['dish'].','.($arr['price']/$arr['quantity']).','.$arr['orderType']; ?>"><i class="bi bi-plus"></i></a>
                            <?php }else{ ?>
                            <a class="btn text-danger">OUT OF STOCK</a>
                            <?php } ?>
                            <a class="btn btn-danger" href="?minus=<?php echo $arr['dish'].','.($arr['price']/$arr['quantity']).','.$arr['orderType']; ?>"><i class="bi bi-dash"></i></a>
                        </td>
                        <td><?php echo '₱'. number_format($arr['price'],2);?></td>
                    </tr>
                    <?php }?>
                    <tr>
                        <td colspan="3"><b>Total Amount:</b></td>
                        <td><b>₱<?php echo number_format($total,2); ?></b></td>
                    </tr>
                </table>
                <form method="post">
                    <!-- place order -->
                    <button id="orderBtn" class="btn btn-lg btn-success col-12 mb-3" name="order">Place Order</button>
                <!-- </form>
                <form method="post"> -->
                    <!-- clear order -->
                    <button type="submit" class="btn btn-lg btn-danger col-12" name="clear">Clear Order</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>

<script>
    document.getElementById("dateTime").disabled = true;
</script>

<script>
document.getElementById("back").onclick = function() { window.location.replace('customerMenu.php'); };
</script>

<?php
    $query = "SELECT balance FROM `weboms_userInfo_tb` where user_id = '$_SESSION[user_id]' ";
    $balance = getQueryOneVal2($query,'balance');
    $balance = $balance == null ? 0 : $balance;

    //clear button
    if(isset($_POST['clear'])){
        for($i=0; $i<count($dishesArr); $i++){ 
            $updateQuery = "UPDATE weboms_menu_tb SET stock = (stock + '$dishesQuantity[$i]') WHERE dish= '$dishesArr[$i]' ";    
            Query2($updateQuery);    
        }
        $_SESSION["dishes"] = array();
        $_SESSION["price"] = array();
        $_SESSION["orderType"] = array(); 
        $_SESSION['multiArr'] = array();
        echo "<script>window.location.replace('customerCart.php');</script>";
    }

    //add
    if(isset($_GET['add'])){
        $arr = explode(',',$_GET['add']);
        $dish = $arr[0];
        $price = $arr[1];
		$orderType = $arr[2];
        array_push($_SESSION['dishes'], $dish);
        array_push($_SESSION['price'], $price);
        array_push($_SESSION['orderType'], $orderType);

        $updateQuery = "UPDATE weboms_menu_tb SET stock = (stock - 1) WHERE dish= '$dish' ";    
        if(Query2($updateQuery))
          echo "<script>window.location.replace('customerCart.php');</script>";    
    }

    //minus
    if(isset($_GET['minus'])){
        $arr = explode(',',$_GET['minus']);
        $dish = $arr[0];
        $price = $arr[1];
        $orderType = $arr[2];
       
        //remove one order 
        $key = array_search($dish, $_SESSION['dishes']);
        unset($_SESSION['dishes'][$key]);
        unset($_SESSION['price'][$key]);
        unset($_SESSION['orderType'][$key]);

        //refresh the array
        $_SESSION['dishes'] = array_values($_SESSION['dishes']);
        $_SESSION['price'] = array_values($_SESSION['price']);
        $_SESSION['orderType'] = array_values($_SESSION['orderType']);

        $updateQuery = "UPDATE weboms_menu_tb SET stock = (stock + 1) WHERE dish= '$dish' ";    
        if(Query2($updateQuery))
            echo "<script>window.location.replace('customerCart.php');</script>";    
    }

    
    //order button
    if(isset($_POST['order'])){
        if($total != 0 && $balance >= $total){
        
            $user_id = $_SESSION['user_id'];
            $query = "SELECT email FROM `weboms_userInfo_tb` WHERE user_id = '$user_id' ";
            $email = getQueryOneVal2($query,'email');
            $name = $_SESSION['name'];
            //company variables init
            $query = "select * from weboms_company_tb";
            $resultSet = getQuery2($query);
            if($resultSet!=null)
                foreach($resultSet as $row){
                $companyName = $row['name'];
                $companyAddress = $row['address'];
                $companyTel = $row['tel'];
            }

            //or number process
            $or_last = getQueryOneVal2("select or_number from weboms_order_tb WHERE id = (SELECT MAX(ID) from weboms_order_tb)","or_number");
            if($or_last == null){
                $or_last = 1;
            }
            else{
                $or_last = $or_last + 1;
            }
            $inputSize = strlen(strval($or_last));
            if($inputSize > 4)
                $str_length = $inputSize;
            else
                $str_length = 4;
            $temp = substr("0000{$or_last}", -$str_length);
            $or_number = $temp;

            //increment order id
            $lastOrderId = getQueryOneVal2("select order_id from weboms_order_tb WHERE order_id = (SELECT MAX(order_id) from weboms_order_tb)","order_id");
            if($lastOrderId == null){
                $lastOrderId = rand(1111,9999);
            }
            else{
                $lastOrderId = $lastOrderId + 1;
            }
            $order_id = $lastOrderId;


            $query1 = "insert into weboms_order_tb( user_id, order_id, or_number, status, date, totalOrder, payment, staffInCharge) values('$user_id', '$order_id', '$or_number', 'prepairing', '$todayWithTime','$total','$total', 'online order')";
            for($i=0; $i<count($dishesArr); $i++){
                $query2 = "insert into weboms_ordersDetail_tb(order_id, quantity, orderType) values('$order_id',$dishesQuantity[$i], $orderType[$i])";
                Query2($query2);
            }

            if(Query2($query1)){
                //minus order amount to balance
                $query = "UPDATE weboms_userInfo_tb SET balance = (balance - '$total') where user_id = '$user_id' ";     
                Query2($query);
                //send receipt to email
                $total = number_format($total,2);
                require_once('../TCPDF-main/tcpdf.php'); 
                $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
                $pdf->SetCreator(PDF_CREATOR);  
                $pdf->SetTitle("Receipt");  
                $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
                $pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);  
                $pdf->setPrintHeader(false);  
                $pdf->setPrintFooter(false);  
                $pdf->SetAutoPageBreak(TRUE, 10);  
                $pdf->AddPage('P','A4');
                date_default_timezone_set('Asia/Manila');
                $date = date("j-m-Y  h:i:s A"); 
                $pdf->SetFont('dejavusans', '', 28);  
                $pdf -> Cell(183,10,"Order#$order_id",'B','0','C');
                $pdf->SetFont('dejavusans', '', 11);  
                $pdf -> ln(15);
                $pdf -> Cell(183,10,"$companyName",'','0','C');
                $pdf -> ln(8);
                $pdf -> Cell(183,10,"$companyAddress",'','0','C');
                $pdf -> ln(8);
                $pdf -> Cell(183,10,"$companyTel",'','0','C');
                $pdf -> ln(8);
                $pdf -> Cell(183,10,"$date",'','0','C');
                $pdf -> ln(15);
                $pdf -> Cell(61,10,"Dish",'B,T','0','C');
                $pdf -> Cell(61,10,"Quantity",'B,T','0','C');
                $pdf -> Cell(61,10,"Price",'B,T','0','C');
                $pdf -> ln(20);
                for($i=0; $i<count($dishesArr); $i++){ 
                    $price = number_format($priceArr[$i],2);
                    $pdf -> Cell(61,10,"$dishesArr[$i]",'','0','C');
                    $pdf -> Cell(61,10,"$dishesQuantity[$i]",'','0','C');
                    $pdf -> Cell(61,10,"₱$price",'','0','C');
                    $pdf -> ln(10);
                }
                $pdf -> ln(10);
                $pdf -> Cell(122,10,"Payment",'T','0','L');
                $pdf -> Cell(61,10,"₱$total",'T','0','C');
                $pdf -> ln(10);
                $pdf -> Cell(122,10,"Total",'','0','L');
                $pdf -> Cell(61,10,"₱$total",'','0','C');
                $pdf -> ln(15);
                $pdf->SetFont('dejavusans', '', 11);  
                $pdf -> Cell(122,10,"Customer: $name",'','0','L');
                $pdf -> ln(10);
                $pdf -> Cell(122,10,"Order Type: Online Order",'','0','L');
                $pdf -> ln(10);
                $pdf -> Cell(122,10,"Order Number: $or_number",'','0','L');
                // ob_end_clean();
                $attachment = $pdf->Output('receipt.pdf', 'S');
                //Load Composer's autoloader
                require '../vendor/autoload.php';
                //Create an instance; passing `true` enables exceptions
                $mail = new PHPMailer(true);
                //Server settings
                $mail->SMTPDebug  = SMTP::DEBUG_OFF;                        //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                include_once('../general/mailerConfig.php');
                $mail->SMTPSecure = 'ssl';                                  //Enable implicit TLS encryption
                $mail->Port       =  465;    
                //Recipients
                $mail->setFrom('weboms098@gmail.com', 'webBasedOrdering');
                $mail->addAddress("$email");                                //sent to
                //Content
                $mail->Subject = 'Receipt';
                $mail->Body    = 'Thank you for ordering!';
                $mail->AddStringAttachment($attachment, 'receipt.pdf', 'base64', 'application/pdf');
                $mail -> send();
                if($mail->send())
                    echo '<script>alert("Success placing order!");</script>';    
                else
                    echo '<script>alert("Error placing order!");</script>';    
                $_SESSION["dishes"] = array();
                $_SESSION["price"] = array();      
                $_SESSION["orderType"] = array();                     
                echo "<script>window.location.replace('customerCart.php')</script>";          
            }
        }  
    }
?>

<script>
//order button (js)
document.getElementById("orderBtn").addEventListener("click", () => {
    if (<?php echo $total == 0 ? 'true':'false';?>) {
        alert('Please place your order!');
        return;
    }
    if (<?php echo $balance < $total ? 'true':'false';?>) {
        alert('Your balance is less than your total order amount!');
        return;
    }
});
</script>

<script>
document.getElementById("customer").onclick = function() { window.location.replace('customer.php'); };
document.getElementById("customerProfile").onclick = function() { window.location.replace('customerProfile.php'); };
document.getElementById("topUp").onclick = function() { window.location.replace('customerTopUp.php'); };
document.getElementById("customerOrder_details").onclick = function() { window.location.replace('customerOrders.php'); };
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