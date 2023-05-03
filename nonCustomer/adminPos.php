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
                    <?php } else{?>
                    <h1 class="text-center bg-danger text-white">CASHIER</h1>
                    <?php }?>

                    <!-- table container -->
                    <div class="table-responsive col-lg-7">
                        <table class="table table-hover table-bordered col-lg-12" id="tbl1">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">DISH</th>
                                    <th scope="col">PRICE</th>
                                    <th scope="col">STOCK</th>
                                    <th scope="col">ADD TO CART</th>
                                </tr>
                            </thead>
                            <tbody id="tbody1"></tbody>
                        </table>
                    </div>

                    <!-- 2nd table container -->
                    <div class="table-responsive col-lg-5 mb-5">
                        <table class="table table-bordered table-hover col-lg-12 mb-4" id="tBody2">
                            <thead>
                                <tr>
                                    <th scope="col">DISH</th>
                                    <th scope="col" colspan="3">QUANTITY</th>
                                    <th scope="col">PRICE</th>
                                </tr>
                            </thead>
                            <tbody id="tbody2"></tbody>
                        </table>
                            <input  id='customerName' placeholder='Customer Name (Optional)' type='text' class='form-control form-control-lg mb-3'>
                            <input  id="cashNum" name="cash"  step=any placeholder="Cash Amount (₱)" type="number" class="form-control form-control-lg mb-4" required>
                            <button id="payThruRfid" type="submit" class="btn btn-lg btn-secondary col-12 mb-1" name="orderBtn">RFID PAYMENT</button>
                            <button id="orderBtn" type="submit" class="btn btn-lg btn-success col-12 mb-1" name="orderBtn">PLACE ORDER</button>
                            <button type="submit" id="clear" class="btn btn-lg btn-danger col-12" name="clear">CLEAR ORDER</button>
                    </div>
                </div>

                <!-- RFID SCANNER (modal)-->
                <div class="modal fade" role="dialog" id="rfid">
                    <div class="modal-dialog">
                        <div class="modal-content modal-content-scanner">
                            <div class="modal-body">
                            <input type="text" id="rfidInput">                            
                                <div class="ocrloader">
                                    <em></em>
                                    <div>Binding RFID</div>                                                               
                                    <span></span>
                                </div>
                                <div class="loading">
                                <span></span>
                                <span></span>
                                <span></span>
                                </div>
                                <br></br>
                                <br></br>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Crendential (modal)-->
                <div class="modal fade" role="dialog" id="credentialTable">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-body">
                             <!-- profile pic -->
                             <div class="table-responsive col-lg-12">
                                    <table class="table table-bordered table-hover col-lg-12" id="tableProfile">
                                        <tbody>
                                            <img id="profilePic" src="../pic/unknown.png" style="width:200px;height:200px;border-radius:10rem;" class="mb-3"> 
                                        </tbody>
                                    </table>
                                </div>

                                <!-- customer credential table-->
                                <div class="table-responsive col-lg-12">
                                    <table class="table table-bordered table-hover col-lg-12" id="tableInformation">
                                        <thead class="table-dark text-white">
                                            <th>Name</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Gender</th>
                                            <th>Phone Number</th>
                                            <th>Address</th>
                                            <th>Balance</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                <button type='button' id="cancel" class="btn btn-lg btn-danger col-6">Cancel</button>
                                <button type='button' id="confirm" class="btn btn-lg btn-success col-6 ">Confirm</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>
