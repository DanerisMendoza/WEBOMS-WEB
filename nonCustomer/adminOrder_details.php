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
    <title>ORDERS | ORDER DETAILS</title>

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
                <li><a href="adminOrders.php" class="active text-danger"><i class="bi-cart me-2"></i>ORDERS</a></li>
                <li><a href="adminOrdersQueue.php"><i class="bi-clock me-2"></i>ORDERS QUEUE</a></li>
                <li><a href="topupRfid.php"><i class="bi-credit-card me-2"></i>TOP-UP RFID</a></li>

                <?php if($_SESSION['accountType'] != 'cashier'){?>
                <li><a href="adminTopUp.php"><i class="bi-wallet me-2"></i>TOP-UP</a></li>
                <li><a href="adminInventory.php"><i class="bi-box me-2"></i>INVENTORY</a></li>
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
                <div class="input-group">
                    <button class="btn btn-dark w-50" id="back"><i class="bi-arrow-left"></i>BACK</button>
                    <?php if(!($customerName == '' && $_SESSION['staffInCharge'] == 'online order')){?>
                    <button class="btn btn-danger w-50" id="viewInPdf">PDF</button>
                    <?php } ?>
                </div>
                <div class="table-responsive mt-3">
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
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>DISH</th>
                                <th>QUANTITY</th>
                                <th>PRICE</th>
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
                                <td></td>
                                <td><b>Total Amount:</b></td>
                                <td><b>₱<?php echo number_format($total,2)?></b></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><b>Payment:</b></td>
                                <?php $payment = getQueryOneVal2("SELECT a.payment FROM weboms_order_tb a where a.order_id = '$id' ",'payment');?>
                                <td><b>₱<?php echo number_format($payment,2); ?></b></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><b>Change:</b></td>
                                <td><b>₱<?php echo number_format($payment-$total,2); ?></b></td>
                            </tr>
                        </tbody>
                    </table>
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