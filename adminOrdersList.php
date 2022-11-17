<?php 
  session_start();
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Orders</title>
        
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
  <link rel="stylesheet" type="text/css" href="css/style.css">
    
</head>
<body class="bg-light">

<div class="container text-center">
  <div class="row justify-content-center">
    <h1 class="font-weight-normal mt-5 mb-4">Orders</h1>
    <button class="btn btn-lg btn-danger col-12 mb-4" id="admin">Admin</button>
        <script>document.getElementById("admin").onclick = function () {window.location.replace('admin.php'); }</script> 
    <div class="table-responsive col-lg-12">
    <?php
      include('method/Query.php');
      if($_SESSION['query'] != 'all'){
        $query = "select customer_tb.*, order_tb.* from customer_tb, order_tb where customer_tb.userlinkId = order_tb.userlinkId && order_tb.isOrdersComplete = 0 ORDER BY order_tb.id asc; ";
        $resultSet =  getQuery($query);
      }
      else{
        $query = "select customer_tb.*, order_tb.* from customer_tb, order_tb where customer_tb.userlinkId = order_tb.userlinkId  ORDER BY order_tb.id asc; ";
        $resultSet =  getQuery($query);
      }
      if($resultSet != null){ ?>
          <table class="table table-striped table-bordered col-lg-12">
          <thead class="table-dark">
            <tr>	
              <th scope="col">NAME</th>
              <th scope="col">ORDERS ID</th>
              <th scope="col"></th>
              <th scope="col"></th>
              <th scope="col">APPROVE STATUS:</th>
              <th scope="col">ORDER COMPLETE STATUS</th>
              <th scope="col">ORDER STATUS:
                <form method="post">
                  <button class="btn btn-light border-dark" type="submit" name="showAll">SHOW/HIDE ALL</button>
                </form>
                </th>
              <th scope="col">DATE & TIME</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($resultSet as $rows){?>
            <tr>	   
              <td><?php echo $rows['name']; ?></td>
              <td><?php echo $rows['ordersLinkId'];?></td>
              <td colspan="2"><a class="btn btn-light border-dark" href="adminOrders.php?idAndPic=<?php echo $rows['ordersLinkId'].','.$rows['proofOfPayment']?>">View Order</a></td>
              <td>
                <?php 
                  if($rows['status'] == 1){
                    echo "Already Approved";
                  }
                  else{
                    ?><a class="btn btn-primary border-dark" href="?status=<?php echo $rows['ordersLinkId'].','.$rows['email']; ?>">Approve</a><?php
                  }?>
              </td>
              <td>
                <?php 
                  if($rows['status'] != 1){
                    echo "waiting for approval";
                  }
                  elseif($rows['isOrdersComplete'] == 1){
                    echo "order is complete";
                  }
                  else{
                    ?> <a class="btn btn-success border-dark" href="?orderComplete=<?php echo $rows['ordersLinkId'] ?>">Order Complete</a><?php
                  }?>  
              </td>
              <td>
              <?php
                if($rows['isOrdersComplete'] == 0 && $rows['status'] == 0){
                  echo "Pending";
                }
                elseif($rows['isOrdersComplete'] == 0){
                  echo "Preparing";
                }
                else{
                  echo "Order Complete";
                }
              ?></td>
              <td><?php echo date('m/d/Y h:i a ', strtotime($rows['date'])); ?></td>
              <td><a class="btn btn-danger border-dark" href="method/deleteOrderMethod.php?idAndPicnameDelete=<?php echo $rows['ID'].','.$rows['proofOfPayment'].','.$rows['ordersLinkId'] ?>">Delete</a></td>
            </tr><?php } ?>
          </tbody>   
        </table>
      <?php } ?>
    </div>
	</div>
</div>
    
</body>
</html>

