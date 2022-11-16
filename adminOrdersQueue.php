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
  <title>Admin OQ</title>
    
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
  <link rel="stylesheet" type="text/css" href="css/style.css">
    
</head>
<body class="bg-light">

<div class="container text-center">
  <div class="row justify-content-center">
    <h1 class="font-weight-normal mt-5 mb-4 text-center">Orders Queue</h1>
      <button class="btn btn-lg btn-danger col-12 mb-3" id="admin">Admin</button>
        <script>
            document.getElementById("admin").onclick = function () {window.location.replace('admin.php'); };    
        </script> 
        
      <div class="table-responsive col-lg-12">
        <table class="table table-striped table-bordered col-lg-12">
          <thead class="table-dark">
            <tr>	
              <th scope="col">ORDERS ID</th>
              <th scope="col">QUEUE NO.</th>
            </tr>
          </thead>
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
</div>

</body>
</html>