<?php 
    $page = 'cashier';
    include('../method/checkIfAccountLoggedIn.php');
    include('../method/query.php');
    $_SESSION['from'] = 'adminOrderList';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>

    <link rel="stylesheet" href="../css/bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <!-- modal script  -->
    <script type="text/javascript" src="../js/jquery-3.6.1.min.js"></script>  
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <!-- data table -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
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
                <li class="mb-2 active">
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
                    <button type="button" id="sidebarCollapse" class="btn" style="font-size:20px;"><i class="bi bi-list"></i> Toggle</button>
                </div>
            </nav>

            <!-- content here -->
            <div class="container-fluid text-center">
                <div class="row g-3 justify-content-center">
                    <form method="get" class="col-lg-12">
                        <!-- select sort -->
                        <select name="sort" class="form-control form-control-lg col-12 mb-3" method="get">
                            <?php 
                                if(isset($_GET['sort'])){ ?>
                            <option value="<?php echo $_GET['sort'];?>" selected>Sort <?php echo ucwords($_GET['sort']);?></option>
                            <?php
                                }else{ ?>
                            <option value="all" selected>Select Option</option>
                            <?php } ?>
                            <option value="all">All</option>
                            <option value="prepairing">Preparing</option>
                            <option value="serving">Serving</option>
                            <option value="order complete">Order Complete</option>
                            <option value="void">Void</option>
                        </select>
                        <!-- button sort -->
                        <input type="submit" value="Sort" class="btn btn-lg btn-success col-12 mb-4">
                    </form>
                        <!-- sorted table -->
                        <?php
                        // sort query
                        if(isset($_GET['sort'])){
                            $_SESSION['query'] = $_GET['sort'];
                        }
                        if($_SESSION['query'] == 'all')
                            $query = "select a.*, b.* from weboms_userInfo_tb a right join weboms_order_tb b on a.user_id = b.user_id  order by b.id asc " ;
                        elseif($_SESSION['query'] == 'prepairing')
                            $query = "select a.*, b.* from weboms_userInfo_tb a right join weboms_order_tb b on a.user_id = b.user_id  where b.status = 'prepairing' order by b.id asc " ;
                        elseif($_SESSION['query'] == 'serving')
                            $query = "select a.*, b.* from weboms_userInfo_tb a right join weboms_order_tb b on a.user_id = b.user_id  where b.status = 'serving' order by b.id asc " ;
                        elseif($_SESSION['query'] == 'order complete')
                            $query = "select a.*, b.* from weboms_userInfo_tb a right join weboms_order_tb b on a.user_id = b.user_id  where b.status = 'complete' order by b.id asc " ;
                        elseif($_SESSION['query'] == 'void')
                            $query = "select a.*, b.* from weboms_userInfo_tb a right join weboms_order_tb b on a.user_id = b.user_id  where b.status = 'void' order by b.id asc " ;

                        $resultSet =  getQuery2($query);
                        if($resultSet != null){ ?>
                        
                        <!-- table container -->
                        <div class="table-responsive col-lg-12">
                            <table class="table table-bordered table-hover col-lg-12" id="tb1">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">NO.</th>
                                        <th scope="col">CUSTOMER NAME</th>
                                        <th scope="col">ORDERS ID</th>
                                        <th scope="col">ORDER STATUS</th>
                                        <th scope="col">DATE(MM/dd/yyyy) & TIME</th>
                                        <th scope="col">STAFF (IN-CHARGE)</th>
                                        <th scope="col">ORDER DETAILS</th>
                                        <th scope="col">CUSTOMER INFO</th>
                                        <th scope="col" colspan="2">OPTIONS</th>
                                    </tr>
                                </thead>
                                <tbody id="table1">
                                <?php foreach($resultSet as $row){?>
                                    <tr>
                                        <td><?php echo $row['ID']; ?></td>
                                        <!--if account is deleted block-->
                                        <?php if($row['staffInCharge'] == 'online order' && $row['name'] == '' ){ ?>
                                                <td><a class="text-danger">Deleted Account</a></td>
                                        <?php }elseif($row['name'] != ''){ ?>
                                            <td ><?php echo $row['name']; ?></td>
                                        <?php }else{ ?> 
                                            <td >(No Name)</td>
                                        <?php }?>
                                        <!-- order id -->
                                        <td><?php echo $row['order_id'];?></td>
                                        <!-- order status -->
                                        <?php 
                                            if($row['status'] == 'approved'){
                                            ?>
                                        <td>Approved</td>
                                        <?php
                                            }
                                            elseif($row['status'] == 'prepairing'){
                                            ?>
                                        <td>Preparing</td>
                                        <?php
                                            }
                                            elseif($row['status'] == 'serving'){
                                            ?>
                                        <td>Serving</td>
                                        <?php
                                            }
                                            elseif($row['status'] == 'complete'){
                                            ?>
                                        <td>Order Complete</td>
                                        <?php
                                            }
                                            elseif($row['status'] == 'void'){
                                            ?>
                                        <td class="text-danger">Void</td>
                                        <?php
                                            }
                                        ?>
                                        <td><?php echo date('m/d/Y h:i a ', strtotime($row['date'])); ?></td>
                                        <td><?php echo ucwords($row['staffInCharge'] == 'online order' ? '('. ucwords($row['staffInCharge']).')' : ucwords($row['staffInCharge']) .' via POS');?></td>
                                        <!-- order details -->
                                        <td>
                                            <a class="btn btn-light" style="border:1px solid #cccccc;" href="adminOrder_details.php?order_id=<?php echo $row['order_id']?>"> <i class="bi bi-list"></i> View</a>
                                        </td>
                                        <!-- options -->

                                            <!-- online -->
                                            <?php if($row['staffInCharge'] == 'online order') {?>
                                                <!-- customer info -->
                                                <td><a class="btn btn-primary" href="?viewCustomerInfo=<?php echo $row['user_id'] ?>"><i class="bi bi-list"></i> View</a></td>
                                                <!-- status -->
                                                <?php  if($row['status'] == 'prepairing'){ ?>
                                                        <td><a class="btn btn-success" href="?serve=<?php echo $row['order_id'] ?>"><i class="bi bi-arrow-bar-left"></i> Serve</a></td>
                                                <?php }elseif($row['status'] == 'serving'){ ?>
                                                        <td><a class="btn btn-success" href="?orderComplete=<?php echo $row['order_id'] ?>"><i class="bi bi-check"></i> Order Complete</a></td><?php }
                                                    elseif($row['status'] == 'complete' || $row['status'] == 'void'){?>
                                                        <td><a class="text-danger">None</a></td><?php } ?>

                                            <!-- pos -->
                                            <?php } else{ ?>
                                                <!-- customer info -->
                                                <td></td>
                                                <!-- status -->
                                                <?php  if($row['status'] == 'prepairing'){ ?>
                                                        <td><a class="btn btn-success" href="?serve=<?php echo $row['order_id'] ?>"><i class="bi bi-arrow-bar-left"></i> Serve</a></td>
                                                <?php }elseif($row['status'] == 'serving'){ ?>
                                                        <td><a class="btn btn-success" href="?orderComplete=<?php echo $row['order_id'] ?>"><i class="bi bi-check"></i> Order Complete</a></td>
                                                <?php }elseif($row['status'] == 'complete' || $row['status'] == 'void'){?>
                                                        <td><a class="text-danger">None</a></td>
                                                        
                                                <?php } ?>
                                            <?php } ?>
                                        <!-- void -->
                                        <?php if($row['status'] != 'void' && $_SESSION['accountType'] != 'cashier'){?>
                                            <td><a class="btn btn-danger" href="?void=<?php echo $row['order_id'].','.$row['user_id'].','.$row['totalOrder'] ?>"><i class="bi bi-dash-circle"></i> Void</a></td>
                                        <?php }else{ ?>
                                            <td><a class="text-danger">None</a></td>
                                        <?php }?>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        <?php } ?>
                    </div>

                    <!-- customerProfileModal (Bootstrap MODAL) -->
                    <div class="modal fade" id="customerProfileModal" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content container">
                                <div class="modal-body">
                                    <!-- table -->
                                    <div class="table-responsive col-lg-12">
                                        <table class="table table-bordered table-hover col-lg-12 text-start">
                                            <tbody>
                                                <?php
                                                    $query = "select a.*,b.* from weboms_user_tb a inner join weboms_userInfo_tb b on a.user_id = b.user_id where a.user_id = '$_GET[viewCustomerInfo]' ";
                                                    $resultSet =  getQuery2($query);
                                                    if($resultSet!= null)
                                                    foreach($resultSet as $row){ 
                                                    // init
                                                    $id = $row['id'];
                                                    $name = $row['name'];
                                                    $picName = $row['picName'];
                                                    $username = $row['username'];
                                                    $g = $row['gender'];
                                                    $phoneNumber = $row['phoneNumber'];
                                                    $address = $row['address'];
                                                    $balance = $row['balance'];
                                                    $email = $row['email'];
                                                    //gender process
                                                    $g = $row['gender'];
                                                    if($g == 'm'){
                                                        $gender = 'male';
                                                        $genderIndex = 0;
                                                    }
                                                    elseif($g == 'f'){
                                                        $gender = 'female';
                                                        $genderIndex = 1;
                                                    }else{
                                                        $gender = 'NA';
                                                        $genderIndex = 2;
                                                    }
                                                    ?>
                                                    <?php if($picName != null){ ?>
                                                        <tr class="text-center">
                                                            <th colspan="2"><img src="../profilePic/<?php echo $picName; ?>" style="width:200px;height:200px;border:1px solid black;"></th>
                                                        </tr>
                                                    <?php } ?>
                                                    <tr>
                                                        <td><b>NAME</b></td>
                                                        <td><?php echo ucwords($name);?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>USERNAME</b></td>
                                                        <td><?php echo $username;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>EMAIL</b></td>
                                                        <td><?php echo $email;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>GENDER</b></td>
                                                        <td><?php echo ucwords($gender);?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>PHONE NUMBER</b></td>
                                                        <td><?php echo $phoneNumber;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>ADDRESS</b></td>
                                                        <td><?php echo ucwords($address);?></td>
                                                    </tr>
                                                    <tr class="bg-success text-white">
                                                        <td><b>BALANCE</b></td>
                                                        <td><b><?php echo '₱'. number_format($balance,2);?></b></td>
                                                    </tr>
                                                    <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
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
// auto refresh 
// function autoRefresh_Tables() {
    // $("#tb1").load("adminOrders.php #tb1", function() {
        // setTimeout(autoRefresh_Tables, 2000);
//     });
// }
// autoRefresh_Tables();
</script>

<?php 
    //button to serve order
    if(isset($_GET['serve'])){
        $order_id = $_GET['serve'];
        $query = "UPDATE weboms_order_tb SET status='serving' WHERE order_id='$order_id' ";     
        if(Query2($query)){
            echo "<SCRIPT>  window.location.replace('adminOrders.php'); alert('SUCCESS!');</SCRIPT>";
        }
    }

    //button to make order complete
    if(isset($_GET['orderComplete'])){
        $order_id = $_GET['orderComplete'];
        $query = "UPDATE weboms_order_tb SET status='complete' WHERE order_id='$order_id' ";     
        if(Query2($query))
            echo "<SCRIPT>  window.location.replace('adminOrders.php'); alert('SUCCESS!');</SCRIPT>";
    }

    //void button
    if(isset($_GET['void'])){
        $arr = explode(',',$_GET['void']);
        $order_id = $arr[0];
        $user_id = $arr[1];
        $totalOrder = $arr[2];
        $query = "UPDATE weboms_order_tb SET status='void' WHERE order_id='$order_id' ";     
        $query2 = "UPDATE weboms_userInfo_tb SET balance = (balance + '$totalOrder') WHERE user_id= '$user_id' ";    
        if(Query2($query)){
            if(Query2($query2)){
                echo "<SCRIPT>  window.location.replace('adminOrders.php'); alert('SUCCESS!');</SCRIPT>";
            }
        }


        $dishesArr = array();
        $dishesQuantity = array();

        $query = "select a.*, b.* from weboms_menu_tb a inner join weboms_ordersDetail_tb b on a.orderType = b.orderType where b.order_id = '$order_id' ";
        $resultSet = getQuery2($query); 

        foreach($resultSet as $row){
            array_push($dishesArr,$row['dish']);
            array_push($dishesQuantity,$row['quantity']);
        }
            
        for($i=0; $i<count($dishesArr); $i++){ 
            $updateQuery = "UPDATE weboms_menu_tb SET stock = (stock + '$dishesQuantity[$i]') WHERE dish= '$dishesArr[$i]' ";    
            Query2($updateQuery);    
        }
    }

    //view customer info
    if(isset($_GET['viewCustomerInfo'])){
        echo "<script>$('#customerProfileModal').modal('show');</script>";
    }
?>

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
<script>
    $('#tb1').dataTable({
    "columnDefs": [
        { "targets": [6,7,8,9], "orderable": false }
    ]
    });
</script>