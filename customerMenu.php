<?php 
    $page = 'customer';
    include('method/checkIfAccountLoggedIn.php');
    include('method/query.php');

    if(!isset($_SESSION["dishes"]) || !isset($_SESSION["price"]) || !isset($_SESSION["orderType"])){
        $_SESSION["dishes"] = array();
        $_SESSION["price"] = array(); 
        $_SESSION["orderType"] = array(); 
    }
    $_SESSION['multiArr'] = array();
    $companyName = getQueryOneVal('select name from WEBOMS_company_tb','name');

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Menu</title>
    
    <link rel="stylesheet" type="text/css" href="css/bootstrap 5/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/customer.css">
    <script type="text/javascript" src="js/bootstrap 5/bootstrap.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <!-- data tables -->
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
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
                        <a class="nav-link text-dark" href="#" id="customer"><i class="bi bi-house-door me-1"></i>HOME</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link text-dark" href="#" id="customerProfile"><i class="bi bi-person-circle me-1"></i>PROFILE</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link text-danger" href="#"><i class="bi bi-book me-1"></i>MENU</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link text-dark" href="#" id="topUp"><i class="bi bi-cash-stack me-1"></i>TOP-UP</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link text-dark" href="#" id="customerOrder_details"><i class="bi bi-list me-1"></i>VIEW ORDERS</a>
                    </li>
                </ul>
                <form method="post">
                    <button class="btn btn-danger" id="Logout" name="logout"><i class="bi bi-power me-1"></i>LOGOUT</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container text-center" style="margin-top:175px;">    
        <div class="row g-5 content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-10 bg-white shadow mb-5"> 
                        <div class="container p-4">
                            <div class="table-responsive col-lg-12">
                                <table id="tbl" class="table table-bordered table-hover table-light col-lg-12">
                                    <thead class="table-dark">
                                        <tr>
                                            <th scope="col">IMAGE</th>
                                            <th scope="col">DISH</th>
                                            <th scope="col">PRICE</th>
                                            <th scope="col">STOCK</th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $query = "select * from WEBOMS_menu_tb";
                                        $resultSet =  getQuery($query);
                                        if($resultSet != null)
                                            foreach($resultSet as $row){ ?>
                                        <tr>
                                            <td><?php $pic = $row['picName']; echo "<img src='dishesPic/$pic' style=width:150px;height:150px>";?></td>
                                            <td><?=$row['dish']?></td>
                                            <td><?php echo '₱'. number_format($row['price'],2); ?></td>
                                            <td><?php echo $row['stock']; ?></td>
                                            <td>
                                                <a class="text-danger text-decoration-none">
                                                <?php if($row['stock'] <= 0) 
                                                    echo "OUT OF STOCK";
                                                    else{
                                                ?>
                                                </a>
                                                <a class="btn btn-light border-secondary" href="?order=<?php echo $row['dish'].",".$row['price'].",".$row['orderType']?>">ADD TO CART</a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 sidenav px-4">
                        <button class="btn btn-lg btn-success col-12 p-4 mb-3 shadow" id="viewCart"><i class="bi bi-cart me-1"></i>CART</button>
                        <button class="btn btn-lg btn-primary col-12 p-4 mb-5" id="customersFeedback"><i class="bi bi-chat-square-text me-1"></i>FEEDBACK</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
<?php 
    //add to cart
	if(isset($_GET['order'])){
        $order = explode(',',$_GET['order']);  
        $dish = $order[0];
        $price = $order[1];
		$orderType = $order[2];
        array_push($_SESSION['dishes'], $dish);
        array_push($_SESSION['price'], $price);
        array_push($_SESSION['orderType'], $orderType);
        $updateQuery = "UPDATE WEBOMS_menu_tb SET stock = (stock - 1) WHERE dish= '$dish' ";    
        if(Query($updateQuery))
          echo "<script>window.location.replace('customerMenu.php');</script>";    
    }				
?>

<script>
document.getElementById("viewCart").onclick = function() { window.location.replace('customerCart.php'); };
document.getElementById("customersFeedback").onclick = function() { window.location.replace('customerFeedbackList.php'); };
</script>

<script>
    $(document).ready(function() {
        $('#tbl').DataTable();
    });
</script>

<script>
document.getElementById("customer").onclick = function() { window.location.replace('customer.php'); };
document.getElementById("customerProfile").onclick = function() { window.location.replace('customerProfile.php'); };
document.getElementById("topUp").onclick = function() { window.location.replace('customerTopUp.php'); };
document.getElementById("customerOrder_details").onclick = function() { window.location.replace('customerOrders.php'); };
</script>

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
          $updateQuery = "UPDATE WEBOMS_menu_tb SET stock = (stock + '$dishesQuantity[$i]') WHERE dish= '$dishesArr[$i]' ";    
          Query($updateQuery);    
        }
    }
    session_destroy();
    echo "<script>window.location.replace('Login.php');</script>";
  }
?>
