<?php 
    $page = 'customer';
    include('../method/checkIfAccountLoggedIn.php');
    include('../method/query.php');
    $companyName = getQueryOneVal2('select name from weboms_company_tb','name');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>View Orders - Feedback</title>

    <link rel="stylesheet" type="text/css" href="../css/bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" href="../css/customer.css"> 
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>
<body style="background:#e0e0e0">

    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow">
        <div class="container py-3">
            <a class="navbar-brand fs-4" href="#"><?php echo $companyName;?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item me-2">
                        <a class="nav-link text-dark" href="customer.php"><i class="bi bi-house-door"></i> HOME</a>
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
                        <a class="nav-link text-danger" href="customerOrders.php"><i class="bi bi-list"></i> VIEW ORDERS</a>
                    </li>
                    <li>
                        <form method="post">
                            <button class="btn btn-danger col-12" id="Logout" name="logout"><i class="bi bi-power"></i> LOGOUT</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
<div class="container text-center bg-white shadow p-5" style="margin-top:130px;">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <button class="btn btn-lg btn-dark col-12 mb-4" id="orderList"><i class="bi bi-arrow-left-short"></i> Back</button>
            <script>document.getElementById("orderList").onclick = function () {window.location.replace('customerOrders.php'); }; </script> 
            <form method="post">
                <textarea type="text" name="feedback" placeholder="Enter your feedback" class="form-control form-control-lg mb-3" rows="5" required></textarea>
                <button type="submit" name="submit" class="btn btn-lg btn-success col-12"><i class="bi bi-file-arrow-up"></i> Submit</button>
            </form>
        </div>
	</div>
</div>
    
</body>
</html>

<?php 
    if(isset($_POST['submit'])){
        $arr = explode(',',$_GET['ordersLinkIdAndUserLinkId']);
        $order_id = $arr[0];
        $user_id = $arr[1];
        $feedback = $_POST['feedback'];
        $query = "insert into weboms_feedback_tb(feedback, order_id, user_id) values('$feedback', '$order_id', '$user_id')";
        if(Query2($query))
            echo "<script>alert('Feedback sent, thanks!'); window.location.replace('customerOrders.php');</script>";
    }
?>

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
