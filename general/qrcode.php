<?php
  $page = 'notLogin';
  $isFromLogin = true;
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
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Page</title>

    <link rel="stylesheet" href="../css/bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <script type="text/javascript" src="../js/bootstrap 5/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/jquery-3.6.1.min.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">

  </head>

    <body class="bg-dark">
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
                      <li class="nav-item me-2"><a class="nav-link" href="ordersQueue.php" ><i class="bi bi-hourglass-split"></i> Orders Queue</a></li>
                      <li class="nav-item me-2"><a class="nav-link" href="ordersQueueOnline.php"><i class="bi bi-hourglass-split"></i> Orders Queue Online</a></li>
                      <li class="nav-item me-2"><a class="nav-link" ><i class="bi bi-qr-code-scan"></i> Download Mobile/QR Code Page</a></li>
                  </ul>
                  <a class="btn btn-outline-light" type="button" href="login.php"><i class="bi bi-person-circle"></i> LOGIN</a>
              </div>
          </div>
      </nav>

      <div class="container">
        <div class="row">
          <div class="col-sm-6">
            <img class="register img-fluid " src="register.png" style="width: auto; border: 1px solid black;">
            <h2 class="font-weight-bold  ms-5" style="color:white;">Register Account</h2>
          </div>
          <div class="col-sm-6">
            <img class="mobileapk img-fluid" src="mobile.png" style="width: auto; border: 1px solid black;">
            <h2 class="font-weight-bold  ms-5" style="color:white;">Mobile Application</h2>
            <a href="http://ucc-csd-bscs.com/WEBOMS/WebomsMobile.apk" class="font-weight-bold ms-5" style="color:blue; background-color:gray; font-size: 20px; padding: 5px; margin-bottom: 10px;">http://ucc-csd-bscs.com/WEBOMS/WebomsMobile.apk</a>
            <br></br>
          </div>
        </div>
      </div>


  </body>
</html>
