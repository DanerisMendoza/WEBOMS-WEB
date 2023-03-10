<?php
  $page = 'admin';
  include('../method/checkIfAccountLoggedIn.php');
  include_once('../method/query.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Up</title>

    <link rel="stylesheet" href="../css/bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <script type="text/javascript" src="../js/bootstrap 5/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/jquery-3.6.1.min.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
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
                <li class="mb-2 active">
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
                <div class="row justify-content-center">
                    <?php
                        $query = "SELECT a.*,b.name FROM `weboms_topUp_tb` a inner join weboms_userInfo_tb b on a.user_id = b.user_id";
                        $resultSet =  getQuery2($query);
                    ?>
                    <div class="table-responsive col-lg-12">
                        <table class="table table-bordered table-hover col-lg-12" id="tb1">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">NAME</th>
                                    <th scope="col">AMOUNT</th>
                                    <th scope="col">STATUS</th>
                                    <th scope="col">DATE & TIME (MM/DD/YYYY)</th>
                                    <th scope="col">PAYMENT</th>
                                    <th scope="col">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if($resultSet!= null)
                                    foreach($resultSet as $row){ ?>
                                <tr>
                                    <td><?php echo ucwords($row['name']); ?></td>
                                    <td><?php echo '₱'. number_format($row['amount'],2);?></td>
                                    <td><?php echo ucwords($row['status']);?></td>
                                    <td><?php echo date('m/d/Y h:i a ', strtotime($row['date']));?></td>
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
                                    <?php  echo "<img src='../payment/$_GET[viewPic]' style=width:300px;height:550px>";?>
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
        $query = "UPDATE weboms_topUp_tb SET status='approved' WHERE id='$id' ";     
        if(Query2($query)){
            $query = "UPDATE weboms_userInfo_tb SET balance = (balance + '$amount') where user_id = '$user_id' ";     
            if(Query2($query)){
                echo "<SCRIPT>  window.location.replace('adminTopUp.php'); alert('Success!');</SCRIPT>";
            }
        }

    }
    //disapprove
    if(isset($_GET['disapprove'])){
        $query = "UPDATE weboms_topUp_tb SET status='disapproved' WHERE id = '$_GET[disapprove]' ";     
        if(Query2($query))
            echo "<SCRIPT>  window.location.replace('adminTopUp.php'); alert('Success!');</SCRIPT>";
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
    $(document).ready(function() {
        $('#tb1').DataTable();
    });
    $('#tb1').dataTable({
    "columnDefs": [
        { "targets": [4,5], "orderable": false }
    ]
    });
</script>