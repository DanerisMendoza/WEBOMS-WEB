<?php
    include_once('class/transactionClass.php');
    include('method/Query.php');
    if(isset($_POST['fetch']) && !isset($_POST['showAll'])){
        $dateFetch1 = $_POST['dateFetch1'];
        $dateFetch2 = $_POST['dateFetch2'];
        $transaction = new transaction($dateFetch1,$dateFetch2);
        $resultSet =  $transaction -> getOrderListByDates(); 
    }
    else{
        $transaction = new transaction();
        $resultSet =  $transaction -> getAllOrderCompleteList(); 
    }
?>
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
                <table class="table table-striped" border="10">
                <tr>	
                <th scope="col">name</th>
                <th scope="col">Orders ID</th>
                <th scope="col">status</th>
                <th scope="col">_______</th>
                <th scope="col">date</th>
                </tr>
                <tbody>
                    <?php 
                    if(!empty($resultSet))
                    foreach($resultSet as $rows){ ?>
                    <tr>	   
                    <td><?php echo $rows['name']; ?></td>
                    <td><?php echo $rows['ordersLinkId'];?></td>
                    <td><?php echo ($rows['isOrdersComplete'] == 1 ? "Order Complete": "Pending"); ?></td>
                    <td><a style="background: white; padding:2px; border: 2px black solid; color:black;"href="adminOrders.php?idAndPic=<?php echo $rows['ordersLinkId'].','.$rows['proofOfPayment']?>">View Order</a></td>
                    <td><?php echo date('m/d/Y h:i:s a ', strtotime($rows['date'])); ?></td>
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
    document.getElementById("viewGraph").onclick = function () {window.location.replace('adminGraph.php'); };
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
  .container{
     padding: 1%;
     margin-top: 2%;
     background: gray;
   }
</style>