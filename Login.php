<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Log in</title>

  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>  
  <script type="text/javascript" src="js/bootstrap.min.js"></script>

</head>
<body class="bg-light">
<?php include_once('connection.php');?>

<div class="container">
  <div class="row justify-content-center">
    <form  method="post" class="form1">
      <!-- <img src="settings/logo.png"><br> -->
      <h1 class="font-weight-normal mt-5 mb-4 text-center">Log in to your account</h1>
      <input class="form-control form-control-lg mb-3" type="text" name="username" placeholder="Username" required>
      <input class="form-control form-control-lg mb-3" type="password" name="password" placeholder="Password" required>
      <div class="mb-3">
        <a href="#" class="pass text-muted">Forgot Password?</a>
      </div>
      <button class="btn btn-primary btn-lg col-12 mb-3" type="submit" name="login" value="login">Login</button>
      <div class="text-center text-muted mb-5">
        Dont'have an account yet? <a href="register.php" class="signup_link text-muted">Sign up</a>
      </div>
    </form>

    <!-- otp (Bootstrap MODAL) -->
    <div class="modal fade" id="otpModal" role="dialog" >
      <div class="modal-dialog">
        <div class="modal-content container">
          <div class="modal-body">
            <form method="post" class="form-group">
              <h3 class="font-weight-normal mb-3">Please Enter Your OTP</h3>
              <input type="text" class="form-control form-control-lg mb-3" placeholder="OTP" name="otp" >          
              <input data-dismiss="modal" type="submit" value="Cancel" name="Cancel" class="btn btn-danger btn-lg col-12 mb-3">
              <input type="submit" value="Resend" name="Resend" class="btn btn-success btn-lg col-12 mb-3">
              <input type="submit" value="Verify" name="Verify" class="btn btn-success btn-lg col-12">
            </form>
          </div>
        </div>
      </div>
    </div>

    <?php
        if(isset($_POST['login'])){
            $_SESSION["username"]  = $_POST['username'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            if(empty($username) || empty($password)){
                echo '<script >alert("Please complete details!");</script>';
                echo "<script>window.location.replace('login.php')</script>";
                return;
            }
        
            //user block
            $query = "select * from user_tb where username = '$username'";
            $resultSet = $conn->query($query);
            if(($resultSet && $resultSet->num_rows)  > 0){
                foreach($resultSet as $rows){
                    $valid = password_verify($password, $rows['password'])?true:false;
                    $accountType = $rows['accountType'];
                }
                if($valid){
                  $query = "select * from customer_Tb where name = '$username'";
                  $resultSet = $conn->query($query);
                  foreach($resultSet as $row){
                    $otp = $row['otp'];
                    $_SESSION['userLinkId'] = $rows['userLinkId'];
                  }
                  
                  switch($accountType){
                    case 1://admin
                      echo "<script> window.location.replace('admin.php');</script>";
                    break;

                    case 2://customer
                      if($valid && $otp == ""){

                        echo "<SCRIPT> window.location.replace('customer.php?username=$username');  </SCRIPT>";
                      }
                      else if($valid && $otp != "")
                        echo "<script>$('#otpModal').modal('show');</script>";
                      else
                        echo "<script>alert('incorrect username or password!');</script>";
                    break;

                  }
                }
                else
                    echo "<script>alert('incorrect username or password!');</script>";
            }
            else
                echo "<script>alert('incorrect username or password! $conn->error');</script>";
            
        }
        if(isset($_POST['Verify'])){
            $username = $_SESSION["username"];
            $otp = $_POST['otp'];
            $userLinkId = $_SESSION['userLinkId'];
            $readQuery = "select * from customer_tb where userlinkId = '$userLinkId' && otp = '$otp' ";
            $result = mysqli_query($conn,$readQuery);
            if(mysqli_num_rows($result) === 1){
                // while($rows = mysqli_fetch_assoc($result))
                //     $_SESSION['userlinkId'] = $rows['userlinkId'];
                $updateQuery = "UPDATE customer_tb SET otp='' WHERE otp='$otp'";
                if(mysqli_query($conn, $updateQuery))
                    echo "<SCRIPT> window.location.replace('customer.php?username=$username'); </SCRIPT>";
            }
            else
              echo  '<script>alert("Incorrect Otp!"); window.location.replace("login.php");</script>';
        }

    ?>


</body>
</html>