<?php 
    $page = 'customer';
    include('../method/checkIfAccountLoggedIn.php');
    include('../method/query.php');

    if(!isset($_SESSION["dishes"]) || !isset($_SESSION["price"]) || !isset($_SESSION["orderType"])){
        $_SESSION["dishes"] = array();
        $_SESSION["price"] = array(); 
        $_SESSION["orderType"] = array(); 
    }
    $_SESSION['multiArr'] = array();
    $companyName = getQueryOneVal2('select name from weboms_company_tb','name');
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Menu</title>
    
    <link rel="stylesheet" type="text/css" href="../css/bootstrap 5/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../css/customer.css">
    <!-- data tables -->
    <script type="text/javascript" src="../js/jquery-3.6.1.min.js"></script>
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
                        <a class="nav-link text-dark" href="customer.php"><i class="bi bi-house-door"></i> HOME</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link text-dark" href="customerProfile.php"><i class="bi bi-person-circle"></i> PROFILE</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link text-danger" href="customerMenu.php"><i class="bi bi-book"></i> MENU</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link text-dark" href="customerTopUp.php"><i class="bi bi-cash-stack"></i> TOP-UP</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link text-dark" href="customerOrders.php"><i class="bi bi-list"></i> VIEW ORDERS</a>
                    </li>
                </ul>
                <form method="post">
                    <button class="btn btn-danger" id="Logout" name="logout"><i class="bi bi-power"></i> LOGOUT</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- content here -->
    <div class="container text-center" style="margin-top:175px;">    
        <div class="row g-5 content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-10 bg-white shadow mb-5"> 
                        <div class="container p-4">
                            <div class="table-responsive col-lg-12">
                                <table id="tbl" class="table table-bordered table-hover col-lg-12">
                                    <thead class="table-dark">
                                        <tr>
                                            <th scope="col">IMAGE</th>
                                            <th scope="col">DISH</th>
                                            <th scope="col">PRICE</th>
                                            <th scope="col">STOCK</th>
                                            <th scope="col">ADD TO CART</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        //orderType, qty
                                        $cartArr =[[],[]];
                                        $getCartQuery = "SELECT * FROM `weboms_cart_tb` where user_id = '$_SESSION[user_id]' ";
                                        $resultSet =  getQuery2($getCartQuery);
                                        if($resultSet != null){
                                            foreach($resultSet as $row){
                                                array_push($cartArr[0],$row['orderType']);
                                                array_push($cartArr[1],$row['qty']);
                                            }
                                        }

                                        $query = "select * from weboms_menu_tb";
                                        $resultSet =  getQuery2($query);
                                        if($resultSet != null)
                                            foreach($resultSet as $row){ ?>
                                        <tr>
                                            <td><?php $pic = $row['picName']; echo "<img src='../dishesPic/$pic' style=width:150px;height:150px>";?></td>
                                            <td><?= ucwords($row['dish']);?></td>
                                            <td><?php echo '₱'. number_format($row['price'],2); ?></td>
                                            <td class="stocks"><?php 
                                                if(in_array($row['orderType'],$cartArr[0])){
                                                    $index = array_search($row['orderType'], $cartArr[0]);
                                                    $result = $row['stock']-$cartArr[1][$index];
                                                    if($result <= 0){
                                                        echo "Out of Stock!";
                                                    }
                                                    else{
                                                        echo $result;
                                                    }
                                                }
                                                else{
                                                    echo $row['stock'];
                                                }
                                            ?></td>
                                            <td>
                                                <!-- out of stock -->
                                                <?php if($row['stock'] <= 0){ ?>
                                                <a class="text-danger text-decoration-none">Out of Stock</a>
                                                <!-- not out of stock -->
                                                <?php } else{ ?>
                                                        <input type="hidden" name="order" value="<?php echo $row['dish'].",".$row['price'].",".$row['orderType'].",".$row['stock']?>">
                                                        <input type="number" placeholder="Quantity" name="qty" class="form-control" value="1">
                                                        <?php $value = $row['dish'].",".$row['price'].",".$row['orderType'].",".$row['stock'];?>
                                                        <button type="button" onclick="AddToCart(this)" value='<?php echo $value;?>'; class="btn btn-light col-12" style="border:1px solid #cccccc;"><i class="bi bi-cart-plus"></i></button>
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
                        <button class="btn btn-lg btn-success col-12 p-4 mb-3 shadow" id="viewCart"><i class="bi bi-cart"></i> Cart</button>
                        <button class="btn btn-lg btn-primary col-12 p-4 mb-5 shadow" id="customersFeedback"><i class="bi bi-chat-square-text"></i> View Feedback</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>


<script>
    document.getElementById("viewCart").onclick = function() { window.location.replace('customerCart.php'); };
    document.getElementById("customersFeedback").onclick = function() { window.location.replace('customerFeedbackList.php'); };

    function AddToCart(button){
        // init 
        var arr = button.value.split(","); // [dish][price][orderType][stock] 
        var qty = parseInt($(button).closest("td").find('[name="qty"]').val());
        let arrayCart = []; //user_id, orderType, qty
        let stock = parseInt($(button).closest("tr").find('.stocks').text());
        arrayCart.push(<?php echo $_SESSION['user_id']; ?>);
        arrayCart.push(arr[2]);
        arrayCart.push(qty);

        //validation  
        if(isNaN(stock)){
            alert('Out Of Stock');
            return;
        }
        if(qty <= 0){
            alert("quantity invalid");
            return;
        }
        if(qty > stock){
            alert("quantity is greater than stocks");
            return;
        }

        // decrease the stock in tb1
        stock = stock - qty;
        
        if(stock<=0){
            $(button).closest("tr").find('.stocks').text("Out Of Stock");
            $(button).closest("tr").css("background-color", "#808080");
        }
        else{
            $(button).closest("tr").find('.stocks').text(stock);
        }

        // add value in cart table in db
        $.ajax({
            url: "ajax/customer_addToCartTable.php",
            method: "post",
            data: {'data':JSON.stringify(arrayCart)},
            success: function(res){
                // refreshTable2();
            }   
        });
    }

    $(document).ready(function() {
        $('#tbl').DataTable();
    });
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
          $updateQuery = "UPDATE weboms_menu_tb SET stock = (stock + '$dishesQuantity[$i]') WHERE dish= '$dishesArr[$i]' ";    
          Query2($updateQuery);    
        }
    }
    session_destroy();
        echo "<script>window.location.replace('../general/login.php');</script>";
  }
?>
