<?php 
  $page = 'notLogin';
  include('../method/checkIfAccountLoggedIn.php');
  include('../method/query.php');
  include_once('connection.php');
  $query = "select * from weboms_company_tb";
  $resultSet = getQuery2($query);
  if($resultSet!=null){
      foreach($resultSet as $row){
          $name = $row['name'];
          $address = $row['address'];
          $tel = $row['tel'];
          $description = $row['description'];
      }
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Queue</title>

    <link rel="stylesheet" href="../css/bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <script type="text/javascript" src="../js/bootstrap 5/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/jquery-3.6.1.min.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <!-- ajax -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script> -->

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark ">
    <div class="container py-3">
        <a class="navbar-brand fs-4" href="#"><?php echo $name;?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item me-2"><a class="nav-link" href="../index.php#home"><i class="bi bi-house-door"></i> HOME</a></li>
                <li class="nav-item me-2"><a class="nav-link" href="../index.php#menu"><i class="bi bi-book"></i> MENU</a></li>
                <li class="nav-item me-2"><a class="nav-link" href="../index.php#about"><i class="bi bi-info-circle"></i> ABOUT</a></li>
                <li class="nav-item me-2"><a class="nav-link" href="ordersQueue.php"><i class="bi bi-hourglass-split"></i> Orders Queue</a></li>
                <li class="nav-item me-2"><a class="nav-link" ><i class="bi bi-hourglass-split"></i> Orders Queue Online</a></li>
            </ul>
            <a class="btn btn-outline-light" type="button" href="login.php"><i class="bi bi-person-circle"></i> LOGIN</a>
        </div>
    </div>
</nav>
     <!-- content here -->
     <div class="container-fluid text-center">
            <div class="row justify-content-center">
                <h1>(Online Order)</h1>
                <!-- serving table -->
                <div class="table-responsive col-lg-6">
                    <table class="table table-bordered table-hover col-lg-12" id="tableServing">
                        <thead class="bg-success text-white">
                            <tr>
                                <th scope="col"><h2><i class="bi bi-arrow-bar-left"></i> SERVING</h2></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- serving table -->
                                <?php   
                                    $getServingOrder = "select a.name, b.* from weboms_userInfo_tb a right join weboms_order_tb b on a.user_id = b.user_id WHERE b.status = 'serving' and b.staffInCharge = 'online order' ORDER BY b.id asc; ";
                                    $resultSet = getQuery2($getServingOrder);
                                    if($resultSet != null)
                                        foreach($resultSet as $row){ 
                                ?>
                                            <tr>
                                                <!-- orders id -->
                                                <td><strong style="font-size: 35px;"><?php echo $row['order_id']; ?></strong></td>
                                            </tr>
                                <?php } ?>
                        </tbody>
                    </table>
                </div>

                <!-- prepairing table -->
                <div class="table-responsive col-lg-6">
                    <table class="table table-bordered table-hover col-lg-12" id="prepairingTable">
                        <thead class="bg-danger text-white">
                            <tr>
                                <th scope="col"><h2><i class="bi bi-clock"></i> PREPARING</h2></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php   
                                $getPrepairingOrder = "select a.name, b.* from weboms_userInfo_tb a right join weboms_order_tb b on a.user_id = b.user_id WHERE b.status = 'preparing' and b.staffInCharge = 'online order' ORDER BY b.id asc; ";
                                $resultSet = getQuery2($getPrepairingOrder);
                                if($resultSet != null)
                                    foreach($resultSet as $row){ 
                            ?>
                                        <tr>
                                            <!-- orders id -->
                                            <td><strong style="font-size: 35px;"><?php echo $row['order_id']; ?></strong></td>
                                        </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
// auto refresh 
function autoRefresh_Tables() {
    $("#tableServing").load("ordersQueueOnline.php #tableServing", function() {
        setTimeout(autoRefresh_Tables, 2000);
    });
    $("#prepairingTable").load("ordersQueueOnline.php #prepairingTable", function() {
        setTimeout(autoRefresh_Tables, 2000);
    });
}
autoRefresh_Tables();
</script>