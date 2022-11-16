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
                    //getting total price
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
                <input name="cash" placeholder="Cash Amount" type="number" class="form-control form-control-lg mb-3"></input>
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
        $cash = $_POST['cash'];
        if(empty($cash)){
            echo "<script>alert('Please Enter your Cash Amount');</script>";
            return;
        }
        if($cash<$total){
            echo "<script>alert('Your Cash is less than your total Payment amount');</script>";
            return;
        }
        include_once('class/orderClass.php');
        $order = new order($dishesQuantity,$dishesArr,$priceArr,$cash,$total);
        $order-> displayReceipt(); 
    }
?>

<!-- 
        add pdf page and size
        //AddPage [P(PORTRAIT),L(LANDSCAPE)],FORMAT(A4-A5-ETC)
        // $obj_pdf->AddPage('P','A5');
        you can see all possible values in this file: tcpdf/include/tcpdf_static.php
 -->
