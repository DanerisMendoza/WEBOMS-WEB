<?php 
    $page = 'cashier';
    include('../../method/checkIfAccountLoggedIn.php');
    include('../../method/query.php');
    if(!isset($_SESSION["dishes"]) && !isset($_SESSION["price"])){
        $_SESSION["dishes"] = array();
        $_SESSION["price"] = array(); 
        $_SESSION["orderType"] = array(); 
    }
    $_SESSION['query'] = 'all';
    $_SESSION['refreshCount'] = 0;
    $_SESSION['multiArr'] = array();
    $_SESSION['fromReceipt'] = 'pos';
    //company variables init
    $query = "select * from weboms_company_tb";
    $resultSet = getQuery3($query);
    if($resultSet!=null)
        foreach($resultSet as $row){
          $_SESSION['companyName'] = $row['name'];
          $_SESSION['companyAddress'] = $row['address'];
          $_SESSION['companyTel'] = $row['tel'];
        }
    // redefining name
    $_SESSION['name'] = getQueryOneVal3("select name from weboms_userInfo_tb where user_id = '$_SESSION[user_id]' ",'name');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Point of Sales</title>

    <link rel="stylesheet" href="../../css/bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/admin.css">
    <script type="text/javascript" src="../../js/bootstrap 5/bootstrap.min.js"></script>
    <script type="text/javascript" src="../../js/jquery-3.6.1.min.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <!-- data tables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
</head>

