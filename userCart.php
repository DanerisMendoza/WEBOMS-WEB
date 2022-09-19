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
            <button class="btn btn-success col-sm-4" id="pos">Home</button>
            <button class="btn btn-success col-sm-4" id="clear">Clear Order</button>
            <div class="col-lg-12">
                <table  class="table table-striped" border="10">
                    <tr>
                        <th scope="col">quantity</th>
                        <th scope="col">dish</th>
                        <th scope="col">price</th>
                    </tr>
                    <?php 
                    $dishesArr = array();
                    $priceArr = array();
                    $dishesQuantity = array();
                    $orderType = array();
      

                    for($i=0; $i<count($_SESSION['dishes']); $i++){
                        if(in_array( $_SESSION['dishes'][$i],$dishesArr)){
                            $index = array_search($_SESSION['dishes'][$i], $dishesArr);
                            $newCost = $priceArr[$index] + $_SESSION['price'][$i];
						    $priceArr[$index] = $newCost;
                        }
                        else{
                            array_push($dishesArr,$_SESSION['dishes'][$i]);
                            array_push($priceArr,$_SESSION['price'][$i]);
                            array_push($orderType,$_SESSION['orderType'][$i]);
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
       
     
                <form method="post" enctype="multipart/form-data">           
                    <label for="fileInput">Proof of Payment: </label>
                    <input type="file"  name="fileInput">
                    <button class="btn btn-danger col-sm-12" name="order">Place Order</button>
                </form>
            </div>
        </div>
    </body>
</html>

<script>
document.getElementById("pos").onclick = function () {window.location.replace('homepage.php'); };

$(document).ready(function () {
            $("#clear").click(function () {
                $.post(
                    "method/clearMethod.php", {
                    }
                );
                window.location.replace('usercart.php');
            });
});

</script> 

<?php

    if(isset($_POST['order'])){
        $file = $_FILES['fileInput'];
        if($_FILES['fileInput']['name']=='')
             echo "<script>alert('Please complete the details!'); window.location.replace('userCart.php');</script>";
        include_once('connection.php');
        $fileName = $_FILES['fileInput']['name'];
        $fileTmpName = $_FILES['fileInput']['tmp_name'];
        $fileSize = $_FILES['fileInput']['size'];
        $fileError = $_FILES['fileInput']['error'];
        $fileType = $_FILES['fileInput']['type'];
        $fileExt = explode('.',$fileName);
        $fileActualExt = strtolower(end($fileExt));
        $allowed = array('jpg','jpeg','png');
        if(in_array($fileActualExt,$allowed)){
            if($fileError === 0){
                if($fileSize < 10000000){
                    $userlinkId = $_SESSION['userlinkId'];
                    $ordersLinkId = uniqid();
                    $fileNameNew = uniqid('',true).".".$fileActualExt;
                    $fileDestination = 'payment/'.$fileNameNew;
                    move_uploaded_file($fileTmpName,$fileDestination);   
                    $query1 = "insert into orderList_tb(proofOfPayment, userlinkId, status, ordersLinkId) values('$fileNameNew','$userlinkId','0','$ordersLinkId')";
                    
                    for($i=0; $i<count($dishesArr); $i++){
                        $query2 = "insert into order_tb(orderslinkId, quantity, orderType) values('$ordersLinkId',$dishesQuantity[$i], $orderType[$i])";
                        mysqli_query($conn,$query2);
                    }

                    if(mysqli_query($conn,$query1)){
                        echo '<script>alert("Sucess Placing Order Please wait for verification!");</script>';       
                        $_SESSION["dishes"] = array();
                        $_SESSION["price"] = array();      
                        $_SESSION["orderType"] = array();                                    
                    }
                    else{
                        echo '<script type="text/javascript">alert("failed to save to database");</script>';  
                    }
                    echo "<script>window.location.replace('usercart.php')</script>";          
                    
                }
                else
                    echo "your file is too big!";
            }
            else
                echo "there was an error uploading your file!";
        }
        else
            echo "you cannot upload files of this type";     
    }
?>


