<?php 
// include('allScript.php');
session_start(); 
?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
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
        include_once('orderClass.php');
        $order = new order($dishesQuantity,$dishesArr,$priceArr,$cash,$total);
        $order-> makeReceipt(); 
    }
?>

<style>
    body{
    background-image: url(settings/bg.jpg);
    background-size: cover;
    background-attachment: fixed;
    background-repeat: no-repeat;
    background-position: center;
    color: white;
    font-family: 'Josefin Sans',sans-serif;
  }
</style>

<!-- 
        add pdf page and size
        //AddPage [P(PORTRAIT),L(LANDSCAPE)],FORMAT(A4-A5-ETC)
        // $obj_pdf->AddPage('P','A5');
        you can see all possible values in this file: tcpdf/include/tcpdf_static.php
 -->
