<?php 
    $page = 'customer';
    include('../method/checkIfAccountLoggedIn.php');
    include('../method/query.php');
    // company name
    $_SESSION['multiArr'] = array();
    $companyName = getQueryOneVal2('select name from weboms_company_tb','name');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>View Orders</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="../css/customer.css">
    <link rel="stylesheet" href="../css/customer-orders.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand animate__animated animate__fadeInLeft" href="#"><?php echo strtoupper($companyName); ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link animate__animated animate__fadeInLeft" href="customer.php">HOME</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link animate__animated animate__fadeInLeft" href="customerProfile.php">PROFILE</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link animate__animated animate__fadeInLeft" href="customerMenu.php">MENU</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link animate__animated animate__fadeInLeft" href="customerTopUp.php">TOP-UP</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger animate__animated animate__fadeInLeft" href="customerOrders.php">VIEW ORDERS</a>
                    </li>
                </ul>
                <form action="" method="post">
                    <button class="btn btn-logout btn-outline-light animate__animated animate__fadeInLeft" id="Logout" name="logout">LOGOUT</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <div class="table-responsive animate__animated animate__fadeInLeft">
                <table class="table table-bordered table-striped" id="tb1">
                    <thead>
                        <tr>
                            <th>NAME</th>
                            <th>ORDER NO.</th>
                            <th>STATUS</th>
                            <th>DATE-TIME (MM/DD/YYYY)</th>
                            <th>FEEDBACK</th>
                            <th>ORDER DETAILS</th>
                        </tr>
                    </thead>
                    <tbody id="tbody1">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
</body>
</html>

<script>
    let user_id = <?php echo $_SESSION['user_id']; ?>;
    function updateTbody(){
        //get cart attributes
        $.getJSON({
            url: "ajax/orders_getOrders.php",
            method: "post",
            data: {'user_id':user_id},
            success: function(result){
              if(result!=null){
                let data = "";
                for(let i=0; i<result['name'].length; i++){
                    data += "<tr>";
                    data += "<td>"+result['name'][i]+"</td>";
                    data += "<td>"+result['order_id'][i]+"</td>";
                    data += "<td>"+result['status'][i]+"</td>";
                    data += "<td>"+result['date'][i]+"</td>";
                    if(result['isAllowToFeedback'][i] == 'Allowed'){
                      data += "<td><a class='btn btn-primary' href='customerFeedBack.php?ordersLinkIdAndUserLinkId="+result['order_id'][i]+","+result['user_id'][i]+"'><i class='bi-pencil-square'></i></a></td>";
                    }
                    else{
                      data += "<td>"+result['isAllowToFeedback'][i]+"</td>";
                    }
                    data += "<td><a class='btn btn-light'' href='customerOrder_details.php?id="+result['order_id'][i]+"'><i class='bi-list'></i></a></td>";
                    data += "</tr>";
                }
                $('#tb1').DataTable().clear().destroy();
                $('#tbody1').append(data);
                $('#tb1').dataTable({
                "columnDefs": [
                    { "targets": [5], "orderable": false }
                ]
                });
              }
            },error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert("Status: " + textStatus); alert("Error: " + errorThrown);
            }
        });   
    }
    updateTbody();

    //get latestId
    var latestId;
    $.getJSON({
    url: "ajax/orders_getNewestOrder.php",
    method: "post",
    success: function(res){
      console.log(res);
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
            url: "ajax/orders_getNewestOrder.php",
            method: "post",
            success: function(res){
              console.log(res);
                let result = parseInt(res) != parseInt(latestId);
                if(result){
                    updateTbody();
                    latestId = res;
                }
              
            },
            complete: function(){
                setTimeout(checkIfDbChange, 2000);
            }
        });
    }
    checkIfDbChange();
</script>

<?php 
    if(isset($_POST['logout'])){
        session_destroy();
        echo "<script>window.location.replace('../general/login.php');</script>";
    }
?>
