<?php 
    $page = 'admin';
    include('../method/checkIfAccountLoggedIn.php');
    include('../method/query.php');
    $_SESSION['query'] = 'all';
    // redefining name
    $_SESSION['name'] = getQueryOneVal2("select name from weboms_userInfo_tb where user_id = '$_SESSION[user_id]' ",'name');

    //  current week total sold
    $todaySold = 0; 
    $dailySoldMultiArr = $weeklySoldMultiArr = $monthlySoldMultiArr =  $yearlySoldMultiArr = [[],[]]; 
    

    
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>

    <link rel="stylesheet" href="../css/bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <script type="text/javascript" src="../js/bootstrap 5/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/jquery-3.6.1.min.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <!-- charts -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>


    <style>
        .h1Admin {
            font-size: 13vw;
            font-weight: normal;
        }
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
                    <button type="button" id="sidebarCollapse" class="btn" style="font-size:20px;"><i class="bi bi-list"></i> Dashboard</button>
                </div>
            </nav>
            <!-- content here -->
            <div class="container-fluid text-center ">
                <div class="row justify-content-center">
                    <div class="table-responsive col-lg-4 mb-4">
                        <table class="table table-bordered table-hover col-lg-12">
                            <tr>
                                <td><b>Total Amount of Stock:</b></td>
                                <td id="totalStockTd"></td>
                            </tr>
                            <tr>
                                <td><b>Total Count of Stock Left:</b></td>
                                <td id="stockLeftTd"></td>
                            </tr>
                            <tr>
                                <td><b>Total Count of Sold:</b></td>
                                <td id="soldCountTd"></td>
                            </tr>
                            <tr>
                                <td><b>Total Sold:</b></td>
                                <td id="soldTd"></td>
                            </tr>
                        </table>
                    </div> 
                    <div class="table-responsive col-lg-4 mb-4">
                        <table class="table table-bordered table-hover col-lg-12">
                            <tr>
                                <td><b>Total Count of Preparing:</b></td>
                                <td id="preparingTd"></td>
                            </tr>
                            <tr>
                                <td><b>Total Count of Serving:</b></td>
                                <td id="servingTd"></td>
                            </tr>
                        </table>
                    </div> 
                    <div class="table-responsive col-lg-4 mb-4">
                        <table class="table table-bordered table-hover col-lg-12">
                            <tr>
                                <td><b>Today Sale:</b></td>
                                <td id="todaySaleTd"></td>
                            </tr>
                            <tr>
                                <td><b>Current Week Sale:</b></td>
                                <td id="currentWeekSoldTd"></td>
                            </tr>
                            <tr>
                                <td><b>Current Month Sale:</b></td>
                                <td id="currentMonthSoldTd"></td>
                            </tr>
                            <tr>
                                <td><b>Current Year Sale:</b></td>
                                <td id="currentYearSoldTd"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="chart" id="piechart"></div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="chart" id="columnchart"></div> 
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="chart" id="columnchart2"></div> 
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="chart" id="columnchart3"></div> 
                        </div>
                        
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="chart" id="columnchart4"></div> 
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="chart" id="columnchart5"></div> 
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>

