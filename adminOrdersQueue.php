<?php 
session_start();
$isSame = false;
include_once('class/transactionClass.php');
include('method/Query.php');
$transaction = new transaction();
$resultSet =  $transaction -> getPrepairingOrder();
$count = 0;
?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
    <div class="container text-center">

        <button class="btn btn-success col-sm-4" id="admin">Admin</button>
        <script>
            document.getElementById("admin").onclick = function () {window.location.replace('admin.php'); };    
        </script> 
        
        <div class="col-lg-12">
            <table class="table table-striped" border="10">
            <tr>	
            <th scope="col">Orders ID</th>
            <th scope="col">Queue Number</th>
            </tr>
              <tbody>
                <?php
                $i = 1;
                if($resultSet != null)
                foreach($resultSet as $rows){ ?>
                <tr>	   
                <td><?php echo $rows['ordersLinkId']; ?></td>
                <td><?php echo $i;?></td>
                </tr>
                <?php $i++;} ?>
              </tbody>
            </table>
          </div>
	    </div>
    </body>
</html>