<?php 
  //button to approve order
    if(isset($_GET['status'])){
      $arr = explode(',',$_GET['status']);  
      $ordersLinkId = $arr[0];
      $email = $arr[1];
      //compute order
        $query ="select menu_tb.*, ordersDetail_tb.* from menu_tb inner join ordersDetail_tb where menu_tb.orderType = ordersDetail_tb.orderType and ordersDetail_tb.ordersLinkId = '$ordersLinkId' ";  
        $resultSet = getQuery($query);
        if ($resultSet != null) {  
            $dishesArr = $priceArr = $dishesQuantity = array();
            $total =0;
            foreach($resultSet as $rows){ 
                $price = ($rows['price']*$rows['quantity']);  
                array_push($dishesArr,$rows['dish']);
                array_push($priceArr,$rows['price']);
                array_push($dishesQuantity,$rows['quantity']);
                $total += $price;
            }
        }

      //send receipt to email
        require_once('TCPDF-main/tcpdf.php'); 
        $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
        $obj_pdf->SetCreator(PDF_CREATOR);  
        $obj_pdf->SetTitle("Generate HTML Table Data To PDF From MySQL Database Using TCPDF In PHP");  
        $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
        $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
        $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
        $obj_pdf->SetDefaultMonospacedFont('helvetica');  
        $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
        $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);  
        $obj_pdf->setPrintHeader(false);  
        $obj_pdf->setPrintFooter(false);  
        $obj_pdf->SetAutoPageBreak(TRUE, 10);  
        $obj_pdf->SetFont('dejavusans', '', 11);  
        $obj_pdf->AddPage(); 
        date_default_timezone_set('Asia/Manila');
        $date = date("j-m-Y  h:i:s A"); 
        $content = '
        <h3>Approve Date: '.$date.'</h3>
        <h3>*******************************************************************</h3>
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
        <br><br>
        <br><br>
        <tr>
            <td></td>
            <td>Total</td>
            <td>₱$total</td>
        </tr>
        
        <style>
        h3 {text-align: center;}
        table,table td {
            border: 1px solid #cccccc;
        }

        td,table{
            text-align: center;
        }
        </style>
        ";
        $obj_pdf->writeHTML($content);  
        // ob_end_clean();
        $attachment = $obj_pdf->Output('file.pdf', 'S');
        require 'vendor/autoload.php';
        $mail = new PHPMailer(true);
        $mail->SMTPDebug  = SMTP::DEBUG_OFF;
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                     //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'webBasedOrdering098@gmail.com'; //from //SMTP username
        $mail->Password   = 'cgzyificorxxdlau';                     //SMTP password
        $mail->SMTPSecure =  PHPMailer::ENCRYPTION_SMTPS;           //Enable implicit TLS encryption
        $mail->Port       =  465;                                   //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('webBasedOrdering098@gmail.com', 'webBasedOrdering');
        $mail->addAddress("{$email}");             //sent to

        //Content
        $mail->Subject = 'Receipt';
        $mail->Body    = ' ';
        $mail->AddStringAttachment($attachment, 'filename.pdf', 'base64', 'application/pdf');
        $mail->send();

      //approve
        $query = "UPDATE order_tb SET status=1 WHERE ordersLinkId='$ordersLinkId' ";     
        if(Query($query))
          echo "<script>alert('Approve Success'); window.location.replace('adminOrdersList.php');</script>";


    }
  //button to make transaction complete
    if(isset($_GET['orderComplete'])){
      $id = $_GET['orderComplete'];
      $query = "UPDATE order_tb SET isOrdersComplete=true WHERE ordersLinkId='$id' ";     
      if(Query($query))
        echo "<SCRIPT>  window.location.replace('adminOrdersList.php'); alert('success!');</SCRIPT>";
    }
  //button to show even completed order or show pending orders only
    if(isset($_POST['showAll'])){
      if($_SESSION['query'] == 'all')
        $_SESSION['query'] = null;
      else
        $_SESSION['query'] = 'all';
      echo "<script>window.location.replace('adminOrdersList.php');</script>";
    }
?>