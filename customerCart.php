<?php 
    session_start(); 
    date_default_timezone_set('Asia/Manila');
    $date = new DateTime();
    $today =  $date->format('Y-m-d'); 
    $todayWithTime =  $date->format('Y-m-d H:i:s'); 
?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
        <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script> 
    </head>
    <body>    
        <div class="container text-center">
            <button class="btn btn-success col-sm-2" id="home">Home</button>
            <button class="btn btn-success col-sm-2" id="back">Back</button>
            <button class="btn btn-success col-sm-2" id="clear">Clear Order</button>
            
            <input id="dateTime" type="datetime-local" class="col-sm-6" name="date" min="<?php echo $todayWithTime;?>" value="<?php echo $todayWithTime;?>"/>
  
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
                    <input type="file"  name="fileInput" required>
                    <button class="btn btn-danger col-sm-12" name="order">Place Order</button>
                </form>
                <script>document.getElementById("dateTime").disabled = true;</script>
            </div>
        </div>
    </body>
</html>

<script>
document.getElementById("home").onclick = function () {window.location.replace('customer.php'); }; 
document.getElementById("back").onclick = function () {window.location.replace('customerMenu.php'); }; 


$(document).ready(function () {
            $("#clear").click(function () {
                $.post(
                    "method/clearMethod.php", {
                    }
                );
                window.location.replace('customerCart.php');
            });
});

</script> 

<?php

    if(isset($_POST['order'])){
        $file = $_FILES['fileInput'];
        if($_FILES['fileInput']['name']=='')
            echo "<script>alert('Please complete the details!'); window.location.replace('customerCart.php');</script>";
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
                    $userlinkId = $_SESSION['userLinkId'];
                    $ordersLinkId = uniqid();
                    $fileNameNew = uniqid('',true).".".$fileActualExt;
                    $fileDestination = 'payment/'.$fileNameNew;
                    move_uploaded_file($fileTmpName,$fileDestination);   
                    $query1 = "insert into orderList_tb(proofOfPayment, userlinkId, status, ordersLinkId, date, isOrdersComplete) values('$fileNameNew','$userlinkId','0','$ordersLinkId','$todayWithTime', '0')";
                    
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
                    echo "<script>window.location.replace('customerCart.php')</script>";          
                    
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
	.container{
     padding: 1%;
     margin-top: 2%;
     background: gray;
   }
</style>
