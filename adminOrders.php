<?php 
    $page = 'admin';
    include('method/checkIfAccountLoggedIn.php');
    $_SESSION['from'] = 'adminOrderList';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Orders</title>

    <link rel="stylesheet" href="css/bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" href="css/admin.css">
    <script src="js/bootstrap 5/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>

<body>

    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar" class="bg-dark">
            <div class="sidebar-header bg-dark">
                <h3 class="mt-3">Admin</h3>
            </div>
            <ul class="list-unstyled components ms-3">
                <li class="mb-2">
                    <a href="#" id="pos">
                        <i class="bi bi-tag me-2"></i>
                        Point of Sales
                    </a>
                </li>
                <li class="mb-2 active">
                    <a href="#">
                        <i class="bi bi-minecart me-2"></i>
                        Orders
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" id="ordersQueue">
                        <i class="bi bi-clock me-2"></i>
                        Orders Queue
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" id="inventory">
                        <i class="bi bi-box-seam me-2"></i>
                        Inventory
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" id="salesReport">
                        <i class="bi bi-bar-chart me-2"></i>
                        Sales Report
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" id="accountManagement">
                        <i class="bi bi-person-circle me-2"></i>
                        Account Management
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" id="customerFeedback">
                        <i class="bi bi-chat-square-text me-2"></i>
                        Customer Feedback
                    </a>
                </li>
                <li class="mb-1">
                    <a href="#" id="adminTopUp">
                        <i class="bi bi-cash-stack me-2"></i>
                        Top-Up
                    </a>
                </li>
                <li>
                    <form method="post">
                        <button class="btn btnLogout btn-dark text-danger" id="Logout" name="logout">
                            <i class="bi bi-power me-2"></i>
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </nav>

        <!-- Page Content  -->
        <div id="content">
            <nav class="navbar navbar-expand-lg bg-light">
                <div class="container-fluid bg-transparent">

                    <button type="button" id="sidebarCollapse" class="btn" style="font-size:20px;">
                        <i class="bi bi-list"></i>
                        <span>Dashboard</span>
                    </button>
                </div>
            </nav>
            <!-- content here -->
            <div class="container-fluid text-center">
                <div class="row justify-content-center">
                    <?php
                        include('method/query.php');
                    ?>
                    <form method="get" class="col-lg-12">
                        <!-- select sort -->
                        <select name="sort" class="form-control form-control-lg col-11 mb-3" method="get">
                            <?php 
                                if(isset($_GET['sort'])){ ?>
                            <option value="<?php echo $_GET['sort'];?>" selected>
                                SORT
                                <?php echo strtoupper($_GET['sort']);?>
                            </option>
                            <?php
                                }else{ ?>
                            <option value="all" selected>
                                SELECT OPTION
                            </option>
                            <?php } ?>
                            </option>
                            <option value="all">ALL</option>
                            <option value="prepairing">PREPARING</option>
                            <option value="serving">SERVING</option>
                            <option value="order complete">ORDER COMPLETE</option>
                        </select>
                        <!-- button sort -->
                        <input type="submit" value="SORT" class="btn btn-lg btn-success col-12 mb-4">
                    </form>
                    <?php
                        if(isset($_GET['sort'])){
                            $_SESSION['query'] = $_GET['sort'];
                        }

                        if($_SESSION['query'] == 'all')
                            $query = "select a.*, b.*, c.* from WEBOMS_userInfo_tb a inner join WEBOMS_order_tb b on a.user_id = b.user_id inner join WEBOMS_user_tb c on a.user_id = c.user_id order by b.id asc " ;
                        elseif($_SESSION['query'] == 'prepairing')
                            $query = "select a.*, b.*, c.* from WEBOMS_userInfo_tb a inner join WEBOMS_order_tb b on a.user_id = b.user_id inner join WEBOMS_user_tb c on a.user_id = c.user_id where b.status = 'prepairing' order by b.id asc " ;
                        elseif($_SESSION['query'] == 'serving')
                            $query = "select a.*, b.*, c.* from WEBOMS_userInfo_tb a inner join WEBOMS_order_tb b on a.user_id = b.user_id inner join WEBOMS_user_tb c on a.user_id = c.user_id where b.status = 'serving' order by b.id asc " ;
                        elseif($_SESSION['query'] == 'order complete')
                            $query = "select a.*, b.*, c.* from WEBOMS_userInfo_tb a inner join WEBOMS_order_tb b on a.user_id = b.user_id inner join WEBOMS_user_tb c on a.user_id = c.user_id where b.status = 'complete' order by b.id asc " ;

                        $resultSet =  getQuery($query);
                        if($resultSet != null){ ?>

                    <!-- table container -->
                    <div class="table-responsive col-lg-12">
                        <table class="table table-bordered col-lg-12">
                            <thead>
                                <tr>
                                    <th scope="col">NAME</th>
                                    <th scope="col">ORDERS ID</th>
                                    <th scope="col">ORDER STATUS</th>
                                    <th scope="col">DATE & TIME</th>
                                    <th scope="col">STAFF (IN-CHARGE)</th>
                                    <th scope="col">ORDER DETAILS</th>
                                    <th scope="col" colspan="3">OPTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($resultSet as $row){?>
                                <tr>
                                    <!-- name -->
                                    <td><?php echo $row['accountType'] == 'customer'  ? $row['name']:'POS'; ?></td>
                                    <!-- orders link id -->
                                    <td><?php echo $row['order_id'];?></td>
                                    <!-- order status -->
                                    <?php 
                                        if($row['status'] == 'approved'){
                                        ?>
                                    <td>
                                        <i class="bi bi-check"></i>
                                        <span class="ms-1">APPROVED</span>
                                    </td>
                                    <?php
                                        }
                                        elseif($row['status'] == 'prepairing'){
                                        ?>
                                    <td>
                                        <i class="bi bi-clock"></i>
                                        <span class="ms-1">PREPARING</span>
                                    </td>
                                    <?php
                                        }
                                        elseif($row['status'] == 'serving'){
                                        ?>
                                    <td>
                                        <i class="bi bi-box-arrow-right"></i>
                                        <span class="ms-1">SERVING</span>
                                    </td>
                                    <?php
                                        }
                                        elseif($row['status'] == 'complete'){
                                        ?>
                                    <td>
                                        <i class="bi bi-check"></i>
                                        <span class="ms-1">ORDER COMPLETE</span>
                                    </td>
                                    <?php
                                        }
                                    ?>
                                    <!-- staff in charge -->
                                    <td><?php echo $row['staffInCharge'] == 'null' ? ' ' :$row['staffInCharge']?></td>
                                    <!-- date and time -->
                                    <td><?php echo date('m/d/Y h:i a ', strtotime($row['date'])); ?></td>
                                    <!-- order details -->
                                    <td>
                                        <a class="btn btn-light border-secondary"
                                            href="adminOrder_details.php?idAndPic=<?php echo $row['order_id']?>">
                                            <i class="bi bi-list"></i>
                                            <span class="ms-1">VIEW</span>
                                        </a>
                                    </td>
                                    <!-- options -->
                                    <?php 
                                        if($row['status'] == 'prepairing'){
                                    ?>
                                    <td colspan='2'>
                                        <a class="btn btn-success" href="?serve=<?php echo $row['order_id'] ?>">
                                            <i class="bi bi-box-arrow-right"></i>
                                            <span class="ms-1">SERVE</span>
                                        </a>
                                    </td>
                                    <?php
                                        }
                                        elseif($row['status'] == 'serving'){
                                        ?>
                                    <td colspan="2">
                                        <a class="btn btn-success" href="?orderComplete=<?php echo $row['order_id'] ?>">
                                            <i class="bi bi-check"></i>
                                            <span class="ms-1">ORDER COMPLETE</span>
                                        </a>
                                    </td>
                                    <?php
                                        }
                                        elseif($row['status'] == 'complete'){
                                        ?>
                                    <td colspan="2">NONE</td>
                                    <?php } ?>
                                    <!-- delete -->
                                    <td>
                                        <a class="btn btn-danger"
                                            href="?delete=<?php echo $row['ID'].','.$row['order_id'] ?>">
                                            <i class="bi bi-trash"></i>
                                            <span class="ms-1">DELETE</span>
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>

<script>
// sidebar (js)
$(document).ready(function() {
    $('#sidebarCollapse').on('click', function() {
        $('#sidebar').toggleClass('active');
    });
});
</script>

<?php 
    $staff = $_SESSION['name'].'('.$_SESSION['accountType'].')';

    //button to serve order
    if(isset($_GET['serve'])){
        $order_id = $_GET['serve'];
        $query = "UPDATE WEBOMS_order_tb SET status='serving' WHERE order_id='$order_id' ";     
        if(Query($query)){
            echo "<SCRIPT>  window.location.replace('adminOrders.php'); alert('success!');</SCRIPT>";
        }
    }

    //button to dissaprove order
        if(isset($_GET['disapprove'])){
        $arr = explode(',',$_GET['disapprove']);  
        $order_id = $arr[0];
        $email = $arr[1];
        $query = "UPDATE WEBOMS_order_tb SET status='disapproved',staffInCharge='$staff' WHERE order_id='$order_id' ";     
        Query($query);
        if(Query($query)){
            echo "<script>alert('Disapprove Success'); window.location.replace('adminOrders.php');</script>";
            $query = "select WEBOMS_menu_tb.*, WEBOMS_ordersDetail_tb.* from WEBOMS_menu_tb inner join WEBOMS_ordersDetail_tb where WEBOMS_menu_tb.orderType = WEBOMS_ordersDetail_tb.orderType and WEBOMS_ordersDetail_tb.order_id = '$order_id' ";
            $dishesArr = array();
            $dishesQuantity = array();
            $resultSet = getQuery($query); 
            foreach($resultSet as $row){
            $qty = $row['quantity'];
            $dish = $row['dish'];
            $updateQuery = "UPDATE WEBOMS_menu_tb SET stock = (stock + '$qty') WHERE dish= '$dish' ";    
            Query($updateQuery);    
            }
        }
        }

    //button to make order complete
        if(isset($_GET['orderComplete'])){
        $order_id = $_GET['orderComplete'];
        $query = "UPDATE WEBOMS_order_tb SET status='complete',staffInCharge='$staff' WHERE order_id='$order_id' ";     
        if(Query($query))
            echo "<SCRIPT>  window.location.replace('adminOrders.php'); alert('success!');</SCRIPT>";
        }

    //delete button
        if(isset($_GET['delete'])){
        $arr = explode(',',$_GET['delete']);
        $id = $arr[0];
        $linkId = $arr[1];
        $query1 = "DELETE FROM WEBOMS_order_tb WHERE id='$id'";
        $query2 = "DELETE FROM WEBOMS_ordersDetail_tb WHERE order_id='$linkId'";
        if(Query($query1) && Query($query2)){
            echo "<script> window.location.replace('adminOrders.php'); alert('Delete data successfully'); </script>";  
        }
        }
?>

<script>
// for navbar click locations
document.getElementById("pos").onclick = function() {
    window.location.replace('adminPos.php');
};
document.getElementById("ordersQueue").onclick = function() {
    window.location.replace('adminOrdersQueue.php');
};
document.getElementById("inventory").onclick = function() {
    window.location.replace('adminInventory.php');
};
document.getElementById("salesReport").onclick = function() {
    window.location.replace('adminSalesReport.php');
};
document.getElementById("accountManagement").onclick = function() {
    window.location.replace('accountManagement.php');
};
document.getElementById("customerFeedback").onclick = function() {
    window.location.replace('customerFeedbackList.php');
};
document.getElementById("adminTopUp").onclick = function() {
    window.location.replace('adminTopUp.php');
};
// yung logout wala pa
</script>