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
    <title>Cart</title>

    <link rel="stylesheet" type="text/css" href="../css/bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/customer.css">
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
            <button class="btn btn-lg btn-dark col-12 mb-4" id="back"><i class="bi bi-arrow-left-short"></i> Back</button>
            <h1 class="form-control form-control-lg bg-light text-center "><?php echo $todayWithTime; ?></h1>
            <h1 id="h1Balance" class="form-control form-control-lg mb-4 bg-light text-center">Balance:<?php echo ' ₱'.$balance; ?></h1>

            <!-- table -->
            <div class="table-responsive col-lg-12">
                <table id="tbl" class="table table-hover table-bordered col-lg-12 mb-4">
                    <thead>
                        <tr>
                            <th scope="col">DISH</th>
                            <th scope="col">QUANTITY</th>
                            <th scope="col">Options</th>
                            <th></th>
                            <th scope="col">PRICE</th>
                        </tr>
                    </thead>
                    <tbody id="tbody2">

                    </tbody>
                </table>
                    <button id="orderBtn" class="btn btn-lg btn-success col-12 mb-3">Place Order</button>
                    <button type="button" class="btn btn-lg btn-danger col-12" id="clear" >Clear Order</button>
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


    document.getElementById("back").onclick = function() { window.location.replace('customerMenu.php'); };
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
                            "<td> <button class='btn btn-success mx-1 ' type='button' name='addToCartSubmit' onclick='increaseQuantity(this)' value='"+multiArrCart[3][i]+"' class='btn btn-light col-12'> <i class='bi bi-plus'></i></button> </td>" +
                            "<td> <button class='btn btn-danger' type='button' name='addToCartSubmit' onclick='decreaseQuantity(this)' value='"+multiArrCart[3][i]+"' class='btn btn-light col-12'> <i class='bi bi bi-dash'></i></button> </td>" +
                            "<td class='price'>" +'₱'+ multiArrCart[1][i]*multiArrCart[2][i] + "</td>"
                        "</tr>";
                    }
                    tbody2 +=   
                            "<tr>"+
                                "<td></td>"+
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
            },error: function(XMLHttpRequest, textStatus, errorThrown) {
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
            alert("Out of Stock!");
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