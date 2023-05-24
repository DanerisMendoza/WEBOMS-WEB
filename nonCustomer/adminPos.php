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
    // company variables init
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
    <title>POINT OF SALES</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/rfid.css">
    <link rel="icon" href="../image/weboms.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
</head>
<body>

    <div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header">
                <a href="admin.php" class="account-type"><?php echo strtoupper($_SESSION['accountType']); ?></a>
            </div>
            <hr>
            <ul class="list-unstyled components">
                <li><a href="adminPos.php" class="active text-danger"><i class="bi-tag me-2"></i>POINT OF SALES</a></li>
                <li><a href="adminOrders.php"><i class="bi-cart me-2"></i>ORDERS</a></li>
                <li><a href="adminOrdersQueue.php"><i class="bi-clock me-2"></i>ORDERS QUEUE</a></li>
                <li><a href="topupRfid.php"><i class="bi-credit-card me-2"></i>TOP-UP RFID</a></li>

                <?php if($_SESSION['accountType'] != 'cashier'){?>
                <li><a href="adminTopUp.php"><i class="bi-wallet me-2"></i>TOP-UP</a></li>
                <li><a href="adminInventory.php"><i class="bi-box me-2"></i>INVENTORY</a></li>
                <li><a href="adminSalesReport.php"><i class="bi-bar-chart me-2"></i>SALES REPORT</a></li>
                <li><a href="adminFeedbackList.php"><i class="bi-chat-square-text me-2"></i>CUSTOMER FEEDBACK</a></li>
                <li><a href="accountManagement.php"><i class="bi-person me-2"></i>ACCOUNT MANAGEMENT</a></li>
                <li><a href="settings.php"><i class="bi-gear me-2"></i>SETTINGS</a></li>
                <?php } ?>
            </ul>
        </nav>

        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-toggle">
                        <i class="bi-list"></i>
                    </button>
                    <button class="btn btn-toggle d-inline-block d-lg-none ml-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="bi-list text-danger"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ms-auto">
                            <li>
                                <form method="post">
                                    <button class="btn text-danger" id="Logout" name="logout">LOGOUT</button>
                                </form>
                            </li>   
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="container-fluid mt-3">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered table-striped" id="tbl1">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>DISH</th>
                                            <th>PRICE</th>
                                            <th>STOCK</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody1">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-sm-5 column-right">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>DISH</th>
                                            <th>QUANTITY</th>
                                            <th colspan="2">ACTION</th>
                                            <th>PRICE</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody2">

                                    </tbody>
                                </table>
                            </div>
                            <input type="text" class="form-control" placeholder="CUSTOMER NAME (OPTIONAL)" id="customerName">
                            <input type="number" class="form-control mb-3" placeholder="₱0.00" id="cashNum" name="cash" step=any required>
                            <button type="submit" class="btn btn-secondary w-100 mb-1" id="payThruRfid" name="orderBtn">RFID PAYMENT</button>
                            <button type="submit" class="btn btn-success w-100 mb-1" id="orderBtn" name="orderBtn">PLACE ORDER</button>
                            <button type="submit" class="btn btn-danger w-100 mb-1" id="clear" name="clear">CLEAR ORDER</button>
                        </div>
                    </div>
                </div>

                <!-- rfid scanner (modal) -->
                <div class="modal fade" role="dialog" id="rfid">
                    <div class="modal-dialog">
                        <div class="modal-content modal-content-scanner">
                            <div class="modal-body">
                            <input type="text" id="rfidInput">                            
                                <div class="ocrloader">
                                    <em></em>
                                    <div>Scanning RFID</div>                                                               
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

                <!-- credential (modal) -->
                <div class="modal fade" role="dialog" id="credentialTable">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="tableProfile">
                                        <center><img src="../pic/unknown.png" class="profile-img" alt="" id="profilePic"></center>
                                    </table>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="tableInformation">
                                        <thead class="bg-dark text-white">
                                            <tr>
                                                <th>NAME</th>
                                                <th>USERNAME</th>
                                                <th>EMAIL</th>
                                                <th>GENDER</th>
                                                <th>PHONE NO.</th>
                                                <th>ADDRESS</th>
                                                <th>BALANCE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="bg-light">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-success fw-bold"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="input-group">
                                    <button type="button" id="cancel" class="btn btn-danger w-50">CANCEL</button>
                                    <button type="button" id="confirm" class="btn btn-success w-50">CONFIRM</button>
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
                        "<button type='button' name='addToCartSubmit' onclick='AddToCart(this)' value='"+multiArrCart[4][i]+"' class='btn btn-dark w-100'>" + "ADD TO CART" + "</button></td>" +
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
                        "<td class ='quantity' name='quantity'>" + multiArrCart[2][i] + "</td>" +
                        "<td> <button class='btn btn-success' type='button' name='addToCartSubmit' onclick='increaseQuantity(this)' value='"+multiArrCart[3][i]+"'><i class='bi-plus-lg'></i></button>" +
                        "       <button class='btn btn-danger' type='button' name='addToCartSubmit' onclick='decreaseQuantity(this)' value='"+multiArrCart[3][i]+"'><i class='bi-dash-lg'></i></button></td>" +
                        "<td><button type='button' name='addToCartSubmit' onclick='removeRow(this)' value='"+multiArrCart[3][i]+"' class='btn btn-light'> <i class='bi bi-cart-x-fill'></i></button> </td>" +
                        "<td class='price'>" +'₱'+ multiArrCart[1][i]*multiArrCart[2][i] + "</td>"
                    "</tr>";
                    }

                    tbody2 += 
                    "<tr>"+
                        "<td colspan='2'></td>"+
                        "<td colspan='2'><b>TOTAL AMOUNT:</b></td>" +
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