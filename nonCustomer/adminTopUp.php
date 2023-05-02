<?php
  $page = 'admin';
  include('../method/checkIfAccountLoggedIn.php');
  include_once('../method/query.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Up</title>

    <link rel="stylesheet" href="../css/bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <script type="text/javascript" src="../js/bootstrap 5/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/jquery-3.6.1.min.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
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
                <li class="mb-2 active">
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
                <div class="row justify-content-center">
                    <div class="table-responsive col-lg-12">
                        <table class="table table-bordered table-hover col-lg-12" id="tb1">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">NAME</th>
                                    <th scope="col">AMOUNT</th>
                                    <th scope="col">STATUS</th>
                                    <th scope="col">DATE & TIME (MM/DD/YYYY)</th>
                                    <th scope="col">PAYMENT</th>
                                    <th scope="col">ACTION</th>
                                </tr>
                            </thead>
                            <tbody id="tbody1">
                              
                            </tbody>
                        </table>
                    </div>

                    <!-- pic (Bootstrap MODAL) -->
                    <div class="modal fade" id="viewPic" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content container">
                                <div class="modal-body">
                                    <img id="imgModal" style=width:300px;height:550px>
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
<?php 
    if(isset($_POST['logout'])){
        session_destroy();
        echo "<script>window.location.replace('../general/login.php');</script>";
    }
?>
<script>
    //get latestId
    var latestId;
    $.getJSON({
    url: "ajax/topup_getNewestTopup.php",
    method: "post",
    success: function(res){
        if(res == null){
            latestId = 0;
        }
        else{
            latestId = res;
        }
    }
    });
    
    function checkIfDbChange(){
        $.getJSON({
            url: "ajax/topup_getNewestTopup.php",
            method: "post",
            success: function(res){
                let result = parseInt(res) > parseInt(latestId);
                if(result){
                    UpdateTable();
                    latestId = res;
                }
              
            },
            complete: function(){
                setTimeout(checkIfDbChange, 2000);
            }
        });
    }
    checkIfDbChange();

    function UpdateTable() {
    $.getJSON({
        url: 'ajax/topup_getTopup.php',
        method: 'POST',
        success: function(result) {
        if (result != null) {
            let data = "";
            for (let i = 0; i < result['name'].length; i++) {
            data += "<tr>";
            data += "<td>" + result['name'][i] + "</td>";
            data += "<td>" + '₱' + result['amount'][i] + "</td>";
            data += "<td>" + result['status'][i] + "</td>";
            data += "<td>" + result['date'][i] + "</td>";
            data += "<td> <button type='button' class='btn btn-light' style='border:1px solid #cccccc;' onclick=viewPicture('"+result['proofOfPayment'][i]+"') > View <i class='bi bi-list'></i> </button></td>";
            if (result['status'][i] == 'Pending') {
                data += "<td>";
                data += "<div style='display:flex; justify-content:space-between'>";
                data += "<button type='button' class='btn btn-success' onclick=approvePayment('" + result['id'][i]+"','"+result['user_id'][i]+"','"+parseInt(result['amount'][i].replace(/,/g, ""))+"')> Approve <i class='bi bi-check'></i> </button>";
                data += "<button type='button' class='btn btn-danger' onclick=disapprovePayment('" + result['id'][i] + "')> Disapprove <i class='bi bi-x'></i> </button>";
                data += "</div>";
                data += "</td>";
            } else {
                data += "<td class='text-danger'>None</td>";
            }
            data += "</tr>";
            }
            $('#tb1').DataTable().clear().destroy();
            $('#tbody1').append(data);
            $('#tb1').dataTable({
            "columnDefs": [{
                "targets": [4, 5],
                "orderable": false
            }]
            });
        }
        }
    });
    }
    UpdateTable();

    function viewPicture(pictureUrl) {
        $('#viewPic').modal('show');
        $('#imgModal').attr('src', "../payment/"+pictureUrl);
      
    }

    function approvePayment(paymentId, userId, amount) {
        $.post({
            url: "ajax/topup_approve.php",
            method: "post",
            data: {'paymentId':JSON.stringify(paymentId),'userId':JSON.stringify(userId),'amount':JSON.stringify(amount)},
            success: function(res){
                UpdateTable();
            }
        });
    }

    function disapprovePayment(paymentId) {
        $.post({
            url: "ajax/topup_disapprove.php",
            method: "post",
            data: {'paymentId':JSON.stringify(paymentId)},
            success: function(res){
                UpdateTable();
            }
        });
    }

    // sidebar toggler
    $(document).ready(function() {
        $('#sidebarCollapse').on('click', function() {
            $('#sidebar').toggleClass('active');
        });
    });
</script>