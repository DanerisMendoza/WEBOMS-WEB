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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders | Order Details</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="../css/customer.css">
    <link rel="stylesheet" href="../css/customer-order-details.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js"></script>
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
                        <a class="nav-link animate__animated animate__fadeInLeft" href="customerMenu.php">MENU</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link animate__animated animate__fadeInLeft" href="customerTopUp.php">TOP-UP</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger animate__animated animate__fadeInLeft" href="customerOrders.php">VIEW ORDERS</a>
                    </li>
                </ul>
                <form action="" method="post">
                    <button class="btn btn-logout btn-outline-light animate__animated animate__fadeInLeft" id="Logout" name="logout">LOGOUT</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <a href="customerOrders.php" class="back-menu"><i class="bi-arrow-left"></i>BACK TO ORDERS</a>
            <div class="card">
                <button class="btn btn-danger" id="viewInPdf">PDF</button>
                <div class="table-responsive">
                    <?php 
                        $id =  $_GET['id'];
                        $_SESSION['dishesArr'] = array();
                        $_SESSION['priceArr'] = array();
                        $_SESSION['dishesQuantity'] = array();

                        $query = "select a.*, b.* from weboms_userInfo_tb a inner join weboms_order_tb b on a.user_id = b.user_id  where b.order_id = '$id' " ;
                        $resultSet = getQuery2($query); 
                        if($resultSet != null){
                            foreach($resultSet as $row){ 
                                //init
                                $_SESSION['order_id'] = $row['order_id'];
                                $_SESSION['or_number'] = $row['or_number'];
                                $_SESSION['customerName'] = $row['name'];
                                $_SESSION['date'] = $row['date'];
                                $_SESSION['cash'] = $row['payment'];
                                $_SESSION['total'] = $row['totalOrder'];
                                $_SESSION['staffInCharge'] = $row['staffInCharge'];
                            }
                        }
                        //company variables init
                        $query = "select * from weboms_company_tb";
                        $resultSet = getQuery2($query);
                        if($resultSet!=null){
                            foreach($resultSet as $row){
                            $_SESSION['companyName'] = $row['name'];
                            $_SESSION['companyAddress'] = $row['address'];
                            $_SESSION['companyTel'] = $row['tel'];
                            }
                        }

                        $query = "select weboms_menu_tb.*, weboms_ordersDetail_tb.* from weboms_menu_tb inner join weboms_ordersDetail_tb where weboms_menu_tb.orderType = weboms_ordersDetail_tb.orderType and weboms_ordersDetail_tb.order_id = '$id' ";
                        $resultSet =  getQuery2($query); 
                    ?>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>DISH</th>
                                <th>QUANTITY</th>
                                <th>PRICE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $total = 0;
                                if($resultSet != null)
                                foreach($resultSet as $row){ 
                                    array_push($_SESSION['dishesArr'],$row['dish']);
                                    array_push($_SESSION['priceArr'],$row['price']);
                                    array_push($_SESSION['dishesQuantity'],$row['quantity']);
                            ?>
                            <tr>	   
                                <?php $price = ($row['price']*$row['quantity']);  $total += $price;?>
                                <td><?php echo strtoupper($row['dish']); ?></td>
                                <td><?php echo $row['quantity']; ?></td>
                                <td><?php echo '₱'.number_format($price, 2); ?></td>
                            </tr>
                            <?php }?>
                            <tr>
                                <td></td>
                                <td><b>Total Amount:</b></td>
                                <td><b>₱<?php echo number_format($total,2);?></b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>

<script>
    //order button (js)
    var viewInPdf = document.getElementById("viewInPdf");
    viewInPdf.addEventListener("click", () => {
        window.open("../pdf/receipt.php");
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