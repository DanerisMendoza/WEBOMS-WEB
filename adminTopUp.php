<?php
  $page = 'admin';
  include('method/checkIfAccountLoggedIn.php');
  include_once('method/query.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>

    <link rel="stylesheet" href="css/bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" href="css/admin.css">
    <script type="text/javascript" src="js/bootstrap 5/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
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
                <li class="mb-2 active">
                    <a href="#"><i class="bi bi-cash-stack me-2"></i>Top-Up</a>
                </li>
                <li class="mb-1">
                    <a href="#" id="settings"><i class="bi bi-gear me-2"></i>Settings</a>
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
                    <button type="button" id="sidebarCollapse" class="btn" style="font-size:20px;"><i class="bi bi-list"></i> Dashboard</button>
                </div>
            </nav>
            <!-- content here -->
            <div class="container-fluid text-center">
                <div class="row justify-content-center">
                    <?php
                        $query = "SELECT a.*,b.name FROM `WEBOMS_topUp_tb` a inner join WEBOMS_userInfo_tb b on a.user_id = b.user_id";
                        $resultSet =  getQuery($query);
                    ?>
                    <div class="table-responsive col-lg-12">
                        <table class="table table-bordered mb-5 col-lg-12">
                            <thead>
                                <tr>
                                    <th scope="col">NAME</th>
                                    <th scope="col">AMOUNT</th>
                                    <th scope="col">STATUS</th>
                                    <th scope="col">DATE & TIME</th>
                                    <th scope="col">PAYMENT</th>
                                    <th scope="col">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if($resultSet!= null)
                                    foreach($resultSet as $row){ ?>
                                <tr>
                                    <!-- name -->
                                    <td><?php echo ucwords($row['name']); ?></td>
                                    <!-- amount -->
                                    <td><?php echo '₱'. number_format($row['amount'],2);?></td>
                                    <!-- status -->
                                    <td><?php echo ucwords($row['status']);?></td>
                                    <!-- date and time -->
                                    <td><?php echo date('m/d/Y h:i a ', strtotime($row['date']));?></td>
                                    <!-- proof of payment -->
                                    <td>
                                        <a class="btn btn-light" style="border:1px solid #cccccc;" href="?viewPic=<?php echo $row['proofOfPayment'];?>"><i class="bi bi-list"></i> View</a>
                                    </td>
                                    <!-- action -->
                                    <?php if($row['status'] == 'pending') {?>
                                    <td>
                                        <a class="btn btn-success" href="?approve=<?php echo $row['id'].','.$row['user_id'].','.$row['amount'];?>"><i class="bi bi-check"></i> Approve</a>
                                        <a class="btn btn-danger" href="?disapprove=<?php echo $row['id'];?>"><i class="bi bi-x"></i> Disapprove</a>
                                    </td>
                                    <?php } else {?>
                                    <td class="text-danger">None</td>
                                    <?php } ?>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- pic (Bootstrap MODAL) -->
                    <div class="modal fade" id="viewPic" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content container">
                                <div class="modal-body">
                                    <?php  echo "<img src='payment/$_GET[viewPic]' style=width:300px;height:550px>";?>
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
<?php 
    //view pic
    if(isset($_GET['viewPic'])){
        echo "<script>$('#viewPic').modal('show');</script>";
    }
    //approve
    if(isset($_GET['approve'])){
        $arr = explode(',',$_GET['approve']);
        $id = $arr[0];
        $user_id = $arr[1];
        $amount = $arr[2];
        $query = "UPDATE WEBOMS_topUp_tb SET status='approved' WHERE id='$id' ";     
        if(Query($query)){
            $query = "UPDATE WEBOMS_userInfo_tb SET balance = (balance + '$amount') where user_id = '$user_id' ";     
            if(Query($query)){
                echo "<SCRIPT>  window.location.replace('adminTopUp.php'); alert('success!');</SCRIPT>";
            }
        }

    }
    //disapprove
    if(isset($_GET['disapprove'])){
        $query = "UPDATE WEBOMS_topUp_tb SET status='disapproved' WHERE id = '$_GET[disapprove]' ";     
        if(Query($query))
            echo "<SCRIPT>  window.location.replace('adminTopUp.php'); alert('success!');</SCRIPT>";
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

<script>
// for navbar click locations
document.getElementById("pos").onclick = function() { window.location.replace('adminPos.php'); };
document.getElementById("orders").onclick = function() { window.location.replace('adminOrders.php'); };
document.getElementById("ordersQueue").onclick = function() { window.location.replace('adminOrdersQueue.php'); };
document.getElementById("inventory").onclick = function() { window.location.replace('adminInventory.php'); };
document.getElementById("salesReport").onclick = function() { window.location.replace('adminSalesReport.php'); };
document.getElementById("accountManagement").onclick = function() { window.location.replace('accountManagement.php'); };
document.getElementById("customerFeedback").onclick = function() { window.location.replace('adminFeedbackList.php'); };
document.getElementById("settings").onclick = function() { window.location.replace('settings.php'); };
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
                $updateQuery = "UPDATE WEBOMS_menu_tb SET stock = (stock + '$dishesQuantity[$i]') WHERE dish= '$dishesArr[$i]' ";    
                Query($updateQuery);    
            }
        }
        session_destroy();
        echo "<script>window.location.replace('Login.php');</script>";
    }
?>