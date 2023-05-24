<?php 
    $page = 'cashier';
    include('../method/checkIfAccountLoggedIn.php');
    include('../method/query.php');
    $_SESSION['from'] = 'adminOrderList';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ORDERS</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../css/admin.css">
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
                <li><a href="adminPos.php"><i class="bi-tag me-2"></i>POINT OF SALES</a></li>
                <li><a href="adminOrders.php" class="active text-danger"><i class="bi-cart me-2"></i>ORDERS</a></li>
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
                <select name="" id="status" class="form-control mb-4">
                    <option value="all">all</option>
                    <option value="preparing">preparing</option>
                    <option value="serving">serving</option>
                    <option value="order complete">order</option>
                    <option value="void">void</option>
                </select>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="tbl1">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>CUSTOMER NAME</th>
                                <th>ORDER NO.</th>
                                <th>ORDER STATUS</th>
                                <th>DATE-TIME (MM/DD/YYYY)</th>
                                <th>STAFF (IN-CHARGE)</th>
                                <th>ORDER DETAILS</th>
                                <th>CUSTOMER INFO</th>
                                <th>ACTION</th>
                                <th>VOID</th>
                            </tr>
                        </thead>
                        <tbody id="tbody1">
                            <script>
                                let status = $('#status').find(":selected").text();
                                var latestId;
                                //get id of latest order then after 2 seconds get the id of latest order again then compare
                                $.getJSON({
                                    url: "ajax/orders_getNewestOrder.php",
                                    method: "post",
                                    data: {'status':JSON.stringify(status)},
                                    success: function(res){
                                        if(res == "null"){
                                            latestId = 0;
                                        }
                                        else{
                                            latestId = res;
                                        }
                                    }
                                });
                                function checkIfDbChange(){
                                    $.getJSON({
                                        url: "ajax/orders_getNewestOrder.php",
                                        method: "post",
                                        data: {'status':JSON.stringify(status)},
                                        success: function(res){
                                            let result = parseInt(res) > parseInt(latestId);
                                            if(result){
                                                updateTb();
                                                latestId = res;
                                            }
                                        },
                                        complete: function(){
                                            setTimeout(checkIfDbChange, 2000);
                                        }
                                    });
                                }
                                checkIfDbChange();
                                // add data in tbl1
                                $.ajax({
                                    url: "ajax/orders_getOrders.php",
                                    method: "post",
                                    data: {'status':JSON.stringify(status)},
                                    success: function(res){
                                        $('#tbody1').append(res);
                                        $('#tbl1').dataTable({
                                        "columnDefs": [
                                            { "targets": [6,7,8,9], "orderable": false }
                                        ]
                                        });
                                    }
                                });

                                $('#status').on('change', function () {
                                    updateTb();
                                });

                                function updateTb(){
                                    let status = $('#status').find(":selected").text();
                                    $.ajax({
                                        url: "ajax/orders_getOrders.php",
                                        method: "post",
                                        data: {'status':JSON.stringify(status)},
                                        success: function(res){
                                            $('#tbl1').DataTable().clear().destroy();
                                            $('#tbody1').append(res);
                                            $('#tbl1').dataTable({
                                            "columnDefs": [
                                                { "targets": [6,7,8,9], 
                                                    "orderable": false }
                                            ]
                                            });
                                        }
                                    });
                                }
                            </script>
                        </tbody>
                    </table>
                </div>

                <!-- customer info (modal) -->
                <div class="modal fade" role="dialog" id="customerProfileModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <tbody>
                                            
                                        </tbody>
                                    </table>
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
    // sidebar (js)
    $(document).ready(function() {
        $('#sidebarCollapse').on('click', function() {
            $('#sidebar').toggleClass('active');
        });
    });

    function profileModal(user_id){
        $.getJSON({
            url: "ajax/orders_getCustomerInfo.php",
            method: "post",
            data: {'user_id':JSON.stringify(user_id)},
            success: function(res){
                // id, name, picName, username, phone number, address, balance, email, gender
                let data ="";
                if(res[2] != null){
                    data += "<tr><th colspan=2><center><img src='../profilePic/"+res[2]+"' class='profile-img'></center></th></tr>";
                }
                    data+= "<tr><td>Name: </td><td>"+res[1]+"</td></tr>";
                    data+= "<tr><td>Username: </td><td>"+res[3]+"</td></tr>";
                    data+= "<tr><td>Gender: </td><td>"+res[4]+"</td></tr>";
                    data+= "<tr><td>Phone: </td><td>"+res[5]+"</td></tr>";
                    data+= "<tr><td>Address: </td><td>"+res[6]+"</td></tr>";
                    data+= "<tr class='table-success'><td><b>Balance: <b></td><td><b>₱"+res[7]+"<b></td></tr>";
                $('#customerProfileModal').find('.modal-body .table tbody tr').remove();
                $('#customerProfileModal').find('.modal-body .table tbody').append(data);
                $('#customerProfileModal').modal('show');
            }
        });
    }

    function serve(order_id){
        $.post({
            url: "ajax/orders_serve.php",
            method: "post",
            data: {'order_id':JSON.stringify(order_id)},
            success: function(res){
                updateTb();
            }
        });
    }

    function complete(order_id){
        $.post({
            url: "ajax/orders_complete.php",
            method: "post",
            data: {'order_id':JSON.stringify(order_id)},
            success: function(res){
                updateTb();
            }
        });
    }

    function voidOrder(order_id, user_id, totalOrder){
        $.post({
            url: "ajax/orders_void.php",
            method: "post",
            data: {'order_id':JSON.stringify(order_id), 'user_id':JSON.stringify(user_id), 'totalOrder':JSON.stringify(totalOrder)},
            success: function(res){
                updateTb();
            }
        });
    }
</script>

<?php 
    if(isset($_POST['logout'])){
        session_destroy();
        echo "<script>window.location.replace('../general/login.php');</script>";
    }
?>