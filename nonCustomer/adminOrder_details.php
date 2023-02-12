<?php      
  $page = 'cashier';
  include('../method/checkIfAccountLoggedIn.php');
  include('../method/query.php');
  $arr = explode(',',$_GET['order_id']);
  $id = $arr[0];
  $query = "select a.*, b.* from weboms_userInfo_tb a right join weboms_order_tb b on a.user_id = b.user_id  where b.order_id = '$id' " ;
  $resultSet = getQuery2($query); 
  if($resultSet != null){
    foreach($resultSet as $row){ 
        //init
        $_SESSION['order_id'] = $row['order_id'];
    
        $or_last = $row['or_number'];
        $inputSize = strlen(strval($or_last));
        if($inputSize > 4)
            $str_length = $inputSize;
        else
            $str_length = 4;
        $temp = substr("0000{$or_last}", -$str_length);
        $_SESSION['or_number'] = $or_number = $temp;

        $_SESSION['customerName'] = $row['name'];
        $_SESSION['date'] = $row['date'];
        $_SESSION['cash'] = $row['payment'];
        $_SESSION['total'] = $row['totalOrder'];
        $_SESSION['name'] = $row['staffInCharge'];
        $_SESSION['staffInCharge'] = $row['staffInCharge'];
        $customerName = $row['name'];
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Orders - Order Details</title>

    <link rel="stylesheet" type="text/css" href="../css/bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/admin.css">
    <script type="text/javascript" src="../js/jquery-3.6.1.min.js"></script>  
    <script src="../js/bootstrap 5/bootstrap.min.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>

<body>

    <div class="wrapper">
     <!-- Sidebar  -->
     <nav id="sidebar" class="bg-dark">
            <div class="sidebar-header bg-dark">
                <h3 class="mt-3"><a href="../admin.php"><?php echo ucwords($_SESSION['accountType']); ?></a></h3>
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
                    <button type="button" id="sidebarCollapse" class="btn" style="font-size:20px;"><i class="bi bi-list"></i> Toggle (Order Details)</button>
                </div>
            </nav>

            <!-- content here -->
            <div class="container-fluid text-center">
                <div class="row justify-content-center">
                    <div class="col-lg-12 cont2">
                        <div class="btn-group container-fluid" role="group" aria-label="Basic mixed styles example">
                            <button class="btn btn-lg btn-dark col-6 mb-3" id="back"><i class="bi bi-arrow-left-short"></i> Back</button>
                            <?php if(!($customerName == '' && $_SESSION['staffInCharge'] == 'online order')){?>
                            <button class="btn btn-lg btn-danger col-6 mb-3" id="viewInPdf"><i class="bi bi-file-pdf"></i> PDF</button>
                            <?php } ?>
                        </div>
                        <!-- table -->
                        <div class="table-responsive col-lg-12">
                            <?php 
                                $_SESSION['dishesArr'] = array();
                                $_SESSION['priceArr'] = array();
                                $_SESSION['dishesQuantity'] = array();

                                 //company variables init
                                $query = "select * from weboms_company_tb";
                                $resultSet = getQuery2($query);
                                if($resultSet!=null){
                                    foreach($resultSet as $row){
                                    $_SESSION['companyName'] = $row['name'];
                                    $_SESSION['companyAddress'] = $row['address'];
                                    $_SESSION['companyTel'] = $row['tel'];
                                    }
                                }

                                $query = "select a.*, b.* from weboms_menu_tb a inner join weboms_ordersDetail_tb b on a.orderType = b.orderType where b.order_id = '$id' ";
                                $resultSet = getQuery2($query); 
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
                                        $price = ($row['price']*$row['quantity']);  $total += $price;
                                        array_push($_SESSION['priceArr'],$price);
                                        array_push($_SESSION['dishesQuantity'],$row['quantity']);
                                        ?>

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
                                        <?php $payment = getQueryOneVal2("SELECT a.payment FROM weboms_order_tb a where a.order_id = '$id' ",'payment');?>
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
        window.open("../pdf/receipt.php");
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