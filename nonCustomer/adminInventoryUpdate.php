<?php 
    $page = 'admin';
    include('../method/checkIfAccountLoggedIn.php');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>INVENTORY | UPDATE INVENTORY</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="icon" href="../image/weboms.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
</head>
<body>

<div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header">
                <a href="admin.php" class="account-type"><?php echo strtoupper($_SESSION['accountType']); ?></a>
            </div>
            <hr>
            <ul class="list-unstyled components">
                <li><a href="adminPos.php"><i class="bi-tag me-2"></i>POINT OF SALES</a></li>
                <li><a href="adminOrders.php"><i class="bi-cart me-2"></i>ORDERS</a></li>
                <li><a href="adminOrdersQueue.php"><i class="bi-clock me-2"></i>ORDERS QUEUE</a></li>
                <li><a href="topupRfid.php"><i class="bi-credit-card me-2"></i>TOP-UP RFID</a></li>

                <?php if($_SESSION['accountType'] != 'cashier'){?>
                <li><a href="adminTopUp.php"><i class="bi-wallet me-2"></i>TOP-UP</a></li>
                <li><a href="adminInventory.php" class="active text-danger"><i class="bi-box me-2"></i>INVENTORY</a></li>
                <li><a href="adminSalesReport.php"><i class="bi-bar-chart me-2"></i>SALES REPORT</a></li>
                <li><a href="adminFeedbackList.php"><i class="bi-chat-square-text me-2"></i>CUSTOMER FEEDBACK</a></li>
                <li><a href="accountManagement.php"><i class="bi-person me-2"></i>ACCOUNT MANAGEMENT</a></li>
                <li><a href="settings.php"><i class="bi-gear me-2"></i>SETTINGS</a></li>
                <?php } ?>
            </ul>
        </nav>

        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-toggle">
                        <i class="bi-list"></i>
                    </button>
                    <button class="btn btn-toggle d-inline-block d-lg-none ml-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="bi-list text-danger"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ms-auto">
                            <li>
                                <form method="post">
                                    <button class="btn text-danger" id="Logout" name="logout">LOGOUT</button>
                                </form>
                            </li>   
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="container-fluid mt-3">
                <?php
                    $idAndPicname = explode(',',$_GET['idAndPicnameUpdate']);    
                    $id = $idAndPicname[0];
                    $dishOriginal = $idAndPicname[1];
                    $priceOriginal = $idAndPicname[2];
                    $picNameOriginal = $idAndPicname[3];
                    $stockOriginal = $idAndPicname[4];
                    $name = $_SESSION['name'];
                ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <td>DISH:</td>
                            <td><?php echo $dishOriginal; ?></td>
                        </tr>
                        <tr>
                            <td>PRICE:</td>
                            <td><?php echo '₱'.number_format($priceOriginal,2); ?></td>
                        </tr>
                        <tr>
                            <td>STOCK:</td>
                            <td><?php echo $stockOriginal; ?></td>
                        </tr>
                        <tr>
                            <td>IMAGE:</td>
                            <td><?php echo $picNameOriginal; ?></td>
                        </tr>
                    </table>
                </div>
                <form action="" method="post" class="form-group" enctype="multipart/form-data">
                    <input type="text" class="form-control" placeholder="Enter new dish name" name="dish">
                    <input type="number" class="form-control" placeholder="Enter new price" name="price">
                    <input type="number" class="form-control" placeholder="Enter new stock" name="stock">
                    <input type="file" class="form-control" name="fileInput">
                    <div class="input-group">
                        <button type="submit" class="btn btn-warning w-50" name="update">UPDATE</button>
                        <button type="button" class="btn btn-danger w-50" id="cancel">CANCEL</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>

<?php
    //if update button click
    if(isset($_POST['update'])){
        include('../method/query.php');
        $dish = $_POST['dish'] == '' ? $dishOriginal : $_POST['dish'];
        $price = $_POST['price'] == '' ? $priceOriginal : $_POST['price'];
        $stock = $_POST['stock'] == '' ? $stockOriginal : $_POST['stock'];
        //if image didn't change 
        if($_FILES['fileInput']['name'] == ''){
            $updateQuery = "UPDATE weboms_menu_tb SET dish='$dish', price='$price', stock =  '$stock', lastModifiedBy = '$name' WHERE orderType=$id ";   
            if(Query2($updateQuery)){
                die ("<script>alert('SUCCESS UPDATING THE DATABASE!'); window.location.replace('adminInventory.php');</script>");       
            }
        }
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
                    $fileNameNew = uniqid('',true).".".$fileActualExt;
                    $fileDestination = '../dishesPic/'.$fileNameNew;
                    move_uploaded_file($fileTmpName,$fileDestination);         
                    $updateQuery = "UPDATE weboms_menu_tb SET dish='$dish', price='$price', picName = '$fileNameNew', stock =  '$stock', lastModifiedBy = '$name' WHERE orderType=$id ";        
                    if(Query2($updateQuery)){
                        echo '<script>alert("Success updating database!");</script>';       
                        unlink("dishespic/".$picName);                                        
                    }
                    echo "<script>window.location.replace('adminInventory.php');</script>";                                
                }
                else
                    echo "Your file is too big!";
            }
            else
                echo "There was an error uploading your file!";
        }
        else
            echo "You cannot upload files of this type!";  
    }
?>

<script>
    document.getElementById("cancel").addEventListener("click",function(){
        window.location.replace('adminInventory.php');
    });
</script>

<script>
// sidebar toggler
$(document).ready(function() {
    $('#sidebarCollapse').on('click', function() {
        $('#sidebar').toggleClass('active');
    });
});
</script>

<?php 
    if(isset($_POST['logout'])){
        $dishesArr = array();
        $dishesQuantity = array();
        if(isset($_SESSION['dishes'])){
            for($i=0; $i<count($_SESSION['dishes']); $i++){
                if(in_array( $_SESSION['dishes'][$i],$dishesArr)){
                    $index = array_search($_SESSION['dishes'][$i], $dishesArr);
                }
                else{
                    array_push($dishesArr,$_SESSION['dishes'][$i]);
                }
            }
            foreach(array_count_values($_SESSION['dishes']) as $count){
                array_push($dishesQuantity,$count);
            }
            for($i=0; $i<count($dishesArr); $i++){ 
                $updateQuery = "UPDATE weboms_menu_tb SET stock = (stock + '$dishesQuantity[$i]') WHERE dish= '$dishesArr[$i]' ";    
                Query2($updateQuery);    
            }
        }
        session_destroy();
        echo "<script>window.location.replace('../general/login.php');</script>";
    }
?>