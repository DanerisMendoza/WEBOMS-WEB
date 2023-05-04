<?php 
    $page = 'customer';
    include('../method/checkIfAccountLoggedIn.php');
    include('../method/query.php');
    $user_id = $_SESSION['user_id'];
    $query = "SELECT a.*,b.name FROM `weboms_topUp_tb` a inner join weboms_userInfo_tb b on a.user_id = b.user_id where a.user_id = '$user_id' order by a.id desc";
    $resultSet =  getQuery2($query);
    //getting balance amount
    $query = "SELECT balance FROM `weboms_userInfo_tb` where user_id = '$_SESSION[user_id]' ";
    $balance = getQueryOneVal2($query,'balance');
    $balance = $balance == null ? 0 : $balance;
    // company name
    $_SESSION['multiArr'] = array();
    $companyName = getQueryOneVal2('select name from weboms_company_tb','name');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Up</title>

    <link rel="stylesheet" type="text/css" href="../css/bootstrap 5/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../css/customer.css">
    <script type="text/javascript" src="../js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap 5/bootstrap.min.js"></script>
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
                        <a class="nav-link text-danger" href="customerTopUp.php"><i class="bi bi-cash-stack"></i> TOP-UP</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link text-dark" href="customerOrders.php"><i class="bi bi-list"></i> VIEW ORDERS</a>
                    </li>
                </ul>
                <form method="post">
                    <button class="btn btn-danger" id="Logout" name="logout"><i class="bi bi-power"></i> LOGOUT</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container text-center" style="margin-top:130px;">    
        <div class="row">
            <div class="container mb-5 shadow">
                <div class="row g-5">
                    <div class="col-sm-4 bg-white"> 
                        <div class="container py-4">
                            <!-- content here -->
                            <h2 class="fw-normal mt-3 mb-4">Top-Up</h2>
                            <form method="post" enctype="multipart/form-data" class="col-12">
                                <select name="amount" class="form-control form-control-lg col-12 mb-4">
                                    <option value="100">₱100.00</option>
                                    <option value="300">₱300.00</option>
                                    <option value="500">₱500.00</option>
                                    <option value="1000">₱1000.00</option>
                                    <option value="3000">₱3000.00</option>
                                    <option value="5000">₱5000.00</option>
                                </select>
                                <h5 class="fw-normal">PROOF OF PAYMENT:</h5>
                                <input type="file" class="form-control form-control-lg mb-4" name="fileInput" required>
                                <button class="btn btn-lg btn-success col-12 mb-3" name="submit"><i class="bi bi-cash-stack"></i> Top-Up</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-sm-8 bg-white">
                        <div class="container py-4">
                            <!-- content here -->
                            <h2 class="mt-3 mb-4 bg-success text-white">Your balance is ₱<?php echo number_format($balance,2);?></h2>
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered col-lg-12" id="tb1">
                                    <thead class="table-dark">
                                        <tr>
                                            <th scope="col">NAME</th>
                                            <th scope="col">AMOUNT</th>
                                            <th scope="col">STATUS</th>
                                            <th scope="col">DATE & TIME<br>(MM/DD/YYYY)</th>
                                            <th scope="col">PAYMENT</th>
                                            <th scope="col">ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if($resultSet!= null)
                                        foreach($resultSet as $row){ ?>
                                        <tr>
                                            <td><?php echo ucwords($row['name']); ?></td>
                                            <td><?php echo '₱'. number_format($row['amount'],2);?></td>
                                            <td><?php echo ucwords($row['status']);?></td>
                                            <td><?php echo date('m/d/Y h:i a ', strtotime($row['date']));?></td>
                                            <?php if($row['status'] != 'approved' && $row['status'] != 'disapproved'){?>
                                            <td>
                                                <a class="btn btn-light" style="border:1px solid #cccccc;" href="?viewPic=<?php echo $row['proofOfPayment'];?>"><i class="bi bi-list"></i> View</a>
                                            </td>
                                            <td>
                                                <a class="btn btn-danger" href="?cancel=<?php echo $row['id'].','.$row['proofOfPayment'];?>"><i class="bi bi-x-circle"></i> Cancel</a>
                                            </td>
                                            <?php }else{ ?>
                                            <td><a class="btn btn-light" style="border:1px solid #cccccc;" href="?viewPic=<?php echo $row['proofOfPayment'];?>"><i class="bi bi-list"></i> View</a></td>
                                            <td></td>
                                            <?php }?>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- pic (Bootstrap MODAL) -->
    <div class="modal fade" id="viewPic" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content container text-center">
                <div class="modal-body">
                    <?php  echo "<img src='../payment/$_GET[viewPic]' style=width:300px;height:550px>";?>
                </div>
            </div>
        </div>
    </div>

</body>

</html>

<?php 
    //view pic
    if(isset($_GET['viewPic'])){
        echo "<script>$('#viewPic').modal('show');</script>";
    }
    //top up button
    if(isset($_POST['submit'])){
        //init
        $amount = $_POST['amount'];+
        date_default_timezone_set('Asia/Manila');
        $date = new DateTime();
        $todayWithTime =  $date->format('Y-m-d H:i:s'); 
        $user_id = $_SESSION['user_id'];
        $fileName = $_FILES['fileInput']['name'];
        $fileTmpName = $_FILES['fileInput']['tmp_name'];
        $fileSize = $_FILES['fileInput']['size'];
        $fileError = $_FILES['fileInput']['error'];
        $fileType = $_FILES['fileInput']['type'];
        $fileExt = explode('.',$fileName);
        $fileActualExt = strtolower(end($fileExt));
        $allowed = array('jpg','jpeg','png');
        //process
        if(in_array($fileActualExt,$allowed)){
            if($fileError === 0){
                if($fileSize < 10000000){
                    $fileNameNew = uniqid('',true).".".$fileActualExt;
                    $fileDestination = '../payment/'.$fileNameNew;
                    move_uploaded_file($fileTmpName,$fileDestination);  
                    $query = "insert into weboms_topUp_tb(user_id, amount,status, proofOfPayment, date) values('$user_id','$amount','pending','$fileNameNew','$todayWithTime')";
                    if(Query2($query)){
                        echo "<script>alert('Success place top-up!');  window.location.replace('customerTopUp.php');</script>";
                    }
                }
                else
                    echo "Your file is too big!";
            }
            else
                echo "There was an error uploading your file!";
        }
        else
            echo "You can't upload files of this type!";   
    }
    if(isset($_GET['cancel'])){
        $arr = explode(',',$_GET['cancel']);
        $id = $arr[0];
        $pic = $arr[1];
        $query = "DELETE FROM weboms_topUp_tb WHERE id='$id' ";
        if(Query2($query)){
          unlink("../payment/"."$pic");
          echo "<script> window.location.replace('customerTopUp.php');</script>";
        }
    }
?>

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
<script>
    $(document).ready(function() {
        $('#tb1').DataTable();
    });
    $('#tb1').dataTable({
    "columnDefs": [
        { "targets": [4,5], "orderable": false }
    ]
    });
</script>