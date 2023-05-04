<?php 
    $page = 'customer';
    include('../method/checkIfAccountLoggedIn.php');
    include('../method/query.php');
    $query = "select a.*,b.* from weboms_user_tb a inner join weboms_userInfo_tb b on a.user_id = b.user_id where a.user_id = '$_SESSION[user_id]' ";
    $resultSet =  getQuery2($query);
    $companyName = getQueryOneVal2('select name from weboms_company_tb','name');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile</title>

    <!-- modal script -->
    <link rel="stylesheet" type="text/css" href="../css/bootstrap 5/bootstrap.min.css"> 
    <link rel="stylesheet" type="text/css" href="../css/customer.css"> 
    <script type="text/javascript" src="../js/bootstrap 5/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/jquery-3.6.1.min.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
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
                        <a class="nav-link text-danger" href="customerProfile.php"><i class="bi bi-person-circle"></i> PROFILE</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link text-dark" href="customerMenu.php"><i class="bi bi-book"></i> MENU</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link text-dark" href="customerTopUp.php"><i class="bi bi-cash-stack"></i> TOP-UP</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link text-dark" href="customerOrders.php"><i class="bi bi-list"></i> VIEW ORDERS</a>
                    </li>
                    <li>
                        <form method="post">
                            <button class="btn btn-danger col-12" id="Logout" name="logout"><i class="bi bi-power"></i> LOGOUT</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
<div class="container text-center bg-white shadow mb-5" style="margin-top:175px;">
    <div class="row g-5 justify-content-center">
        <div class="table-responsive col-lg-12 px-5">
            <table class="table table-bordered col-lg-12 mb-4">
                <tbody>
                    <?php
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
                    <tr>
                        <?php if($picName != null){?>
                        <img src="../profilePic/<?php echo $picName; ?>" style="width:300px; height:300px; border-radius:10rem;" class="mb-3"> <br>
                        <?php } ?>
                        <a class="h1 text-decoration-none text-dark"><?php echo strtoupper($name);?></a> <br><br>
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
            <div class="input-group">
                <button class="btn btn-lg btn-warning col-6 mb-5" id="update">UPDATE INFO</button>
                <button class="btn btn-lg btn-secondary col-6 mb-5" id="updatePassword">UPDATE PASSWORD</button>
            </div>
        </div>
    </div>
    
    <!-- userInfoUpdate -->
    <div class="modal fade" role="dialog" id="userInfoUpdate">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body ">
                    <form method="post" class="form-group" enctype="multipart/form-data">
                        <!-- profile pic -->
                        <input type="file" class="form-control form-control-lg mb-3" name="fileInput">
                        <input type="text" class="form-control form-control-lg mb-3" name="name" placeholder="Enter new name" required>
                        <input type="text" class="form-control form-control-lg mb-3" name="username" placeholder="Enter new username" required>
                        <input type="email" class="form-control form-control-lg mb-3" name="email" placeholder="Enter new email" required>
                        <select name='gender' class="form-control form-control-lg mb-3" required>
                            <option value="m">Male</option>
                            <option value="f">Female</option>
                            <option value="NA">Rather Not Say</option>
                        </select>
                        <input type="text" class="form-control form-control-lg mb-3" name="address" placeholder="Enter new address" required>
                        <input id="phone" type="number" class="form-control form-control-lg mb-3" name="phoneNumber" id="phoneNumber"  placeholder="Enter new phone number" required>
                        <button type="submit" class="btn btn-lg btn-warning col-12" name="updateUserInfo"><i class="bi bi-arrow-repeat"></i> Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

      <!-- userPassword -->
      <div class="modal fade" role="dialog" id="passwordUpdateModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body ">
                    <form method="post" class="form-group">
                        <input  type="password" class="form-control form-control-lg mb-3" name="password" placeholder="Enter new password" required>
                        <button type="submit" class="btn btn-lg btn-warning col-12" name="updatePassword"><i class="bi bi-arrow-repeat"></i> Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>

