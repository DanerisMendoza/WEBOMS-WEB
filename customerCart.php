<?php 
    $page = 'customer';
    include('method/checkIfAccountLoggedIn.php');
    include('method/query.php');
    date_default_timezone_set('Asia/Manila');
    $date = new DateTime();
    $today =  $date->format('Y-m-d'); 
    $todayWithTime =  $date->format('Y-m-d H:i:s'); 
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    $_SESSION['multiArr'] = array();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Costumer Menu - View Cart</title>
        
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script> 
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css"> 
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script> 
   
</head>
<body class="bg-light">    

<div class="container text-center mt-5">
    <div class="row justify-content-center">
        <button class="btn btn-lg btn-dark col-6 mb-3" id="back">Back</button>
        <button class="btn btn-lg btn-dark col-6 mb-3" id="home">Home</button>
        <input id="dateTime" type="datetime-local" class="form-control form-control-lg mb-4" name="date" min="<?php echo $todayWithTime;?>" value="<?php echo $todayWithTime;?>"/>
        
        <div class="table-responsive col-lg-12 mb-5">
            <table id="tbl" class="table table-striped table-bordered col-lg-12 mb-4">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">DISH</th>
                        <th scope="col">QUANTITY</th>
                        <th scope="col">PRICE</th>
                        <th scope="col" colspan="1">Option</th>
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
                        <td><?php echo $arr['dish'];?></td>
                        <td><?php echo $arr['quantity'];?></td>
                        <td><?php echo '₱'.number_format($arr['price'],2);?></td>
                        <td>
                            <!-- check stock -->
                            <?php if(getQueryOneVal("select stock from WEBOMS_menu_tb where dish = '$arr[dish]' ",'stock') > 0) { ?>
                            <a class="btn btn-success border-dark" href="?add=<?php echo $arr['dish'].','.($arr['price']/$arr['quantity']).','.$arr['orderType']; ?>">+</a>
                            <?php }else{ ?>
                            <a class="btn btn-success border-dark">Out of Stock</a>
                            <?php } ?> 
                            <a class="btn btn-success border-dark" href="?minus=<?php echo $arr['dish'].','.($arr['price']/$arr['quantity']).','.$arr['orderType']; ?>">-</a>
                        </td>
                    </tr>
                    <?php }?>
                    <tr>
                        <td colspan="2"><b>TOTAL AMOUNT:</b></td>
                        <td><b>₱<?php echo number_format($total,2); ?></b></td>
                    </tr>
                </table> 
                <!-- place order -->
                <form method="post">           
                    <button id="orderBtn" class="btn btn-lg btn-success col-12 mb-3" name="order">Place Order</button>
                </form>
                <!-- clear order -->
                <form method="post">
                    <button type="submit" class="btn btn-lg btn-danger col-12 mb-3" name="clear">Clear Order</button>
                </form>
                <script>document.getElementById("dateTime").disabled = true;</script>
            </div>
        </div>
        </div>
    
</body>
</html>

