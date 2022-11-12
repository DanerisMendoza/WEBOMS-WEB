<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script> 
        <script type="text/javascript" src="js/bootstrap.js"></script>
        

    </head>
    <body>
    <?php
            include_once('connection.php');
            $sold = 0;
            $initialCost = 0;
            $stock = 0;
            $profit = 0;
            $sql = mysqli_query($conn,"select * from dishes_tb");  
            if (mysqli_num_rows($sql)) { 
                while($rows = mysqli_fetch_assoc($sql)){
                    $initialCost += $rows['cost'];
                    $stock = $rows['stock'];  
                    // $stock += $rows['stock'];
                }
                $initialCost = ($initialCost * $stock);
                // print_r($initialCost);
            }

            $sql = mysqli_query($conn,"select dishes_tb.*, order_tb.* from dishes_tb inner join order_tb where dishes_tb.orderType = order_tb.orderType");  
            if (mysqli_num_rows($sql)) { 
                while($rows = mysqli_fetch_assoc($sql)){
                    $sold += ($rows['price']*$rows['quantity']);
                    // $stock += $rows['stock'];
                }
            }
        ?>
        <center>
    <div class="container text-center">
        <button class="btn btn-success col-sm-4" id="viewSalesReport">Sales Report</button>
            <div class="col-lg-12">
                <h1>Sales Report: </h1>
                <h5>Total Initial cost: <?php echo '₱'.$initialCost?></h5>
                <h5>Total Amount of Sold: <?php echo '₱'.$sold?></h5>
                <h5>Total Profit: <?php echo (($sold-$initialCost)<0 ? 'no profit': '₱'.($sold-$initialCost))?></h5>
                <h5>Loss: <?php echo ($initialCost-$sold)<0 ? '₱0': '₱'.($initialCost-$sold)?></h5>
                <div class="col-lg-12" id="piechart" style="width: 900px; height: 500px;"></div>
            </div>
        </div>
        </center>
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

<style>
    body{
    background-image: url(settings/bg.jpg);
    background-size: cover;
    background-attachment: fixed;
    background-repeat: no-repeat;
    background-position: center;
    /* background-color: #ED212D; */
    color: white;
    font-family: 'Josefin Sans',sans-serif;
  }
</style>