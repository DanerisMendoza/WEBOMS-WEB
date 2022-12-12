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
    <title>View Graph</title>
        
  
    <link rel="stylesheet" type="text/css" href="css/bootstrap 5/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script> 
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <!-- charts -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <style>
        .chart {
            width: 100%; 
            min-height: 450px;
        }
        .row {
            margin:0 !important;
        }
    </style>
</head>
<body>

    <?php
        $dishesArr = array();
        $qantityArr = array();
        $sold = 0;
        $countOfSold = 0;
        $stockLeft = 0;
        $query = "select * from WEBOMS_menu_tb;";
        $resultSet = getQuery($query);
        // get stock left
        if($resultSet!=null){
            foreach($resultSet as $row){
                $stockLeft += $row['stock'];
            }
        }

        $query = "select dish,quantity from WEBOMS_ordersDetail_tb a inner join WEBOMS_menu_tb b on a.orderType = b.orderType inner join WEBOMS_order_tb c on a.order_id = c.order_id where c.status = 'complete'";
        $resultSet = getQuery($query);
        if($resultSet!=null){
            foreach($resultSet as $row){
                // get sold stock
                $countOfSold += $row['quantity'];
                //merge dish quantity into 1 
                if(in_array($row['dish'], $dishesArr)){
                    $index = array_search($row['dish'], $dishesArr);
                    $newQuantity = $qantityArr[$index] + $row['quantity'];
                    $qantityArr[$index] = $newQuantity;
                }
                else{
                    array_push($dishesArr,$row['dish']);
                    array_push($qantityArr,$row['quantity']);
                }
            }
            // merge multiple array into multi arr
            $multiArr = array();
            for($i=0; $i<sizeof($dishesArr); $i++){
                $arr = array('dish' => $dishesArr[$i], 'quantity' => $qantityArr[$i]);
                array_push($multiArr,$arr);
            }

            // manual sort
            for($i=0; $i<sizeof($multiArr); $i++){
                for($j=$i+1; $j<sizeof($multiArr); $j++){
                    if($multiArr[$i]['quantity'] > $multiArr[$j]['quantity']){
                        $tempArr = $multiArr[$i];
                        $multiArr[$i] = $multiArr[$j];
                        $multiArr[$j] = $tempArr;
                    }
                }                
            }
        }
    ?>

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
                <li class="mb-2">
                    <a href="#" id="adminTopUp"><i class="bi bi-cash-stack me-2"></i>Top-Up</a>
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
                    <button type="button" id="sidebarCollapse" class="btn" style="font-size:20px;">
                        <i class="bi bi-list"></i>Dashboard (View Graph)
                    </button>
                </div>
            </nav>

            <!-- content here -->
            <div class="container-fluid text-center ">
                <div class="row justify-content-center">
                    <button class="btn btn-lg btn-dark col-12 mb-3" id="viewSalesReport"><i class="bi bi-arrow-left me-1"></i>BACK</button>
                    <div class="table-responsive col-lg-12 mb-4">
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
                    </div>
                    <div class="row">
                        <div class="clearfix"></div>
                        <div class="col-md-6 mb-4">
                            <div class="chart" id="piechart" ></div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="chart" id="columnchart" ></div> 
                        </div>
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
          backgroundColor: '',
          is3D: false,
        };
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
    
        }
</script>

<script>
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawStuff);

      function drawStuff() {
        var data = new google.visualization.arrayToDataTable([
        ['Order Counts', 'Order Counts'],

            <?php
                $i = 0;
                foreach($multiArr as $arr){
                    if($i!=sizeof($multiArr))
                        echo "['$arr[dish]','$arr[quantity]'],";
                    else
                        echo "['$arr[dish]','$arr[quantity]']";
                    $i++;
                }    
            ?>
        ]);

        var options = {
         backgroundColor: '',
          legend: { position: 'none' },
          chart: {
            title: 'Most Ordered Food',
            subtitle: '' },
          axes: {
          },
          bar: { groupWidth: "90%" }
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart'));
        // Convert the Classic options to Material options.
        chart.draw(data, google.charts.Bar.convertOptions(options));
      };
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
document.getElementById("orders").onclick = function() { window.location.replace('adminOrders.php'); };
document.getElementById("ordersQueue").onclick = function() { window.location.replace('adminOrdersQueue.php'); };
document.getElementById("inventory").onclick = function() { window.location.replace('adminInventory.php'); };
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