<script>
document.getElementById("home").onclick = function () {window.location.replace('customer.php'); }; 
document.getElementById("back").onclick = function () {window.location.replace('customerMenu.php'); }; 
</script> 
<?php
    $query = "SELECT balance FROM `WEBOMS_userInfo_tb` where user_id = '$_SESSION[user_id]' ";
    $balance = getQueryOneVal($query,'balance');
    $balance = $balance == null ? 0 : $balance;

    //clear button
    if(isset($_POST['clear'])){
        for($i=0; $i<count($dishesArr); $i++){ 
            $updateQuery = "UPDATE WEBOMS_menu_tb SET stock = (stock + '$dishesQuantity[$i]') WHERE dish= '$dishesArr[$i]' ";    
            Query($updateQuery);    
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

        $updateQuery = "UPDATE WEBOMS_menu_tb SET stock = (stock - 1) WHERE dish= '$dish' ";    
        if(Query($updateQuery))
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

        $updateQuery = "UPDATE WEBOMS_menu_tb SET stock = (stock + 1) WHERE dish= '$dish' ";    
        if(Query($updateQuery))
            echo "<script>window.location.replace('customerCart.php');</script>";    
    }

    
    //order button
    if(isset($_POST['order'])){
        if($total != 0 && $balance >= $total){
            $user_id = $_SESSION['user_id'];
            $query = "SELECT email FROM `WEBOMS_userInfo_tb` WHERE user_id = '$user_id' ";
            $email = getQueryOneVal($query,'email');
            $order_id = uniqid();
            $total = number_format($total,2);
            $name = $_SESSION['name'];

            $query1 = "insert into WEBOMS_order_tb( user_id, status, order_id, date, totalOrder, payment, staffInCharge) values('$user_id','prepairing','$order_id','$todayWithTime','$total','$total', 'online order')";
            for($i=0; $i<count($dishesArr); $i++){
                $query2 = "insert into WEBOMS_ordersDetail_tb(order_id, quantity, orderType) values('$order_id',$dishesQuantity[$i], $orderType[$i])";
                Query($query2);
            }

            if(Query($query1)){
                //minus order amount to balance
                $query = "UPDATE WEBOMS_userInfo_tb SET balance = (balance - '$total') where user_id = '$user_id' ";     
                Query($query);
                //send receipt to email
                require_once('TCPDF-main/tcpdf.php'); 
                $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
                $pdf->SetCreator(PDF_CREATOR);  
                $pdf->SetTitle("Receipt");  
                $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
                $pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);  
                $pdf->setPrintHeader(false);  
                $pdf->setPrintFooter(false);  
                $pdf->SetAutoPageBreak(TRUE, 10);  
                $pdf->SetFont('dejavusans', '', 11);  
                $pdf->AddPage('P','A4');
                date_default_timezone_set('Asia/Manila');
                $date = date("j-m-Y  h:i:s A"); 
                $pdf -> Cell(60,10,"$date",'0','C');
                $pdf -> ln(10);
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
                $pdf -> ln(10);
                $pdf->SetFont('dejavusans', '', 18);  
                $pdf -> Cell(183,10,"Order#$order_id",'','0','C');
                $pdf -> ln(20);
                $pdf->SetFont('dejavusans', '', 11);  
                $pdf -> Cell(122,10,"Customer: $name",'','0','L');
                $pdf -> ln(10);
                $pdf -> Cell(122,10,"Order Type: Online Order",'','0','L');
                ob_end_clean();
                $attachment = $pdf->Output('receipt.pdf', 'S');
                //Load Composer's autoloader
                require 'vendor/autoload.php';
                //Create an instance; passing `true` enables exceptions
                $mail = new PHPMailer(true);
                //Server settings
                $mail->SMTPDebug  = SMTP::DEBUG_OFF;                        //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host = 'mail.ucc-csd-bscs.com';		                  //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'weboms@ucc-csd-bscs.com';              //from //SMTP username
                $mail->Password   = '-Dxru8*6v]z4';                         //SMTP password
                $mail->SMTPSecure = 'ssl';                                  //Enable implicit TLS encryption
                $mail->Port       =  465;          //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom('weboms098@gmail.com', 'webBasedOrdering');
                $mail->addAddress("$email");                                //sent to
                //Content
                $mail->Subject = 'Receipt';
                $mail->Body    = 'Thank you for ordering!';
                $mail->AddStringAttachment($attachment, 'receipt.pdf', 'base64', 'application/pdf');
                $mail->send();   
                $_SESSION["dishes"] = array();
                $_SESSION["price"] = array();      
                $_SESSION["orderType"] = array();                     
                echo '<script>alert("Sucess Placing Order!");</script>';    
                echo "<script>window.location.replace('customerCart.php')</script>";          
            }
        }  
    }
?>

<script>
    //order button (js)
    document.getElementById("orderBtn").addEventListener("click", () => {
        if(<?php echo $total == 0 ? 'true':'false';?>){
            alert('Please place your order!');
            return;
        }
        if(<?php echo $balance < $total ? 'true':'false';?>){
            alert('Your balance is less than your total order amount!');
            return;
        }
    });
</script>