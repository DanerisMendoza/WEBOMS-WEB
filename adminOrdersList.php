<?php 
  $page = 'admin';
  include('method/checkIfAccountLoggedIn.php');
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
  <title>Admin Orders</title>

  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
  <link rel="stylesheet" type="text/css" href="css/style.css">
    
</head>
<body class="bg-light">

<div class="container text-center mt-5">
  <div class="row justify-content-center">
    <!-- <h1 class="font-weight-normal mt-5 mb-4">Orders</h1> -->
    <button class="btn btn-lg btn-dark col-12 mb-3" id="admin">Admin</button>
        <script>document.getElementById("admin").onclick = function () {window.location.replace('admin.php'); }</script> 
    <div class="table-responsive col-lg-12">
    <?php
      include('method/query.php');
      ?>
       <form method="get">
            <select name="sort" class="form-control form-control-lg col-12 mb-3" method="get">
              <?php 
                if(isset($_GET['sort'])){
                  ?><option value="<?php echo $_GET['sort'];?>" selected>Sort: <?php echo strtoupper($_GET['sort']);?></option><?php
                }else{
                  ?><option value="all" selected>Sort: All</option><?php }
              ?>
              </option>
              <option value="all">All</option>  
              <option value="pending">Pending</option>  
              <option value="prepairing">Prepairing</option>  
              <option value="serving">Serving</option>  
              <option value="order complete">Order Complete</option>  
              <option value="order invalid">Order Invalid</option>  
            </select>
            <input type="submit" value="Sort" class="btn btn-lg btn-success col-12 mb-4"> 
          </form>
      <?php
      if(isset($_GET['sort'])){
        $_SESSION['query'] = $_GET['sort'];
      }

      if($_SESSION['query'] == 'all')
        $query = "select WEBOMS_userInfo_tb.*, WEBOMS_order_tb.*, WEBOMS_user_tb.accountType from WEBOMS_userInfo_tb, WEBOMS_order_tb, WEBOMS_user_tb where WEBOMS_userInfo_tb.user_id = WEBOMS_order_tb.user_id  and WEBOMS_user_tb.user_id = WEBOMS_userInfo_tb.user_id ORDER BY WEBOMS_order_tb.id asc; ";
      if($_SESSION['query'] == 'pending')
        $query = "select WEBOMS_userInfo_tb.*, WEBOMS_order_tb.*, WEBOMS_user_tb.accountType from WEBOMS_userInfo_tb inner join WEBOMS_order_tb on WEBOMS_userInfo_tb.user_id = WEBOMS_order_tb.user_id inner join WEBOMS_user_tb on WEBOMS_user_tb.user_id = WEBOMS_userInfo_tb.user_id where status = 'pending' ORDER BY WEBOMS_order_tb.id asc; ";
      if($_SESSION['query'] == 'prepairing')
        $query = "select WEBOMS_userInfo_tb.*, WEBOMS_order_tb.*, WEBOMS_user_tb.accountType from WEBOMS_userInfo_tb inner join WEBOMS_order_tb on WEBOMS_userInfo_tb.user_id = WEBOMS_order_tb.user_id inner join WEBOMS_user_tb on WEBOMS_user_tb.user_id = WEBOMS_userInfo_tb.user_id where status = 'prepairing'  ORDER BY WEBOMS_order_tb.id asc; ";
      if($_SESSION['query'] == 'serving')
        $query = "select WEBOMS_userInfo_tb.*, WEBOMS_order_tb.*, WEBOMS_user_tb.accountType from WEBOMS_userInfo_tb inner join WEBOMS_order_tb on WEBOMS_userInfo_tb.user_id = WEBOMS_order_tb.user_id inner join WEBOMS_user_tb on WEBOMS_user_tb.user_id = WEBOMS_userInfo_tb.user_id where status = 'serving'  ORDER BY WEBOMS_order_tb.id asc; ";
      if($_SESSION['query'] == 'order complete')
        $query = "select WEBOMS_userInfo_tb.*, WEBOMS_order_tb.*, WEBOMS_user_tb.accountType from WEBOMS_userInfo_tb inner join WEBOMS_order_tb on WEBOMS_userInfo_tb.user_id = WEBOMS_order_tb.user_id inner join WEBOMS_user_tb on WEBOMS_user_tb.user_id = WEBOMS_userInfo_tb.user_id where status =  'complete' ORDER BY WEBOMS_order_tb.id asc; ";
      if($_SESSION['query'] == 'order invalid')
        $query = "select WEBOMS_userInfo_tb.*, WEBOMS_order_tb.*, WEBOMS_user_tb.accountType from WEBOMS_userInfo_tb inner join WEBOMS_order_tb on WEBOMS_userInfo_tb.user_id = WEBOMS_order_tb.user_id inner join WEBOMS_user_tb on WEBOMS_user_tb.user_id = WEBOMS_userInfo_tb.user_id where status = 'disapproved' ORDER BY WEBOMS_order_tb.id asc; ";

      $resultSet =  getQuery($query);
      if($resultSet != null){ ?>
          <table class="table table-striped table-bordered col-lg-12">
          <thead class="table-dark">
            <tr>	
              <th scope="col">NAME</th>
              <th scope="col">ORDERS ID</th>
              <th scope="col"></th>
              <th scope="col">ORDER STATUS</th>
              <th scope="col" colspan="2">Option</th>
              <th scope="col">DATE & TIME</th>
              <th scope="col"></th>
              <th scope="col">Staff In Charge</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($resultSet as $rows){?>
            <tr>	   
              <!-- name -->
              <td><?php echo $rows['accountType'] == 'customer'  ? $rows['name']:'POS'; ?></td>
              <!-- orders link id -->
              <td><?php echo $rows['order_id'];?></td>
              <!-- order details -->
              <td><a class="btn btn-light border-dark" href="adminOrders.php?idAndPic=<?php echo $rows['order_id'].','.$rows['proofOfPayment']?>">Order Details</a></td>
              <!-- order status -->
                  <?php 
                    if($rows['status'] == 'pending'){
                      ?><td>Pending</td><?php
                    }
                    elseif($rows['status'] == 'approved'){
                      ?><td>Approved></td><?php
                    }
                    elseif($rows['status'] == 'disapproved'){
                      ?><td>Disapproved</td><?php
                    }
                    elseif($rows['status'] == 'prepairing'){
                      ?><td>Prepairing</td><?php
                    }
                    elseif($rows['status'] == 'serving'){
                      ?><td>Serving</td><?php
                    }
                    elseif($rows['status'] == 'complete'){
                      ?><td>Complete</td><?php
                    }
                  ?>
              <!-- option -->
            
              <?php 
                    if($rows['status'] == 'pending'){
                      ?> <td><a class="btn btn-primary border-dark" href="?approve=<?php echo $rows['order_id'].','.$rows['email']; ?>">Approve</a></td>
                      <td><a class="btn btn-primary border-dark" href="?disapprove=<?php echo $rows['order_id'].','.$rows['email']; ?>">Disapprove</a></td><?php
                    }
                    elseif($rows['status'] == 'approved'){
                      ?><td colspan="2">Approved></td><?php
                    }
                    elseif($rows['status'] == 'disapproved'){
                      ?><td colspan="2">None</td><?php
                    }
                    elseif($rows['status'] == 'prepairing'){
                      ?> <td colspan='2'><a class="btn btn-success border-dark" href="?serve=<?php echo $rows['order_id'] ?>">Serve</a></td><?php
                    }
                    elseif($rows['status'] == 'serving'){
                      ?> <td colspan="2"><a class="btn btn-success border-dark" href="?orderComplete=<?php echo $rows['order_id'] ?>">Order Complete</a></td><?php
                    }
                    elseif($rows['status'] == 'complete'){
                      ?><td colspan="2">None</td><?php
                    }
                  ?>
        
              <!-- date -->
              <td><?php echo date('m/d/Y h:i a ', strtotime($rows['date'])); ?></td>
              <!-- delete -->
              <td><a class="btn btn-danger border-dark" href="?delete=<?php echo $rows['ID'].','.$rows['proofOfPayment'].','.$rows['order_id'] ?>">Delete</a></td>
              <td><?php echo $rows['staffInCharge'] == 'null' ? ' ' :$rows['staffInCharge']?></td>
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
  //button to serve order
  if(isset($_GET['serve'])){
    $order_id = $_GET['serve'];
    $query = "UPDATE WEBOMS_order_tb SET status='serving' WHERE order_id='$order_id' ";     
    if(Query($query))
      echo "<SCRIPT>  window.location.replace('adminOrdersList.php'); alert('success!');</SCRIPT>";

  }
  //button to approve order
    if(isset($_GET['approve'])){
      $arr = explode(',',$_GET['approve']);  
      $order_id = $arr[0];
      $email = $arr[1];
      //compute order
        $query ="select WEBOMS_menu_tb.*, WEBOMS_ordersDetail_tb.* from WEBOMS_menu_tb inner join WEBOMS_ordersDetail_tb where WEBOMS_menu_tb.orderType = WEBOMS_ordersDetail_tb.orderType and WEBOMS_ordersDetail_tb.order_id = '$order_id' ";  
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
        $mail->Username   = 'weboms098@gmail.com'; //from //SMTP username
        $mail->Password   = 'pcqezwnqunxuvzth';                     //SMTP password
        $mail->SMTPSecure =  PHPMailer::ENCRYPTION_SMTPS;           //Enable implicit TLS encryption
        $mail->Port       =  465;                                   //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('weboms098@gmail.com', 'webBasedOrdering');
        $mail->addAddress("$email");             //sent to

        //Content
        $mail->Subject = 'Receipt';
        $mail->Body    = ' ';
        $mail->AddStringAttachment($attachment, 'filename.pdf', 'base64', 'application/pdf');
        $mail->send();

      //approve
        $staff = $_SESSION['name'].'('.$_SESSION['accountType'].')';
        $query = "UPDATE WEBOMS_order_tb SET status='prepairing', staffInCharge = '$staff' WHERE order_id='$order_id' ";     
        if(Query($query))
          echo "<script>alert('Approve Success'); window.location.replace('adminOrdersList.php');</script>";
    }

  //button to dissaprove order
    if(isset($_GET['disapprove'])){
      $arr = explode(',',$_GET['disapprove']);  
      $order_id = $arr[0];
      $email = $arr[1];
      $staff = $_SESSION['name'].'('.$_SESSION['accountType'].')';
      $query = "UPDATE WEBOMS_order_tb SET status='disapproved',staffInCharge='$staff' WHERE order_id='$order_id' ";     
      Query($query);
      if(Query($query)){
        echo "<script>alert('Disapprove Success'); window.location.replace('adminOrdersList.php');</script>";
        $query = "select WEBOMS_menu_tb.*, WEBOMS_ordersDetail_tb.* from WEBOMS_menu_tb inner join WEBOMS_ordersDetail_tb where WEBOMS_menu_tb.orderType = WEBOMS_ordersDetail_tb.orderType and WEBOMS_ordersDetail_tb.order_id = '$order_id' ";
        $dishesArr = array();
        $dishesQuantity = array();
        $resultSet = getQuery($query); 
        foreach($resultSet as $rows){
          $qty = $rows['quantity'];
          $dish = $rows['dish'];
          $updateQuery = "UPDATE WEBOMS_menu_tb SET stock = (stock + '$qty') WHERE dish= '$dish' ";    
          Query($updateQuery);    
        }
      }
    }

  //button to make order complete
    if(isset($_GET['orderComplete'])){
      $order_id = $_GET['orderComplete'];
      $query = "UPDATE WEBOMS_order_tb SET status='complete' WHERE order_id='$order_id' ";     
      if(Query($query))
        echo "<SCRIPT>  window.location.replace('adminOrdersList.php'); alert('success!');</SCRIPT>";
    }

  //delete button
    if(isset($_GET['delete'])){
      $arr = explode(',',$_GET['delete']);
      $id = $arr[0];
      $Pic = $arr[1];
      $linkId = $arr[2];
      $query1 = "DELETE FROM WEBOMS_order_tb WHERE id='$id'";
      $query2 = "DELETE FROM WEBOMS_ordersDetail_tb WHERE order_id='$linkId'";
      if(Query($query1) && Query($query2)){
        if($Pic != 'null')
          unlink("payment/".$Pic);
        echo "<script> window.location.replace('adminOrdersList.php'); alert('Delete data successfully'); </script>";  
      }
    }
?>