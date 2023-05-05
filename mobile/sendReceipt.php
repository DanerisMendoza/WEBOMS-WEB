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
        // send receipt to email
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
        ob_end_clean();
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