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
    <title>Settings</title>
    <!-- for modal -->
    <link rel="stylesheet" href="../css/bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <script src="../js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/jquery-3.6.1.min.js"></script>
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
                <li class="mb-2">
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
                <li class="mb-1 active">
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
                    <button type="button" id="sidebarCollapse" class="btn" style="font-size:20px;"><i class="bi bi-list"></i> Toggle</button>
                </div>
            </nav>

            <!-- content here -->
            <div class="container-fluid text-center">
                <form method="post">
                    <h4 class="text-start">COMPANY NAME</h4>
                    <input type="text" name="name" placeholder="Enter new company name" class="form-control form-control-lg mb-4" value="<?php echo $name; ?>" required></textarea>
                    <h4 class="text-start">COMPANY ADDRESS</h4>
                    
                    <input type="text" name="address" placeholder="Enter new company address" class="form-control form-control-lg mb-4" value="<?php echo ucwords($address); ?>" required></textarea>
                    <h4 class="text-start">COMPANY TELEPHONE/PHONE NUMBER</h4>
                    
                    <input type="number" name="tel" placeholder="Enter new company telephone/phone number" class="form-control form-control-lg mb-4" value="<?php echo $tel; ?>" required></textarea>
                    <h4 class="text-start">COMPANY DESCRIPTION/HISTORY</h4>
                    
                    <textarea rows="8" name="description" placeholder="Enter new company description/history" class="form-control form-control-lg mb-4"  required><?php echo $description; ?></textarea>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="submit" name="update" class="btn btn-lg btn-warning col-12 mb-3"><i class="bi bi-arrow-repeat"></i> UPDATE</button>
                            </div>
                            <div class="col-sm-6">
                                <!-- reset all button -->
                                <button type="submit" name="reset" class="btn btn-lg btn-danger col-12"><i class="bi bi-database-fill"></i> RESET DATABASE</button>
                            </div>
                        </div>
                    </div>
                </form>
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