<script>
    var customer,cash,otherAttributes = [];
    var userIdAndTotal = [];

    $(document).ready(function(){
        // modal trigger
        $("#payThruRfid").click(function(){
            $('#rfid').modal('show');
        });

        $("#confirm").click(function(){
            // trigger order
            otherAttributes = [];     // staff, customer, cash, total
            otherAttributes.push('<?php echo $_SESSION['name']; ?>');
            otherAttributes.push(customer);
         
            
            let cont = true;

            $("#tbody2 tr ").each (function() {
                let bg = $(this).find('.dishes').closest('tr').css('background-color');
                if(bg == 'rgb(128, 128, 128)'){
                    cont = false;
                }
            });

            if(cont == false){
                alert('Please decrease some order in your cart!');
                return;
            }
        
            if ($('#tbody2 tr').length == 0) {
                alert('Please place your order!');
                return;
            }

        
            let total = parseInt($("#tbody2 tr").find("#total").text().slice(1));

            if (cash < total) {
                alert("Amount Less than total!");
                return;
            }
            cash = total;
            userIdAndTotal.push(total);
            otherAttributes.push(cash);
            otherAttributes.push(total);

            //get cart attributes
            $.getJSON({
                url: "ajax/pos_getCartAttributes.php",
                method: "post",
                data: {'user_id':<?php echo $_SESSION['user_id'];?>},
                success: function(multiArrCart){
                    if(multiArrCart != "null"){
                        // compute price
                        for(let i=0; i<multiArrCart[0].length; i++){
                            multiArrCart[1][i] = multiArrCart[1][i]*multiArrCart[2][i]; 
                        }
                        // insert the order
                        $.ajax({
                        url: "ajax/pos_insertOrder.php",
                        method: "post",
                        data: {'multiArrCart':JSON.stringify(multiArrCart),'otherAttributes':JSON.stringify(otherAttributes)},
                        success: function(){
                            // subtract cart qty to menu stock
                            $.ajax({
                            url: "ajax/pos_subtractCartOnMenuTb.php",
                            method: "post",
                            data: {'multiArrCart':JSON.stringify(multiArrCart)},
                            success: function(){
                                // subtract cart total to customer balance
                                $.ajax({
                                url: "ajax/pos_subtractOrderToCustomerBalance.php",
                                method: "post",
                                data: {'userIdAndTotal':JSON.stringify(userIdAndTotal)},
                                success: function(res){
                                    console.log(res);
                                    // clear the cart
                                    $.ajax({
                                    url: "ajax/pos_clearCart.php",
                                    method: "post",
                                    data: {'data':JSON.stringify(<?php echo $_SESSION['user_id'];?>)},
                                    success: function(){
                                        createTable1("clear");
                                        $("#tbody2 tr").each (function() {
                                            this.remove();
                                        });
                                        document.getElementById("tbody2").innerHTML = "";
                                        document.getElementById("customerName").value = "";
                                        document.getElementById("cashNum").value = "";
                                        $('#credentialTable').modal('hide');
                                        // open receipt in new tab
                                        alert("Success placing order!");
                                        window.open("../pdf/receipt.php");
                                    }
                                    });
                                }
                                });
                            }
                            });
                        }
                        });
                    }
                },error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert("Status: " + textStatus); alert("Error: " + errorThrown);
                }
            });
        });

        $("#cancel").click(function(){
            $('#credentialTable').modal('hide');
        });

        // focus on textbox
        $("#rfid").on('shown.bs.modal', function(){
            $(this).find('input[type="text"]').val('');
            $(this).find('input[type="text"]').focus();
        });

        // show credential table
        $('#rfidInput').keyup(function(){
            if($(this).val().length == 10){
                userIdAndTotal = [];
                let rfid = rfidGlobal= $(this).val();
                $.ajax({
                    url: "ajax/topupRfid_getUserAttributes.php",
                    type: "POST",
                    data: {'data':rfid},
                    success: function(attributes){  
                        $(this).val('');
                        $('#rfid').modal('hide');
                        if(attributes == false){
                            alert("RFID Do not exist!");
                            return;
                        }
                        $('#rfid').modal('hide');
                        $('#credentialTable').modal('show');
                     
                        let arr = attributes.split(",") , i = 0; 
                        $("#tableInformation td").each(function() {
                            if(i == 6)
                                $(this).html('₱'+arr[i]);
                            else
                                $(this).html(arr[i]);
                            i++;
                        });
                        customer = arr[0];
                        cash = arr[6];
                        userIdAndTotal.push(arr[8]);
                        let src;
                        if(arr[7] != '')
                            src = '../profilePic/'+arr[7];
                        else
                            src = '../pic/unknown.png';
                        $("#profilePic").attr("src",src);
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { 
                        alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                    }     
                });
            }
        });
    });

    function createTable1(callIsFrom){
         //get cart attributes
         $.getJSON({
            url: "ajax/pos_getMenuAttribute.php",
            method: "post",
            success: function(multiArrCart){
                if(multiArrCart != "null"){
                    // [dish][price][orderType][stock] 
                    // update and refresh table body 2
                    let tbody1 = "", total = 0;
                    for(let i = 0; i < multiArrCart[0].length; i++){
                    total += parseFloat(multiArrCart[1][i]) * parseInt(multiArrCart[2][i]);

                    tbody1 +=
                    "<tr>" +
                        "<td class='dishes' >" + multiArrCart[0][i] + "</td>" +
                        "<td class='price' >" +'₱'+ multiArrCart[1][i] + "</td>" +
                        "<td class ='stocks'>" + multiArrCart[2][i] + "</td>" +
                        "<td><input type='number' placeholder='Quantity' name='qty' class='form-control' value='1' id='qty' >" + 
                        "<button type='button' name='addToCartSubmit' onclick='AddToCart(this)' value='"+multiArrCart[4][i]+"' class='btn btn-light col-12'  style='border:1px solid #cccccc;' >" + "<i class='bi bi-cart-plus'></i>" + "</button></td>" +
                    "</tr>";
                    }
                    $("#tbody1 tr").remove();
                    $("#tbody1").append(tbody1);
                    if(callIsFrom != 'clear'){
                        $('#tbl1').dataTable({
                        "columnDefs": [
                            { "targets": [3], "orderable": false }
                        ]});
                    }
                    subtractTb2OnTb1();
                    checkStock();
                }

            },error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert("Status: " + textStatus); alert("Error: " + errorThrown);
            }
        });
    }

    function refreshTable2(){
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
                        "<td> <button class='btn btn-success mx-1' type='button' name='addToCartSubmit' onclick='increaseQuantity(this)' value='"+multiArrCart[3][i]+"' class='btn btn-light col-12'> <i class='bi bi-plus'></i></button>" +
                        "       <button class='btn btn-danger' type='button' name='addToCartSubmit' onclick='decreaseQuantity(this)' value='"+multiArrCart[3][i]+"' class='btn btn-light col-12'> <i class='bi bi bi-dash'></i></button> </td>" +
                        "<td> <button type='button' name='addToCartSubmit' onclick='removeRow(this)' value='"+multiArrCart[3][i]+"' class='btn btn-light col-12' style='border:1px solid #cccccc;'> <i class='bi bi-cart-x-fill'></i></button> </td>" +
                        "<td class='price'>" +'₱'+ multiArrCart[1][i]*multiArrCart[2][i] + "</td>"
                    "</tr>";
                    }

                    tbody2 += 
                    "<tr>"+
                        "<td colspan='4'> <b>Total Amount:</b> </td>" +
                        "<td id='total'><b>₱"+total+"</b></td>"
                    "</tr>";
                    $("#tbody2 tr").remove();
                    $("#tbody2").append(tbody2);
                }
                else{
                    $("#tbody2 tr").find("#total").closest("tr").remove();
                }
            subtractTb2OnTb1();
            checkStock();

            },error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert("Status: " + textStatus); alert("Error: " + errorThrown);
            }
        });
    }

    function subtractTb2OnTb1(){
        $("#tbody2 tr .dishes").closest('tr').each (function() {
            //tb2    
            let tb2Tr = $(this);
            let dish = tb2Tr.find('.dishes').text();
            let quantity = parseInt(tb2Tr.find('.quantity').text());

            //tb1
            let tb1Tr = $("#tbody1 tr:contains('"+dish+"')");
            let stockTd = tb1Tr.find('.stocks');
            let stock = parseInt(stockTd.text());
            
            // original stock
            $.ajax({
                url: "ajax/pos_getStockOriginalValue.php",
                method: "post",
                data: {'data':dish},
                success: function(originalStock){
                    if((originalStock-quantity) <= 0){
                        stockTd.text("Out Of Stock");
                        tb1Tr.css("background-color", "#808080");    
                    }
                    if((originalStock-quantity) < 0){
                        tb2Tr.css("background-color", "#808080");    
                    }
                    else{
                        if(originalStock-quantity == 0){
                            stockTd.text("Out of Stock");
                        }
                        else{
                            stockTd.text(originalStock-quantity);
                        }
                        tb2Tr.css("background-color", "#FFFFFF");
                    }
                }
            });

        }); 
    }
    
    function checkStock(){
        // put out of stock and make background gray 
        $("#tbody1 tr .dishes").closest('tr').each (function() {
            let tb1Tr = $(this);
            let stockTd = tb1Tr.find('.stocks');
            let stock = parseInt(stockTd.text());
            if(stock <= 0){
                stockTd.text("Out Of Stock");
                tb1Tr.css("background-color", "#808080");    
            }
        }); 
    }

    createTable1();

    //call refreshTable to create table2 
    refreshTable2();

    // global arrays    [dishes][prices][quantity][order type]
    var multiArrCart =  [[],[],[],[]];

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

        // decrease the stock in tb1
        stock = stock - qty;
        
        if(stock<=0){
            $(button).closest("tr").find('.stocks').text("Out Of Stock");
            $(button).closest("tr").css("background-color", "#808080");
        }
        else{
            $(button).closest("tr").find('.stocks').text(stock);
        }

        // subtractTb2OnTb1();

        // add value in cart table in db
        $.ajax({
            url: "ajax/pos_addToCartTable.php",
            method: "post",
            data: {'data':JSON.stringify(arrayCart)},
            success: function(res){
                refreshTable2();
            }   
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
            url: "ajax/pos_clearCart.php",
            method: "post",
            data: {'data':JSON.stringify(<?php echo $_SESSION['user_id'];?>)},
            success: function(res){
                createTable1("clear");
                $("#tbody2 tr").each (function() {
                    this.remove();
                });
            }
        });
    });

    //order button (js)
    document.getElementById("orderBtn").addEventListener("click", () => {
        // staff, customer, cash, total
        otherAttributes = [];
        customer =  document.getElementById("customerName").value;
        cash = document.getElementById("cashNum").value;
        otherAttributes.push('<?php echo $_SESSION['name']; ?>');
        otherAttributes.push(customer);
        otherAttributes.push(cash);
        
        let cont = true;

        $("#tbody2 tr ").each (function() {
            let bg = $(this).find('.dishes').closest('tr').css('background-color');
            if(bg == 'rgb(128, 128, 128)'){
                cont = false;
            }
        });

        if(cont == false){
            alert('Please decrease some order in your cart!');
            return;
        }
       
        if(cash == ""){
            alert('Please Enter Amount');
            return;
        }
        if ($('#tbody2 tr').length == 0) {
            alert('Please place your order!');
            return;
        }

       
        let total = parseInt($("#tbody2 tr").find("#total").text().slice(1));

        if (cash < total) {
            alert("Amount Less than total!");
            return;
        }

        otherAttributes.push(total);

        //get cart attributes
        $.getJSON({
            url: "ajax/pos_getCartAttributes.php",
            method: "post",
            data: {'user_id':<?php echo $_SESSION['user_id'];?>},
            success: function(multiArrCart){
                if(multiArrCart != "null"){
                    // compute price
                    for(let i=0; i<multiArrCart[0].length; i++){
                        multiArrCart[1][i] = multiArrCart[1][i]*multiArrCart[2][i]; 
                    }
                    // insert the order
                    $.ajax({
                    url: "ajax/pos_insertOrder.php",
                    method: "post",
                    data: {'multiArrCart':JSON.stringify(multiArrCart),'otherAttributes':JSON.stringify(otherAttributes)},
                    success: function(){
                        // subtract cart qty to menu stock
                        $.ajax({
                        url: "ajax/pos_subtractCartOnMenuTb.php",
                        method: "post",
                        data: {'multiArrCart':JSON.stringify(multiArrCart)},
                        success: function(){
                            // clear the cart
                            $.ajax({
                            url: "ajax/pos_clearCart.php",
                            method: "post",
                            data: {'data':JSON.stringify(<?php echo $_SESSION['user_id'];?>)},
                            success: function(){
                                createTable1("clear");
                                $("#tbody2 tr").each (function() {
                                    this.remove();
                                });
                                document.getElementById("tbody2").innerHTML = "";
                                document.getElementById("customerName").value = "";
                                document.getElementById("cashNum").value = "";
                                // open receipt in new tab
                                alert("Success placing order!");
                                window.open("../pdf/receipt.php");
                            }
                            });
                        }
                        });
                    }
                    });
                }
            },error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert("Status: " + textStatus); alert("Error: " + errorThrown);
            }
        });

    });


    // increaseQuantity
    function increaseQuantity(button){
        // user_id, orderType, qty
        var arrayCart = [];
        arrayCart.push(<?php echo $_SESSION['user_id'];?>);
        arrayCart.push(button.value);
        arrayCart.push(1);
        let dish = $(button).closest("tr").find('[name="dish"]').text();

        // validation
        let stockTd = $("#tbody1 tr:contains('"+dish+"')").find('.stocks');
        if(isNaN(stockTd.text())){
            alert("Out of Stock!");
            return;
        }
        else{
            // add value in cart table in db
            $.ajax({
                url: "ajax/pos_addToCartTable.php",
                method: "post",
                data: {'data':JSON.stringify(arrayCart)}
            });

            // update tb1 stock
            let stock = parseInt(stockTd.text()) - 1;
            if(stock<=0){
                stockTd.text("Out Of Stocks");
                stockTd.closest("tr").css("background-color", "#808080");
            }
            else{
                stockTd.text(stock);
            }

            // update tb2 quantity
            let quantityTd = $("#tbody2 tr:contains('"+dish+"')").find('.quantity');
            let quantity = parseInt(quantityTd.text()) + 1;
            quantityTd.text(quantity);
   
            // update tb2 price
            let priceTd1 = $("#tbody1 tr:contains('"+dish+"')").find('.price');
            let priceTd2 = $("#tbody2 tr:contains('"+dish+"')").find('.price');
            let price = parseInt(priceTd1.text().slice(1));
            let price2 = parseInt(priceTd2.text().slice(1));
            let p = price2+price;
            priceTd2.text("₱"+p);
        }
        computeTotal();
        // subtractTb2OnTb1();
       
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
            data: {'data':JSON.stringify(arrayCart)},
        });

        // update tb1 stock
        let stockTd = $("#tbody1 tr:contains('"+dish+"')").find('.stocks');
        if(isNaN(stockTd.text())){
            stockTd.text("1");
            stockTd.closest("tr").css("background-color", "#FFFFFF");
        }
        else{
            let stock = parseInt(stockTd.text()) + 1;
            stockTd.text(stock);
        }

        // update tb2 quantity
        let quantityTd = $("#tbody2 tr:contains('"+dish+"')").find('.quantity');
        let quantity = parseInt(quantityTd.text()) - 1;
        if(quantity <= 0){
            quantityTd.closest('tr').remove();
        }
        else{
            quantityTd.text(quantity);
        }

        // update tb2 price
        let priceTd1 = $("#tbody1 tr:contains('"+dish+"')").find('.price');
        let priceTd2 = $("#tbody2 tr:contains('"+dish+"')").find('.price');
        let price = parseInt(priceTd1.text().slice(1));
        let price2 = parseInt(priceTd2.text().slice(1));
        let p = price2-price;
        priceTd2.text("₱"+p);
        subtractTb2OnTb1();
        computeTotal();
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
        totalTr.text("₱"+total);
        if(total <= 0){
            $("#tbody2 tr").find("#total").closest("tr").remove();
        }
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
                        let this0 = this;
                        if(isNaN(stock)){

                            $.ajax({
                                url: "ajax/pos_getStockOriginalValue.php",
                                method: "post",
                                data: {'data':dish},
                                success: function(originalStock){
                                    $(this0).closest("tr").find(".stocks").text(originalStock);
                                }
                            });

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
                computeTotal();
            }
        });
        
    }
    
