<?php 
    $page = 'admin';
    include('method/checkIfAccountLoggedIn.php');
    include('method/query.php');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin SR - View Graph</title>
        
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" type="text/css" href="css/bootstrap 5/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script> 
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>
<body>

    <?php
        $sold = 0;
        $countOfSold = 0;
        $stockLeft = 0;
        $query = "select * from WEBOMS_menu_tb;";
        $resultSet = getQuery($query);
        if($resultSet!=null){
            foreach($resultSet as $row){
                $stockLeft += $row['stock'];
            }
        }
        $query = "select WEBOMS_menu_tb.*,WEBOMS_ordersDetail_tb.*,WEBOMS_order_tb.status from WEBOMS_menu_tb inner join WEBOMS_ordersDetail_tb on WEBOMS_menu_tb.orderType = WEBOMS_ordersDetail_tb.orderType inner join WEBOMS_order_tb on WEBOMS_order_tb.order_id = WEBOMS_ordersDetail_tb.order_id where status = 'complete';";
        $resultSet = getQuery($query);
        if($resultSet!=null){
            foreach($resultSet as $row){
                $countOfSold += $row['quantity'];
            }
        }
    ?>

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
                <li class="mb-2 active">
                    <a href="#"><i class="bi bi-bar-chart me-2"></i>Sales Report</a>
                </li>
                <li class="mb-2">
                    <a href="#" id="accountManagement"><i class="bi bi-person-circle me-2"></i>Account Management</a>
                </li>
                <li class="mb-2">
                    <a href="#" id="customerFeedback"><i class="bi bi-chat-square-text me-2"></i>Customer Feedback</a>
                </li>
                <li class="mb-1">
                    <a href="#" id="adminTopUp"><i class="bi bi-cash-stack me-2"></i>Top-Up</a>
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
                        <i class="bi bi-list"></i>Dashboard (View Graph)
                    </button>
                </div>
            </nav>

            <!-- content here -->
            <div class="container-fluid text-center ">
                <div class="row justify-content-center">
                    <button class="btn btn-lg btn-dark col-12 mb-4" id="viewSalesReport"><i class="bi bi-arrow-left me-1"></i>BACK</button>
                    <div class="table-responsive col-lg-12">
                        <table class="table table-bordered table-hover col-lg-12">
                            <tr>
                                <td>TOTAL AMOUNT OF STOCK</td>
                                <td><?php echo $countOfSold+$stockLeft?></td>
                            </tr>
                            <tr>
                                <td>TOTAL COUNT OF SOLD</td>
                                <td><?php echo $countOfSold?></td>
                            </tr>
                            <tr>
                                <td>TOTAL COUNT OF STOCK LEFT</td>
                                <td><?php echo $stockLeft?></td>
                            </tr>
                        </table>
                        <table class="col-lg-12">
                            <div class="col-lg-12 bg-secondary" id="piechart" style="width:1000px; height:500px;"></div>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>

<script>
        document.getElementById("viewSalesReport").onclick = function () {window.location.replace('adminSalesReport.php'); };

        //graphs
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChartPie);
    
        //pie
        function drawChartPie() {
        var data = google.visualization.arrayToDataTable([
            ['name', 'cost'],
            ['Sold',<?php echo $countOfSold?>],
            ['Stock Left',<?php echo $stockLeft?>]
        ]);

        var options = {
          title: '',
          backgroundColor: 'white',
          is3D: false,
        };
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
    
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
document.getElementById("pos").onclick = function() {
    window.location.replace('adminPos.php');
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
document.getElementById("accountManagement").onclick = function() {
    window.location.replace('accountManagement.php');
};
document.getElementById("customerFeedback").onclick = function() {
    window.location.replace('adminFeedbackList.php');
};
document.getElementById("adminTopUp").onclick = function() {
    window.location.replace('adminTopUp.php');
};
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