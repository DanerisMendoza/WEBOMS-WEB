<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
    </head>
    <body>    
        <div class="container text-center">
            <button class="btn btn-success col-sm-4" id="pos">Pos</button>
            <button class="btn btn-success col-sm-4" id="clear">Clear Order</button>
            <div class="col-lg-12">
                <table  class="table table-striped" border="10">
                    <tr>
                        <th scope="col">quantity</th>
                        <th scope="col">dish</th>
                        <th scope="col">cost</th>
                    </tr>
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
                    //getting total price
                    for($i=0; $i<count($priceArr); $i++){
                        $total += $priceArr[$i];
                    }
                    for($i=0; $i<count($dishesArr); $i++){ ?>
                    <tr>  
                        <td> <?php echo $dishesQuantity[$i];?></td>
                        <td> <?php echo $dishesArr[$i];?></td>
                        <td> <?php echo '₱'.$priceArr[$i];?></td>
                    </tr>
                    <?php }?>
                    <tr>
                        <td colspan="2">Total</td>
                        <td>₱<?php echo $total; ?></td>
                    </tr>
                </table> 
       
     
                <form method="post">
                    <input name="cash" placeholder="Cash Amount" type="number"></input>
                    <br></br>    
                    <button class="btn btn-danger col-sm-12" name="order">Order</button>
                </form>
            </div>
        </div>
    </body>
</html>

<script>
document.getElementById("pos").onclick = function () {window.location.replace('pos.php'); };

$(document).ready(function () {
            $("#clear").click(function () {
                $.post(
                    "method/clearMethod.php", {
                    }
                );
                window.location.replace('cart.php');
            });
});

</script> 

<?php

    if(isset($_POST['order'])){
        $cash = $_POST['cash'];
        if(empty($cash)){
            echo "<script>alert('Please Enter your Cash Amount');</script>";
            return;
        }
        if($cash<$total){
            echo "<script>alert('Your Cash is less than your total Payment amount');</script>";
            return;
        }
        require_once('TCPDF-main/tcpdf.php'); 

        $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
        define ('PDF_PAGE_FORMAT', 'A4');
        $obj_pdf->SetCreator(PDF_CREATOR);  
        $obj_pdf->SetTitle("Receipt");  
        $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
        $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
        $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
        $obj_pdf->SetDefaultMonospacedFont('helvetica');  
        $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
        $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);  
        $obj_pdf->setPrintHeader(false);  
        $obj_pdf->setPrintFooter(false);  
        $obj_pdf->SetAutoPageBreak(TRUE, 10);  
        $obj_pdf->SetFont('dejavusans', '', 12);  
        $obj_pdf->AddPage();  
        setlocale(LC_CTYPE, 'en_US');
        
        date_default_timezone_set('Asia/Manila');
        $date = date("j-m-Y  h:i:s A");
        $change = $cash-$total;
        $content = '
          
        <h3>'.$date.'</h3>
        <table  text-center cellspacing="0" cellpadding="3">  
        <tr>
            <th scope="col">Quantity</th>
            <th scope="col">Dish</th>
            <th scope="col">Cost</th>
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
    }
?>


<!-- 
        add pdf page and size
        //AddPage [P(PORTRAIT),L(LANDSCAPE)],FORMAT(A4-A5-ETC)
        // $obj_pdf->AddPage('P','A5');
        you can see all possible values in this file: tcpdf/include/tcpdf_static.php
 -->
