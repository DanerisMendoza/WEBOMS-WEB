<?php 
    $page = 'customer';
    include('../method/checkIfAccountLoggedIn.php');
    include('../method/query.php');
    date_default_timezone_set('Asia/Manila');
    $date = new DateTime();
    $today =  $date->format('Y-m-d'); 
    $todayWithTime =  $date->format('Y-m-d H:i:s'); 
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    $_SESSION['multiArr'] = array();
    $companyName = getQueryOneVal2('select name from weboms_company_tb','name');
    $total = 0;
    $query = "SELECT balance FROM `weboms_userInfo_tb` where user_id = '$_SESSION[user_id]' ";
    $balance = getQueryOneVal2($query,'balance');
    $balance = $balance == null ? 0 : $balance;
?>  

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Menu | Cart</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../css/customer.css">
    <link rel="stylesheet" href="../css/customer-cart.css">
    <link rel="icon" href="../image/weboms.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/typewriter-effect@latest/dist/core.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
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

    <div class="container cart-container">
        <div class="card back-menu-card ">
            <a href="customerMenu.php" class="back-menu animate__animated animate__fadeInLeft"><i class="bi-arrow-left"></i>BACK TO MENU</a>
            <div class="card cart-card animate__animated animate__fadeInLeft">
                <div class="input-group ">
                    <label for="" class="form-control time"><?php echo $todayWithTime; ?></label>
                    <label for="" class="form-control balance bg-success" id="h1Balance"><?php echo '₱'.$balance; ?></label>
                </div>

                <!-- table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>DISH</th>
                                <th>QUANTITY</th>
                                <th>ACTIONS</th>
                                <th>PRICE</th>
                            </tr>
                        </thead>
                        <tbody id="tbody2">

                        </tbody>
                    </table>
                </div>
                <div class="input-group">
                    <button class="btn btn-place btn-success" id="orderBtn">PLACE ORDER</button>
                    <button class="btn btn-clear btn-danger" id="clear">CLEAR ORDER</button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

