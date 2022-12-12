<?php      
  $page = 'cashier';
  include('method/checkIfAccountLoggedIn.php');
  include('method/query.php');
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>View Orders</title>

    <link rel="stylesheet" type="text/css" href="css/bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>  
    <script src="js/bootstrap 5/bootstrap.min.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>

<body>

    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar" class="bg-dark">
            <div class="sidebar-header bg-dark">
            <h3 class="mt-3"><a href="admin.php"><?php echo $_SESSION['accountType']; ?></a></h3>
            </div>
            <ul class="list-unstyled components ms-3">
                <li class="mb-2">
                    <a href="#" id="pos"><i class="bi bi-tag me-2"></i>Point of Sales</a>
                </li>
                <li class="mb-2 active">
                    <a href="#"><i class="bi bi-minecart me-2"></i>Orders</a>
                </li>
                <li class="mb-2">
                    <a href="#" id="ordersQueue"><i class="bi bi-clock me-2"></i>Orders Queue</a>
                </li>

            <?php if($_SESSION['accountType'] != 'cashier'){?>

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
                <li class="mb-1">
                    <a href="#" id="settings"><i class="bi bi-gear me-2"></i>Settings</a>
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
                    <button type="button" id="sidebarCollapse" class="btn" style="font-size:20px;"><i class="bi bi-list"></i> Dashboard (Order Details)</button>
                </div>
            </nav>

            <!-- content here -->
            <div class="container-fluid text-center">
                <div class="row justify-content-center">
                    <div class="col-lg-12 cont2">
                        <div class="btn-group container-fluid" role="group" aria-label="Basic mixed styles example">
                            <button class="btn btn-lg btn-dark col-6 mb-3" id="back"><i class="bi bi-arrow-left-short"></i> BACK</button>
                            <button class="btn btn-lg btn-danger col-6 mb-3" id="viewInPdf"><i class="bi bi-file-pdf"></i> PDF</button>
                        </div>

                        <!-- table -->
                        <div class="table-responsive col-lg-12">
                            <?php 
                                $arr = explode(',',$_GET['order_id']);
                                $id = $arr[0];
                                $_SESSION['dishesArr'] = array();
                                $_SESSION['priceArr'] = array();
                                $_SESSION['dishesQuantity'] = array();

                                $query = "select a.*, b.* from WEBOMS_userInfo_tb a inner join WEBOMS_order_tb b on a.user_id = b.user_id  where b.order_id = '$id' " ;
                                $resultSet = getQuery($query); 
                                if($resultSet != null){
                                    foreach($resultSet as $row){ 
                                        //init
                                        $_SESSION['order_id'] = $row['order_id'];
                                        $_SESSION['or_number'] = $row['or_number'];
                                        $_SESSION['customerName'] = $row['name'];
                                        $_SESSION['date'] = $row['date'];
                                        $_SESSION['cash'] = $row['payment'];
                                        $_SESSION['total'] = $row['totalOrder'];
                                        $_SESSION['name'] = $row['staffInCharge'];
                                        $_SESSION['staffInCharge'] = $row['staffInCharge'];

                                    }
                                }

                                 //company variables init
                                $query = "select * from WEBOMS_company_tb";
                                $resultSet = getQuery($query);
                                if($resultSet!=null){
                                    foreach($resultSet as $row){
                                    $_SESSION['companyName'] = $row['name'];
                                    $_SESSION['companyAddress'] = $row['address'];
                                    $_SESSION['companyTel'] = $row['tel'];
                                    }
                                }

                                $query = "select a.*, b.* from WEBOMS_menu_tb a inner join WEBOMS_ordersDetail_tb b on a.orderType = b.orderType where b.order_id = '$id' ";
                                $resultSet = getQuery($query); 
                            ?>
                            <table class="table table-bordered table-hover col-lg-12">
                                <thead>
                                    <tr>
                                        <th scope="col">DISH</th>
                                        <th scope="col">QUANTITY</th>
                                        <th scope="col">PRICE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $total = 0;
                                        if($resultSet != null)
                                        foreach($resultSet as $row){ ?>
                                    <tr>
                                        <?php 
                                        array_push($_SESSION['dishesArr'],$row['dish']);
                                        array_push($_SESSION['priceArr'],$row['price']);
                                        array_push($_SESSION['dishesQuantity'],$row['quantity']);
                                        $price = ($row['price']*$row['quantity']);  $total += $price;?>

                                        <td><?php echo ucwords($row['dish']); ?></td>
                                        <td><?php echo $row['quantity']; ?></td>
                                        <td><?php echo '₱'. number_format($price,2)?></td>
                                    </tr>
                                    <?php }?>
                                    <tr>
                                        <td colspan="2"><b>Total Amount:</b></td>
                                        <td><b>₱<?php echo number_format($total,2)?></b></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><b>Payment:</b></td>
                                        <?php $payment = getQueryOneVal("SELECT a.payment FROM WEBOMS_order_tb a where a.order_id = '$id' ",'payment');?>
                                        <td><b>₱<?php echo number_format($payment,2); ?></b></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><b>Change:</b></td>
                                        <td><b>₱<?php echo number_format($payment-$total,2); ?></b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script>
//order button (js)
var viewInPdf = document.getElementById("viewInPdf");
viewInPdf.addEventListener("click", () => {
        window.open("pdf/receipt.php");
});
</script>


<script>
var from = "<?php echo $_SESSION['from'];?>";
document.getElementById("back").onclick = function() {
    if (from == 'adminSalesReport')
        window.location.replace('adminSalesReport.php');
    else
        window.location.replace('adminOrders.php');
}
</script>

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
    document.getElementById("ordersQueue").onclick = function() { window.location.replace('adminOrdersQueue.php'); };
    document.getElementById("inventory").onclick = function() { window.location.replace('adminInventory.php'); };
    document.getElementById("salesReport").onclick = function() { window.location.replace('adminSalesReport.php'); };
    document.getElementById("accountManagement").onclick = function() { window.location.replace('accountManagement.php'); };
    document.getElementById("customerFeedback").onclick = function() { window.location.replace('adminFeedbackList.php'); };
    document.getElementById("adminTopUp").onclick = function() { window.location.replace('adminTopUp.php'); };
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