<script>
document.getElementById("update").onclick = function () {
    $('#userInfoUpdate').modal('show'); 
    document.forms[1].name.value = '<?php echo $name;?>';
    document.forms[1].username.value = '<?php echo $username;?>';
    document.forms[1].email.value = '<?php echo $email;?>';
    document.forms[1].gender.selectedIndex  = <?php echo $genderIndex; ?>;
    document.forms[1].address.value  = '<?php echo $address; ?>';
    document.forms[1].phoneNumber.value  = '<?php echo $phoneNumber; ?>';
}; 
document.getElementById("updatePassword").onclick = function () {
    $('#passwordUpdateModal').modal('show'); 
}; 
</script> 

<?php 
    // update password process
    if(isset($_POST['updatePassword'])){
        $password = $_POST['password'];
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $query = "update weboms_user_tb SET password = '$hash' WHERE user_id = '$_SESSION[user_id]' ";
        if(Query2($query)){
            echo "<script>alert('Success!');</script>";
        }
    }

    // update submit button process
    if(isset($_POST['updateUserInfo'])){
        //validation
        $query = "select * from weboms_userInfo_tb where name = '$_POST[name]' && name != '$name' ";
        if(getQuery2($query) != null)
            die ("<script>alert('Name already exist!');</script>");
        $query = "select * from weboms_user_tb where username = '$_POST[username]' && username != '$username' ";
        if(getQuery2($query) != null)
            die ("<script>alert('Name already exist!');</script>");
        $query = "select * from weboms_userInfo_tb where email = '$_POST[email]' && email != '$email' ";
        if(getQuery2($query) != null)
            die ("<script>alert('Email already exist!');</script>");

        //file input process
        $name = $_POST['name'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $phoneNumber = $_POST['phoneNumber'];
        $address = $_POST['address'];
        $gender = $_POST['gender'];
        $fileName = $_FILES['fileInput']['name'];
        //if image didn't change 
        if($fileName == ''){
        $query = "update weboms_user_tb a inner join weboms_userInfo_tb b on a.user_id = b.user_id SET name = '$name', username = '$username', picName = '$picName', email = '$email', gender = '$gender', address = '$address', phoneNumber = '$phoneNumber'  WHERE a.user_id = '$_SESSION[user_id]' ";
            if(Query2($query)){
                die ("<script>alert('Success updating the database!'); window.location.replace('customerProfile.php');</script>");    
            }
        }
        //if image change block
        $fileTmpName = $_FILES['fileInput']['tmp_name'];
        $fileSize = $_FILES['fileInput']['size'];
        $fileError = $_FILES['fileInput']['error'];
        $fileType = $_FILES['fileInput']['type'];
        $fileExt = explode('.',$fileName);
        $fileActualExt = strtolower(end($fileExt));
        $allowed = array('jpg','jpeg','png');
        if(in_array($fileActualExt,$allowed)){
            if($fileError === 0){
                if($fileSize < 10000000){
                    $fileNameNew = uniqid('',true).".".$fileActualExt;
                    $fileDestination = '../profilePic/'.$fileNameNew;
                    move_uploaded_file($fileTmpName,$fileDestination);         
                    $query = "update weboms_user_tb a inner join weboms_userInfo_tb b on a.user_id = b.user_id SET name = '$name', username = '$username', picName = '$fileNameNew', email = '$email', gender = '$gender', address = '$address', phoneNumber = '$phoneNumber'  WHERE a.user_id = '$_SESSION[user_id]' ";
                    if(Query2($query)){
                        echo '<script>alert("Success updating the database!");</script>';
                        if($picName != null)       
                            unlink("../profilePic/".$picName);                                        
                    }
                    echo "<script>window.location.replace('customerProfile.php');</script>";                                
                }
                else
                    echo "YOUR FILE IS TOO BIG!";
            }
            else
                echo "THERE WAS AN ERROR UPLOADING YOUR FILE!";
        }
        else
            echo "YOU CANNOT UPLOAD FILES OF THIS TYPE!";  
    }
?>

<script>
    //cut phone num if excess
    $("#phone").bind("change paste input", function() {
        var phone = document.forms[1].phone.value;
        if (phone.length >= 11 || !isNaN(phone)) {
            document.forms[1].phone.value = phone.substring(0, 11);
        }
    });
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