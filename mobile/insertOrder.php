<?php 
    include('../method/query.php');
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    if(isset($_POST['post']) && $_POST['post'] == 'webomsMobile') {
        $user_id = $_POST['user_id'];
        $dishesArr = $_POST['dishesArr'];
        $priceArr  = $_POST['priceArr'];
        $orderType = $_POST['orderType'];
        $dishesQuantity = $_POST['dishesQuantity'];
        $total = $_POST['total'];

        // $query = "INSERT INTO `try`(`user_id`, `dishesArr`, `priceArr`, `orderType`, `dishesQuantity`, `total`) 
        // VALUES ('$user_id','$dishesArr','$priceArr','$orderType','$dishesQuantity','$total')";
        // Query2($query);
        

        $dishesArr = explode(",",$dishesArr);
        $priceArr  = explode(",",$priceArr );
        $orderType = explode(",",$orderType);
        $dishesQuantity = explode(",",$dishesQuantity);

        $query = "SELECT email FROM `weboms_userInfo_tb` WHERE user_id = '$user_id' ";
        $email = getQueryOneVal2($query,'email');
        $query = "SELECT name FROM `weboms_userInfo_tb` WHERE user_id = '$user_id' ";
        $name = getQueryOneVal2($query,'name');
        date_default_timezone_set('Asia/Manila');
        $date = new DateTime();
        $today =  $date->format('Y-m-d'); 
        $todayWithTime =  $date->format('Y-m-d H:i:s'); 

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

        // insert order
        $query1 = "insert into weboms_order_tb( user_id, order_id, or_number, status, date, totalOrder, payment, staffInCharge) values('$user_id', '$order_id', '$or_number', 'preparing', '$todayWithTime','$total','$total', 'online order')";
        Query2($query1);
        // insert order details
        for($i=0; $i<count($dishesArr); $i++){
            $query2 = "insert into weboms_ordersDetail_tb(order_id, quantity, orderType) values('$order_id',$dishesQuantity[$i], $orderType[$i])";
            Query2($query2);
        }
        //update stock
        for($i=0; $i<count($dishesArr); $i++){
            $query3 = "UPDATE weboms_menu_tb SET stock = (stock - $dishesQuantity[$i]) WHERE dish= '$dishesArr[$i]' ";    
            Query2($query3);
        }
        // update balance
        $query4 = "UPDATE weboms_userInfo_tb SET balance = (balance - '$total') where user_id = '$user_id' ";     
        Query2($query4);

        //send receipt to email
        $total = number_format($total,2);
        require_once('../TCPDF-main/tcpdf.php'); 

        // pdf process 
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
        $pdf->SetCreator(PDF_CREATOR);  
        $pdf->SetTitle("Receipt");  
        // $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
        $pdf->SetMargins(1,1,1);  
        $pdf->setPrintHeader(false);  
        $pdf->setPrintFooter(false);  
        $pdf->SetAutoPageBreak(TRUE, 1);  

        $pdf->AddPage('P','A6');
        date_default_timezone_set('Asia/Manila');
        $date = date("j-m-Y  h:i:s A"); 
        $pdf->SetFont('freemono', 'B', 25);  
        $pdf -> Cell(0,15,"Order#$order_id",0,1,'C');

        $pdf->SetFont('freemono', '', 13);  
        $pdf -> Cell(0,5,"$companyName",0,1,'C');
        $pdf -> Cell(0,5,"$companyAddress",0,1,'C');
        $pdf -> Cell(0,5,"$companyTel",0,1,'C');
        $pdf -> ln(3);
        $pdf -> Cell(0,5,"$date",0,1,'C');
        $pdf -> ln(5);

        $pdf -> Cell(20,10,"QTY",'B,T',0,'C');
        $pdf -> Cell(40,10,"DISH",'B,T',0,'L');
        $pdf -> Cell(0,10,"PRICE",'B,T',0,'R');
        $pdf -> ln(10);
        for($i=0; $i<count($dishesArr); $i++){ 
            $price = number_format($priceArr[$i],2);
            $pdf -> Cell(20,10,"$dishesQuantity[$i]",'',0,'C');
            $pdf -> Cell(40,10,"$dishesArr[$i]",'',0,'L');
            $pdf -> Cell(0,10,"$price",'',0,'R');
            $pdf -> ln(5);
        }
        $pdf -> ln(8);

        $pdf->SetFont('freemono', 'B', 18);
        $pdf -> Cell(70,10,"TOTAL",'T','0','L');
        $pdf -> Cell(33,10,"₱$total",'T','0','R');
        $pdf -> ln(7);
        $pdf->SetFont('freemono', '', 13);
        $pdf -> Cell(70,10,"PAYMENT",'','0','L');
        $pdf -> Cell(33,10,"₱$total",'','0','R');
        $pdf -> ln(13);

        $pdf -> Cell(0,10,"CUSTOMER: $name",'T','0','L');
        $pdf -> ln(5);
        $pdf -> Cell(0,10,"ORDER TYPE: ONLINE ORDER",'','0','L');
        $pdf -> ln(5);
        $pdf -> Cell(122,10,"ORDER NO.: $or_number",'','0','L');
        $pdf -> ln(12);

        // $pdf->SetFont('dejavusans', '', 13);
        $pdf -> Cell(0,10,"Thank you. Please come again.", 'B,T', 1,'C');

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
    }
?>