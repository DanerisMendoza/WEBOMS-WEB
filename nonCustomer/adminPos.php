<?php 
    $page = 'cashier';
    include('../method/checkIfAccountLoggedIn.php');
    include('../method/query.php');
    if(!isset($_SESSION["dishes"]) && !isset($_SESSION["price"])){
        $_SESSION["dishes"] = array();
        $_SESSION["price"] = array(); 
        $_SESSION["orderType"] = array(); 
    }
    $_SESSION['query'] = 'all';
    $_SESSION['refreshCount'] = 0;
    $_SESSION['multiArr'] = array();
    $_SESSION['fromReceipt'] = 'pos';
    //company variables init
    $query = "select * from weboms_company_tb";
    $resultSet = getQuery2($query);
    if($resultSet!=null)
        foreach($resultSet as $row){
          $_SESSION['companyName'] = $row['name'];
          $_SESSION['companyAddress'] = $row['address'];
          $_SESSION['companyTel'] = $row['tel'];
        }
    // redefining name
    $_SESSION['name'] = getQueryOneVal2("select name from weboms_userInfo_tb where user_id = '$_SESSION[user_id]' ",'name');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Point of Sales</title>

    <link rel="stylesheet" href="../css/bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <script type="text/javascript" src="../js/bootstrap 5/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/jquery-3.6.1.min.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <!-- data tables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <div class="wrapper">
       <!-- Sidebar  -->
       <nav id="sidebar" class="bg-dark">
            <div class="sidebar-header bg-dark">
                <h3 class="mt-3"><a href="admin.php"><?php echo ucwords($_SESSION['accountType']); ?></a></h3>
            </div>
            <ul class="list-unstyled components ms-3">
                <li class="mb-2 active">
                    <a href="adminPos.php"><i class="bi bi-tag me-2"></i>Point of Sales</a>
                </li>
                <li class="mb-2">
                    <a href="adminOrders.php"><i class="bi bi-minecart me-2"></i>Orders</a>
                </li>
                <li class="mb-2">
                    <a href="adminOrdersQueue.php"><i class="bi bi-clock me-2"></i>Orders Queue</a>
                </li>
                <li class="mb-2">
                    <a href="topupRfid.php"><i class="bi bi-credit-card me-2"></i>Top-Up RFID</a>
                </li>
            
            <?php if($_SESSION['accountType'] != 'cashier'){?>
                <li class="mb-2">
                    <a href="adminInventory.php"><i class="bi bi-box-seam me-2"></i>Inventory</a>
                </li>
                <li class="mb-2">
                    <a href="adminSalesReport.php"><i class="bi bi-bar-chart me-2"></i>Sales Report</a>
                </li>
                <li class="mb-2">
                    <a href="accountManagement.php"><i class="bi bi-person-circle me-2"></i>Account Management</a>
                </li>
                <li class="mb-2">
                    <a href="adminFeedbackList.php"><i class="bi bi-chat-square-text me-2"></i>Customer Feedback</a>
                </li>
                <li class="mb-2">
                    <a href="adminTopUp.php"><i class="bi bi-cash-stack me-2"></i>Top-Up</a>
                </li>
                <li class="mb-1">
                    <a href="settings.php"><i class="bi bi-gear me-2"></i>Settings</a>
                </li>
            <?php } ?>
                <li>
                    <form method="post">
                        <button class="btn btnLogout btn-dark text-danger" id="Logout" name="logout"><i class="bi bi-power me-2"></i>Logout</button>
                    </form>
                </li>
            </ul>
        </nav>

        <!-- Page Content  -->
        <div id="content">
            <nav class="navbar navbar-expand-lg bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn" style="font-size:20px;"><i class="bi bi-list"></i> Toggle</button>
                </div>
            </nav>

            <!-- content here -->
            <div class="container-fluid text-center">
                <div class="row g-5 justify-content-center">
                    <!-- admin -->
                    <?php if($_SESSION['accountType'] != 'cashier'){?>
                    <h1 class="text-center bg-dark text-white"><?php echo strtoupper($_SESSION['accountType']); ?></h1>

                    <!-- cashier -->
                    <?php }else{?>
                    <h1 class="text-center bg-danger text-white">CASHIER</h1>
                    <?php }?>

                    <!-- table container -->
                    <div class="table-responsive col-lg-7">
                        <table class="table table-hover table-bordered col-lg-12" id="tBody1">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">DISH</th>
                                    <th scope="col">PRICE</th>
                                    <th scope="col">STOCK</th>
                                    <th scope="col">ADD TO CART</th>
                                </tr>
                            </thead>
                            <tbody id="tbody1">
                            <?php 
                                $query = "select * from weboms_menu_tb";
                                $resultSet =  getQuery2($query);
                                if($resultSet != null)
                                    foreach($resultSet as $row){ ?>
                            <tr>
                                <td class="dishes" ><?= $row['dish'];?></td>
                                <td><?php echo "₱".number_format($row['price'],2); ?></td>
                                <td class="stocks"><?php echo $row['stock']; ?></td>
                                <!-- add to cart -->
                                <td>
                                    <!-- out of stock -->
                                    <?php if($row['stock'] <= 0){ ?>
                                        <a class="text-danger">Out of Stock</a>
                                        <!-- not out of stock -->
                                        <?php } else{ ?>
                                                <?php $a = $row['dish'].",".$row['price'].",".$row['orderType'].",".$row['stock'];?>
                                                <input type="number" placeholder="Quantity" name="qty" class="form-control" value="1" id="qty">
                                                <button type="button" name="addToCartSubmit" onclick='AddToCart(this)' value="<?php echo $a; ?>" class="btn btn-light col-12" style="border:1px solid #cccccc;"><i class="bi bi-cart-plus"></i></button>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- 2nd table container -->
                    <div class="table-responsive col-lg-5 mb-5">
                        <table class="table table-bordered table-hover col-lg-12 mb-4" id="tBody2">
                            <thead>
                                <tr>
                                    <th scope="col">DISH</th>
                                    <th scope="col">QUANTITY</th>
                                    <th scope="col">PRICE</th>
                                    <th scope="col" colspan="3">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tbody2">
                            </tbody>
                        </table>
                            <input  id='customerName' placeholder='Customer Name (Optional)' type='text' class='form-control form-control-lg mb-3'>
                            <input  id="cashNum" name="cash"  step=any placeholder="Cash Amount (₱)" type="number" class="form-control form-control-lg mb-4" required>
                            <button id="orderBtn" type="submit" class="btn btn-lg btn-success col-12 mb-3" name="orderBtn">Place Order</button>
                            <button type="submit" id="clear" class="btn btn-lg btn-danger col-12" name="clear">Clear Order</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
<script>
    function refreshTable2(mode){
        //get cart attributes
        $.getJSON({
            url: "ajax/pos_getCartAttributes.php",
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
                        "<td>" +'₱'+ multiArrCart[1][i] + "</td>" +
                        "<td> <button class='btn btn-success' type='button' name='addToCartSubmit' onclick='increaseQuantity(this)' value='"+multiArrCart[3][i]+"' class='btn btn-light col-12' style='border:1px solid #cccccc;'> <i class='bi bi-plus'></i></button> </td>" +
                        "<td> <button class='btn btn-danger' type='button' name='addToCartSubmit' onclick='decreaseQuantity(this)' value='"+multiArrCart[3][i]+"' class='btn btn-light col-12' style='border:1px solid #cccccc;'> <i class='bi bi bi-dash'></i></button> </td>" +
                        "<td> <button type='button' name='addToCartSubmit' onclick='removeRow(this)' value='"+multiArrCart[3][i]+"' class='btn btn-light col-12' style='border:1px solid #cccccc;'> <i class='bi bi-cart-x-fill'></i></button> </td>"
                    "</tr>";
                    }

                    tbody2 += 
                    "<tr>"+
                        "<td colspan='2'> <b>Total Amount:</b> </td>" +
                        "<td id='total'><b>₱"+total+"</b></td>"+
                        "<td></td>"+
                        "<td></td>"
                    "</tr>";
                    $("#tbody2 tr").remove();
                    $("#tbody2").append(tbody2);
                }
                else{
                    $("#tbody2 tr").find("#total").closest("tr").remove();
                }

                if(mode == 'firstLoad'){
                    // subtract cart products quantity to db stock
                    $("#tbody1 tr .dishes").each (function() {
                        let stock = parseInt($(this).closest("tr").find(".stocks").text());
                        if(multiArrCart[0].includes(this.innerHTML)){ 
                            let index = multiArrCart[0].indexOf(this.innerHTML.toString().toLowerCase());
                            stock -= multiArrCart[2][index];
                        }
                        if(stock == 0){
                            $(this).closest("tr").find(".stocks").text("Out of Stock");
                            return;
                        }
                        else if(stock < 0){
                            // $("#tbody2 tr:contains('"+this.innerHTML+"')").remove();
                            $("#tbody2 tr:contains('"+this.innerHTML+"')").css("background-color", "#808080");
                            $(this).closest("tr").find(".stocks").closest('tr').css("background-color", "#808080");
                            $(this).closest("tr").find(".stocks").text("Out of Stock");
                            return;
                        }
                        $(this).closest("tr").find(".stocks").text(stock);
                    });       
                }
                else{
                    // subtract 
                    let dish = $(mode).closest("tr").find('.dishes').text();
                    let qty = parseInt($(mode).closest("td").find('[name="qty"]').val());
                    let stocks = $(mode).closest("tr").find('.stocks').text();
                    $.ajax({
                        url: "ajax/pos_checkStockOriginalValue.php",
                        method: "post",
                        data: {'data':dish},
                        success: function(originalStock){
                            let quantity = parseInt($("#tbody2 tr:contains('"+dish+"')").find('.quantity').text());
                            if(quantity > parseInt(originalStock)){
                                $("#tbody2 tr:contains('"+dish+"')").css("background-color", "#808080");
                            }
                            else{
                                $("#tbody2 tr:contains('"+dish+"')").css("background-color", "#FFFFFF");
                            }
                        }
                    });

                    if(stocks == 0){
                        $(mode).closest("tr").find('.stocks').text("Out Of Stocks");
                        return;
                    }
                    $(mode).closest("tr").find('.stocks').text(stocks-qty);
                }

            },error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert("Status: " + textStatus); alert("Error: " + errorThrown);
            }
        });
    }
    
    refreshTable2('firstLoad');

    // global arrays    [dishes][prices][quantity][order type]
    var multiArrCart =  [[],[],[],[]];
    var total = 0;

    // add to cart
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

        // add value in cart table in db
        $.ajax({
            url: "ajax/pos_addToCartTable.php",
            method: "post",
            data: {'data':JSON.stringify(arrayCart)}//,
            // success: function(res){
            //     refreshTable2(button);
            // }
        });
      
    }

    // sidebar(js)
    $(document).ready(function() {
        $('#sidebarCollapse').on('click', function() {
            $('#sidebar').toggleClass('active');
        });
    });

    // clear
    document.getElementById("clear").addEventListener("click", () => {  
        $.ajax({
            url: "ajax/pos_addStocks.php",
            method: "post",
            data: {'data':JSON.stringify(multiArrCart)},
            success: function(){
                // refresh tbody1
                $.get("ajax/pos_tbody1.php", function(tbody1) {
                    $("#tbody1").html(tbody1);
                });
            }
        });
        multiArrCart =  [[],[],[],[]];
        document.getElementById("tbody2").innerHTML = "";
    });

    //order button (js)
    document.getElementById("orderBtn").addEventListener("click", () => {
        // staff, customer, cash, total
        let otherAttributes = [];
        var customer =  document.getElementById("customerName").value;
        var cash = document.getElementById("cashNum").value;
        otherAttributes.push('<?php echo $_SESSION['name']; ?>');
        otherAttributes.push(customer);
        otherAttributes.push(cash);
        // get total
            // get total
        total = 0;
        for(let i=0; i<multiArrCart[1].length; i++){    
            total = total + multiArrCart[1][i];
        }
        otherAttributes.push(total);
        if(cash == ""){
            alert('Please Enter Amount');
            return;
        }
        if (total == 0) {
            alert('Please place your order!');
            return;
        }
        if (cash >= total) {
            $.ajax({
            url: "ajax/pos_insertOrder.php",
            method: "post",
            data: {'multiArrCart':JSON.stringify(multiArrCart),'otherAttributes':JSON.stringify(otherAttributes)},
            success: function(){
                // clean this window variables
                multiArrCart =  [[],[],[],[]];
                document.getElementById("tbody2").innerHTML = "";
                document.getElementById("customerName").value = "";
                document.getElementById("cashNum").value = "";
                // open receipt in new tab
                alert("Success placing order!");
                window.open("../pdf/receipt.php");
            }
            });
        }
        else{
            alert("Amount Less than total!");
        }
    });

    // data table
    $(document).ready(function() {
        $('#tBody1').DataTable();
    });


    // increaseQuantity
    function increaseQuantity(button){
        // user_id, orderType, qty
        var arrayCart = [];
        arrayCart.push(<?php echo $_SESSION['user_id'];?>);
        arrayCart.push(button.value);
        arrayCart.push(1);
        let dish = $(button).closest("tr").find('[name="dish"]').text();
        $("#tbody1 tr .dishes").each (function() {
            if(this.innerHTML == dish){ 
                let stock = $(this).closest("tr").find(".stocks").text();
                let qty = $(button).closest("tr").find('[name="quantity"]');
                let qtyInt = parseInt(qty.text()) + 1; 
                if(isNaN(stock)){
                    alert("Out of Stocks");
                    return;
                }

                // add value in cart table in db
                $.ajax({
                    url: "ajax/pos_addToCartTable.php",
                    method: "post",
                    data: {'data':JSON.stringify(arrayCart)}
                });
                
                stock = parseInt(stock);
                stock--;

                if(stock <= 0){
                    $(this).closest("tr").find(".stocks").text("Out Of Stocks");
                }
                else{
                    $(this).closest("tr").find(".stocks").text(stock);
                }
                qty.text(qtyInt);
            }
        });       
    }

    // decreaseQuantity
    function decreaseQuantity(button){
        // user_id, orderType, qty
        var arrayCart = [];
        arrayCart.push(<?php echo $_SESSION['user_id'];?>);
        arrayCart.push(button.value);
        arrayCart.push(1);
        let dish = $(button).closest("tr").find('[name="dish"]').text();

        // update value in cart table in db
        $.ajax({
            url: "ajax/pos_subtractToCartTable.php",
            method: "post",
            data: {'data':JSON.stringify(arrayCart)}
        });
        $("#tbody1 tr .dishes").each (function() {
            if(this.innerHTML == dish){ 
                // init
                var originalStock;
                // tb2
                let qty = $(button).closest("tr").find('[name="quantity"]');
                let qtyInt = parseInt(qty.text()) - 1; 
                let this0 = this;
                // check original value
                $.ajax({
                    url: "ajax/pos_checkStockOriginalValue.php",
                    method: "post",
                    data: {'data':dish},
                    success: function(originalStock){
                        let stock = $(this0).closest("tr").find(".stocks").text();
                        // tb 1
                        if(isNaN(stock)){
                            if(qtyInt < parseInt(originalStock)){
                                $(this0).closest("tr").find(".stocks").text("1");
                                qty.text(qtyInt);
                                return;
                            }
                        }
                        // tb 1
                        if(qtyInt < parseInt(originalStock)){
                            stock = parseInt(stock);
                            stock++;
                            $(this0).closest("tr").find(".stocks").text(stock);
                        }

                        if(qtyInt == parseInt(originalStock)){
                            $(this0).closest("tr").css("background-color", "#FFFFFF");
                            $(button).closest("tr").find('[name="dish"]').closest('tr').css("background-color", "#FFFFFF");
                        }
                        // remove tbody row 2 and the row of current button
                        if(qtyInt == 0){
                            $(button).closest("tr").find('[name="dish"]').closest('tr').remove();
                            $("#tbody2 tr").eq(1).remove();
                        }
                        qty.text(qtyInt);
                    }
                });
            }
        });       
    }


    function removeRow(button){
        // user_id, orderType
        var arrayCart = [];
        arrayCart.push(<?php echo $_SESSION['user_id'];?>);
        arrayCart.push(button.value);
        let dish = $(button).closest("tr").find('[name="dish"]').text();
        let quantity = $(button).closest("tr").find('[name="quantity"]').text();

        // update value in cart table in db
        $.ajax({
            url: "ajax/pos_removeDishInCart.php",
            method: "post",
            data: {'data':JSON.stringify(arrayCart)},
            success: function(){
                $("#tbody1 tr .dishes").each (function() {
                    if(this.innerHTML == dish){ 
                        let stock = $(this).closest("tr").find(".stocks").text();
                        if(isNaN(stock)){
                            $(this).closest("tr").find(".stocks").text(quantity);
                        }
                        else{
                            stock = parseInt(stock);
                            stock+=parseInt(quantity);
                            $(this).closest("tr").find(".stocks").text(stock);
                        }
                        $(this).closest("tr").css("background-color", "#FFFFFF");
                        button.closest("tr").remove();
                    }
                });    
           
            }
        });
    }
    
</script>