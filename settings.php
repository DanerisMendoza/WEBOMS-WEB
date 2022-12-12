<?php
  $page = 'admin';
  include('method/checkIfAccountLoggedIn.php');
  include_once('method/query.php');
  $query = "select * from WEBOMS_company_tb";
  $resultSet = getQuery($query);
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
    <link rel="stylesheet" href="css/bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" href="css/admin.css">
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>
    <body>

    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar" class="bg-dark">
            <div class="sidebar-header bg-dark">
                <h3 class="mt-3"><a href="admin.php">Admin</a></h3>
            </div>
            <ul class="list-unstyled components ms-3">
                <li class="mb-2">
                    <a href="#" id="pos"><i class="bi bi-tag me-2"></i>Point of Sales</a>
                </li>
                <li class="mb-2">
                    <a href="#" id="orders"><i class="bi bi-minecart me-2"></i>Orders</a>
                </li>
                <li class="mb-2">
                    <a href="#" id="ordersQueue"><i class="bi bi-clock me-2"></i>Orders Queue</a>
                </li>
                <li class="mb-2">
                    <a href="#" id="inventory"><i class="bi bi-box-seam me-2"></i>Inventory</a>
                </li>
                <li class="mb-2">
                    <a href="#" id="salesReport"><i class="bi bi-bar-chart me-2"></i>Sales Report</a>
                </li>
                <li class="mb-2">
                    <a href="#" id="accountManagement"><i class="bi bi-person-circle me-2"></i>Account Management</a>
                </li>
                <li class="mb-2">
                    <a href="#" id="customerFeedback"><i class="bi bi-chat-square-text me-2"></i>Customer Feedback</a>
                </li>
                <li class="mb-2">
                    <a href="#" id="adminTopUp"><i class="bi bi-cash-stack me-2"></i>Top-Up</a>
                </li>
                <li class="mb-1 active">
                    <a href="#"><i class="bi bi-gear me-2"></i>Settings</a>
                </li>
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
                    <button type="button" id="sidebarCollapse" class="btn" style="font-size:20px;">
                        <i class="bi bi-list me-1"></i>Dashboard
                    </button>
                </div>
            </nav>

            <!-- content here -->
            <div class="container-fluid text-center">
                <form method="post">
                    <h4 class="text-start">COMPANY NAME</h4>
                    <input type="text" name="name" placeholder="ENTER NEW COMPANY NAME" class="form-control form-control-lg mb-2" value="<?php echo $name; ?>" required></textarea>
                    <h4 class="text-start">COMPANY ADDRESS</h4>
                    
                    <input type="text" name="address" placeholder="ENTER NEW COMPANY ADDRESS" class="form-control form-control-lg mb-2" value="<?php echo $address; ?>" required></textarea>
                    <h4 class="text-start">COMPANY TELEPHONE/PHONE NUMBER</h4>
                    
                    <input type="number" name="tel" placeholder="ENTER NEW COMPANY TELEPHONE NUMBER" class="form-control form-control-lg mb-2" value="<?php echo $tel; ?>" required></textarea>
                    <h4 class="text-start">COMPANY DESCRIPTION/HISTORY</h4>
                    
                    <textarea rows="8" name="description" placeholder="ENTER NEW COMPANY DESCRIPTION" class="form-control form-control-lg mb-2"  required><?php echo $description; ?></textarea>
                    <button type="submit" name="update" class="btn btn-lg btn-warning col-12 mb-5"><i class="bi bi-arrow-repeat me-1"></i>UPDATE</button>
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
        $query = "update WEBOMS_company_tb SET name = '$name', address = '$address', tel = '$tel', description = '$description' ";
        if(Query($query)){
            echo "<script>alert('SUCCESS!'); window.location.replace('settings.php');</script>";
        }
    }

?>

<?php 
    // logout
    // include('method/query.php');
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
                $updateQuery = "UPDATE WEBOMS_menu_tb SET stock = (stock + '$dishesQuantity[$i]') WHERE dish= '$dishesArr[$i]' ";    
                Query($updateQuery);    
            }
        }
        session_destroy();
        echo "<script>window.location.replace('index.php');</script>";
    }
?>

<script>
// for navbar click locations
document.getElementById("pos").onclick = function() { window.location.replace('adminPos.php'); };
document.getElementById("orders").onclick = function() { window.location.replace('adminOrders.php'); };
document.getElementById("ordersQueue").onclick = function() { window.location.replace('adminOrdersQueue.php'); };
document.getElementById("inventory").onclick = function() { window.location.replace('adminInventory.php'); };
document.getElementById("salesReport").onclick = function() { window.location.replace('adminSalesReport.php'); };
document.getElementById("accountManagement").onclick = function() { window.location.replace('accountManagement.php'); };
document.getElementById("customerFeedback").onclick = function() { window.location.replace('adminFeedbackList.php'); };
document.getElementById("adminTopUp").onclick = function() { window.location.replace('adminTopUp.php'); };
</script>

<script>
// sidebar toggler
$(document).ready(function() {
    $('#sidebarCollapse').on('click', function() {
        $('#sidebar').toggleClass('active');
    });
});
</script>