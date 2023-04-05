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
    <title>Orders</title>

    <link rel="stylesheet" href="../css/bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <!-- modal script  -->
    <script type="text/javascript" src="../js/jquery-3.6.1.min.js"></script>  
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <!-- data table -->
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
                <li class="mb-2">
                    <a href="adminPos.php"><i class="bi bi-tag me-2"></i>Point of Sales</a>
                </li>
                <li class="mb-2 active">
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
                    <button type="button" id="sidebarCollapse" class="btn" style="font-size:20px;"><i class="bi bi-list"></i> Toggle</button>
                </div>
            </nav>

            <!-- content here -->
            <div class="container-fluid text-center">
                <div class="row g-3 justify-content-center">
            
                        <!-- select sort -->
                        <select id="status" class="form-control form-control-lg col-12 mb-3" >
                            <option value="all">all</option>
                            <option value="prepairing">preparing</option>
                            <option value="serving">serving</option>
                            <option value="order complete">complete</option>
                            <option value="void">void</option>
                        </select>
                        
                        <!-- table container -->
                        <div class="table-responsive col-lg-12">
                            <table class="table table-bordered table-hover col-lg-12" id="tbl1">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">NO.</th>
                                        <th scope="col">CUSTOMER<br>NAME</th>
                                        <th scope="col">ORDER#</th>
                                        <th scope="col">ORDER<br>STATUS</th>
                                        <th scope="col">DATE & TIME<br>(MM/DD/YYYY)</th>
                                        <th scope="col">STAFF<br>(IN-CHARGE)</th>
                                        <th scope="col">ORDER<br>DETAILS</th>
                                        <th scope="col">CUSTOMER<br>INFO</th>
                                        <th scope="col">OPTIONS</th>
                                        <th></th>
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

                    <!-- customerProfileModal (Bootstrap MODAL) -->
                    <div class="modal fade" id="customerProfileModal" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content container">
                                <div class="modal-body">
                                    <!-- table -->
                                    <div class="table-responsive col-lg-12">
                                        <table class="table table-bordered table-hover col-lg-12 text-start">
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
            data += "<tr align='center'><th><img src='../profilePic/"+res[2]+"' style='width:200px;height:200px;border:1px solid black;'> </th></tr>";
          }
            data+= "<tr align='center'><td>Name: "+res[1]+"</td></tr>";
            data+= "<tr align='center'><td>Username: "+res[3]+"</td></tr>";
            data+= "<tr align='center'><td>Gender: "+res[4]+"</td></tr>";
            data+= "<tr align='center'><td>Phone: "+res[5]+"</td></tr>";
            data+= "<tr align='center'><td>Address: "+res[6]+"</td></tr>";
            data+= "<tr align='center'><td>Balance: ₱"+res[7]+"</td></tr>";
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
<script>
   
</script>