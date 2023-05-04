<?php 
    $page = 'customer';
    include('../method/checkIfAccountLoggedIn.php');
    include('../method/query.php');
    if(!isset($_SESSION["dishes"]) || !isset($_SESSION["price"]) || !isset($_SESSION["orderType"])){
    $_SESSION["dishes"] = array();
    $_SESSION["price"] = array(); 
    $_SESSION["orderType"] = array(); 
    }
    $balance = getQueryOneVal2("SELECT balance FROM `weboms_userInfo_tb` where user_id = '$_SESSION[user_id]' ",'balance');
    $companyName = getQueryOneVal2('select name from weboms_company_tb','name');
    $balance = $balance == null ? 0 : $balance;
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Costumer</title>

    <link rel="stylesheet" type="text/css" href="../css/bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="../css/customer.css">
    <script type="text/javascript" src="../js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.js"></script>
    <script type="text/javascript" src="js/bootstrap 5/bootstrap.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300&display=swap');

        .h1Admin {
            font-family: 'Cormorant Garamond', serif;
            font-size: 10vw;
            font-weight: normal;
        }
    </style>
</head>

<body style="background: url(https://images.squarespace-cdn.com/content/v1/5e875099333b8827ab0762b7/1648063622690-486R94LW89XFTZTEV6TW/Pasta+-+Dark+Background+-+High+Resolution-69.jpg?format=2500w) no-repeat center center fixed; 
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;">

    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top shadow">
        <div class="container py-3">
            <a class="navbar-brand fs-4" href="#"><?php echo $companyName;?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item me-2">
                        <a class="nav-link text-danger" href="customer.php"><i class="bi bi-house-door"></i> HOME</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link text-dark" href="customerProfile.php"><i class="bi bi-person-circle"></i> PROFILE</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link text-dark" href="customerMenu.php"><i class="bi bi-book"></i> MENU</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link text-dark" href="customerTopUp.php"><i class="bi bi-cash-stack"></i> TOP-UP</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link text-dark" href="customerOrders.php"><i class="bi bi-list"></i> VIEW ORDERS</a>
                    </li>
                </ul>
                <form method="post">
                    <button class="btn btn-danger col-12" id="Logout" name="logout"><i class="bi bi-power"></i> LOGOUT</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container text-white" style="margin-top:150px;">
        <div class="row justify-content-center">
            <h1 class="col-12 h1Admin">Hi Customer, <br><?php echo ucwords($_SESSION['name']);?>!</h1>
            <h1 class="fw-normal col-12 ms-3 ">Your balance is ₱<?php echo number_format($balance,2); ?></h1>
        </div>
    </div>
</body>

</html>

<?php 
  if(isset($_POST['logout'])){
    $dishesArr = array();
    $dishesQuantity = array();
    if(isset($_SESSION['dishes'])){
        for($i=0; $i<count($_SESSION['dishes']); $i++){
            if(in_array( $_SESSION['dishes'][$i],$dishesArr)){
              $index = array_search($_SESSION['dishes'][$i], $dishesArr);
            }
            else{
              array_push($dishesArr,$_SESSION['dishes'][$i]);
            }
        }
        foreach(array_count_values($_SESSION['dishes']) as $count){
          array_push($dishesQuantity,$count);
        }
        for($i=0; $i<count($dishesArr); $i++){ 
          $updateQuery = "UPDATE weboms_menu_tb SET stock = (stock + '$dishesQuantity[$i]') WHERE dish= '$dishesArr[$i]' ";    
          Query2($updateQuery);    
        }
    }
    session_destroy();
    echo "<script>window.location.replace('../general/login.php');</script>";
  }
?>