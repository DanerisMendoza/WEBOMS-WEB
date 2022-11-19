<?php 
    $page = 'customer';
    include('method/checkIfAccountLoggedIn.php');
    include('method/query.php');
    date_default_timezone_set('Asia/Manila');
    $date = new DateTime();
    $today =  $date->format('Y-m-d'); 
    $todayWithTime =  $date->format('Y-m-d H:i:s'); 
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Costumer Menu - View Cart</title>
        
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script> 
   
</head>
<body class="bg-light">    

<div class="container text-center mt-5">
    <div class="row justify-content-center">
        <!-- <h1 class="font-weight-normal mt-5 mb-4 text-center">View Cart</h1> -->
        <button class="btn btn-lg btn-dark col-6 mb-3" id="back">Back</button>
        <button class="btn btn-lg btn-dark col-6 mb-3" id="home">Home</button>
        <input id="dateTime" type="datetime-local" class="form-control form-control-lg mb-4" name="date" min="<?php echo $todayWithTime;?>" value="<?php echo $todayWithTime;?>"/>
        
        <div class="table-responsive col-lg-12 mb-5">
            <table class="table table-striped table-bordered col-lg-12 mb-4">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">QUANTITY</th>
                        <th scope="col">DISH</th>
                        <th scope="col">PRICE</th>
                    </tr>
                </thead>
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
       
                <form method="post" enctype="multipart/form-data">           
                    <label for="fileInput">PROOF OF PAYMENT:</label>
                    <input type="file" class="form-control form-control-lg mb-3" name="fileInput" required>
                    <button class="btn btn-lg btn-success col-12 mb-3" name="order">Place Order</button>
                </form>
                <form method="post">
                    <button type="submit" class="btn btn-lg btn-danger col-12 mb-3" name="clear">Clear Order</button>
                </form>
                <script>document.getElementById("dateTime").disabled = true;</script>
            </div>
        </div>
        </div>
    
</body>
</html>

<script>
document.getElementById("home").onclick = function () {window.location.replace('customer.php'); }; 
document.getElementById("back").onclick = function () {window.location.replace('customerMenu.php'); }; 
</script> 
<?php
    //clear button
    if(isset($_POST['clear'])){
        for($i=0; $i<count($dishesArr); $i++){ 
            $updateQuery = "UPDATE menu_tb SET stock = (stock + '$dishesQuantity[$i]') WHERE dish= '$dishesArr[$i]' ";    
            Query($updateQuery);    
        }
        $_SESSION["dishes"] = array();
        $_SESSION["price"] = array();
        $_SESSION["orderType"] = array(); 
        echo "<script>window.location.replace('customerCart.php');</script>";
    }
    //order button
    if(isset($_POST['order'])){
        $file = $_FILES['fileInput'];
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
                    $query1 = "insert into order_tb(proofOfPayment, userlinkId, status, ordersLinkId, date, totalOrder) values('$fileNameNew','$userlinkId','pending','$ordersLinkId','$todayWithTime','$total')";
                    for($i=0; $i<count($dishesArr); $i++){
                        $query2 = "insert into ordersDetail_tb(orderslinkId, quantity, orderType) values('$ordersLinkId',$dishesQuantity[$i], $orderType[$i])";
                        Query($query2);
                    }

                    if(Query($query1)){
                        echo '<script>alert("Sucess Placing Order Please wait for verification!");</script>';       
                        $_SESSION["dishes"] = array();
                        $_SESSION["price"] = array();      
                        $_SESSION["orderType"] = array();                                    
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