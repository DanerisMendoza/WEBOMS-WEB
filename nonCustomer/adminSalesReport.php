<?php
    $page = 'admin';
    include('../method/checkIfAccountLoggedIn.php');
    include('../method/query.php');
    // init
    $_SESSION['from'] = 'adminSalesReport';

    // default value
    $query = "select a.name, b.* from weboms_userInfo_tb a right join weboms_order_tb b on a.user_id = b.user_id where b.status = 'complete' ORDER BY b.id asc; ";
    $resultSet =  getQuery2($query); 
    $_SESSION['name'] = getQueryOneVal2("select name from weboms_userInfo_tb where user_id = '$_SESSION[user_id]' ",'name');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../css/admin2.css">
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
                <li><a href="adminPos.php"><i class="bi-tag me-2"></i>POINT OF SALES</a></li>
                <li><a href="adminOrders.php"><i class="bi-cart me-2"></i>ORDERS</a></li>
                <li><a href="adminOrdersQueue.php"><i class="bi-clock me-2"></i>ORDERS QUEUE</a></li>
                <li><a href="topupRfid.php"><i class="bi-credit-card me-2"></i>TOP-UP RFID</a></li>

                <?php if($_SESSION['accountType'] != 'cashier'){?>
                <li><a href="adminTopUp.php"><i class="bi-wallet me-2"></i>TOP-UP</a></li>
                <li><a href="adminInventory.php"><i class="bi-box me-2"></i>INVENTORY</a></li>
                <li><a href="adminSalesReport.php" class="active text-danger"><i class="bi-bar-chart me-2"></i>SALES REPORT</a></li>
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
                <button class="btn btn-danger" id="viewInPdf">PDF</button>
                <div class="table-responsive mt-3">
                    <table class="table table-bordered">
                        <tr>
                            <td><label for="" class="form-control">FROM:</label></td>
                            <td><input id="dateFetch1" type="datetime-local" class="form-control"></td>
                            <td><button type="button" class="btn btn-primary w-100" onclick="updateTable1('showByTwoDate')">FETCH</button></td>
                        </tr>
                        <tr>
                            <td><label for="" class="form-control">TO:</label></td>
                            <td><input id="dateFetch2" type="datetime-local" class="form-control"></td>
                            <td><button type="button" class="btn btn-primary w-100" onclick="updateTable1('showAll')">SHOW ALL</button></td>
                        </tr>
                    </table>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="tbl1">
                        <thead class="table-dark">
                            <tr>
                                <th>NAME</th>
                                <th>TRANSACTION #</th>
                                <th>DATE-TIME (MM/DD/YYYY)</th>
                                <th>TOTAL ORDER</th>
                                <th>ORDER DETAILS</th>
                            </tr>
                        </thead>
                        <tbody id="tbody1">

                        </tbody>
                    </table>
                </div>  
            </div>
        </div>
    </div>

</body>
</html>

<script>
    function updateTable1(mode){
        let date1 = $("#dateFetch1").val();
        let date2 = $("#dateFetch2").val();
        if(mode == 'showByTwoDate' && (date1 == '' || date2 == '')){
            alert('Please Complete Selecting Date!');
            return;
        }
        if(mode == 'showAll'){
            $('#dateFetch1').val('');
            $('#dateFetch2').val('');
        }

        let dateArr = new Array(2);
        dateArr[0] = date1;
        dateArr[1] = date2;

        $.getJSON({
            url: "ajax/salesReport_getSales.php",
            method: "post",
            data: {
                    'mode':JSON.stringify(mode),
                    'dateArr':JSON.stringify(dateArr),
                },
            success: function(sales){
                //username, name, email, accountType, rfid, user_id
                let data = "";
                if(sales != "null"){
                    for(let i=0; i<sales['name'].length; i++){
                        data += "<tr>";
                            if(sales['staffInCharge'][i] == 'online order' && sales['name'][i] == null){
                                data += "<td><a class='text-danger'>Deleted Account</a></td>";
                            }
                            else if(sales['name'][i] != null){
                                data += "<td>"+sales['name'][i]+"</td>";
                            }
                            else{
                                data += "<td>(No Name)</td>";
                            }
                            data += "<td>"+sales['order_id'][i]+"</td>";
                            data += "<td>"+sales['date'][i]+"</td>";
                            data += "<td>₱"+sales['totalOrder'][i]+"</td>";
                            data += "<td><center><a class='btn btn-light' href='adminOrder_details.php?order_id="+sales['order_id'][i]+"'><i class='bi-list'></i></a></ center></td>";
                        data += "</tr>";
                    }
                }
                console.log(sales);
                $('#tbl1').DataTable().clear().destroy();
                $('#tbody1').append(data);
                $('#tbl1').dataTable({
                "columnDefs": [
                    { "targets": [4], "orderable": false }
                ]
                });
            }
        });
    }
    updateTable1('showAll');

    //order button (js)
    document.getElementById("viewInPdf").addEventListener("click", () => {
        if ($('#tbl1').DataTable().data().length === 0) {
            alert('PDF is empty!');
            return;
        } else {
            window.open("../pdf/salesReport.php");
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
    if(isset($_POST['logout'])){
        session_destroy();
        echo "<script>window.location.replace('../general/login.php');</script>";
    }
?>