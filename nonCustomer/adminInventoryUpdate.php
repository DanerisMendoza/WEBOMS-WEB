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
    <title>Inventory - Update</title>

    <link rel="stylesheet" type="text/css" href="../css/bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="../css/admin.css">
    <script type="text/javascript" src="../js/jquery-3.6.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>
<body>

    <div class="wrapper">
    <!-- Sidebar  -->
    <nav id="sidebar" class="bg-dark">
            <div class="sidebar-header bg-dark">
                <h3 class="mt-3"><a href="admin.php"><?php echo ucwords($_SESSION['accountType']); ?></a></h3>
            </div>
            <ul class="list-unstyled components ms-3">
                <li class="mb-2">
                    <a href="adminPos.php"><i class="bi bi-tag me-2"></i>Point of Sales</a>
                </li>
                <li class="mb-2">
                    <a href="adminOrders.php"><i class="bi bi-minecart me-2"></i>Orders</a>
                </li>
                <li class="mb-2">
                    <a href="adminOrdersQueue.php"><i class="bi bi-clock me-2"></i>Orders Queue</a>
                </li>
                <li class="mb-2">
                    <a href="topupRfid.php"><i class="bi bi-credit-card me-2"></i>Top-Up RFID</a>
                </li>
            
            <?php if($_SESSION['accountType'] != 'cashier'){?>
                <li class="mb-2 active">
                    <a href="adminInventory.php"><i class="bi bi-box-seam me-2"></i>Inventory</a>
                </li>
                <li class="mb-2">
                    <a href="adminSalesReport.php"><i class="bi bi-bar-chart me-2"></i>Sales Report</a>
                </li>
                <li class="mb-2">
                    <a href="accountManagement.php"><i class="bi bi-person-circle me-2"></i>Account Management</a>
                </li>
                <li class="mb-2">
                    <a href="adminFeedbackList.php"><i class="bi bi-chat-square-text me-2"></i>Customer Feedback</a>
                </li>
                <li class="mb-2">
                    <a href="adminTopUp.php"><i class="bi bi-cash-stack me-2"></i>Top-Up</a>
                </li>
                <li class="mb-1">
                    <a href="settings.php"><i class="bi bi-gear me-2"></i>Settings</a>
                </li>
            <?php } ?>
                <li>
                    <form method="post">
                        <button class="btn btnLogout btn-dark text-danger" id="Logout" name="logout"><i class="bi bi-power me-2"></i>Logout</button>
                    </form>
                </li>
            </ul>
        </nav>

        <!-- Page Content  -->
        <div id="content">
            <nav class="navbar navbar-expand-lg bg-light">
                <div class="container-fluid bg-transparent">
                    <button type="button" id="sidebarCollapse" class="btn" style="font-size:20px;"><i class="bi bi-list"></i> Toggle (Inventory Update)</button>
                </div>
            </nav>

            <!-- content here -->
            <div class="container-fluid text-center">
                <div class="row justify-content-center">
                    <?php
                        $idAndPicname = explode(',',$_GET['idAndPicnameUpdate']);    
                        $id = $idAndPicname[0];
                        $dishOriginal = $idAndPicname[1];
                        $priceOriginal = $idAndPicname[2];
                        $picNameOriginal = $idAndPicname[3];
                        $stockOriginal = $idAndPicname[4];
                        $name = $_SESSION['name'];
                    ?>

                    <div class="table-responsive col-lg-12 mb-4">
                        <table class="table table-bordered col-lg-12">
                            <tr>
                                <td><b>DISH:</b></td>
                                <td><?php echo ucwords($dishOriginal); ?></td>
                            </tr>
                            <tr>
                                <td><b>PRICE:</b></td>
                                <td><?php echo '₱'.number_format($priceOriginal,2); ?></td>
                            </tr>
                            <tr>
                                <td><b>STOCK:</b></td>
                                <td><?php echo $stockOriginal ?></td>
                            </tr>
                            <tr>
                                <td><b>IMAGE (FILE NAME):</b></td>
                                <td><?php echo $picNameOriginal ?></td>
                            </tr>
                        </table>
                    </div>

                    <div class="container-fluid">
                        <form method="post" class="form-group" enctype="multipart/form-data">
                            <input type="text" class="form-control form-control-lg mb-3" name="dish" placeholder="Enter new dish name">
                            <input type="number" class="form-control form-control-lg mb-3" name="price" placeholder="Enter new price">
                            <input type="number" class="form-control form-control-lg mb-3" name="stock" placeholder="Enter new number of stock">
                            <input type="file" class="form-control form-control-lg mb-3" name="fileInput">
                            <div class="btn-group container-fluid" role="group" aria-label="Basic mixed styles example">
                                <button type="submit" class="btn btn-lg btn-warning col-12" name="update"><i class="bi bi-arrow-repeat"></i> Update</button>
                                <button type="button" class="btn btn-lg btn-danger col-12" id="cancel"><i class="bi bi-x-circle"></i> Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
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