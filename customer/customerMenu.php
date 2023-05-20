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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="../css/customer.css">
    <link rel="stylesheet" href="../css/customer-menu.css">
    <link rel="icon" href="../image/weboms.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand animate__animated animate__fadeInLeft" href="#"><?php echo strtoupper($companyName); ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link animate__animated animate__fadeInLeft" href="customer.php">HOME</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link animate__animated animate__fadeInLeft" href="customerProfile.php">PROFILE</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger animate__animated animate__fadeInLeft" href="customerMenu.php">MENU</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link animate__animated animate__fadeInLeft" href="customerTopUp.php">TOP-UP</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link animate__animated animate__fadeInLeft" href="customerOrders.php">ORDERS</a>
                    </li>
                </ul>
                <form action="" method="post">
                    <button class="btn btn-logout btn-outline-light animate__animated animate__fadeInLeft" id="Logout" name="logout">LOGOUT</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-10">
                    <div class="card menu-card">
                        <div class="table-responsive animate__animated animate__fadeInLeft">
                            <table class="table table-bordered table-striped" id="tbl">
                                <thead style="background: #181C25; color: #FFFFFF;">
                                    <tr>
                                        <th>IMAGE</th>
                                        <th>DISH</th>
                                        <th>PRICE</th>
                                        <th>STOCK</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        // orderType, qty
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
                                            <td>
                                                <center><?php $pic = $row['picName']; echo "<img src='../dishesPic/$pic' class='menu-img'";?></center>
                                            </td>
                                            <td><?= strtoupper($row['dish']);?></td>
                                            <td><?php echo '₱'. number_format($row['price'],2); ?></td>
                                            <td class="stocks">
                                                <?php 
                                                    if(in_array($row['orderType'],$cartArr[0])){
                                                        $index = array_search($row['orderType'], $cartArr[0]);
                                                        $result = $row['stock']-$cartArr[1][$index];
                                                        if($result <= 0){
                                                            echo "<a class='text-danger fw-bold'>OUT OF STOCK</a>";
                                                        }
                                                        else{
                                                            echo $result;
                                                        }
                                                    }
                                                    else{
                                                        echo $row['stock'];
                                                    } 
                                                ?>
                                            </td>
                                            <td>
                                                <!-- out of stock -->
                                                <?php if($row['stock'] <= 0){ ?>
                                                <label for="">OUT OF STOCK</label>
                                                <!-- not out of stock    -->
                                                <?php } else{ ?>
                                                <input type="hidden" name="order" value="<?php echo $row['dish'].",".$row['price'].",".$row['orderType'].",".$row['stock']?>">
                                                <input type="number" placeholder="QUANTIY" name="qty" class="form-control" value="1">
                                                <?php $value = $row['dish'].",".$row['price'].",".$row['orderType'].",".$row['stock'];?>
                                                <button type="button" onclick="AddToCart(this)" value='<?php echo $value;?>'; class="btn btn-add-to-cart">ADD TO CART</button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <a href="customerCart.php" class="btn btn-cart animate__animated animate__fadeInLeft">CART</a>
                    <a href="customerFeedbackList.php" class="btn btn-feedback animate__animated animate__fadeInLeft">FEEDBACK</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

<script>
    function AddToCart(button){
        // init 
        var arr = button.value.split(","); // [dish][price][orderType][stock] 
        var qty = parseInt($(button).closest("td").find('[name="qty"]').val());
        let arrayCart = []; // user_id, orderType, qty
        let stock = parseInt($(button).closest("tr").find('.stocks').text());
        arrayCart.push(<?php echo $_SESSION['user_id']; ?>);
        arrayCart.push(arr[2]);
        arrayCart.push(qty);

        // validation  
        if(isNaN(stock)){
            alert('OUT OF STOCK');
            return;
        }
        if(qty <= 0){
            alert("QUANTITY INVALID");
            return;
        }
        if(qty > stock){
            alert("QUANTITY IS GREATER THAN STOCKS");
            return;
        }

        // decrease the stock in tb1
        stock = stock - qty;
        
        if(stock<=0){
            $(button).closest("tr").find('.stocks').text("OUT OF STOCK");
            $(button).closest("tr").css("background-color", "#c46f70");
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
