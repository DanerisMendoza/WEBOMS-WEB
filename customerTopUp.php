<?php 
    $page = 'customer';
    include('method/checkIfAccountLoggedIn.php');
    include('method/query.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>TopUp</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    </head>
    <body>
    <body>
        <br></br>
        <div class="container">
            <div class="row justify-content-center">
            <button class="btn btn-lg btn-dark col-12 mb-3" id="back">Back</button>
                <form method="post" enctype="multipart/form-data" class="col-12">   
                    <select name="amount" class="form-control form-control-lg col-12 mb-3">
                        <option value="100">₱100</option>  
                        <option value="200">₱300</option>  
                        <option value="400">₱500</option>  
                        <option value="1000">₱1000</option>  
                        <option value="3000">₱3000</option>  
                        <option value="5000">₱5000</option>  
                    </select>     
                    <label for="fileInput">PROOF OF PAYMENT:</label>
                    <input type="file" class="form-control form-control-lg mb-3" name="fileInput" required>
                    <button class="btn btn-lg btn-success col-12 mb-3" name="submit">Place Order</button>
                </form>
            </div>
        </div>    
    </body>
</html>
<?php 
    //top up button
    if(isset($_POST['submit'])){
        //init
        $amount = $_POST['amount'];+
        date_default_timezone_set('Asia/Manila');
        $date = new DateTime();
        $todayWithTime =  $date->format('Y-m-d H:i:s'); 
        $user_id = $_SESSION['user_id'];
        $fileName = $_FILES['fileInput']['name'];
        $fileTmpName = $_FILES['fileInput']['tmp_name'];
        $fileSize = $_FILES['fileInput']['size'];
        $fileError = $_FILES['fileInput']['error'];
        $fileType = $_FILES['fileInput']['type'];
        $fileExt = explode('.',$fileName);
        $fileActualExt = strtolower(end($fileExt));
        $allowed = array('jpg','jpeg','png');
        //process
        if(in_array($fileActualExt,$allowed)){
            if($fileError === 0){
                if($fileSize < 10000000){
                    $fileNameNew = uniqid('',true).".".$fileActualExt;
                    $fileDestination = 'payment/'.$fileNameNew;
                    move_uploaded_file($fileTmpName,$fileDestination);  
                    $query = "insert into WEBOMS_topUp_tb(user_id, amount,status, proofOfPayment, date) values('$user_id','$amount','pending','$fileNameNew','$todayWithTime')";
                    if(Query($query)){
                        echo "<script>alert('sucess place topUp');</script>";
                    }
                    else{
                        echo "<scrip>alert('sucess place topUp');</script>";
                    }
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
<script>
    	document.getElementById("back").onclick = function () {window.location.replace('customer.php'); };
</script>