</script>

<?php 
    // logout button
    if(isset($_POST['logout'])){
        session_destroy();
        echo "<script>window.location.replace('../general/login.php');</script>";
    }
?>
<style>
   .modal-content-scanner{
        width: 800px;
        height: 500px;
        position: absolute;
        align-items: center;
        background-color: rgba(0, 0, 0, 0.9);
        color: #fff;
        font-family: Sans-Serif;
        font-size: 30px;  
        top: 120px;           
    }

    .ocrloader { 
        position: relative;
        width: 300px;
        height: 300px;
        background: url(rfid01.png);
        background-size: 300px;    
    }
    .ocrloader:before {
        content:'';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%; 
        background: url(rfid02.png);
        background-size: 300px;
        filter: drop-shadow(0 0 3px #00FFFF) drop-shadow(0 0 7px #00FFFF);
        overflow: hidden;
        animation: animate 2s linear infinite;
    }
    @keyframes animate
    {
        0%, 50%, 100%
        {
            height: 0%;
        }
        50%
        {
            height: 70%;
        }
        75%
        {
            height: 100%;
        }
    }
    .ocrloader span {
        content:'';
        position: absolute;
        inset: 1px;
        width: calc(100% - 2px);
        height: 3px;
        background-color: #fff;
        animation: animateLine 2s linear infinite;
    }
    @keyframes animateLine{
        0%
        {
            top: 1px;
        }
        50%
        {
            top: 225px;
        }
        75%
        {
            top: 300px;
        }
    }
    *{margin: 0; padding: 0;}
    .loading span {
        position: relative;
        left: 220px;
        top: 35px;       
        width: 10px;
        height: 10px;       
        background-color: #fff;
        border-radius: 50%;
        display: inline-block;
        animation-name: dots;
        animation-duration: 2s;
        animation-iteration-count: infinite;
        animation-timing-function: ease-in-out;
        filter: drop-shadow(0 0 10px #fff) drop-shadow(0 0 20px #fff);
    }

    .loading span:nth-child(2){
        animation-delay: 0.4s;
    }
    .loading span:nth-child(3){
        animation-delay: 0.8s;
    }

    @keyframes dots{
        50%{
            opacity: 0;
            transform: scale(0.7) translateY(10px);
        }
    }
    .ocrloader > div {
        z-index: 1;
        position: absolute;
        left: 62%;
        top: 120%;
        transform: translate(-50%, -50%);
        width: 100%;
        backface-visibility: hidden;
        filter: drop-shadow(0 0 20px #fff) drop-shadow(0 0 40px #fff);
    }
    .ocrloader em:after,
    .ocrloader em:before {
        border-color: #fff;
        content: "";
        position: absolute;
        width: 19px;
        height: 16px;
        border-style: solid;
        border-width: 0px;
    }
    .ocrloader:before {
        left: 0;
        top: 0;
        border-left-width: 1px;
        border-top-width: 1px;
    }
    .ocrloader:after {
        right: 0;
        top: 0;
        border-right-width: 1px;
        border-top-width: 1px;
    }
    .ocrloader em:before {
        left: 0;
        bottom: 0;
        border-left-width: 1px;
        border-bottom-width: 1px;
    }
    .ocrloader em:after {
        right: 0;
        bottom: 0;
        border-right-width: 1px;
        border-bottom-width: 1px;
    }
    
    #rfidInput{
        opacity: 0;
    }
</style>