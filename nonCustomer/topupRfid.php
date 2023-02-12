<?php 
    $page = 'admin';
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
                <div class="container-fluid bg-transparent">
                    <button type="button" id="sidebarCollapse" class="btn" style="font-size:20px;"><i class="bi bi-list"></i> TOGGLE</button>
                </div>
            </nav>
        <!-- content here -->
            <select id="amount" class="form-control form-control-lg col-12 mb-4">
                <option value="100">₱100.00</option>
                <option value="300">₱300.00</option>
                <option value="500">₱500.00</option>
                <option value="1000">₱1000.00</option>
                <option value="3000">₱3000.00</option>
                <option value="5000">₱5000.00</option>
            </select>
           
            <div class="container-fluid text-center">
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

                </div>
            </div>
            <div class="btn-group container-fluid" role="group" aria-label="Basic mixed styles example">
                <button class="btn btn-lg btn-primary col-6 mb-3" id="scanRfid"><i class="bi bi-credit-card"></i> RFID Scan</button>
                <button class="btn btn-lg btn-success col-6 mb-3" id="topupButton"><i class="bi bi-cash-stack"></i> Top-Up</button>
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

    $(document).ready(function(){
        // modal trigger
        $("#scanRfid").click(function(){
            $('#rfid').modal('show');
        });
        $("#topupButton").click(function(){
            let arr = [];
            let amount = parseInt($('#amount').find(":selected").text().substr(1));
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

</script>

<?php 
    // logout
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
        echo "<script>window.location.replace('../index.php');</script>";
    }
?>

<style>
    .ocrloader {
        width: 94px;
        height: 77px;
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        backface-visibility: hidden;
    }
    .ocrloader span {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 20px;
        background-color: rgba(45, 183, 183, 0.54);
        z-index: 1;
        transform: translateY(135%);
        animation: move 0.7s cubic-bezier(0.15, 0.44, 0.76, 0.64);
        animation-iteration-count: infinite;
    }
    .ocrloader > div {
        z-index: 1;
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        width: 48%;
        backface-visibility: hidden;
    }
    .ocrloader:before,
    .ocrloader:after,
    .ocrloader em:after,
    .ocrloader em:before {
        border-color: #000;
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
    @keyframes move {
    0%,
    100% {
        transform: translateY(135%);
    }
    50% {
        transform: translateY(0%);
    }
    75% {
        transform: translateY(272%);
    }
    }

    #rfidInput{
        opacity: 0;
    }
</style>