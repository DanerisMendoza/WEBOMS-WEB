<?php
    $page = 'admin';
    include('../method/checkIfAccountLoggedIn.php');
    include_once('../method/query.php');
    $query = "select * from weboms_company_tb";
    $resultSet = getQuery2($query);
    if($resultSet!=null)
        foreach($resultSet as $row){
            $name = $row['name'];
            $address = $row['address'];
            $tel = $row['tel'];
            $description = $row['description'];
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SETTINGS</title>

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
                <li><a href="adminInventory.php"><i class="bi-box me-2"></i>INVENTORY</a></li>
                <li><a href="adminSalesReport.php"><i class="bi-bar-chart me-2"></i>SALES REPORT</a></li>
                <li><a href="adminFeedbackList.php"><i class="bi-chat-square-text me-2"></i>CUSTOMER FEEDBACK</a></li>
                <li><a href="accountManagement.php"><i class="bi-person me-2"></i>ACCOUNT MANAGEMENT</a></li>
                <li><a href="settings.php" class="active text-danger"><i class="bi-gear me-2"></i>SETTINGS</a></li>
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
                <div class="col-sm-12">
                    <form method="post">
                        <div class="row">
                            <div class="col-sm-2">
                                <label for="" class="company">COMPANY NAME</label>
                            </div>
                            <div class="col-sm-10">
                                <input type="text" class="form-control form-control-lg" placeholder="Enter new company name" name="name" value="<?php echo strtoupper($name); ?>" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2">
                                <label for="" class="company">COMPANY ADDRESS</label>
                            </div>
                            <div class="col-sm-10">
                                <input type="text" class="form-control form-control-lg" placeholder="Enter new company address" name="address" value="<?php echo strtoupper($address); ?>" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2">
                                <label for="" class="company">COMPANY TELEPHONE</label>
                            </div>
                            <div class="col-sm-10">
                                <input type="number" class="form-control form-control-lg" placeholder="Enter new company telephone number" name="tel" value="<?php echo $tel; ?>" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2">
                                <label for="" class="company">COMPANY DESCRIPTION</label>
                            </div>
                            <div class="col-sm-10">
                                <textarea name="description" class="form-control form-control-lg" rows="8" placeholder="Enter new company description" required><?php echo strtoupper($description); ?></textarea>
                            </div>
                        </div>
                        <center>
                            <button type="submit" class="btn btn-lg btn-warning w-100 mt-2 mb-2" name="update">UPDATE</button>
                        </center>
                    </form>

        
                    <form method='post'>
                        <center>
                            <button type="submit" class="btn btn-lg btn-danger w-100" name="reset">RESET DATABASE</button>
                        </center>
                    </form>
            
                </div>
            </div>
        </div>
    </div>
        
</body>
</html>

<?php 
    // update company_tb process
    if(isset($_POST['update'])){
        $name = $_POST['name'];
        $address = $_POST['address'];
        $tel = $_POST['tel'];
        $description = $_POST['description'];
        $query = "update weboms_company_tb SET name = '$name', address = '$address', tel = '$tel', description = '$description' ";
        if(Query2($query)){
            echo "<script>alert('Success!'); window.location.replace('settings.php');</script>";
        }
    }

    // reset database 
    if(isset($_POST['reset'])){
        $query = "DROP TABLE `weboms_company_tb`, `weboms_feedback_tb`, `weboms_menu_tb`, `weboms_ordersDetail_tb`, `weboms_order_tb`, `weboms_topUp_tb`, `weboms_userInfo_tb`, `weboms_user_tb` , `weboms_cart_tb`, `weboms_usedRfid_tb`";
        if(Query2($query)){
            // clear all pic in folders
            $arr = array('../dishesPic', '../payment', '../profilePic');
            foreach($arr as $str){
                $folder_path = $str;
                $files = glob($folder_path.'/*'); 
                foreach($files as $file) {
                    if(is_file($file)){ 
                        unlink($file); 
                    }
                }
                $myfile = fopen("$str/ignore.txt", "w");
            }
            session_destroy();
            echo "<script>alert('SUCCESS!'); window.location.replace('../index.php');</script>";
        }
    }
?>

<?php 
    // logout
    if(isset($_POST['logout'])){
        session_destroy();
        echo "<script>window.location.replace('../index.php');</script>";
    }
?>

<script>
    // sidebar toggler
    $(document).ready(function() {
        $('#sidebarCollapse').on('click', function() {
            $('#sidebar').toggleClass('active');
        });
    });
</script>