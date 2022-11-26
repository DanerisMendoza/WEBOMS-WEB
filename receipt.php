<?php 
    $page = 'cashier';
    include('method/checkIfAccountLoggedIn.php');
    require_once('TCPDF-main/tcpdf.php'); 
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
            <tr><td></td></tr>
            <tr><td></td></tr>
            <tr>
                <td></td>
                <td>Payment</td>
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
            <tr><td></td></tr>
            <tr><td></td></tr>
            <tr><td></td></tr>
            <tr><td></td></tr>
            <h2>Order#$order_id</h1>
        </table>
    <style>
    h3{text-align: center;}
  
    </style>
    ";
    ob_end_clean();
    $obj_pdf->writeHTML($content);  
    $obj_pdf->Output('file.pdf', 'I');

    $_SESSION["dishes"] = $_SESSION["price"] = $_SESSION["orderType"] = array(); 
    $_SESSION['total'] = $_SESSION['cash'] = $_SESSION['order_id'] = null;
  
?>