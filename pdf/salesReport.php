<?php 
    $page = 'admin';
    include('../method/checkIfAccountLoggedIn.php');
    include('../method/query.php');
    require('../fpdf185/fpdf.php');
    $query = $_SESSION['query'];
    $resultSet =  getQuery2($query); 
    $pdf = new FPDF();
    $pdf->AddPage('p','LETTER');
    $pdf->SetFont('Times','',18);
    $pdf -> Cell(60,10,"Sales Report",'0','C');
    $_SESSION['date1'] == '' ? $pdf -> Cell(60,10,"All Sold Item",'0','C') : $pdf -> Cell(60,10,"Date: $_SESSION[date1] - $_SESSION[date2]",'0','C');
    $pdf -> ln(10);
    $pdf -> Cell(60,10,"Name",'B,R,L,T','0','C');
    $pdf -> Cell(45,10,"Transaction No",'B,R,L,T','0','C');
    $pdf -> Cell(60,10,"Date & Time Total",'B,R,L,T','0','C');
    $pdf -> Cell(30,10,"Total",'B,R,L,T','0','C');
    $pdf -> ln(10);
    $total = 0;
    foreach($resultSet as $row){
        $d = date('m/d/Y h:i a ', strtotime($row['date']));
        $pdf -> Cell(60,10,"$row[name]",'B,R,L,T','0','C');
        $pdf -> Cell(45,10,"$row[order_id]",'B,R,L,T','0','C');
        $pdf -> Cell(60,10,"$d",'B,R,L,T','0','C');
        $pdf -> Cell(30,10,"$row[totalOrder]",'B,R,L,T','0','C');
        $pdf -> ln(10);
        $total += $row['totalOrder'];
    }
    $pdf -> Cell(165,10,"Total :",'B,R,L,T','0','C');
    $pdf -> Cell(30,10,"$total",'B,R,L,T','0','C');
    
    $pdf->Output();
    $_SESSION['date1'] = $_SESSION['date2'] = '';
?>