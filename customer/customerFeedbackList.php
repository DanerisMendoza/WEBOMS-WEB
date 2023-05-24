<?php
    $page = 'customer';
    include('../method/checkIfAccountLoggedIn.php');
    include_once('../method/query.php');
    $companyName = getQueryOneVal2('select name from weboms_company_tb','name');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MENU | FEEDBACK</title>
 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/customer.css">
    <link rel="icon" href="../image/weboms.png">
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
                        <a class="nav-link text-danger animate__animated animate__fadeInLeft" href="customerMenu.php">MENU</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link animate__animated animate__fadeInLeft" href="customerTopUp.php">TOP-UP</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link animate__animated animate__fadeInLeft" href="customerOrders.php">ORDERS</a>
                    </li>
                </ul>
                <form action="" method="post">
                    <button class="btn btn-logout btn-outline-light animate__animated animate__fadeInLeft" id="Logout" name="logout">LOGOUT</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container feedback-container">
        <div class="card feedback-outer-card">
            <a href="customerMenu.php" class="back-menu animate__animated animate__fadeInLeft"><i class="bi-arrow-left"></i>BACK TO MENU</a>
            <div class="card feedback-card animate__animated animate__fadeInLeft">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="tb1">
                        <thead class="table-dark">
                            <tr>
                                <th>NAME</th>
                                <th>FEEDBACK</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>

<script>
    //get latestId
    var latestId;
    $.getJSON({
    url: "ajax/feedback_getNewestFeedback.php",
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
            url: "ajax/feedback_getNewestFeedback.php",
            method: "post",
            success: function(res){
                let result = parseInt(res) > parseInt(latestId);
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

    function updateTbody(){
        $.getJSON({
            url: "ajax/feedback_getFeedback.php",
            method: "post",
            success: function(result){
                $('#tbody1 tr').remove();
                if(result!=null){
                    let data = "";
                    for(let i=0; i<result['name'].length; i++){
                        data += "<tr>";
                        data += "<td>"+result['name'][i]+"</td>";
                        data += "<td>"+result['feedback'][i]+"</td>";
                        data += "</tr>";
                    };
                    $('#tb1').DataTable().clear().destroy();
                    $('#tbody1').append(data);
                    $('#tb1').DataTable();
                }
            },
        });
    }updateTbody();

    $(document).ready(function() {
        $('#tb1').DataTable();
    });
</script>

<?php 
    if(isset($_POST['logout'])){
        session_destroy();
        echo "<script>window.location.replace('../general/login.php');</script>";
    }
?>