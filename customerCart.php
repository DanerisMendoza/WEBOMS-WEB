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
   
</head>
<body class="bg-light">    

<div class="container text-center mt-5">
    <div class="row justify-content-center">
        <button class="btn btn-lg btn-dark col-6 mb-3" id="back">Back</button>
        <button class="btn btn-lg btn-dark col-6 mb-3" id="home">Home</button>
        <input id="dateTime" type="datetime-local" class="form-control form-control-lg mb-4" name="date" min="<?php echo $todayWithTime;?>" value="<?php echo $todayWithTime;?>"/>
        
        <div class="table-responsive col-lg-12 mb-5">
            <table class="table table-striped table-bordered col-lg-12 mb-4">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">QUANTITY</th>
                        <th scope="col">DISH</th>
                        <th scope="col">PRICE</th>
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
                    <button id="orderBtn" class="btn btn-lg btn-success col-12 mb-3" name="order">Place Order</button>
                </form>
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
        echo "<script>window.location.replace('customerCart.php');</script>";
    }

    
    //order button
    if(isset($_POST['order'])){
        if($total != 0 && $balance >= $total){
            $user_id = $_SESSION['user_id'];
            $query = "SELECT email FROM `WEBOMS_userInfo_tb` WHERE user_id = '$user_id' ";
            $email = getQueryOneVal($query,'email');
            $order_id = uniqid();

            $query1 = "insert into WEBOMS_order_tb( user_id, status, order_id, date, totalOrder, staffInCharge) values('$user_id','prepairing','$order_id','$todayWithTime','$total', 'online order')";
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
                $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
                $obj_pdf->SetCreator(PDF_CREATOR);  
                $obj_pdf->SetTitle("Receipt");  
                $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
                $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);  
                $obj_pdf->setPrintHeader(false);  
                $obj_pdf->setPrintFooter(false);  
                $obj_pdf->SetAutoPageBreak(TRUE, 10);  
                $obj_pdf->SetFont('dejavusans', '', 11);  
                $obj_pdf->AddPage('P','A6');
                date_default_timezone_set('Asia/Manila');
                $date = date("j-m-Y  h:i:s A"); 
                $content = '
                <h3>Order Date:</h3>
                <h3>'.$date.'</h3>
                <table  text-center cellspacing="0" cellpadding="3">  
                    <tr>
                        <th scope="col">Quantity</th>
                        <th scope="col">Dish</th>
                        <th scope="col">Price</th>
                    </tr>
                    ';  
                    for($i=0; $i<count($dishesArr); $i++){ 
                    $content .= "
                    <tr>  
                    <td>$dishesQuantity[$i]</td>
                    <td>$dishesArr[$i]</td>
                    <td>₱$priceArr[$i]</td>
                    </tr>
                    ";
                    }
                    $content .= "   
                    <tr><td></td></tr>
                    <tr><td></td></tr>
                    <tr>
                        <td></td>
                        <td>Payment</td>
                        <td>₱$total</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Total</td>
                        <td>₱$total</td>
                    </tr>
                    <tr><td></td></tr>
                    <tr><td></td></tr>
                    <h2>Order#$order_id</h1>
                </table>
                <style>
                h3{text-align: center;}
                </style>";
                ob_end_clean();
                $obj_pdf->writeHTML($content); 
                $attachment = $obj_pdf->Output('file.pdf', 'S');
                //Load Composer's autoloader
                require 'vendor/autoload.php';
                //Create an instance; passing `true` enables exceptions
                $mail = new PHPMailer(true);
                //Server settings
                $mail->SMTPDebug  = SMTP::DEBUG_OFF;
                //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                     //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'weboms098@gmail.com'; //from //SMTP username
                $mail->Password   = 'pcqezwnqunxuvzth';                     //SMTP password
                $mail->SMTPSecure =  PHPMailer::ENCRYPTION_SMTPS;           //Enable implicit TLS encryption
                $mail->Port       =  465;                                   //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

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
        if(<?php echo $_SESSION['balance'] < $total ? 'true':'false';?>){
            alert('Your balance is less than your total order amount!');
            return;
        }
    });
</script>