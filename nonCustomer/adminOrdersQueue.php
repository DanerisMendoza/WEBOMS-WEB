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
    <title>Orders Queue</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/admin2.css">
    <link rel="stylesheet" href="../css/admin-orders-queue.css">
    <link rel="icon" href="../image/weboms.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
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
                <li><a href="adminOrdersQueue.php" class="active text-danger"><i class="bi-clock me-2"></i>ORDERS QUEUE</a></li>
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
                <div class="com-sm-12">
                    <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-bordered">
                                <thead class="bg-danger text-white">
                                    <tr>
                                        <th>PREPARING</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody1">

                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-6">
                            <table class="table table-bordered">
                                <thead class="bg-success text-white">
                                    <tr>
                                        <th>SERVING</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody2">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

<script>
    // sidebar toggler
    $(document).ready(function() {
        $('#sidebarCollapse').on('click', function() {
            $('#sidebar').toggleClass('active');
        });
    });


    function updateTbody(){
        //preparing
        $.getJSON({
            url: "ajax/ordersQueue_getPreparing.php",
            method: "post",
            success: function(result){
                $('#tbody1 tr').remove();
                if(result!=null){
                    let data = "";
                    result.forEach(function(element) {
                        data += "<tr>";
                        data +=     "<td><strong style='font-size: 35px;'>"+element+"</strong></td>";
                        data += "</tr>";
                    });
                    $('#tbody1').append(data);
                }
            }
        });
        //serving
        $.getJSON({
                url: "ajax/ordersQueue_getServing.php",
                method: "post",
                success: function(result){
                    $('#tbody2 tr').remove();
                    if(result!=null){
                        let data = "";
                        result.forEach(function(element) {
                            data += "<tr>";
                            data +=     "<td><strong style='font-size: 35px;'>"+element+"</strong></td>";
                            data += "</tr>";
                        });
                        $('#tbody2').append(data);
                    }
                },
                complete: function(){
                    setTimeout(updateTbody, 2000);
                }
        });
    }
    updateTbody();
</script>

<?php 
    if(isset($_POST['logout'])){
        session_destroy();
        echo "<script>window.location.replace('../index.php');</script>";
    }
?>