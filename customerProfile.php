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
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
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
            <thead>
                <tr>
                    <th scope="col">Profile Info</th>
                    <img src='' style=width:100px;height:100px>
                </tr>
            </thead>
            <tbody>
                <?php
                    if($resultSet!= null)
                    foreach($resultSet as $row){ 
                    // init
                    $name = $row['name'];
                    $username = $row['username'];
                    $g = $row['gender'];
                    $phoneNumber = $row['phoneNumber'];
                    $address = $row['address'];
                    $balance = $row['balance'];
                    //gender process
                    $g = $row['gender'];
                    if($g == 'm')
                        $gender = 'male';
                    elseif($g == 'f')
                        $gender = 'female';
                    else
                        $gender = 'NA';
                ?>
                <tr><td><?php echo $name;?></td></tr>
                <tr><td><strong>Username: </strong> <?php echo $username;?></td></tr>
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
    
</body>
</html>
