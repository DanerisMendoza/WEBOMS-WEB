<?php 
    $page = 'customer';
    include('method/checkIfAccountLoggedIn.php');
    if(!isset($_SESSION["dishes"]) || !isset($_SESSION["price"]) || !isset($_SESSION["orderType"])){
        $_SESSION["dishes"] = array();
        $_SESSION["price"] = array(); 
        $_SESSION["orderType"] = array(); 
    }
    $_SESSION['multiArr'] = array();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Costumer Menu</title>
    
    <link rel="stylesheet" type="text/css" href="css/bootstrap 5/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/customer.css">
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <!-- data tables -->
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top" style="border-bottom:1px solid #e3e1e1;">
        <div class="container py-3">
            <a class="navbar-brand fs-4" href="#">RESTONAME</a>
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
                    <button class="btn btn-danger col-12" id="Logout" name="logout"><i class="bi bi-power me-1"></i>LOGOUT</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container text-center" style="margin-top:120px;">
        <div class="row justify-content-center">
            <button type="button" class="btn btn-lg btn-dark col-12 mb-3" id="back">Back</button>
            <button class="btn btn-lg btn-success col-6 mb-4" id="customersFeedback">Customers Feedback</button>
            <button type="button" class="btn btn-lg btn-success col-6 mb-4" id="viewCart">View Cart</button>
            <div class="table-responsive col-lg-12">
                <table id="tbl" class="table table-bordered table-hover col-lg-12">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">DISH</th>
                            <th scope="col">PRICE</th>
                            <th scope="col">STOCK</th>
                            <th scope="col">IMAGE</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        include_once('method/query.php');
                        $query = "select * from WEBOMS_menu_tb";
                        $resultSet =  getQuery($query);
                        if($resultSet != null)
                            foreach($resultSet as $row){ ?>
                        <tr>
                            <td><?=$row['dish']?></td>
                            <td><?php echo $row['price']; ?></td>
                            <td><?php echo $row['stock']; ?></td>
                            <td><?php $pic = $row['picName']; echo "<img src='dishesPic/$pic' style=width:100px;height:100px>";?>
                            </td>
                            <td><a class="btn btn-light border-dark" <?php   if($row['stock'] <= 0) 
                                                echo "<button>Out of stock</button>";
                                            else{
                                    ?>
                                    href="?order=<?php echo $row['dish'].",".$row['price'].",".$row['orderType']?>">Add
                                    To Cart</a><?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
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
document.getElementById("back").onclick = function() { window.location.replace('customer.php'); };
document.getElementById("viewCart").onclick = function() { window.location.replace('customerCart.php'); };
document.getElementById("customersFeedback").onclick = function() { window.location.replace('customerFeedbackList.php'); };
</script>

<script>
$(document).ready(function() {
    $('#tbl').DataTable();
});

// $('#tbl').dataTable( {
//       "aoColumnDefs": [
//           { 'bSortable': false, 'aTargets': [ 3 ] }
//        ]
// });

// $('#tbl').tablesorter({
//         headers: {
//             0: { sorter: false },
//             4: { sorter: false }
//         }
//     });

// $(document).ready(function() {
//     $('#tbl').dataTable( {
//         "aaSorting": [[ 1, "desc" ]]
//     } );
// } );
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