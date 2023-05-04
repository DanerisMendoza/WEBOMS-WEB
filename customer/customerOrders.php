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

  <link rel="stylesheet" type="text/css" href="../css/bootstrap 5/bootstrap.min.css"> 
  <link rel="stylesheet" type="text/css" href="../css/customer.css">
  <script type="text/javascript" src="../js/bootstrap 5/bootstrap.min.js"></script>
  <script type="text/javascript" src="../js/jquery-3.6.1.min.js"></script>
  <!-- online css bootsrap icon -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
  <!-- data table -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

</head>

<body style="background:#e0e0e0">

    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow">
      <div class="container py-3">
        <a class="navbar-brand fs-4" href="#"><?php echo $companyName;?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item me-2">
                    <a class="nav-link text-dark" href="customer.php"><i class="bi bi-house-door"></i> HOME</a>
                </li>
                <li class="nav-item me-2">
                    <a class="nav-link text-dark" href="customerProfile.php"><i class="bi bi-person-circle"></i> PROFILE</a>
                </li>
                <li class="nav-item me-2">
                    <a class="nav-link text-dark" href="customerMenu.php"><i class="bi bi-book"></i> MENU</a>
                </li>
                <li class="nav-item me-2">
                    <a class="nav-link text-dark" href="customerTopUp.php"><i class="bi bi-cash-stack"></i> TOP-UP</a>
                </li>
                <li class="nav-item me-2">
                    <a class="nav-link text-danger" href="customerOrders.php"><i class="bi bi-list"></i> VIEW ORDERS</a>
                </li>
            </ul>
            <form method="post">
                <button class="btn btn-danger" id="Logout" name="logout"><i class="bi bi-power"></i> LOGOUT</button>
            </form>
        </div>
      </div>
    </nav>
    
    <div class="container text-center bg-white shadow" style="margin-top:130px;">    
      <div class="row justify-content-center">
        <div class="table-responsive col-lg-12 p-5">
          <table class="table table-bordered table-hover col-lg-12" id="tb1">
            <thead class="table-dark">
              <tr>	
                <th scope="col">NAME</th>
                <th scope="col">ORDER NO.</th>
                <th scope="col">STATUS</th>
                <th scope="col">DATE & TIME (MM/DD/YYYY)</th>
                <th scope="col">FEEDBACK</th>
                <th scope="col">ORDER DETAILS</th>
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
                    data +=     "<td>"+result['name'][i]+"</td>";
                    data +=     "<td>"+result['order_id'][i]+"</td>";
                    data +=     "<td>"+result['status'][i]+"</td>";
                    data +=     "<td>"+result['date'][i]+"</td>";
                    if(result['isAllowToFeedback'][i] == 'Allowed'){
                      data += "<td><a class='btn btn-primary' style='border:1px solid #cccccc;' href='customerFeedBack.php?ordersLinkIdAndUserLinkId="+result['order_id'][i]+","+result['user_id'][i]+"'><i class='bi bi-chat-square-text'></i></a></td>";
                    }
                    else{
                      data +=   "<td>"+result['isAllowToFeedback'][i]+"</td>";
                    }
                    data += "<td><a class='btn btn-light' style='border:1px solid #cccccc;' href='customerOrder_details.php?id="+result['order_id'][i]+"'><i class='bi bi-list'></i> View</a></td>";
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
