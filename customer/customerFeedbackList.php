<?php
  $page = 'customer';
  include('../method/checkIfAccountLoggedIn.php');
  include_once('../method/query.php');
  $query = "select a.name, b.feedback from  weboms_userInfo_tb a inner join weboms_feedback_tb b on a.user_id = b.user_id";
  $resultSet =  getQuery2($query);
  $companyName = getQueryOneVal2('select name from weboms_company_tb','name');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Menu - Feedback List</title>
        <!-- for modal -->
        <link rel="stylesheet" href="../css/bootstrap 5/bootstrap.min.css">
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
                        <a class="nav-link text-dark" href="#" id="customer"><i class="bi bi-house-door"></i> HOME</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link text-dark" href="#" id="customerProfile"><i class="bi bi-person-circle"></i> PROFILE</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link text-danger" href="#"><i class="bi bi-book"></i> MENU</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link text-dark" href="#" id="topUp"><i class="bi bi-cash-stack"></i> TOP-UP</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link text-dark" href="#" id="customerOrder_details"><i class="bi bi-list"></i> VIEW ORDERS</a>
                    </li>
                </ul>
                <form method="post">
                    <button class="btn btn-danger" id="Logout" name="logout"><i class="bi bi-power"></i> LOGOUT</button>
                </form>
            </div>
        </div>
    </nav>

        <div class="container text-center" style="margin-top:130px;">
            <div class="row justify-content-center">

                <!-- table -->
                <div class="table-responsive bg-white shadow p-5 col-lg-12">
                    <!-- back button -->
                    <button class="btn btn-lg btn-dark col-12 mb-4" id="back"><i class="bi bi-arrow-left-short"></i> Back</button>
                    <script>
                    document.getElementById("back").onclick = function() {
                        window.location.replace('customerMenu.php');
                    };
                    </script>
                    <table class="table table-bordered table-hover col-lg-12" id="tb1">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">NAME</th>
                                <th scope="col">FEEDBACK</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if($resultSet!= null)
                                foreach($resultSet as $row){ ?>
                            <tr>
                                <td><?php echo ucwords($row['name']); ?></td>
                                <td><?php echo $row['feedback'];?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php ?>
                </div>
            </div>
        </div>

    </body>
</html>

<script>
    $(document).ready(function() {
        $('#tb1').DataTable();
    });
</script>

<script>
document.getElementById("customer").onclick = function() { window.location.replace('customer.php'); };
document.getElementById("customerProfile").onclick = function() { window.location.replace('customerProfile.php'); };
document.getElementById("topUp").onclick = function() { window.location.replace('customerTopUp.php'); };
document.getElementById("customerOrder_details").onclick = function() { window.location.replace('customerOrders.php'); };
</script>

<?php 
  if(isset($_POST['logout'])){
    $dishesArr = array();
    $dishesQuantity = array();
    if(isset($_SESSION['dishes'])){
        for($i=0; $i<count($_SESSION['dishes']); $i++){
            if(in_array( $_SESSION['dishes'][$i],$dishesArr)){
              $index = array_search($_SESSION['dishes'][$i], $dishesArr);
            }
            else{
              array_push($dishesArr,$_SESSION['dishes'][$i]);
            }
        }
        foreach(array_count_values($_SESSION['dishes']) as $count){
          array_push($dishesQuantity,$count);
        }
        for($i=0; $i<count($dishesArr); $i++){ 
          $updateQuery = "UPDATE weboms_menu_tb SET stock = (stock + '$dishesQuantity[$i]') WHERE dish= '$dishesArr[$i]' ";    
          Query2($updateQuery);    
        }
    }
    session_destroy();
    echo "<script>window.location.replace('../general/login.php');</script>";
  }
?>