<script>
    var dishesArr = [];
    var priceArr  = [];
    var orderType = [];
    var dishesQuantity = [];

    refreshTable2();

    function refreshTable2(){
        //get cart attributes
        $.getJSON({
            url: "ajax/customer_getCartAttributes.php",
            method: "post",
            data: {'user_id':<?php echo $_SESSION['user_id'];?>},
            success: function(multiArrCart){
                if(multiArrCart != "null"){
                    // [dish][price][orderType][stock] 
                    // update and refresh table body 2
                    let tbody2 = "", total = 0;
                    for(let i = 0; i < multiArrCart[0].length; i++){
                        total += parseFloat(multiArrCart[1][i]) * parseInt(multiArrCart[2][i]);
                        tbody2 +=
                        "<tr>" +
                            "<td class='dishes' name='dish'>" + multiArrCart[0][i] + "</td>" +
                            "<td class ='quantity' name='quantity' >" + multiArrCart[2][i] + "</td>" +
                            "<td> <button class='btn btn-success' type='button' name='addToCartSubmit' onclick='increaseQuantity(this)' value='"+multiArrCart[3][i]+"'><i class='bi-plus'></i></button> <button class='btn btn-danger' type='button' name='addToCartSubmit' onclick='decreaseQuantity(this)' value='"+multiArrCart[3][i]+"'><i class='bi bi-dash'></i></button> </td>" +
                            "<td class='price'>" +'₱'+ multiArrCart[1][i]*multiArrCart[2][i] + "</td>"
                        "</tr>";
                    }
                    tbody2 +=   
                        "<tr>"+
                            "<td></td>"+
                            "<td></td>"+
                            "<td> <b>Total Amount:</b> </td>" +
                            "<td id='total'><b>₱"+total+"</b></td>"
                        "</tr>";
                    $("#tbody2 tr").remove();
                    $("#tbody2").append(tbody2);
                }
                else{
                    $("#tbody2 tr").find("#total").closest("tr").remove();
                }
                subtractTb2OnTb1();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert("Status: " + textStatus); alert("Error: " + errorThrown);
            }
        });   
    }
    document.getElementById("clear").onclick = function() { 
        $.ajax({
            url: "ajax/customer_clearCart.php",
            method: "post",
            data: {'data':JSON.stringify(<?php echo $_SESSION['user_id'];?>)},
            success: function(res){
                $("#tbody2 tr").each (function() {
                    this.remove();
                });
            }
        });
    };

    function subtractTb2OnTb1(){
        $("#tbody2 tr .dishes").closest('tr').each (function() {
            //tb2    
            let tb2Tr = $(this);
            let dish = tb2Tr.find('.dishes').text();
            let quantity = parseInt(tb2Tr.find('.quantity').text());
            
            // original stock
            $.ajax({
                url: "ajax/customer_getStockOriginalValue.php",
                method: "post",
                data: {'data':dish},
                success: function(originalStock){
                    if((originalStock-quantity) < 0){
                        tb2Tr.css("background-color", "#808080");    
                    }
                    else{
                        tb2Tr.css("background-color", "#FFFFFF");
                    }
                }
            });
        }); 
    }

    // increaseQuantity
    function increaseQuantity(button){
        // user_id, orderType, qty
        var arrayCart = [];
        arrayCart.push(<?php echo $_SESSION['user_id'];?>);
        arrayCart.push(button.value);
        arrayCart.push(1);
        let dish = $(button).closest("tr").find('[name="dish"]').text();

        // validation
        let bg = $(button).closest('tr').css('background-color');
        if(bg == 'rgb(128, 128, 128)'){
            alert("OUT OF STOCK");
            return;
        }

        $.ajax({
            url: "ajax/customer_getStockOriginalValue.php",
            method: "post",
            data: {'data':dish},
            success: function(originalStock){  
                let quantityTd = $("#tbody2 tr:contains('"+dish+"')").find('.quantity');
                let quantity = parseInt(quantityTd.text()) + 1;

                let result = originalStock - quantity;
                if(result < 0){
                    alert('Out of Stock');
                    return;
                }
                // add value in cart table in db
                $.ajax({
                    url: "ajax/customer_addToCartTable.php",
                    method: "post",
                    data: {'data':JSON.stringify(arrayCart)}
                });

                // update tb2 quantity
                quantityTd.text(quantity);

                // compute row price
                $.ajax({
                    url: "ajax/customer_getPriceOriginalValue.php",
                    method: "post",
                    data: {'data':dish},
                    success: function(originalPrice){  
                        // update tb2 price
                        let priceTd2 = $("#tbody2 tr:contains('"+dish+"')").find('.price');
                        let price2 = parseInt(priceTd2.text().slice(1));
                        let p = price2+parseInt(originalPrice);
                        priceTd2.text("₱"+p);
                        computeTotal();
                    }
                });
            }
        });
    }
       
    function computeTotal(){
        let totalTr = $("#tbody2 tr").find("#total"); 
        let total = 0;
        $("#tbody2 tr .dishes").closest('tr').each (function() {
            //tb2    
            let tb2Tr = $(this);
            let dish = tb2Tr.find('.dishes').text();
            let price = parseInt(tb2Tr.find('.price').text().slice(1));
            total += (price);
        }); 
        totalTr.text("₱"+total).css("font-weight", "bold");
        if(total <= 0){
            $("#tbody2 tr").find("#total").closest("tr").remove();
        }
    }

    // decreaseQuantity
    function decreaseQuantity(button){
        // user_id, orderType, qty
        var arrayCart = [];
        arrayCart.push(<?php echo $_SESSION['user_id'];?>);
        arrayCart.push(button.value);
        arrayCart.push(1);
        let dish = $(button).closest("tr").find('[name="dish"]').text();
        let quantityTd = $("#tbody2 tr:contains('"+dish+"')").find('.quantity');
        let quantity = parseInt(quantityTd.text()) - 1;

        $.ajax({
            url: "ajax/customer_getStockOriginalValue.php",
            method: "post",
            data: {'data':dish},
            success: function(originalStock){  
                let result = originalStock - quantity;
                if(result < 0){
                    $(button).closest('tr').css("background-color", "#808080"); 
                }
                else{
                    $(button).closest('tr').css("background-color", "#FFFFFF"); 
                }
            }
        });

        // update value in cart table in db
        $.ajax({
            url: "ajax/customer_subtractToCartTable.php",
            method: "post",
            data: {'data':JSON.stringify(arrayCart)},
        });

        // update tb2 quantity
        if(quantity <= 0){
            quantityTd.closest('tr').remove();
        }
        else{
            quantityTd.text(quantity);
        }

        $.ajax({
            url: "ajax/customer_getPriceOriginalValue.php",
            method: "post",
            data: {'data':dish},
            success: function(originalStock){  
                // update tb2 price
                let priceTd2 = $("#tbody2 tr:contains('"+dish+"')").find('.price');
                let price2 = parseInt(priceTd2.text().slice(1));
                let p = price2-parseInt(originalStock);
                priceTd2.text("₱"+p);
                computeTotal();
            }
        });
    }

    //order button (js)
    document.getElementById("orderBtn").addEventListener("click", () => {
        let cont = true;
        $("#tbody2 tr ").each (function() {
            let bg = $(this).find('.dishes').closest('tr').css('background-color');
            if(bg == 'rgb(128, 128, 128)'){
                cont = false;
            }
        });

        //check if you have order
        if ((!$("#tbody2 tr").find("#total").length > 0)) {
            alert('Please place your order!');
            return;
        }

        //check if what you have in cart is still available
        if(cont == false){
            alert('Please decrease some order in your cart!');
            return;
        }

        // check if  balance can order
        let total = parseInt($("#tbody2 tr").find("#total").text().slice(1));
        if (<?php echo $balance;?> < total) {
            alert('Your balance is less than your total order amount!');
            return;
        }

        // add value in cart table in db
           $.ajax({
            url: "ajax/customer_insertOrder.php",
            method: "post",
            data: {
                'user_id':JSON.stringify(<?php echo $_SESSION['user_id'];?>),
                'total':JSON.stringify(total),
                'post':JSON.stringify('webomsMobile'),
            },
            success: function(res){  
                alert("order success");
                console.log(res);
                $.ajax({
                    url: "ajax/customer_clearCart.php",
                    method: "post",
                    data: {'data':JSON.stringify(<?php echo $_SESSION['user_id'];?>)},
                    success: function(res){
                        $("#tbody2 tr").each (function() {
                            this.remove();
                        });
                        let balance = parseInt($("#h1Balance").text().slice(10)) - total;
                        $("#h1Balance").text("Balance: ₱"+balance);
                    }
                });
            }
        });
        
    });
</script>

<?php 
    if(isset($_POST['logout'])){
        session_destroy();
        echo "<script>window.location.replace('../general/login.php');</script>";
    }
?>