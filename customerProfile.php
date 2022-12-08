<?php 
    $page = 'customer';
    include('method/checkIfAccountLoggedIn.php');
    include('method/query.php');
    $query = "select a.*,b.* from WEBOMS_user_tb a inner join WEBOMS_userInfo_tb b on a.user_id = b.user_id where a.user_id = '$_SESSION[user_id]' ";
    $resultSet =  getQuery($query);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Costumer - Feedback</title>
    <!-- modal script -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
    <script type="text/javascript" src="js/bootstrap 5/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>
</head>
<body class="bg-light">
    
<div class="container text-center mt-5">
    <div class="row justify-content-center">
        <button class="btn btn-lg btn-dark col-12 mb-3" id="back">Back</button>
        <script>document.getElementById("back").onclick = function () {window.location.replace('customer.php'); }; </script> 
	</div>
    <!-- table -->
    <div class="table-responsive col-lg-12">
        <table class="col-lg-12">
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
                    <th scope="col">Profile Info</th>
                    <?php if($picName != null){?>
                    <img src="profilePic/<?php echo $picName; ?>" style=width:200px;height:200px>
                    <?php } ?>
                </tr>
                <tr><td><strong>Name: <?php echo $name;?></strong></td></tr>
                <tr><td><strong>Username: </strong> <?php echo $username;?></td></tr>
                <tr><td><strong>Email: </strong> <?php echo $email;?></td></tr>
                <tr><td><strong>Gender: </strong> <?php echo $gender;?></td></tr>
                <tr><td><strong>Phone Number: </strong> <?php echo $phoneNumber;?></td></tr>
                <tr><td><strong>Address: </strong> <?php echo $address;?></td></tr>
                <tr><td><strong>Balance: </strong> <?php echo '₱'.$balance;?></td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <!-- userInfoUpdate -->
    <div class="modal fade" role="dialog" id="userInfoUpdate">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body ">
                    <form method="post" class="form-group" enctype="multipart/form-data">
                        <!-- profile pic -->
                        <label>Profile Pic</label>
                        <input type="file" class="form-control form-control-lg mb-3" name="fileInput">
                        <input type="text" class="form-control form-control-lg mb-3" name="name" placeholder="ENTER NEW NAME" required>
                        <input type="text" class="form-control form-control-lg mb-3" name="username" placeholder="ENTER NEW USERNAME" required>
                        <input type="email" class="form-control form-control-lg mb-3" name="email" placeholder="ENTER NEW EMAIL">
                        <select name='gender' class="form-control form-control-lg mb-3">
                            <option value="m">Male</option>
                            <option value="f">Female</option>
                            <option value="NA">Rather Not Say</option>
                        </select>
                        <input type="text" class="form-control form-control-lg mb-3" name="address" placeholder="ENTER NEW ADDRESS">
                        <input id="phone" type="number" class="form-control form-control-lg mb-3" name="phoneNumber" id="phoneNumber"  placeholder="ENTER NEW Phone Number" required>
                        <button type="submit" class="btn btn-lg btn-warning col-12" name="updateUserInfo">UPDATE</button>
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
                        <input  type="password" class="form-control form-control-lg mb-3" name="password"  placeholder="ENTER NEW PASSWORD" required>
                        <button type="submit" class="btn btn-lg btn-warning col-12" name="updatePassword">UPDATE</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <button class="btn btn-lg btn-dark col-3 mb-3" id="update">Update Info</button>
    <button class="btn btn-lg btn-dark col-3 mb-3" id="updatePassword">Update Password</button>
</div>
</body>
</html>
<script>
document.getElementById("update").onclick = function () {
    $('#userInfoUpdate').modal('show'); 
    document.forms[0].name.value = '<?php echo $name;?>';
    document.forms[0].username.value = '<?php echo $username;?>';
    document.forms[0].email.value = '<?php echo $email;?>';
    document.forms[0].gender.selectedIndex  = <?php echo $genderIndex; ?>;
    document.forms[0].address.value  = '<?php echo $address; ?>';
    document.forms[0].phoneNumber.value  = '<?php echo $phoneNumber; ?>';
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
        $query = "update WEBOMS_user_tb SET password = '$hash' WHERE id='$id' ";
        if(Query($query)){
            echo "<script>alert('SUCCESS!');</script>";
        }
    }

    // update submit button process
    if(isset($_POST['updateUserInfo'])){
        //validation
        $query = "select * from WEBOMS_userInfo_tb where name = '$_POST[name]' && name != '$name' ";
        if(getQuery($query) != null)
            die ("<script>alert('Name Already Exist!');</script>");
        $query = "select * from WEBOMS_user_tb where username = '$_POST[username]' && username != '$username' ";
        if(getQuery($query) != null)
            die ("<script>alert('Username Already Exist!');</script>");
        $query = "select * from WEBOMS_userInfo_tb where email = '$_POST[email]' && email != '$email' ";
        if(getQuery($query) != null)
            die ("<script>alert('Email Already Exist!');</script>");

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
        $query = "update WEBOMS_user_tb a inner join WEBOMS_userInfo_tb b on a.user_id = b.user_id SET name = '$name', username = '$username', picName = '$picName', email = '$email', gender = '$gender', address = '$address', phoneNumber = '$phoneNumber'  WHERE a.id='$id' ";
            if(Query($query)){
                die ("<script>alert('Sucess updating the database!'); window.location.replace('customerProfile.php');</script>");       
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
                    $fileDestination = 'profilePic/'.$fileNameNew;
                    move_uploaded_file($fileTmpName,$fileDestination);         
                    $query = "update WEBOMS_user_tb a inner join WEBOMS_userInfo_tb b on a.user_id = b.user_id SET name = '$name', username = '$username', picName = '$fileNameNew', email = '$email', gender = '$gender', address = '$address', phoneNumber = '$phoneNumber'  WHERE a.id='$id' ";
                    if(Query($query)){
                        echo '<script>alert("Sucess updating the database!");</script>';
                        if($picName != null)       
                            unlink("profilePic/".$picName);                                        
                    }
                    echo "<script>window.location.replace('customerProfile.php');</script>";                                
                }
                else
                    echo "your file is too big!";
            }
            else
                echo "there was an error uploading your file!";
        }
        else
            echo "you cannot upload files of this type";  
    }
?>
<script>
    //cut phone num if excess
    $("#phone").bind("change paste input", function() {
        var phone = document.forms[0].phone.value;
        if (phone.length >= 11) {
            document.forms[0].phone.value = phone.substring(0, 11);
        }
    });
</script>