</html>
<script>
    $(document).ready(function() {
    // Load the Visualization API and the corechart package
    google.charts.load('current', {'packages':['corechart']});
    // google.charts.load('current', {'packages':['bar']});

    // Set a callback to run when the Visualization API is loaded

    setInterval(function() {
        google.charts.setOnLoadCallback(updateStats);
    }, 1000);
    });


    function updateStats(){
        $.getJSON({
            url: "ajax/graphs_Query.php",
            method: "post",
            success: function(data){
                // Pie Graph | Convert the data to a DataTable object
                var dataTable = new google.visualization.DataTable();
                dataTable.addColumn('string', 'name');
                dataTable.addColumn('number', 'count');
                dataTable.addRow(['Count Of Sold',data['countOfSold']]);
                dataTable.addRow(['Count Of Stock Left',data['stockLeft']]);
                dataTable.addRow(['Count Of Preparing',data['preparing']]);
                dataTable.addRow(['Count Of Serving',data['serving']]);

                // Set the options for the pie chart
                var options = {
                    title: 'Overall Stock Graph'
                };

                // Create the pie chart and bind it to the chart_div element
                var chart = new google.visualization.PieChart($('#piechart').get(0));
                chart.draw(dataTable, options);

                //column graph
                var dataTable = new google.visualization.DataTable();
                dataTable.addColumn('string', 'name');
                dataTable.addColumn('number', 'count');
                for(let i=0; i<data['multiArr'].length; i++){
                    dataTable.addRow([data['multiArr'][i]['dish'],Number(data['multiArr'][i]['quantity'])]);
                }
                
                //column graph 2
                var dataTable2 = new google.visualization.DataTable();
                dataTable2.addColumn('string', 'name');
                dataTable2.addColumn('number', 'count');
                for(let i=0; i<data['dailySoldMultiArr'][0].length; i++){
                    dataTable2.addRow([data['dailySoldMultiArr'][0][i],Number(data['dailySoldMultiArr'][1][i])]);
                }
          
                //column graph 3
                var dataTable3 = new google.visualization.DataTable();
                dataTable3.addColumn('string', 'name');
                dataTable3.addColumn('number', 'count');
                for(let i=0; i<data['weeklySoldMultiArr'][0].length; i++){
                    dataTable3.addRow([data['weeklySoldMultiArr'][2][i],Number(data['weeklySoldMultiArr'][1][i])]);
                }

                //column graph 4
                var dataTable4 = new google.visualization.DataTable();
                dataTable4.addColumn('string', 'name');
                dataTable4.addColumn('number', 'count');
                for(let i=0; i<data['monthlySoldMultiArr'][0].length; i++){
                    dataTable4.addRow([data['monthlySoldMultiArr'][0][i],Number(data['monthlySoldMultiArr'][1][i])]);
                }
          
                //column graph 5
                var dataTable5 = new google.visualization.DataTable();
                dataTable5.addColumn('string', 'name');
                dataTable5.addColumn('number', 'count');
                for(let i=0; i<data['yearlySoldMultiArr'][0].length; i++){
                    dataTable5.addRow([data['yearlySoldMultiArr'][0][i],Number(data['yearlySoldMultiArr'][1][i])]);
                }

                var options = {
                legend: { position: 'none' },
                title: 'Most Ordered Food',
                height: 400,
                width: 600,
                colors: ['#3366CC']
                };

                var options2 = {
                legend: { position: 'none' },
                title: 'Daily Orders Count (Current Weak)',
                height: 400,
                width: 600,
                colors: ['#3366CC']
                };
        
                var options3 = {
                legend: { position: 'none' },
                title: 'Weekly Orders Count (Current Month)',
                height: 400,
                width: 600,
                colors: ['#3366CC']
                };
             
                var options4 = {
                legend: { position: 'none' },
                title: 'Monthly Orders Count (Current Year)',
                height: 400,
                width: 600,
                colors: ['#3366CC']
                };
          
                var options5 = {
                legend: { position: 'none' },
                title: 'Yearly Orders Count',
                height: 400,
                width: 600,
                colors: ['#3366CC']
                };

                var chart = new google.visualization.ColumnChart($('#columnchart')[0]);
                chart.draw(dataTable, options);

                var chart2 = new google.visualization.ColumnChart($('#columnchart2')[0]);
                chart2.draw(dataTable2, options2);
          
                var chart3 = new google.visualization.ColumnChart($('#columnchart3')[0]);
                chart3.draw(dataTable3, options3);
            
                var chart4 = new google.visualization.ColumnChart($('#columnchart4')[0]);
                chart4.draw(dataTable4, options4);

                var chart5 = new google.visualization.ColumnChart($('#columnchart5')[0]);
                chart5.draw(dataTable5, options5);

                //td
                $("#totalStockTd").html(data['countOfSold']+data['stockLeft']+data['preparing']+data['serving']);
                $("#stockLeftTd").html(data['stockLeft']);
                $("#soldCountTd").html(data['countOfSold']);
                $("#soldTd").html("₱"+data['totalSold']);
                $("#preparingTd").html(data['preparing']);
                $("#servingTd").html(data['serving']);
                $("#todaySaleTd").html("₱"+data['todaySold']);
                $("#currentWeekSoldTd").html("₱"+data['currentWeekSold']);
                $("#currentMonthSoldTd").html("₱"+data['currentMonthSold']);
                $("#currentYearSoldTd").html("₱"+data['currentYearSold']);
            }
        });
    }
    
   

    
</script>

<?php 
    // logout
    if(isset($_POST['logout'])){
        session_destroy();
        echo "<script>window.location.replace('../index.php');</script>";
    }
?>