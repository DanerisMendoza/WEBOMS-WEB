<!DOCTYPE html>
<html>
<head>
    <title>Admin SR - View Graph</title>
        
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script> 
    <script type="text/javascript" src="js/bootstrap.js"></script>
        
</head>
<body class="bg-light">
    <?php
        include('method/query.php');
        $sold = 0;
        $initialCost = 0;
        $stock = 0;
        $profit = 0;
        $query = "select * from menu_tb";
        $resultSet = getQuery($query);
        foreach($resultSet as $row){
            $initialCost += ($row['cost'] * $row['stock']);
        }
        $query = "select menu_tb.*,ordersDetail_tb.*,order_tb.isOrdersComplete from menu_tb inner join ordersDetail_tb on menu_tb.orderType = ordersDetail_tb.orderType inner join order_tb on order_tb.ordersLinkId = ordersDetail_tb.OrdersLinkId where order_tb.isOrdersComplete = 1;";
        $resultSet = getQuery($query);
        foreach($resultSet as $row){
            $sold += ($row['price']*$row['quantity']);
        }
    ?>
        <!-- <center> -->
<div class="container text-center">
    <div class="row justify-content-center">
        <h1 class="font-weight-normal mt-5 mb-4 text-center">View Graph</h1>
        <button class="btn btn-lg btn-danger col-12 mb-3" id="viewSalesReport">Sales Report</button>
            <div class="col-lg-12">
                <!-- <h1>Sales Report: </h1> -->
                <h5 class="font-weight-normal">TOTAL INITIAL COST: <?php echo '₱'.$initialCost?></h5>
                <h5 class="font-weight-normal">TOTAL AMOUNT OF SOLD: <?php echo '₱'.$sold?></h5>
                <h5 class="font-weight-normal">TOTAL PROFIT: <?php echo (($sold-$initialCost)<0 ? '₱0': '₱'.($sold-$initialCost))?></h5>
                <h5 class="font-weight-normal">LOSS: <?php echo ($initialCost-$sold)<0 ? '₱0': '₱'.($initialCost-$sold)?></h5>
                <div class="col-lg-12" id="piechart" style="width: 900px; height: 500px;"></div> <!-- di pa responsive to -->
            </div>
    </div>
</div>
        <!-- </center> -->
    
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
            ['sold',<?php echo $sold?>],
            ['initial cost',<?php echo $initialCost?>]
        ]);

        var options = {
          title: '',
          backgroundColor: 'gray',
          is3D: false,
        };
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
    
        }
</script>