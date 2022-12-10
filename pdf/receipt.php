<?php 
    $page = 'receipt';
    include('../method/checkIfAccountLoggedIn.php');
    require_once('../TCPDF-main/tcpdf.php'); 
    if($_SESSION['refreshCount'] < 2){
        $_SESSION['refreshCount'] += 1;
        die ("<script>window.location.replace('receipt.php');</script>");
    }
    $date = date('m/d/Y h:i:s a ', strtotime($_SESSION['date']));
    $cash = $_SESSION['cash'];
    $total = $_SESSION['total'];
 
    $change =  $cash-$total;
    $cash = number_format($cash,2);
    $total = number_format($total,2);
   
    $change = number_format($change,2);
    $dishesArr = $_SESSION['dishesArr'];
    $priceArr = $_SESSION['priceArr'];
    $dishesQuantity = $_SESSION['dishesQuantity'];
 

    $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
    $pdf->SetCreator(PDF_CREATOR);  
    $pdf->SetTitle("Receipt");  
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
    $pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);  
    $pdf->setPrintHeader(false);  
    $pdf->setPrintFooter(false);  
    $pdf->SetAutoPageBreak(TRUE, 10);  
    $pdf->SetFont('dejavusans', '', 11);  
    $pdf->AddPage('P','a6');
    $pdf -> Cell(183,10,"$_SESSION[companyName]",'','0','C');
    $pdf -> ln(8);
    $pdf -> Cell(183,10,"$_SESSION[companyAddress]",'','0','C');
    $pdf -> ln(8);
    $pdf -> Cell(183,10,"$_SESSION[companyTel]",'','0','C');
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
    $pdf -> Cell(61,10,"₱$cash",'T','0','C');
    $pdf -> ln(10);
    $pdf -> Cell(122,10,"Total",'','0','L');
    $pdf -> Cell(61,10,"₱$total",'','0','C');
    $pdf -> ln(10);
    $pdf -> Cell(122,10,"Change",'B','0','L');
    $pdf -> Cell(61,10,"₱$change",'B','0','C');
    $pdf -> ln(10);
    $pdf->SetFont('dejavusans', '', 18);  
    $pdf -> Cell(183,10,"Order#$_SESSION[order_id]",'','0','C');
    $pdf -> ln(20);
    $pdf->SetFont('dejavusans', '', 11);  
    $pdf -> Cell(122,10,"Customer: $_SESSION[customerName]" ,'','0','L');
    $pdf -> ln(10);
    if($_SESSION['staffInCharge'] == 'online order'){
        $pdf -> Cell(122,10,"Order Type: ONLINE ORDER",'','0','L');
    }
    else{
        $pdf -> Cell(122,10,"Order Type: POS",'','0','L');
    }
    $pdf -> ln(10);
    $pdf -> Cell(122,10,"Order Number: $_SESSION[or_number] ",'','0','L');
    $pdf -> ln(10);
    if($_SESSION['staffInCharge'] != 'online order'){
        $pdf -> Cell(122,10,"Cashier: $_SESSION[name]",'','0','L');
    }
    ob_end_clean();
    $pdf->Output('file.pdf', 'I');

    if($_SESSION['fromReceipt'] == 'pos'){
        $_SESSION["dishes"] = $_SESSION["price"] = $_SESSION["orderType"] = $_SESSION['multiArr'] = array(); 
        $_SESSION['fromReceipt'] = $_SESSION['staffInCharge'] = $_SESSION['total'] = $_SESSION['cash'] = $_SESSION['order_id'] = null;
    }
  
?>