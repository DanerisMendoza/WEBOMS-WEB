<?php 
    $page = 'admin';
    include('method/checkIfAccountLoggedIn.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Management</title>

    <link rel="stylesheet" href="css/bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" href="css/admin.css">
    <!-- modal script -->
    <script type="text/javascript" src="js/bootstrap 5/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
</head>

<body>

    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar" class="bg-dark">
            <div class="sidebar-header bg-dark">
                <h3 class="mt-3">
                    <a href="admin.php">Admin</a>
                </h3>
            </div>
            <ul class="list-unstyled components ms-3">
                <li class="mb-2">
                    <a href="#" id="pos">
                        <i class="bi bi-tag me-2"></i>
                        Point of Sales
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" id="orders">
                        <i class="bi bi-minecart me-2"></i>
                        Orders
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" id="ordersQueue">
                        <i class="bi bi-clock me-2"></i>
                        Orders Queue
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" id="inventory">
                        <i class="bi bi-box-seam me-2"></i>
                        Inventory
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" id="salesReport">
                        <i class="bi bi-bar-chart me-2"></i>
                        Sales Report
                    </a>
                </li>
                <li class="mb-2 active">
                    <a href="#">
                        <i class="bi bi-person-circle me-2"></i>
                        Account Management
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" id="customerFeedback">
                        <i class="bi bi-chat-square-text me-2"></i>
                        Customer Feedback
                    </a>
                </li>
                <li class="mb-1">
                    <a href="#" id="adminTopUp">
                        <i class="bi bi-cash-stack me-2"></i>
                        Top-Up
                    </a>
                </li>
                <li>
                    <form method="post">
                        <button class="btn btnLogout btn-dark text-danger" id="Logout" name="logout">
                            <i class="bi bi-power me-2"></i>
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </nav>

        <!-- Page Content  -->
        <div id="content">
            <nav class="navbar navbar-expand-lg bg-light">
                <div class="container-fluid bg-transparent">
                    <button type="button" id="sidebarCollapse" class="btn" style="font-size:20px;">
                        <i class="bi bi-list"></i>
                        <span>Dashboard</span>
                    </button>
                </div>
            </nav>

            <!-- content here -->
            <div class="container-fluid text-center">
                <div class="row justify-content-center">
                    <?php
                        include_once('method/query.php');
                        $selectAllUser = "select * from WEBOMS_user_tb inner join WEBOMS_userInfo_tb on WEBOMS_user_tb.user_id = WEBOMS_userInfo_tb.user_id";
                        $resultSet =  getQuery($selectAllUser);
                    ?>
                    <div class="table-responsive col-lg-12">
                        <table class="table table-bordered col-lg-12">
                            <thead>
                                <tr>
                                    <th scope="col">USERNAME</th>
                                    <th scope="col">NAME</th>
                                    <th scope="col">EMAIL</th>
                                    <th scope="col">ACCOUNT TYPE</th>
                                    <th scope="col">
                                        <button id="addButton" type="button" class="btn btn-success mb-1"
                                            data-bs-toggle="modal" data-bs-target="#loginModal">
                                            <i class="bi bi-person-plus me-1"></i>
                                            ADD NEW ACCOUNT
                                        </button>
                                    </th>
                                    <th scope="col">OPTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if($resultSet!= null)
                                    foreach($resultSet as $row){ ?>
                                <tr>
                                    <!-- username -->
                                    <td><?php echo $row['username']; ?></td>
                                    <!-- name -->
                                    <td><?php echo $row['name']; ?></td>
                                    <!-- email -->
                                    <td><?php echo $row['email']; ?></td>
                                    <!-- account type -->
                                    <td><?php echo strtoupper($row['accountType']);?></td>
                                    <!-- options -->
                                    <td>
                                        <a class="btn btn-warning"
                                            href="?update=<?php echo $row['username'].','.$row['email'] ?>">
                                            <i class="bi bi-arrow-repeat me-1"></i>
                                            UPDATE
                                        </a>
                                    </td>
                                    <td>
                                        <?php if($row['username'] != 'admin'){?>
                                        <a class="btn btn-danger" href="?delete=<?php echo $row['user_id'] ?>">
                                            <i class="bi bi-trash me-1"></i>
                                            DELETE
                                        </a>
                                        <?php } 
                                            else
                                                echo "YOU CAN NOT DELETE </BR> ADMIN ACCOUNT!" ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- insert -->
            <div class="modal fade" role="dialog" id="loginModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body ">
                            <form method="post" class="form-group">
                                <!-- username -->
                                <input type="text" class="form-control form-control-lg mb-3" name="username"
                                    placeholder="ENTER USERNAME" required>
                                <!-- name -->
                                <input type="text" class="form-control form-control-lg mb-3" name="name"
                                    placeholder="ENTER NAME" required>
                                <!-- email -->
                                <input type="email" class="form-control form-control-lg mb-3" name="email"
                                    placeholder="ENTER EMAIL" required>
                                <!-- password -->
                                <input type="password" class="form-control form-control-lg mb-3" name="password"
                                    placeholder="ENTER PASSWORD" required>
                                <!-- manager/cashier -->
                                <select name="accountType" class="form-control form-control-lg col-12 mb-3">
                                    <option value="manager">MANAGER</option>
                                    <option value="cashier">CASHIER</option>
                                </select>
                                <!-- button -->
                                <button type="submit" class="btn btn-lg btn-success col-12" name="insert">
                                    <i class="bi bi-plus me-1"></i>
                                    INSERT
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- update -->
            <!-- <div class="modal fade" role="dialog" id="updateModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body ">
                            <form method="post" class="form-group">
                                <input type="text" class="form-control form-control-lg mb-3" name="username"
                                    placeholder="ENTER NEW USERNAME" required>
                                <input type="text" class="form-control form-control-lg mb-3" name="name"
                                    placeholder="ENTER NEW NAME" required>
                                <input type="email" class="form-control form-control-lg mb-3" name="email"
                                    placeholder="ENTER NEW EMAIL" required>
                                <input type="password" class="form-control form-control-lg mb-3" name="password"
                                    placeholder="ENTER NEW PASSWORD" required>
                                <select name="accountType" class="form-control form-control-lg col-12 mb-3">
                                    <option value="manager">MANAGER</option>
                                    <option value="cashier">CASHIER</option>
                                </select>
                                <button type="submit" class="btn btn-lg btn-success col-12" name="updateNonAdmin">
                                    <i class="bi bi-arrow-repeat me-1"></i>
                                    UPDATE
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- passAndEmail -->
            <div class="modal fade" role="dialog" id="passAndEmail">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body ">
                            <form method="post" class="form-group">
                                <!-- username -->
                                <input type="text" class="form-control form-control-lg mb-3" name="username"
                                    placeholder="ENTER NEW USERNAME" required>
                                <!-- email -->
                                <input type="email" class="form-control form-control-lg mb-3" name="email"
                                    placeholder="ENTER NEW EMAIL">
                                <!-- password -->
                                <input type="password" class="form-control form-control-lg mb-3" name="password"
                                    placeholder="ENTER NEW PASSWORD" required>
                                <!-- button -->
                                <button type="submit" class="btn btn-lg btn-warning col-12" name="updateAdmin">
                                    <i class="bi bi-arrow-repeat me-1"></i>
                                    UPDATE
                                </button>
                            </form>
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
        $user_id = uniqid('',true);

        //validation
        $query = "select * from WEBOMS_user_tb where username = '$username'";
        if(getQuery($query) != null)
            die ("<script>alert('Name Already Exist!');</script>");
        $query = "select * from WEBOMS_userInfo_tb where name = '$name'";
        if(getQuery($query) != null)
            die ("<script>alert('Name Already Exist!');</script>");
        $query = "select * from WEBOMS_userInfo_tb where email = '$email'";
        if(getQuery($query) != null)
            die ("<script>alert('Email Already Exist!');</script>");

        //insert
        $query1 = "insert into WEBOMS_user_tb(username, password, accountType, user_id) values('$username','$hash','$accountType','$user_id')";
        $query2 = "insert into WEBOMS_userInfo_tb(name, email, otp, user_id) values('$name','$email','','$user_id')";
        if(!Query($query1))
          echo "FAIL TO SAVE TO DATABASE!";
        elseif(!Query($query2))
          echo "FAIL TO SAVE TO DATABASE!";
        else
          echo ("<script>window.location.replace('accountManagement.php'); alert('SUCCESS!');</script>");
  
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
        $query = "select * from WEBOMS_userInfo_tb where email = '$email'";
        if(getQuery($query) != null && $email != $oldEmail)
            die ("<script>alert('Email Already Exist!');</script>");

        $query = "update WEBOMS_user_tb as a inner join WEBOMS_userInfo_tb as b on a.user_id = b.user_id SET password = '$hash', email = '$email' WHERE username='$username' ";
        if(Query($query)){
            echo "<script>alert('SUCCESS!');</script>";
            echo "<script>history.replaceState({},'','accountManagement.php');</script>";
            echo "<script>window.location.replace('accountManagement.php');</script>";
        }
    }

    //delete
    if(isset($_GET['delete'])){
        $user_id = $_GET['delete'];
        $query = "DELETE FROM WEBOMS_user_tb WHERE user_id='$user_id' ";
        $query2 = "DELETE FROM WEBOMS_userInfo_tb WHERE user_id='$user_id' ";
        if(Query($query))
            if(Query($query2))
                echo "<script>window.location.replace('accountManagement.php');</script>";
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

<script>
// for navbar click locations
document.getElementById("pos").onclick = function() {
    window.location.replace('adminPos.php');
};
document.getElementById("orders").onclick = function() {
    window.location.replace('adminOrders.php');
};
document.getElementById("ordersQueue").onclick = function() {
    window.location.replace('adminOrdersQueue.php');
};
document.getElementById("inventory").onclick = function() {
    window.location.replace('adminInventory.php');
};
document.getElementById("salesReport").onclick = function() {
    window.location.replace('adminSalesReport.php');
};
document.getElementById("customerFeedback").onclick = function() {
    window.location.replace('adminFeedbackList.php');
};
document.getElementById("adminTopUp").onclick = function() {
    window.location.replace('adminTopUp.php');
};
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
                $updateQuery = "UPDATE WEBOMS_menu_tb SET stock = (stock + '$dishesQuantity[$i]') WHERE dish= '$dishesArr[$i]' ";    
                Query($updateQuery);    
            }
        }
        session_destroy();
        echo "<script>window.location.replace('Login.php');</script>";
    }
?>