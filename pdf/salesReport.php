<?php 
    $page = 'admin';
    include('../method/checkIfAccountLoggedIn.php');
    require('../TCPDF-main/tcpdf.php'); 
    include('../method/query.php');
    $resultSet = getQuery2($_SESSION['query']); 
    $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
    $pdf->SetCreator(PDF_CREATOR);  
    $pdf->SetTitle("Sales Report");  
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
    $pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);  
    $pdf->setPrintHeader(false);  
    $pdf->setPrintFooter(false);  
    $pdf->SetAutoPageBreak(TRUE, 10);  
    $pdf->SetFont('dejavusans', '', 11);  
    $pdf->AddPage('P','A4');
    $pdf -> Cell(60,10,"Sales Report",'0','C');
    !isset($_SESSION['date1']) || $_SESSION['date1'] == '' ? $pdf -> Cell(60,10,"All Sold Item",'0','C') : $pdf -> Cell(60,10,"Date(MM/dd/yyyy): $_SESSION[date1] - $_SESSION[date2]",'0','C');
    $pdf -> ln(10);
    $pdf -> Cell(60,10,"Name",'B,R,L,T','0','C');
    $pdf -> Cell(45,10,"Transaction No",'B,R,L,T','0','C');
    $pdf -> Cell(60,10,"Date & Time(MM/dd/yyyy)",'B,R,L,T','0','C');
    $pdf -> Cell(20,10,"Total",'B,R,L,T','0','C');
    $pdf -> ln(10);
    $total = 0;
    foreach($resultSet as $row){
        $d = date('m/d/Y h:i a ', strtotime($row['date']));
        if($row['name'] != ''){
            $pdf -> Cell(60,10,"$row[name]",'B,R,L,T','0','C');
        }
        else{
            $pdf -> Cell(60,10,"(No Name)",'B,R,L,T','0','C');
        }
        $pdf -> Cell(45,10,"$row[order_id]",'B,R,L,T','0','C');
        $pdf -> Cell(60,10,"$d",'B,R,L,T','0','C');
        $pdf -> Cell(20,10,"₱$row[totalOrder]",'B,R,L,T','0','C');
        $pdf -> ln(10);
        $total += $row['totalOrder'];
    }
    $pdf -> Cell(165,10,"Total",'B,R,L,T','0','C');
    $pdf -> Cell(20,10,"₱$total",'B,R,L,T','0','C');
    $pdf -> ln(20);
    $pdf -> Cell(46,10,"",'','0','C');
    $pdf -> Cell(46,10,"",'','0','C');
    $pdf -> Cell(43,10,"Approved By: ",'','0','C');
    $pdf -> Cell(34,10,"______________________",'','0','C');
    $pdf -> ln(5);
    $pdf -> Cell(46,10,"",'','0','C');
    $pdf -> Cell(46,10,"",'','0','C');
    $pdf -> Cell(43,10,"",'','0','C');
    $pdf -> Cell(34,10,"$_SESSION[name]",'','0','C');
    $pdf -> ln(5);
    $pdf -> Cell(46,10,"",'','0','C');
    $pdf -> Cell(46,10,"",'','0','C');
    $pdf -> Cell(43,10,"",'','0','C');
    $pdf -> Cell(34,10,"$_SESSION[accountType]",'','0','C');
    $pdf -> ln(5);
    $pdf -> ln(5);
    $pdf->Output();
?>