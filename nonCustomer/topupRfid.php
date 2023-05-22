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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/admin2.css">
    <link rel="stylesheet" href="../css/admin-topup.css">
    <link rel="stylesheet" href="../css/rfid2.css">
    <link rel="icon" href="../image/weboms.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body> 

<div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header">
                <a href="admin.php" class="account-type"><?php echo strtoupper($_SESSION['accountType']); ?></a>
            </div>
            <hr>
            <ul class="list-unstyled components">
                <li><a href="adminPos.php"><i class="bi-tag me-2"></i>POINT OF SALES</a></li>
                <li><a href="adminOrders.php"><i class="bi-cart me-2"></i>ORDERS</a></li>
                <li><a href="adminOrdersQueue.php"><i class="bi-clock me-2"></i>ORDERS QUEUE</a></li>
                <li><a href="topupRfid.php" class="active text-danger"><i class="bi-credit-card me-2"></i>TOP-UP RFID</a></li>

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
                <div class="input-group mb-2" id="input1">
                    <label for="" class="form-control text-success">₱<span class="amountSpan">0.00</span></label>
                    <button class="btn btn-clear btn-lg btn-danger" onclick="clearVal()">CLEAR</button>
                </div>
                <div class="input-group mb-5">
                    <button class="btn btn-light" onclick="addVal(5)">5</button>
                    <button class="btn btn-light" onclick="addVal(10)">10</button>
                    <button class="btn btn-light" onclick="addVal(20)">20</button>
                    <button class="btn btn-light" onclick="addVal(30)">30</button>
                    <button class="btn btn-light" onclick="addVal(40)">40</button>
                    <button class="btn btn-light" onclick="addVal(50)">50</button>
                    <button class="btn btn-light" onclick="addVal(100)">100</button>
                    <button class="btn btn-light" onclick="addVal(300)">300</button>
                    <button class="btn btn-light" onclick="addVal(500)">500</button>
                    <button class="btn btn-light" onclick="addVal(1000)">1000</button>
                    <button class="btn btn-light" onclick="addVal(3000)">3000</button>
                    <button class="btn btn-light" onclick="addVal(5000)">5000</button>
                </div>
                
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-2">
                            <table class="table table-bordered" id="tableProfile">
                                <thead>
                                    <center><img src="../pic/unknown.png" alt="" id="profilePic"></center>
                                </thead>
                            </table>
                        </div>
                        <div class="col-sm-10">
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
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="bg-success fw-bold text-white"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="input-group">
                    <button class="btn btn-lg btn-primary" id="scanRfid">RFID SCAN</button>
                    <button class="btn btn-lg btn-success" id="topupButton">TOP-UP</button>
                </div>

                <!-- scan rfid (modal) -->
                <div class="modal fade" role="dialog" id="rfid">
                    <div class="modal-dialog">
                        <div class="modal-content">
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
    // global variable init
    var rfidGlobal;

    addVal = (amount) =>{
        let currentVal = parseInt($(".amountSpan").text());
        $(".amountSpan").text(currentVal + amount);
    }

    clearVal = () => {$(".amountSpan").text("0")};

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
            url: "ajax/topupRfid_updateBalanceAndAddRequest.php",
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