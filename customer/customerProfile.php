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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="../css/customer.css">
    <link rel="stylesheet" href="../css/customer-profile.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js"></script>
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
                        <a class="nav-link text-danger animate__animated animate__fadeInLeft" href="customerProfile.php">PROFILE</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link animate__animated animate__fadeInLeft" href="customerMenu.php">MENU</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link animate__animated animate__fadeInLeft" href="customerTopUp.php">TOP-UP</a>
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

    <div class="container profile-container">
        <div class="card profile-card">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
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
                            // gender process
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
                            <img src="../profilePic/<?php echo $picName; ?>" class="profile-img animate__animated animate__fadeInLeft"><br>
                            <?php } ?>
                            <label for="" class="name animate__animated animate__fadeInLeft"><?php echo strtoupper($name); ?></label>
                        </tr>
                    </thead>
                    <tbody class="animate__animated animate__fadeInLeft">
                        <tr>
                            <td>USERNAME</td>
                            <td><?php echo $username;?></td>
                        </tr>
                        <tr>
                            <td>EMAIL</td>
                            <td><?php echo $email;?></td>
                        </tr>
                        <tr>
                            <td>GENDER</td>
                            <td><?php echo strtoupper($gender);?></td>
                        </tr>
                        <tr>
                            <td>PHONE NUMBER</td>
                            <td><?php echo $phoneNumber;?></td>
                        </tr>
                        <tr>
                            <td>ADDRESS</td>
                            <td><?php echo strtoupper($address);?></td>
                        </tr>
                        <tr class="bg-success text-white">
                            <td>BALANCE</td>
                            <td><?php echo '₱'. number_format($balance,2);?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="input-group animate__animated animate__fadeInLeft">
                    <button class="btn btn-update-info" id="update">UPDATE INFO</button>
                    <button class="btn btn-update-password" id="updatePassword">UPDATE PASSWORD</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- userInfoUpdate -->
    <div class="modal" tabindex="-1" role="dialog" id="userInfoUpdate">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="" method="post" class="form-group" enctype="multipart/form-data">
                        <input type="file" class="form-control" name="fileInput">
                        <input type="text" class="form-control" placeholder="Enter new name" name="name">
                        <input type="text" class="form-control" placeholder="Enter new username" name="username">
                        <input type="email" class="form-control" placeholder="Enter new email" name="email">
                        <select name="gender" id="" class="form-control">
                            <option value="m">Male</option>
                            <option value="f">Female</option>
                            <option value="NA">Rather Not Say</option>
                        </select>
                        <input type="text" class="form-control" placeholder="Enter new address" name="address">
                        <input type="number" class="form-control" placeholder="Enter new phone number" name="phoneNumber" id="phoneNumber">
                        <button class="btn btn-update" type="submit" name="updateUserInfo">UPDATE</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- userPassword -->
    <div class="modal" tabindex="-1" role="dialog" id="passwordUpdateModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="" method="post" class="form-group">
                        <input type="password" class="form-control" placeholder="Enter new password" name="password">
                        <button class="btn btn-update2" type="submit" name="updatePassword">UPDATE</button>
                    </form>
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