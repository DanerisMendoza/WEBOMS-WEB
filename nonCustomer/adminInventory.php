<?php 
    $page = 'admin';
    include('../method/query.php');
    include('../method/checkIfAccountLoggedIn.php');

    // redefining name
    $_SESSION['name'] = getQueryOneVal2("select name from weboms_userInfo_tb where user_id = '$_SESSION[user_id]' ",'name');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../css/admin2.css">
    <link rel="stylesheet" href="../css/admin-inventory.css">
    <link rel="stylesheet" href="../css/rfid.css">
    <link rel="icon" href="../image/weboms.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
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
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="tbl">
                        <thead class="table-dark">
                            <tr>
                                <th>IMAGE</th>
                                <th>DISH</th>
                                <th>PRICE</th>
                                <th>STOCK</th>
                                <th>LAST MODIFIED</th>
                                <th><button type="button" class="btn btn-success" id="addButton" data-bs-toggle="modal" data-bs-target="#loginModal"><b><i class="bi-plus-lg me-2"></i>ADD NEW DISH</b></button></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $query = "select * from weboms_menu_tb";
                                $resultSet = getQuery2($query);
                                if($resultSet != null){
                                foreach($resultSet as $row){?>
                                <tr>
                                    <td><center><?php $pic = $row['picName']; echo "<img src='../dishesPic/$pic'>";?></center></td>
                                    <td><?php echo ucwords($row['dish']);?></td>
                                    <td><?php echo '₱'. number_format($row['price'],2); ?></td>
                                    <td><?php echo $row['stock']; ?></td>
                                    <td><?php echo ucwords($row['lastModifiedBy']); ?></td>
                                    <td>
                                        <a class="btn btn-warning" href="adminInventoryUpdate.php?idAndPicnameUpdate=<?php echo $row['orderType'].",".$row['dish'].",".$row['price'].",".$row['picName'].",".$row['stock']; ?>"><i class="bi-arrow-repeat"></i></a>
                                        <a class="btn btn-danger" href="?idAndPicnameDelete=<?php echo $row['orderType']." ".$row['picName']; ?>"><i class="bi-trash3"></i></a>
                                    </td>
                                </tr>
                                <?php } 
                                }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- add new dish (modal) -->
                <div class="modal fade" role="dialog" id="loginModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <form method="post" class="form-group" enctype="multipart/form-data">
                                    <input type="text" class="form-control" name="dishes" placeholder="Enter dish name" required>
                                    <input type="number" class="form-control" name="price" step="any" placeholder="Enter price" required>
                                    <input type="number" class="form-control" name="stock" placeholder="Enter number of stock" required>
                                    <input type="file" class="form-control" name="fileInput" required>
                                    <button type="submit" class="btn btn-success w-100" name="insert">SAVE</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

<?php
    //delete 
    if (isset($_GET['idAndPicnameDelete'])){
        $arr = explode(' ',$_GET['idAndPicnameDelete']);
        $id = $arr[0];
        $pic = $arr[1];
        $query = "DELETE FROM weboms_menu_tb WHERE orderType='$id' ";
        if(Query2($query)){
        unlink("dishespic/"."$pic");
        echo "<script> window.location.replace('adminInventory.php');</script>";
        }
    }

    //insert
    if(isset($_POST['insert'])){
    $dishes = $_POST['dishes'];
    $price = $_POST['price'];
    $file = $_FILES['fileInput'];
    $stock = $_POST['stock'];
    $fileName = $_FILES['fileInput']['name'];
    $name = $_SESSION['name'];

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
                $query = "insert into weboms_menu_tb(dish, price, picName, stock, lastModifiedBy) values('$dishes','$price','$fileNameNew','$stock','$name')";
                if(Query2($query));
                    echo "<script>window.location.replace('adminInventory.php')</script>";                                
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
    // for data tables
    $(document).ready(function() {
        $('#tbl').DataTable();
    });

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