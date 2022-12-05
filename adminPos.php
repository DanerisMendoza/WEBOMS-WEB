<?php 
    $page = 'cashier';
    include('method/checkIfAccountLoggedIn.php');
    include('method/query.php');
    if(!isset($_SESSION["dishes"]) && !isset($_SESSION["price"])){
        $_SESSION["dishes"] = array();
        $_SESSION["price"] = array(); 
        $_SESSION["orderType"] = array(); 
    }
    $_SESSION['refreshCount'] = 0;
    $_SESSION['multiArr'] = array();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Point of Sales</title>

    <link rel="stylesheet" href="css/bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" href="css/admin.css">
    <script src="js/bootstrap 5/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>
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
                <h3 class="mt-3">Admin</h3>
            </div>
            <ul class="list-unstyled components ms-3">
                <li class="mb-2 active">
                    <a href="#">
                        <i class="bi bi-tag me-2"></i>
                        Point of Sales
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" id="orders">
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
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn" style="font-size:20px;">
                        <i class="bi bi-list"></i>
                        <span>Dashboard</span>
                    </button>
                </div>
            </nav>
            <!-- content here -->
            <div class="container-fluid text-center">
                <div class="row justify-content-center">
                    <?php if($_SESSION['accountType'] != 'cashier'){?>
                    <!-- admin -->
                    <button class="btn btn-lg btn-dark col-12 mb-4" id="admin">
                        <i class="bi bi-arrow-left"></i>
                        <span class="ms-1">ADMIN</span>
                    </button>

                    <script>
                    document.getElementById("admin").onclick = function() {
                        window.location.replace('admin.php');
                    };
                    </script>

                    <?php }else{?>
                    <!-- logout -->
                    <form method="post" class="col-12">
                        <button class="btn btn-lg btn-danger col-12 mb-4" id="Logout" name="logout">
                            <i class="bi bi-power"></i>
                            <span class="ms-1">LOGOUT</span>
                        </button>
                    </form> <br>
                    <?php }?>

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
                        echo "<script>window.location.replace('index.php');</script>";
                        }
                    ?>

                    <!-- table container -->
                    <!-- <div class="container col-lg-7">
                        <div class="row g-3">
                            <?php 
                                $query = "select * from WEBOMS_menu_tb";
                                $resultSet =  getQuery($query)
                            ?>
                            <?php 
                                if($resultSet != null)
                                foreach($resultSet as $rows){ ?>
                            <div class="col-sm-3">
                                <div class="card bg-light">
                                    <button class="btn btn-light">
                                        insert image
                                        <a href="#" class="text-decoration-none fw-normal text-danger">
                                            <?=$rows['dish']?>
                                        </a> <br>
                                        <a href="#" class="text-decoration-none fw-normal text-danger">
                                            Stock:
                                            <?php echo $rows['stock']; ?>
                                        </a> <br>
                                        <a href="#" class="text-decoration-none fw-normal text-danger">
                                            Price:
                                            <?php echo number_format($rows['price'],2); ?>
                                        </a> <br>
                                        <a class="text-decoration-none fw-normal text-black" <?php if($rows['stock'] <= 0) 
                                                    echo "<button>Out of Stock</button>";
                                                    else{
                                                ?>
                                            href="?order=<?php echo $rows['dish'].",".$rows['price'].",".$rows['orderType']?>">
                                            Add to Cart
                                        </a>
                                    </button>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div> -->

                    <!-- table container -->
                    <div class="table-responsive col-lg-6">
                        <?php 
                            $query = "select * from WEBOMS_menu_tb";
                            $resultSet =  getQuery($query)
                        ?>
                        <table class="table table-striped table-bordered col-lg-12" id="tbl">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">DISH</th>
                                    <th scope="col">PRICE</th>
                                    <th scope="col">STOCK</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    if($resultSet != null)
                                        foreach($resultSet as $rows){ ?>
                                <tr>
                                    <!-- dish -->
                                    <td><?=$rows['dish']?></td>
                                    <!-- price -->
                                    <td><?php echo number_format($rows['price'],2); ?></td>
                                    <!-- stock -->
                                    <td><?php echo $rows['stock']; ?></td>
                                    <!-- add to cart -->
                                    <td>
                                        <a class="btn btn-light border-secondary" <?php if($rows['stock'] <= 0) 
                                            echo "<button>Out of stock</button>";
                                            else{
                                        ?>
                                            href="?order=<?php echo $rows['dish'].",".$rows['price'].",".$rows['orderType']?>">
                                            <i class="bi bi-cart-plus"></i>
                                            <span class="ms-1">ADD TO CART</span>
                                        </a>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- 2nd table container -->
                    <div class="table-responsive col-lg-6">
                        <table class="table table-bordered col-lg-12">
                            <thead>
                                <tr>
                                    <th scope="col">DISH</th>
                                    <th scope="col">PRICE</th>
                                    <th scope="col" colspan="2">QUANTITY</th>
                                </tr>
                            </thead>
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

                                //create a table using the multi dimensional array
                                foreach($_SESSION['multiArr'] as $arr){ ?>
                            <tr>
                                <!-- dish -->
                                <td><?php echo $arr['dish'];?></td>
                                <!-- price -->
                                <td><?php echo '₱'.number_format($arr['price'],2);?></td>
                                <!-- quantity -->
                                <td><?php echo $arr['quantity'];?></td>
                                <td>
                                    <!-- check stock -->
                                    <?php if(getQueryOneVal("select stock from WEBOMS_menu_tb where dish = '$arr[dish]' ",'stock') > 0) { ?>
                                    <a class="btn btn-light border-secondary"
                                        href="?add=<?php echo $arr['dish'].','.($arr['price']/$arr['quantity']).','.$arr['orderType']; ?>">
                                        <i class="bi bi-plus"></i>
                                    </a>
                                    <?php }else{ ?>
                                    <a class="btn btn-success border-dark">OUT OF STOCK</a>
                                    <?php } ?>
                                    <a class="btn btn-light border-secondary"
                                        href="?minus=<?php echo $arr['dish'].','.($arr['price']/$arr['quantity']).','.$arr['orderType']; ?>">
                                        <i class="bi bi-dash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php }?>
                            <tr>
                                <!-- total amount -->
                                <td colspan="2"><b>TOTAL AMOUNT:</b></td>
                                <td><b>₱<?php echo number_format($total,2); ?></b></td>
                                <!-- check stock -->
                                <td></td>
                            </tr>
                        </table>
                        <form method="post">
                            <!-- cash amount -->
                            <input id="cashNum" name="cash" min="<?php echo $total;?>" step=any
                                placeholder="CASH AMOUNT" type="number" class="form-control form-control-lg mb-3"
                                required></input>
                            <!-- place order -->
                            <button id="orderBtn" type="submit" class="btn btn-lg btn-success col-12 mb-3" name="order">
                                <i class="bi bi-cart"></i>
                                <span class="ms-1">PLACE ORDER</span>
                            </button>
                        </form>
                        <form method="post">
                            <!-- clear order -->
                            <button type="submit" id="clear" class="btn btn-lg btn-danger col-12 mb-5" name="clear">
                                <i class="bi bi-trash"></i>
                                <span class="ms-1">CLEAR ORDER</span>
                            </button>
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
</script>

<?php 
    //clear button
    if(isset($_POST['clear'])){
        for($i=0; $i<count($dishesArr); $i++){ 
            $updateQuery = "UPDATE WEBOMS_menu_tb SET stock = (stock + '$dishesQuantity[$i]') WHERE dish= '$dishesArr[$i]' ";    
            Query($updateQuery);    
        }
        $_SESSION["dishes"] = array();
        $_SESSION["price"] = array();
        $_SESSION["orderType"] = array(); 
        echo "<script>window.location.replace('adminPos.php');</script>";
    }
    
    //add to cart
    if(isset($_GET['order'])){
      $order = explode(',',$_GET['order']);  
      $dish = $order[0];
      $price = $order[1];
      $orderType = $order[2];
      array_push($_SESSION['dishes'], $dish);
      array_push($_SESSION['price'], $price);
      array_push($_SESSION['orderType'], $orderType);

      $updateQuery = "UPDATE WEBOMS_menu_tb SET stock = (stock - 1) WHERE dish= '$dish' ";    
      if(Query($updateQuery))
        echo "<script>window.location.replace('adminPos.php');</script>";    
    }

    // add
    if(isset($_GET['add'])){
        $arr = explode(',',$_GET['add']);
        $dish = $arr[0];
        $price = $arr[1];
		    $orderType = $arr[2];
        array_push($_SESSION['dishes'], $dish);
        array_push($_SESSION['price'], $price);
        array_push($_SESSION['orderType'], $orderType);

        $updateQuery = "UPDATE WEBOMS_menu_tb SET stock = (stock - 1) WHERE dish= '$dish' ";    
        if(Query($updateQuery))
          echo "<script>window.location.replace('adminPos.php');</script>";    
    }

    //minus
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

        $updateQuery = "UPDATE WEBOMS_menu_tb SET stock = (stock + 1) WHERE dish= '$dish' ";    
        if(Query($updateQuery))
            echo "<script>window.location.replace('adminPos.php');</script>";    
    }

    //order button (php)
    if(isset($_POST['order'])){
        $cash = $_POST['cash'];
        if($cash >= $total && $total != 0){
            $_SESSION['continue'] = true;
            date_default_timezone_set('Asia/Manila');
            $date = new DateTime();
            $today =  $date->format('Y-m-d'); 
            $todayWithTime =  $date->format('Y-m-d H:i:s'); 
            $_SESSION['date'] = $todayWithTime;
            $_SESSION['cash'] = $cash;
            $_SESSION['total'] = $total;
            $_SESSION['dishesArr'] = $dishesArr;
            $_SESSION['priceArr'] = $priceArr;
            $_SESSION['dishesQuantity'] = $dishesQuantity;
            $staff = $_SESSION['name'].'('.$_SESSION['accountType'].')';
            $user_id = $_SESSION['user_id'];
            $order_id = uniqid();
            $_SESSION['order_id'] = $order_id;
            $query1 = "insert into WEBOMS_order_tb(user_id, status, order_id, date, totalOrder, payment,  staffInCharge) values('$user_id','prepairing','$order_id','$todayWithTime','$total','$cash', '$staff')";
            for($i=0; $i<count($dishesArr); $i++){
                $query2 = "insert into WEBOMS_ordersDetail_tb(order_id, quantity, orderType) values('$order_id',$dishesQuantity[$i], $orderType[$i])";
                Query($query2);
            }
            Query($query1);
            echo "<script>document.getElementById('clear').click();</script>";
        }   
    }
?>

<script>
//order button (js)
var orderBtn = document.getElementById("orderBtn");
orderBtn.addEventListener("click", () => {
    var num = document.getElementById("cashNum").value;
    if (<?php echo $total == 0 ? 'true':'false';?>) {
        alert('Please place your order!');
        return;
    }
    if (num >= <?php echo $total;?>) {
        alert("Sucess Placing Order!");
        window.open("pdf/receipt.php");
    }
});
</script>

<script>
// data table
$(document).ready(function() {
    $('#tbl').DataTable();
});
</script>

<script>
// for navbar click locations
document.getElementById("orders").onclick = function() {
    window.location.replace('adminOrders.php');
};
document.getElementById("orders").onclick = function() {
    window.location.replace('adminOrders.php');
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
</script>