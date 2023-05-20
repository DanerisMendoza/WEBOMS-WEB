<?php 
    $page = 'customer';
    include('../method/checkIfAccountLoggedIn.php');
    include('../method/query.php');
    $user_id = $_SESSION['user_id'];
    $query = "SELECT a.*,b.name FROM `weboms_topUp_tb` a inner join weboms_userInfo_tb b on a.user_id = b.user_id where a.user_id = '$user_id' order by a.id desc";
    $resultSet =  getQuery2($query);
    // getting balance amount
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/customer.css">
    <link rel="stylesheet" href="../css/customer-top-up.css">

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.5.3/js/bootstrap.min.js"></script>
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
                        <a class="nav-link text-danger animate__animated animate__fadeInLeft" href="customerTopUp.php">TOP-UP</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link animate__animated animate__fadeInLeft" href="customerOrders.php">VIEW ORDERS</a>
                    </li>
                </ul>
                <form action="" method="post">
                    <button class="btn btn-logout btn-outline-light animate__animated animate__fadeInLeft" id="Logout" name="logout">LOGOUT</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card top-up-card">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-4 colum-left animate__animated animate__fadeInLeft">
                        <center><label for="" class="top-up">TOP-UP</label></center>
                        <form method="post" enctype="multipart/form-data">
                            <select name="amount" class="form-control">
                                <option value="100">₱100.00</option>
                                <option value="300">₱300.00</option>
                                <option value="500">₱500.00</option>
                                <option value="1000">₱1000.00</option>
                                <option value="3000">₱3000.00</option>
                                <option value="5000">₱5000.00</option>
                            </select>
                            <label for="">PROOF OF PAYMENT:</label>
                            <input type="file" class="form-control" name="fileInput" required>
                            <button class="btn btn-submit btn-success" name="submit">SUBMIT</button>
                        </form> 
                    </div>
                    <div class="col-sm-8 animate__animated animate__fadeInLeft">
                        <center><label for="" class="balance text-success">BALANCE: P<?php echo number_format($balance, 2); ?></label></center>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="tb1">
                                <thead>
                                    <tr>
                                        <th>NAME</th>
                                        <th>AMOUNT</th>
                                        <th>STATUS</th>
                                        <th>DATE-TIME (MM/DD/YYYY)</th>
                                        <th>PAYMENT</th>
                                        <th>ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if($resultSet!= null)
                                        foreach($resultSet as $row){ ?>
                                        <tr>
                                            <td><?php echo strtoupper($row['name']); ?></td>
                                            <td><?php echo '₱'.number_format($row['amount'],2);?></td>
                                            <td><?php echo strtoupper($row['status']);?></td>
                                            <td><?php echo date('m/d/Y h:i a ', strtotime($row['date']));?></td>
                                            <?php if($row['status'] != 'approved' && $row['status'] != 'disapproved'){?>
                                            <td>
                                                <a class="btn btn-light" href="?viewPic=<?php echo $row['proofOfPayment'];?>"><i class="bi-list"></i></a>
                                            </td>
                                            <td>
                                                <a class="btn btn-danger" href="?cancel=<?php echo $row['id'].','.$row['proofOfPayment'];?>"><i class="bi-x-lg"></i></a>
                                            </td>
                                            <?php }else if($row['proofOfPayment'] != ''){ ?>
                                            <td><a class="btn btn-light" href="?viewPic=<?php echo $row['proofOfPayment'];?>"><i class="bi-list"></i></a></td>
                                            <td></td>
                                            <?php }else { ?>
                                            <td>PAYMENT THROUGH RFID</td>
                                            <td></td>
                                            <?php } ?>
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

    <!-- pic (Bootstrap MODAL) -->
    <div class="modal" tabindex="-1" id="viewPic" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <center><?php echo "<img src='../payment/$_GET[viewPic]' class='proof-img'";?></center>
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