<body>

    <div class="wrapper">
       <!-- Sidebar  -->
       <nav id="sidebar" class="bg-dark">
            <div class="sidebar-header bg-dark">
                <h3 class="mt-3"><a href="../admin.php"><?php echo ucwords($_SESSION['accountType']); ?></a></h3>
            </div>
            <ul class="list-unstyled components ms-3">
                <li class="mb-2 active">
                    <a href="../adminPos.php"><i class="bi bi-tag me-2"></i>Point of Sales</a>
                </li>
                <li class="mb-2">
                    <a href="../adminOrders.php"><i class="bi bi-minecart me-2"></i>Orders</a>
                </li>
                <li class="mb-2">
                    <a href="../ordersQueue/adminOrdersQueue.php"><i class="bi bi-clock me-2"></i>Orders Queue</a>
                </li>
            
            <?php if($_SESSION['accountType'] != 'cashier'){?>
                <li class="mb-2">
                    <a href="../adminInventory.php"><i class="bi bi-box-seam me-2"></i>Inventory</a>
                </li>
                <li class="mb-2">
                    <a href="../adminSalesReport.php"><i class="bi bi-bar-chart me-2"></i>Sales Report</a>
                </li>
                <li class="mb-2">
                    <a href="../accountManagement.php"><i class="bi bi-person-circle me-2"></i>Account Management</a>
                </li>
                <li class="mb-2">
                    <a href="../adminFeedbackList.php"><i class="bi bi-chat-square-text me-2"></i>Customer Feedback</a>
                </li>
                <li class="mb-2">
                    <a href="../adminTopUp.php"><i class="bi bi-cash-stack me-2"></i>Top-Up</a>
                </li>
                <li class="mb-1">
                    <a href="../settings.php"><i class="bi bi-gear me-2"></i>Settings</a>
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
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn" style="font-size:20px;"><i class="bi bi-list"></i> Toggle</button>
                </div>
            </nav>

            <!-- content here -->
            <div class="container-fluid text-center">
                <div class="row g-5 justify-content-center">
                    <!-- admin -->
                    <?php if($_SESSION['accountType'] != 'cashier'){?>
                    <h1 class="text-center bg-dark text-white"><?php echo strtoupper($_SESSION['accountType']); ?></h1>

                    <!-- cashier -->
                    <?php }else{?>
                    <h1 class="text-center bg-danger text-white">CASHIER</h1>
                    <?php }?>

                    <!-- table container -->
                    <div class="table-responsive col-lg-7">
                        <table class="table table-hover table-bordered col-lg-12" id="tbl">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">DISH</th>
                                    <th scope="col">PRICE</th>
                                    <th scope="col">STOCK</th>
                                    <th scope="col">ADD TO CART</th>
                                </tr>
                            </thead>
                            <tbody id="tBody1">
                            <?php 
                                $query = "select * from weboms_menu_tb";
                                $resultSet =  getQuery3($query);
                                if($resultSet != null)
                                    foreach($resultSet as $row){ ?>
                            <tr>
                                <td><?= ucwords($row['dish']);?></td>
                                <td><?php echo "₱".number_format($row['price'],2); ?></td>
                                <td><?php echo $row['stock']; ?></td>
                                <!-- add to cart -->
                                <td>
                                    <!-- out of stock -->
                                    <?php if($row['stock'] <= 0){ ?>
                                        <a class="text-danger">Out of Stock</a>
                                        <!-- not out of stock -->
                                        <?php } else{ ?>
                                            <form method="post">
                                                <input type="hidden" name="
                                                " value="<?php echo $row['dish'].",".$row['price'].",".$row['orderType'].",".$row['stock']?>">
                                                <input type="number" placeholder="Quantity" name="qty" class="form-control" value="1">
                                                <button type="submit" name="addToCartSubmit" class="btn btn-light col-12" style="border:1px solid #cccccc;"><i class="bi bi-cart-plus"></i></button>
                                            </form>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- 2nd table container -->
                    <div class="table-responsive col-lg-5 mb-5">
                        <table class="table table-bordered table-hover col-lg-12 mb-4">
                            <thead>
                                <tr>
                                    <th scope="col">DISH</th>
                                    <th scope="col" colspan="2">QUANTITY</th>
                                    <th scope="col">PRICE</th>
                                </tr>
                            </thead>
                            <tbody id="tBody2">
                            <?php 
                                $dishesArr = array();
                                $priceArr = array();
                                $dishesQuantity = array();
                                $orderType = array();

                                //merge repeating order into 1 
                                for($i=0; $i<count($_SESSION['dishes']); $i++){
                                    if(in_array( $_SESSION['dishes'][$i],$dishesArr)){
                                        $index = array_search($_SESSION['dishes'][$i], $dishesArr);
                                        $newCost = $priceArr[$index] + $_SESSION['price'][$i];
                                                    $priceArr[$index] = $newCost;
                                    }
                                    else{
                                        array_push($dishesArr,$_SESSION['dishes'][$i]);
                                        array_push($priceArr,$_SESSION['price'][$i]);
                                        array_push($orderType,$_SESSION['orderType'][$i]);
                                    }
                                }
                                //push order quantity into arrray
                                foreach(array_count_values($_SESSION['dishes']) as $count){
                                    array_push($dishesQuantity,$count);
                                }

                                //merge 3 array into 1 multi dimensional
                                for($i=0; $i<count($dishesArr); $i++){ 
                                    $arr = array('dish'=> $dishesArr[$i], 'price' => $priceArr[$i], 'quantity' => $dishesQuantity[$i], 'orderType' => $orderType[$i]);
                                    array_push($_SESSION['multiArr'],$arr);
                                }
                                //sort multi dimensional
                                sort($_SESSION['multiArr']);
                                $total = 0;
                                for($i=0; $i<count($priceArr); $i++){
                                    $total += $priceArr[$i];
                                }

                                //populate table using the multi dimensional array
                                foreach($_SESSION['multiArr'] as $arr){ ?>
                                <tr>
                                    <td><?php echo ucwords($arr['dish']);?></td>
                                    <td><?php echo $arr['quantity'];?></td>
                                    <td>
                                        <!-- check stock -->
                                        <?php if(getQueryOneVal3("select stock from weboms_menu_tb where dish = '$arr[dish]' ",'stock') > 0) { ?>
                                        <!-- quantity plus -->
                                        <a class="btn btn-success" href="?add=<?php echo $arr['dish'].','.($arr['price']/$arr['quantity']).','.$arr['orderType']; ?>"><i class="bi bi-plus"></i></a>
                                        <?php }else{ ?>
                                        <a class="text-danger me-2">Out of Stock</a>
                                        <?php } ?>
                                        <!-- quantity minus -->
                                        <a class="btn btn-danger" href="?minus=<?php echo $arr['dish'].','.($arr['price']/$arr['quantity']).','.$arr['orderType']; ?>"><i class="bi bi-dash"></i></a>
                                    </td>
                                    <td><?php echo '₱'.number_format($arr['price'],2);?></td>
                                </tr>
                                    <?php }?>
                                <tr>
                                    <td colspan="3"><b>Total Amount:</b></td>
                                    <td><b>₱<?php echo number_format($total,2); ?></b></td>
                                </tr>
                            </tbody>
                        </table>
                        <form method="post">
                            <input name="customerName" placeholder="Customer Name (Optional)" type="text" class="form-control form-control-lg mb-3">
                            <input id="cashNum" name="cash" min="<?php echo $total;?>" step=any placeholder="Cash Amount (₱)" type="number" class="form-control form-control-lg mb-4" required>
                            <button id="orderBtn" type="submit" class="btn btn-lg btn-success col-12 mb-3" name="orderBtn">Place Order</button>
                        </form>
                        <form method="post">
                            <button type="submit" id="clear" class="btn btn-lg btn-danger col-12" name="clear">Clear Order</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
<script>
    // sidebar(js)
    $(document).ready(function() {
        $('#sidebarCollapse').on('click', function() {
            $('#sidebar').toggleClass('active');
        });
    });

    //order button (js)
    var orderBtn = document.getElementById("orderBtn");
    orderBtn.addEventListener("click", () => {
        var num = document.getElementById("cashNum").value;
        if (<?php echo $total == 0 ? 'true':'false';?>) {
            alert('Please place your order!');
            return;
        }
        if (num >= <?php echo $total;?>) {
            alert("Success placing order!");
            window.open("../../pdf/receipt.php");
        }
    });

    // data table
    $(document).ready(function() {
        $('#tbl').DataTable();
    });

    function reloadTables() {
        // pending to refresh table without refresh
        // $("#tBody1").load("tBody1.php", function() {});
        // $("#tBody2").load("tBody2.php", function() {});
        // $("#tBody1").load(location.href+ " #tBody1");
    }
    // reloadTables();  
</script>

<?php 
    //clear button
    if(isset($_POST['clear'])){
        for($i=0; $i<count($dishesArr); $i++){ 
            $updateQuery = "UPDATE weboms_menu_tb SET stock = (stock + '$dishesQuantity[$i]') WHERE dish= '$dishesArr[$i]' ";    
            Query3($updateQuery);    
        }
        $_SESSION["dishes"] = array();
        $_SESSION["price"] = array();
        $_SESSION["orderType"] = array(); 
        echo "<script>window.location.replace('adminPos.php');</script>";
    }
    
    //add to cart
    if(isset($_POST['addToCartSubmit'])){
      $order = explode(',',$_POST['order']);  
      //init
      $dish = $order[0];
      $price = $order[1];
      $orderType = $order[2];
      $stock = $order[3];
      $qty = $_POST['qty'];
    //   validation
      if($qty <= 0 && !str_contains($qty, '.')){
        die ("<script>
        alert('Quantity invalid');
        window.location.replace('adminPos.php');
        </script>");    
      }
      if($qty > $stock){
        die ("<script>
        alert('Stock is less than Quantity');
        window.location.replace('adminPos.php');
        </script>");    
      }
      //process
      for($i=0; $i<$qty; $i++){
        array_push($_SESSION['dishes'], $dish);
        array_push($_SESSION['price'], $price);
        array_push($_SESSION['orderType'], $orderType);
      }
      $updateQuery = "UPDATE weboms_menu_tb SET stock = (stock - $qty) WHERE dish= '$dish' ";    
      if(Query3($updateQuery)){
        echo "<script>window.location.replace('adminPos.php');</script>";    
        // echo "<script>reloadTables();</script>";    
      }
    }

    // add quantity
    if(isset($_GET['add'])){
        $arr = explode(',',$_GET['add']);
        $dish = $arr[0];
        $price = $arr[1];
		    $orderType = $arr[2];
        array_push($_SESSION['dishes'], $dish);
        array_push($_SESSION['price'], $price);
        array_push($_SESSION['orderType'], $orderType);

        $updateQuery = "UPDATE weboms_menu_tb SET stock = (stock - 1) WHERE dish= '$dish' ";    
        if(Query3($updateQuery))
          echo "<script>window.location.replace('adminPos.php');</script>";    
    }

    //minus quantity
    if(isset($_GET['minus'])){
        $arr = explode(',',$_GET['minus']);
        $dish = $arr[0];
        $price = $arr[1];
        $orderType = $arr[2];
       
        //remove one order 
        $key = array_search($dish, $_SESSION['dishes']);
        unset($_SESSION['dishes'][$key]);
        unset($_SESSION['price'][$key]);
        unset($_SESSION['orderType'][$key]);

        //refresh the array
        $_SESSION['dishes'] = array_values($_SESSION['dishes']);
        $_SESSION['price'] = array_values($_SESSION['price']);
        $_SESSION['orderType'] = array_values($_SESSION['orderType']);

        $updateQuery = "UPDATE weboms_menu_tb SET stock = (stock + 1) WHERE dish= '$dish' ";    
        if(Query3($updateQuery))
            echo "<script>window.location.replace('adminPos.php');</script>";    
    }

    //order button (php)
    if(isset($_POST['orderBtn'])){
        $cash = $_POST['cash'];
        $customerName = $_POST['customerName'];
        if($cash >= $total && $total != 0){
            $_SESSION['continue'] = true;
            date_default_timezone_set('Asia/Manila');
            $date = new DateTime();
            $today =  $date->format('Y-m-d'); 
            $todayWithTime =  $date->format('Y-m-d H:i:s'); 

            //or number process
            $or_last = getQueryOneVal3("select or_number from weboms_order_tb WHERE id = (SELECT MAX(ID) from weboms_order_tb)","or_number");
            if($or_last == null){
                $or_last = 1;
            }
            else{
                $or_last = $or_last + 1;
            }
            $inputSize = strlen(strval($or_last));
            if($inputSize > 4)
                $str_length = $inputSize;
            else
                $str_length = 4;
            $temp = substr("0000{$or_last}", -$str_length);
            $or_number = $temp;

            // init sessions
            $_SESSION['or_number'] = $or_number;
            $_SESSION['customerName'] = $customerName;
            $_SESSION['staffInCharge'] = 'POS';
            $_SESSION['date'] = $todayWithTime;
            $_SESSION['cash'] = $cash;
            $_SESSION['total'] = $total;
            $_SESSION['dishesArr'] = $dishesArr;
            $_SESSION['priceArr'] = $priceArr;
            $_SESSION['dishesQuantity'] = $dishesQuantity;
            $staff = $_SESSION['name'];

            //get two user id from different table
            $lastUserIdOrder = getQueryOneVal3("SELECT MAX(user_id) from weboms_order_tb","MAX(user_id)");
            $lastUserIdUserInfo = getQueryOneVal3("SELECT MAX(user_id) from weboms_userInfo_tb","MAX(user_id)");
            //compare which user id is higher 
            if($lastUserIdOrder > $lastUserIdUserInfo)
                $user_id = $lastUserIdOrder;
            else
                $user_id = $lastUserIdUserInfo;   
            // increment user id
            $user_id++;

            //increment order id
            $lastOrderId = getQueryOneVal3("select order_id from weboms_order_tb WHERE order_id = (SELECT MAX(order_id) from weboms_order_tb)","order_id");
            if($lastOrderId == null){
                $lastOrderId = rand(1111,9999);
            }
            else{
                $lastOrderId = $lastOrderId + 1;
            }
            $order_id = $lastOrderId;

            $_SESSION['order_id'] = $order_id;
            $query1 = "insert into weboms_order_tb(user_id, order_id, or_number, status, date, totalOrder, payment,  staffInCharge) values('$user_id', '$order_id', '$or_number', 'prepairing', '$todayWithTime','$total','$cash', '$staff')";
            for($i=0; $i<count($dishesArr); $i++){
                $query2 = "insert into weboms_ordersDetail_tb(order_id, quantity, orderType) values('$order_id',$dishesQuantity[$i], $orderType[$i])";
                Query3($query2);
            }
            $query3 = "insert into weboms_userInfo_tb(name,user_id) values('$customerName','$user_id')";
            if($customerName != '')
                Query3($query3);
            Query3($query1);
            $_SESSION["dishes"] = array();
            $_SESSION["price"] = array();
            $_SESSION["orderType"] = array(); 
            echo "<script>window.location.replace('adminPos.php');</script>";
        }   
    }

    // logout button
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
                Query3($updateQuery);    
            }   
        }
        session_destroy();
        echo "<script>window.location.replace('../general/login.php');</script>";
    }
?>