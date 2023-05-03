<?php 
    $page = 'cashier';
    include('../method/checkIfAccountLoggedIn.php');
    include('../method/query.php');
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topup RFID</title>

    <link rel="stylesheet" href="../css/bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/topup.css">
    <script type="text/javascript" src="../js/bootstrap 5/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/jquery-3.6.1.min.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <!-- charts -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>


    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300&display=swap');

        .h1Admin {
            font-family: 'Cormorant Garamond', serif;
            font-size: 13vw;
            font-weight: normal;
        }
        .chart {
            width: 100%; 
            min-height: 450px;
        }
        .row {
            margin:0 !important;
        }
    </style>
</head>

<body> 
    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar" class="bg-dark">
            <div class="sidebar-header bg-dark">
                <h3 class="mt-3"><a href="admin.php"><?php echo ucwords($_SESSION['accountType']); ?></a></h3>
            </div>
            <ul class="list-unstyled components ms-3">
                <li class="mb-2">
                    <a href="adminPos.php"><i class="bi bi-tag me-2"></i>Point of Sales</a>
                </li>
                <li class="mb-2">
                    <a href="adminOrders.php"><i class="bi bi-minecart me-2"></i>Orders</a>
                </li>
                <li class="mb-2">
                    <a href="adminOrdersQueue.php"><i class="bi bi-clock me-2"></i>Orders Queue</a>
                </li>
                <li class="mb-2 active">
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
                <div class="container-fluid bg-transparent">
                    <button type="button" id="sidebarCollapse" class="btn" style="font-size:20px;"><i class="bi bi-list"></i> TOGGLE</button>
                </div>
            </nav>
        <!-- content here -->

            <div class="row ">
                <h1 class="col-10 border border-dark text-success">₱<span class="amountSpan">0</span></h1>
                <button class="col-2 border border-dark bg-danger text-white mb-2 btnClear" onclick="clearVal()">CLEAR</button>
                <button class="col-2 btn btn-secondary btnNum me-1 mb-1" onclick="addVal(5)">5</button>
                <button class="col-2 btn btn-secondary btnNum me-1 mb-1" onclick="addVal(10)">10</button>
                <button class="col-2 btn btn-secondary btnNum me-1 mb-1" onclick="addVal(20)">20</button>
                <button class="col-2 btn btn-secondary btnNum me-1 mb-1" onclick="addVal(30)">30</button>
                <button class="col-2 btn btn-secondary btnNum me-1 mb-1" onclick="addVal(40)">40</button>
                <button class="col-2 btn btn-secondary btnNum me-1 mb-1" onclick="addVal(50)">50</button>
                <button class="col-2 btn btn-secondary btnNum me-1 mb-1" onclick="addVal(100)">100</button>
                <button class="col-2 btn btn-secondary btnNum me-1 mb-1" onclick="addVal(300)">300</button>
                <button class="col-2 btn btn-secondary btnNum me-1 mb-1" onclick="addVal(500)">500</button>
                <button class="col-2 btn btn-secondary btnNum me-1 mb-1" onclick="addVal(1000)">1000</button>
                <button class="col-2 btn btn-secondary btnNum me-1 mb-1" onclick="addVal(3000)">3000</button>
                <button class="col-2 btn btn-secondary btnNum me-1 mb-1" onclick="addVal(5000)">5000</button>
            </div>
           
            <div class="container-fluid text-center mt-2">
                <div class="row justify-content-center">

            <div class="table-responsive col-lg-3">
                <table class="table table-bordered table-hover col-lg-12" id="tableProfile">
                    <tbody>
                        <img id="profilePic" src="../pic/unknown.png" style="width:200px;height:200px;border-radius:10rem;" class="mb-3"> 
                    </tbody>
                </table>
            </div>

            <div class="table-responsive col-lg-9">
                <table class="table table-bordered table-hover col-lg-12" id="tableInformation">
                    <thead class="table-dark text-white">
                        <th>NAME</th>
                        <th>USERNAME</th>
                        <th>EMAIL</th>
                        <th>GENDER</th>
                        <th>PHONE NO.</th>
                        <th>ADDRESS</th>
                        <th>BALANCE</th>
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

                </div>
            </div>
            <div class="btn-group container-fluid" role="group" aria-label="Basic mixed styles example">
                <button class="btn btn-lg btn-primary col-6 mb-3" id="scanRfid"><i class="bi bi-vr"></i> RFID SCAN</button>
                <button class="btn btn-lg btn-success col-6 mb-3" id="topupButton"><i class="bi bi-cash"></i> TOP-UP</button>
            </div>
        </div>

        <!-- RFID SCANNER (modal)-->
        <div class="modal fade" role="dialog" id="rfid">
            <div class="modal-dialog">
                <div class="modal-content">
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

    </div>
</body>
</html>

<script>
    // global variable init
    var rfidGlobal;

    addVal = (amount) =>{
        let currentVal = parseInt($(".amountSpan").text());
        $(".amountSpan").text(currentVal + amount);
    }

    clearVal = () => {$(".amountSpan").text("0")};

    //amount dropdown change
    $(document).ready(function() {
        $('#amount').on('change', function() {
            let amount = $(this).find(":selected").text();
            $(".amountSpan").text(amount.substr(1));
        });
    });

    $(document).ready(function(){
        // modal trigger
        $("#scanRfid").click(function(){
            $('#rfid').modal('show');
        });
        $("#topupButton").click(function(){
            if(rfidGlobal == null){
                alert("Please Scan your Rfid Card");
                return;
            }
            let arr = [];
            let amount = parseInt(parseInt($(".amountSpan").text()));
            arr.push(amount);
            arr.push(rfidGlobal);
            $.ajax({
            url: "ajax/topupRfid_updateBalance.php",
            type: "POST",
            data: {'data':JSON.stringify(arr)},
            success: function(attributes){  
                let arr = attributes.split(",") , i = 0; 
                $("#tableInformation td").each(function() {
                    if(i == 6)
                        $(this).html('₱'+arr[i]);
                    else
                        $(this).html(arr[i]);
                    i++;
                });
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                alert("Status: " + textStatus); alert("Error: " + errorThrown); 
            }     
            });
        });
    });

    // focus on textbox
    $("#rfid").on('shown.bs.modal', function(){
        $(this).find('input[type="text"]').val('');
        $(this).find('input[type="text"]').focus();
    });
    
    // trigger this block when card is scanned
    var move = true;
    $('#rfidInput').keyup(function(){
    if($(this).val().length == 10){
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
                let arr = attributes.split(",") , i = 0; 
                $("#tableInformation td").each(function() {
                    if(i == 6)
                        $(this).html('₱'+arr[i]);
                    else
                        $(this).html(arr[i]);
                    i++;
                });
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
    // sidebar toggler
    $(document).ready(function() {
        $('#sidebarCollapse').on('click', function() {
            $('#sidebar').toggleClass('active');
        });
    });

</script>

<?php 
    // logout
    if(isset($_POST['logout'])){
        session_destroy();
        echo "<script>window.location.replace('../index.php');</script>";
    }
?>

<style>
   .modal-content{
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

