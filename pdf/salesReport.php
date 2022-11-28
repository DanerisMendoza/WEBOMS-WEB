<?php 
    $page = 'admin';
    include('../method/checkIfAccountLoggedIn.php');
    require('../TCPDF-main/tcpdf.php'); 
    $resultSet = $_SESSION['resultSet'];
    $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
    $obj_pdf->SetCreator(PDF_CREATOR);  
    $obj_pdf->SetTitle("Sales Report");  
    $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
    $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);  
    $obj_pdf->setPrintHeader(false);  
    $obj_pdf->setPrintFooter(false);  
    $obj_pdf->SetAutoPageBreak(TRUE, 10);  
    $obj_pdf->SetFont('dejavusans', '', 11);  
    $obj_pdf->AddPage('P','A4');
    $obj_pdf -> Cell(60,10,"Sales Report",'0','C');
    !isset($_SESSION['date1']) || $_SESSION['date1'] == '' ? $obj_pdf -> Cell(60,10,"All Sold Item",'0','C') : $obj_pdf -> Cell(60,10,"Date: $_SESSION[date1] - $_SESSION[date2]",'0','C');
    $obj_pdf -> ln(10);
    $obj_pdf -> Cell(60,10,"Name",'B,R,L,T','0','C');
    $obj_pdf -> Cell(45,10,"Transaction No",'B,R,L,T','0','C');
    $obj_pdf -> Cell(60,10,"Date & Time",'B,R,L,T','0','C');
    $obj_pdf -> Cell(20,10,"Total",'B,R,L,T','0','C');
    $obj_pdf -> ln(10);
    $total = 0;
    foreach($resultSet as $row){
        $d = date('m/d/Y h:i a ', strtotime($row['date']));
        $obj_pdf -> Cell(60,10,"$row[name]",'B,R,L,T','0','C');
        $obj_pdf -> Cell(45,10,"$row[order_id]",'B,R,L,T','0','C');
        $obj_pdf -> Cell(60,10,"$d",'B,R,L,T','0','C');
        $obj_pdf -> Cell(20,10,"₱$row[totalOrder]",'B,R,L,T','0','C');
        $obj_pdf -> ln(10);
        $total += $row['totalOrder'];
    }
    $obj_pdf -> Cell(165,10,"Total",'B,R,L,T','0','C');
    $obj_pdf -> Cell(20,10,"₱$total",'B,R,L,T','0','C');
    
    $obj_pdf->Output();
?>