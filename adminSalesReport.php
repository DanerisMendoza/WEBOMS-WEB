<?php
    include('method/checkIfAccountLoggedIn.php');
    include('method/Query.php');
    $_SESSION['from'] = 'salesReport';
    if(isset($_POST['fetch']) && !isset($_POST['showAll'])){
        $date1 = $_POST['dateFetch1'];
        $date2 = $_POST['dateFetch2'];
        $query = "select customer_tb.name, order_tb.* from customer_tb, order_tb where customer_tb.userlinkId = order_tb.userlinkId and order_tb.status = 'complete' and order_tb.date between '$date1' and '$date2' ORDER BY order_tb.id asc; ";
        $resultSet =  getQuery($query); 
    }
    else{
        $query = "select customer_tb.name, order_tb.* from customer_tb, order_tb where customer_tb.userlinkId = order_tb.userlinkId and order_tb.status = 'complete' ORDER BY order_tb.id asc; ";
        $resultSet =  getQuery($query); 
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin SR</title>
        
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">

</head>
<body class="bg-light">

<div class="container text-center">
    <div class="row justify-content-center">
        <h1 class="font-weight-normal mt-5 mb-4 text-center">Sales Report</h1>
        <button class="btn btn-lg btn-danger col-12 mb-3" id="admin">Admin</button>
        <button class="btn btn-lg btn-success col-12 mb-3" id="viewGraph">View Graph</button>
        <form method="post">
            <input type="datetime-local" name="dateFetch1" class="form-control form-control-lg mb-2" value="<?php echo(isset($_POST['dateFetch1'])?  $_POST['dateFetch1']: " ") ?>" >
            <button type="submit" name="fetch" class="btn btn-lg btn-success col-12 mb-2">Fetch</button>
            <input type="datetime-local" name="dateFetch2" class="form-control form-control-lg mb-2" value="<?php echo(isset($_POST['dateFetch1'])?  $_POST['dateFetch2']: " ") ?>" >
            <button type="submit" name="showAll" class="btn btn-lg btn-success col-12 mb-3">Show All</button>
        </form>
            <div class="table-responsive col-lg-12">
                <table class="table table-striped table-bordered col-lg-12">
                    <thead class="table-dark">
                        <tr>	
                            <th scope="col">NAME</th>
                            <th scope="col">TRANSACTION NO</th>
                            <th scope="col">STATUS</th>
                            <th scope="col">DATE & TIME</th>
                            <th scope="col">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        if($resultSet != null)
                            foreach($resultSet as $rows){ ?>
                                <tr>	   
                                <td><?php echo $rows['name']; ?></td>
                                <td><?php echo $rows['ordersLinkId'];?></td>
                                <td><a class="btn btn-light border-dark" href="adminOrders.php?idAndPic=<?php echo $rows['ordersLinkId'].','.$rows['proofOfPayment']?>">View Order</a></td>
                                <td><?php echo date('m/d/Y h:i:s a ', strtotime($rows['date'])); ?></td>
                                <td><?php echo '₱'.$rows['totalOrder']; ?></td>
                                <?php $total += $rows['totalOrder'];?>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td colspan="4"><strong>Total</strong></td>
                                <td><strong><?php echo '₱'.$total;?></strong></td>
                            </tr>
                    </tbody>
                </table>
            </div>
    </div>
</div>
    
</body>
</html>

<script>
    document.getElementById("admin").onclick = function () {window.location.replace('admin.php'); };
    document.getElementById("viewGraph").onclick = function () {window.location.replace('adminGraph.php'); };
</script>