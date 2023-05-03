<?php
    $page = 'admin';
    include('../method/checkIfAccountLoggedIn.php');
    include('../method/query.php');
    //init
    $_SESSION['from'] = 'adminSalesReport';

    //default value
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

    <link rel="stylesheet" href="../css/bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <script type="text/javascript" src="../js/bootstrap 5/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/jquery-3.6.1.min.js"></script>
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
                <li class="mb-2 active">
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
            <div class="container-fluid text-center">
                <div class="row justify-content-center g-3">
                    <div class="btn-group container-fluid" role="group" aria-label="Basic mixed styles example">
                        <button class="btn btn-lg btn-danger" id="viewInPdf"><i class="bi bi-file-earmark-pdf-fill"></i> PDF</button>
                    </div>

                    <!-- table -->
                        <div class="table-responsive col-lg-12">
                            <table class="table table-bordered border-white col-lg-12">
                                <tr>
                                    <td><h1 class="fw-normal h3 form-control form-control-lg">FROM:</h1></td>
                                    <td><input type="datetime-local" id="dateFetch1" class="form-control form-control-lg"></td>
                                    <td><button type="button" onclick="updateTable1('showByTwoDate')" class="btn btn-lg btn-primary col-12">FETCH</button></td>
                                </tr>
                                <tr>
                                    <td><h1 class="fw-normal h3 form-control form-control-lg">TO:</h1></td>
                                    <td><input type="datetime-local" id="dateFetch2" class="form-control form-control-lg"></td>
                                    <td><button type="button" onclick="updateTable1('showAll')" class="btn btn-lg btn-primary col-12">SHOW ALL</button></td>
                                </tr>
                            </table>
                        </div>

                    <!-- table 2 -->
                    <div class="table-responsive col-lg-12">
                        <table id="tbl1" class="table table-bordered table-hover col-lg-12">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">NAME</th>
                                    <th scope="col">TRANSACTION NO.</th>
                                    <th scope="col">DATE & TIME (MM/DD/YYYY)</th>
                                    <th scope="col">TOTAL ORDER</th>
                                    <th scope="col">ORDER DETAILS</th>
                                </tr>
                            </thead>
                            <tbody id="tbody1">
                    
                            </tbody>
                        </table>
                    </div>
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
                    data += "<td> <a class='btn btn-light' style='border:1px solid #cccccc;' href='adminOrder_details.php?order_id="+sales['order_id'][i]+"'><i class='bi bi-list'></i> View</a></td>";
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
</script>

<script>
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