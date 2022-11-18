<?php 
    session_start(); 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin POS - View Cart</title>
        
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
    <link rel="stylesheet" type="text/css" href="css/style.css">  
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script> 

</head>
<body class="bg-light">    
        
<div class="container text-center">
    <div class="row justify-content-center">
        <h1 class="font-weight-normal mt-5 mb-4">View Cart</h1>
        <button class="btn btn-lg btn-danger col-12 mb-3" id="pos">POS</button>
        <button class="btn btn-lg btn-success col-12 mb-4" id="clear">Clear Order</button>
        
        <div class="table-responsive col-lg-12 mb-5">
            <table class="table table-striped table-bordered col-lg-12">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">QUANTITY</th>
                        <th scope="col">DISH</th>
                        <th scope="col">COST</th>
                    </tr>
                </thead>
                    <?php 
                    $dishesArr = array();
                    $priceArr = array();
                    $dishesQuantity = array();
                    for($i=0; $i<count($_SESSION['dishes']); $i++){
                        if(in_array( $_SESSION['dishes'][$i],$dishesArr)){
                            $index = array_search($_SESSION['dishes'][$i], $dishesArr);
                            $newCost = $priceArr[$index] + $_SESSION['price'][$i];
						    $priceArr[$index] = $newCost;
                        }
                        else{
                            array_push($dishesArr,$_SESSION['dishes'][$i]);
                            array_push($priceArr,$_SESSION['price'][$i]);
                        }
                    }
    
                    foreach(array_count_values($_SESSION['dishes']) as $count){
                        array_push($dishesQuantity,$count);
                    }
                       
                    $total = 0;
                    for($i=0; $i<count($priceArr); $i++){
                        $total += $priceArr[$i];
                    }
                    
                    for($i=0; $i<count($dishesArr); $i++){ ?>
                    <tr>  
                        <td><?php echo $dishesQuantity[$i];?></td>
                        <td><?php echo $dishesArr[$i];?></td>
                        <td><?php echo '₱'.$priceArr[$i];?></td>
                    </tr>
                    <?php }?>
                    <tr>
                        <td colspan="2"><b>TOTAL AMOUNT:</b></td>
                        <td><b>₱<?php echo $total; ?></b></td>
                    </tr>
            </table> 
       
            <form method="post">
                <input name="cash" placeholder="Cash Amount" type="number" class="form-control form-control-lg mb-3" required></input>
                <button class="btn btn-lg btn-success col-12 mb-5" name="order">Order</button>
            </form>
        </div>
    </div>
</div>
    
</body>
</html>

<script>
document.getElementById("pos").onclick = function () {window.location.replace('adminPos.php'); }

$(document).ready(function () {
    $("#clear").click(function () {
        $.post(
            "method/clearMethod.php", {
            }
        );
        window.location.replace('adminCart.php');
    });
});

</script> 

<?php
    if(isset($_POST['order'])){
        include('method/query.php');
        $cash = $_POST['cash'];
        if($cash<$total)
            die ("<script>alert('Your Cash is less than your total Payment amount');</script>");
    
        for($i=0; $i<count($dishesArr); $i++){ 
            $updateQuery = "UPDATE menu_tb SET stock = (stock - '$dishesQuantity[$i]') WHERE dish= '$dishesArr[$i]' ";    
            Query($updateQuery);    
        }
        $change =  $cash-$total;
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
        $_SESSION["dishes"] = array();
        $_SESSION["price"] = array();
        $_SESSION["orderType"] = array(); 
    }
?>

<!-- 
        add pdf page and size
        //AddPage [P(PORTRAIT),L(LANDSCAPE)],FORMAT(A4-A5-ETC)
        // $obj_pdf->AddPage('P','A5');
        you can see all possible values in this file: tcpdf/include/tcpdf_static.php
 -->
