<?php 
    $page = 'admin';
    include('../method/checkIfAccountLoggedIn.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Management</title>

    <link rel="stylesheet" href="../css/bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <!-- modal script -->
    <script type="text/javascript" src="../js/bootstrap 5/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/jquery-3.6.1.min.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
        <!-- data tables -->
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
                        <a href="pos/adminPos.php"><i class="bi bi-tag me-2"></i>Point of Sales</a>
                    </li>
                    <li class="mb-2">
                        <a href="adminOrders.php"><i class="bi bi-minecart me-2"></i>Orders</a>
                    </li>
                    <li class="mb-2">
                        <a href="ordersQueue/adminOrdersQueue.php"><i class="bi bi-clock me-2"></i>Orders Queue</a>
                    </li>
                
                <?php if($_SESSION['accountType'] != 'cashier'){?>
                    <li class="mb-2">
                        <a href="adminInventory.php"><i class="bi bi-box-seam me-2"></i>Inventory</a>
                    </li>
                    <li class="mb-2">
                        <a href="adminSalesReport.php"><i class="bi bi-bar-chart me-2"></i>Sales Report</a>
                    </li>
                    <li class="mb-2 active">
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
                <div class="row justify-content-center">
                    <?php
                        include_once('../method/query.php');
                        $selectAllUser = "select * from weboms_user_tb inner join weboms_userInfo_tb on weboms_user_tb.user_id = weboms_userInfo_tb.user_id";
                        $resultSet =  getQuery2($selectAllUser);
                    ?>
                    <div class="table-responsive col-lg-12">
                        <table id="tbl" class="table table-bordered table-hover col-lg-12">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">USERNAME</th>
                                    <th scope="col">NAME</th>
                                    <th scope="col">EMAIL</th>
                                    <th scope="col">ACCOUNT TYPE</th>
                                    <th scope="col">CUSTOMER INFO</th>
                                    <th scope="col">
                                        <button id="addButton" type="button" class="btn btn-success mb-1" data-bs-toggle="modal" data-bs-target="#loginModal"><i class="bi bi-person-plus"></i> ADD NEW ACCOUNT</button>
                                    </th>
                                    <th scope="col">OPTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if($resultSet!= null)
                                    foreach($resultSet as $row){ ?>
                                <tr>
                                    <td><?php echo $row['username']; ?></td>
                                    <td><?php echo ucwords($row['name']); ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo ucwords($row['accountType']);?></td>
                                    <td><a class="btn btn-primary" href="?viewCustomerInfo=<?php echo $row['user_id'] ?>"><i class="bi bi-list"></i> View</a></td>
                                    <!-- options -->
                                    <td>
                                        <a class="btn btn-warning" href="?update=<?php echo $row['username'].','.$row['email'] ?>"><i class="bi bi-arrow-repeat"></i> Update</a>
                                    </td>
                                    <td>
                                        <?php if($row['username'] != 'admin'){?>
                                        <a class="btn btn-danger" href="?delete=<?php echo $row['user_id'] ?>"><i class="bi bi-trash3"></i> Delete</a>
                                        <?php } 
                                            else
                                                echo "<a class='text-danger'>You cannot delete this account!</a>" ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- add new account -->
            <div class="modal fade" role="dialog" id="loginModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body ">
                            <form method="post" class="form-group">
                                <input type="text" class="form-control form-control-lg mb-3" name="username" placeholder="Enter username" required>
                                <input type="text" class="form-control form-control-lg mb-3" name="name" placeholder="Enter name" required>
                                <input type="email" class="form-control form-control-lg mb-3" name="email" placeholder="Enter email" required>
                                <input type="password" class="form-control form-control-lg mb-3" name="password" placeholder="Enter password" required>
                                <select name="accountType" class="form-control form-control-lg col-12 mb-3">
                                    <option value="manager">Manager</option>
                                    <option value="cashier">Cashier</option>
                                </select>
                                <button type="submit" class="btn btn-lg btn-success col-12" name="insert"><i class="bi bi-plus-circle"></i> Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- passAndEmail -->
            <div class="modal fade" role="dialog" id="passAndEmail">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body ">
                            <form method="post" class="form-group">
                                <input type="text" class="form-control form-control-lg mb-3" name="username" placeholder="Enter new username" required>
                                <input type="email" class="form-control form-control-lg mb-3" name="email" placeholder="Enter new email">
                                <input type="password" class="form-control form-control-lg mb-3" name="password" placeholder="Enter new password" required>
                                <button type="submit" class="btn btn-lg btn-warning col-12" name="updateAdmin"><i class="bi bi-arrow-repeat"></i> Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- customerProfileModal (Bootstrap MODAL) -->
            <div class="modal fade" id="customerProfileModal" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content container">
                        <div class="modal-body">
                            <!-- table -->
                            <div class="table-responsive col-lg-12">
                                <table class="table table-bordered col-lg-12 text-start">
                                    <tbody>
                                        <?php
                                        $query = "select a.*,b.* from weboms_user_tb a inner join weboms_userInfo_tb b on a.user_id = b.user_id where a.user_id = '$_GET[viewCustomerInfo]' ";
                                        $resultSet =  getQuery2($query);
                                        if($resultSet!= null)
                                        foreach($resultSet as $row){ 
                                        // init
                                        $id = $row['id'];
                                        $name = $row['name'];
                                        $picName = $row['picName'];
                                        $username = $row['username'];
                                        $g = $row['gender'];
                                        $phoneNumber = $row['phoneNumber'];
                                        $address = $row['address'];
                                        $balance = $row['balance'];
                                        $email = $row['email'];
                                        //gender process
                                        $g = $row['gender'];
                                        if($g == 'm'){
                                            $gender = 'male';
                                            $genderIndex = 0;
                                        }
                                        elseif($g == 'f'){
                                            $gender = 'female';
                                            $genderIndex = 1;
                                        }else{
                                            $gender = 'NA';
                                            $genderIndex = 2;
                                        }
                                        ?>
                                        <?php if($picName != null){ ?>
                                            <tr class="text-center">
                                                <th colspan="2"><img src="profilePic/<?php echo $picName; ?>" style="width:200px;height:200px;border:1px solid black;"></th>
                                            </tr>
                                        <?php } ?>
                                        <tr>
                                            <td><b>NAME</b></td>
                                            <td><?php echo ucwords($name);?></td>
                                        </tr>
                                        <tr>
                                            <td><b>USERNAME</b></td>
                                            <td><?php echo $username;?></td>
                                        </tr>
                                        <tr>
                                            <td><b>EMAIL</b></td>
                                            <td><?php echo $email;?></td>
                                        </tr>
                                        <tr>
                                            <td><b>GENDER</b></td>
                                            <td><?php echo ucfirst($gender);?></td>
                                        </tr>
                                        <tr>
                                            <td><b>PHONE NUMBER</b></td>
                                            <td><?php echo $phoneNumber;?></td>
                                        </tr>
                                        <tr>
                                            <td><b>ADDRESS</b></td>
                                            <td><?php echo ucwords($address);?></td>
                                        </tr>
                                        <tr class="bg-success text-white">
                                            <td><b>BALANCE</b></td>
                                            <td><b><?php echo '₱'. number_format($balance,2);?></b></td>
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
</body>

</html>

<?php 
    //insert
    if(isset($_POST['insert'])){
        //initialization
        $name =  $_POST['name'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password =  $_POST['password'];
        $accountType = $_POST['accountType'];
        $hash = password_hash($password, PASSWORD_DEFAULT);

        //get two user id from different table
        $lastUserIdOrder = getQueryOneVal2("SELECT MAX(user_id) from weboms_order_tb","MAX(user_id)");
        $lastUserIdUserInfo = getQueryOneVal2("SELECT MAX(user_id) from weboms_userInfo_tb","MAX(user_id)");
        //compare which user id is higher 
        if($lastUserIdOrder > $lastUserIdUserInfo)
            $user_id = $lastUserIdOrder;
        else
            $user_id = $lastUserIdUserInfo;   
        // increment user id
        $user_id++;

        //validation
        $query = "select * from weboms_user_tb where username = '$username'";
        if(getQuery2($query) != null)
            die ("<script>alert('NAME ALREADY EXIST!');</script>");
        $query = "select * from weboms_userInfo_tb where name = '$name'";
        if(getQuery2($query) != null)
            die ("<script>alert('NAME ALREADY EXIST!');</script>");
        $query = "select * from weboms_userInfo_tb where email = '$email'";
        if(getQuery2($query) != null)
            die ("<script>alert('EMAIL ALREADY EXIST!');</script>");

        //insert
        $query1 = "insert into weboms_user_tb(username, password, accountType, user_id) values('$username','$hash','$accountType','$user_id')";
        $query2 = "insert into weboms_userInfo_tb(name, email, otp, user_id) values('$name','$email','','$user_id')";
        if(!Query2($query1))
          echo "Failed to save to database!";
        elseif(!Query2($query2))
          echo "Failed to save to database!";
        else
          echo ("<script>window.location.replace('accountManagement.php'); alert('Success!');</script>");
  
    }
    //update form
    if(isset($_GET['update'])){
        $arr = explode(',',$_GET['update']);
        $username = $arr[0];
        $email = $arr[1];
        $_SESSION['oldEmail'] = $email;
        echo "<script>$('#passAndEmail').modal('show');</script>";
        echo "<script>
        document.forms[2].username.value = '$username';
        document.forms[2].email.value = '$email';
        document.forms[2].username.disabled = true;
        </script>";
    }

    //update button
    if(isset($_POST['updateAdmin'])){
        $password = $_POST['password'];
        $email = $_POST['email'];
        $oldEmail = $_SESSION['oldEmail'];
        $hash = password_hash($password, PASSWORD_DEFAULT);

        //validation
        $query = "select * from weboms_userInfo_tb where email = '$email'";
        if(getQuery2($query) != null && $email != $oldEmail)
            die ("<script>alert('Email Already Exist!');</script>");

        $query = "update weboms_user_tb as a inner join weboms_userInfo_tb as b on a.user_id = b.user_id SET password = '$hash', email = '$email' WHERE username='$username' ";
        if(Query2($query)){
            echo "<script>alert('Success!');</script>";
            echo "<script>history.replaceState({},'','accountManagement.php');</script>";
            echo "<script>window.location.replace('accountManagement.php');</script>";
        }
    }

    //delete
    if(isset($_GET['delete'])){
        $user_id = $_GET['delete'];
        $query = "DELETE FROM weboms_user_tb WHERE user_id='$user_id' ";
        $query2 = "DELETE FROM weboms_userInfo_tb WHERE user_id='$user_id' ";
        if(Query2($query))
            if(Query2($query2))
                echo "<script>window.location.replace('accountManagement.php');</script>";
    }

    //view customer info
    if(isset($_GET['viewCustomerInfo'])){
        echo "<script>$('#customerProfileModal').modal('show');</script>";
    }
?>

<script>
// sidebar toggler
$(document).ready(function() {
    $('#sidebarCollapse').on('click', function() {
        $('#sidebar').toggleClass('active');
    });
});
</script>

<?php 
// logout
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
    $('#tbl').DataTable();
});
</script>