<?php 
    session_start();
    require_once('TCPDF-main/tcpdf.php'); 
    $date = $_SESSION['date'];
    $cash = $_SESSION['cash'];
    $total = $_SESSION['total'];
    $change =  $cash-$total;
    $dishesArr = $_SESSION['dishesArr'];
    $priceArr = $_SESSION['priceArr'];
    $dishesQuantity = $_SESSION['dishesQuantity'];
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
    $content = '
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
    <br><br>
    <br><br>
    <tr>
        <td></td>
        <td>Cash</td>
        <td>₱$cash</td>
    </tr>
    <tr>
        <td></td>
        <td>Total</td>
        <td>₱$total</td>
    </tr>
    <tr>
        <td></td>
        <td>Change</td>
        <td>₱$change</td>
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
    ob_end_clean();
    $obj_pdf->Output('file.pdf', 'I');
    $_SESSION["dishes"] = $_SESSION["price"] = $_SESSION["orderType"] = array(); 
    $_SESSION['total'] = $_SESSION['cash'] = null;

?>