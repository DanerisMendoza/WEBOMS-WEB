<?php 
    $page = 'admin';
    include('../method/checkIfAccountLoggedIn.php');
    include('../method/query.php');
    $_SESSION['query'] = 'all';
    // redefining name
    $_SESSION['name'] = getQueryOneVal2("select name from weboms_userInfo_tb where user_id = '$_SESSION[user_id]' ",'name');

    // current week total sold
    $todaySold = 0; 
    $dailySoldMultiArr = $weeklySoldMultiArr = $monthlySoldMultiArr =  $yearlySoldMultiArr = [[],[]]; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/admin2.css">
    <link rel="stylesheet" href="../css/admin-dashboard.css">
    <link rel="icon" href="../image/weboms.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://www.gstatic.com/charts/loader.js"></script>
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
                <li><a href="adminOrders.php"><i class="bi-cart me-2"></i>ORDERS</a></li>
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
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-4">
                            <table class="table table-bordered">
                                <tr>
                                    <td>TOTAL AMOUNT OF STOCK:</td>
                                    <td id="totalStockTd"></td>
                                </tr>
                                <tr>
                                    <td>TOTAL COUNT OF STOCK LEFT:</td>
                                    <td id="stockLeftTd"></td>
                                </tr>
                                <tr>
                                    <td>TOTAL COUNT OF SOLD:</td>
                                    <td id="soldCountTd"></td>
                                </tr>
                                <tr>
                                    <td>TOTAL SOLD:</td>
                                    <td id="soldTd"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-sm-4">
                            <table class="table table-bordered">
                                <tr>
                                    <td>TOTAL COUNT OF PREPARING:</td>
                                    <td id="preparingTd"></td>
                                </tr>
                                <tr>
                                    <td>TOTAL COUNT OF SERVING:</td>
                                    <td id="servingTd"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-sm-4">
                            <table class="table table-bordered">
                                <tr>
                                    <td>TODAY SALE:</td>
                                    <td id="todaySaleTd"></td>
                                </tr>
                                <tr>
                                    <td>CURRENT WEEK SALE:</td>
                                    <td id="currentWeekSoldTd"></td>
                                </tr>
                                <tr>
                                    <td>CURRENT MONTH SALE:</td>
                                    <td id="currentMonthSoldTd"></td>
                                </tr>
                                <tr>
                                    <td>CURRENT YEAR SALE:</td>
                                    <td id="currentYearSoldTd"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <div class="row g-2">
                            <div class="col-sm-6">
                                <div class="chart" id="piechart"></div>
                            </div>
                            <div class="col-sm-6">
                                <div class="chart" id="columnchart"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="chart" id="columnchart2"></div>
                            </div>
                            <div class="col-sm-6">
                                <div class="chart" id="columnchart3"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="chart" id="columnchart4"></div>
                            </div>
                            <div class="col-sm-6">
                                <div class="chart" id="columnchart5"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>

<script>
    // sidebar toggler
    $(document).ready(function() {
        $('#sidebarCollapse').on('click', function() {
            $('#sidebar').toggleClass('active');
        });
    });
    
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