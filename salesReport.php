<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css"></head>
    </head>
    <body>
        <div class="container text-center">
        <button class="btn btn-success col-sm-4" id="admin">Admin</button>
        <button class="btn btn-success col-sm-4" id="viewGraph">View Graph</button>
        </br>
        <form method="post">
        <input type="datetime-local" name="dateFetch1" value="<?php echo(isset($_POST['dateFetch1'])?  $_POST['dateFetch1']: " ") ?>" >
        <button type="submit" name="fetch">fetch</button>
        <input type="datetime-local" name="dateFetch2" value="<?php echo(isset($_POST['dateFetch1'])?  $_POST['dateFetch2']: " ") ?>" >
        </form>
        <form method="POST"><button type="submit" name="showAll">Show All</button></form>
            <div class="col-lg-12">
                <?php
                    include_once('orderClass.php');
                    if(isset($_POST['fetch']) && !isset($_POST['showAll'])){
                        $dateFetch1 = $_POST['dateFetch1'];
                        $dateFetch2 = $_POST['dateFetch2'];
                        $order = new orderList();
                        $orderlist =  $order -> getOrderListByDates($dateFetch1,$dateFetch2); 
                    }
                    else{
                        $order = new orderList();
                        $orderlist =  $order -> getApprovedOrderList(); 
                    }
                ?>
                <table class="table table-striped" border="10">
                <tr>	
                <th scope="col">name</th>
                <th scope="col">status</th>
                <th scope="col"></th>
                <th scope="col">date</th>
                </tr>
                <tbody>
                    
                    <?php 
                    if(!empty($orderlist))
                    foreach($orderlist as $rows){ ?>
                    <tr>	   
                    <td><?php echo $rows['name']; ?></td>
                    <td><?php echo ($rows['status'] == 1 ? "Approved": "Pending"); ?></td>
                    <td><a href="viewOrders.php?idAndPic=<?php echo $rows['ordersLinkId'].','.$rows['proofOfPayment']?>">View Order</a></td>
                    <td><?php echo $rows['date']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
                </table>
            </div>
        </div>
    </body>
</html>

<script>
    document.getElementById("admin").onclick = function () {window.location.replace('admin.php'); };
    document.getElementById("viewGraph").onclick = function () {window.location.replace('graph.php'); };
</script>


<style>
    body{
    background-image: url(settings/bg.jpg);
    background-size: cover;
    background-attachment: fixed;
    background-repeat: no-repeat;
    background-position: center;
    color: white;
    font-family: 'Josefin Sans',sans-serif;
  }
</style>