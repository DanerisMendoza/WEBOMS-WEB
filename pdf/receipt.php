<?php 
    $page = 'cashier';
    include('../method/checkIfAccountLoggedIn.php');
    require_once('../TCPDF-main/tcpdf.php'); 
    if($_SESSION['refreshCount'] < 1){
        $_SESSION['refreshCount'] += 1;
        die ("<script>window.location.replace('receipt.php');</script>");
    }
    $date = date('m/d/Y h:i:s a ', strtotime($_SESSION['date']));
    $cash = $_SESSION['cash'];
    $total = $_SESSION['total'];
    $change =  $cash-$total;
    $dishesArr = $_SESSION['dishesArr'];
    $priceArr = $_SESSION['priceArr'];
    $dishesQuantity = $_SESSION['dishesQuantity'];
    $order_id = $_SESSION['order_id'];
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
    $pdf -> Cell(60,10,"$date",'0','C');
    $pdf -> ln(10);
    $pdf -> Cell(61,10,"Dish",'B,T','0','C');
    $pdf -> Cell(61,10,"Quantity",'B,T','0','C');
    $pdf -> Cell(61,10,"Price",'B,T','0','C');
    $pdf -> ln(20);
    for($i=0; $i<count($dishesArr); $i++){ 
        $d = date('m/d/Y h:i a ', strtotime($row['date']));
        $pdf -> Cell(61,10,"$dishesArr[$i]",'','0','C');
        $pdf -> Cell(61,10,"$dishesQuantity[$i]",'','0','C');
        $pdf -> Cell(61,10,"₱$priceArr[$i]",'','0','C');
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
    $pdf -> Cell(183,10,"Order#$order_id",'','0','C');
    ob_end_clean();
    $pdf->Output('file.pdf', 'I');

    $_SESSION["dishes"] = $_SESSION["price"] = $_SESSION["orderType"] = array(); 
    $_SESSION['total'] = $_SESSION['cash'] = $_SESSION['order_id'] = null;
  
?>