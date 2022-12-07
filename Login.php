<?php     
  $page = 'notLogin';
  include('method/checkIfAccountLoggedIn.php');
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
    <!-- online css bootsrap icon -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"> -->
</head>

<body class="bg-dark">
        <!-- otp (Bootstrap MODAL) -->
        <div class="modal fade" id="otpModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content container">
                    <div class="modal-body">
                        <form method="post" class="form-group">
                            <h3 class="fw-normal mb-3">Please Enter Your OTP</h3>
                            <input type="text"
                                class="form-control form-control-lg mb-3"
                                placeholder="OTP" name="otp">
                            <input type="submit" value="Verify" name="Verify"
                                class="btn btn-success btn-lg col-12 mb-1">
                            <!-- <input type="submit" value="Resend" name="Resend" class="btn btn-secondary btn-lg col-12 mb-1"> -->
                            <input data-dismiss="modal" type="submit" value="Cancel"
                                name="Cancel"
                                class="btn btn-danger btn-lg col-12 mb-1">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <?php
        include_once('connection.php');
        include('method/query.php');
        if(isset($_POST['Login'])){
            $_SESSION["username"]  = $_POST['username'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            //user block
            $query = "select * from WEBOMS_user_tb where username = '$username'";
            $resultSet = getQuery($query);
            if(($resultSet && $resultSet->num_rows)  > 0){
                foreach($resultSet as $row){
                    $valid = password_verify($password, $row['password'])?true:false;
                    $accountType = $row['accountType'];
                    $user_id = $row['user_id'];
                }
                if($valid){
                  //setting credential if valid
                  $query = "select * from WEBOMS_userInfo_tb where user_id = '$user_id'";
                  $resultSet = getQuery($query);
                  foreach($resultSet as $row){
                    $_SESSION['name'] = $row['name'];
                    $otp = $row['otp'];
                  }
                  $_SESSION['user_id'] = $user_id;
                  $_SESSION['accountType'] = $accountType;
                  $_SESSION['username'] = $username;
                  $_SESSION['account'] = 'valid';
                  switch($accountType){
                    case ('admin'):
                      echo "<script> window.location.replace('admin.php');</script>";
                    break;

                    case ('manager'):
                      echo "<script> window.location.replace('admin.php');</script>";
                    break;

                    case 'cashier';
                      echo "<script> window.location.replace('adminPos.php');</script>";
                    break;

                    case 'customer':
                      //if customer account is valid
                      if($valid && $otp == ''){
                        echo "<SCRIPT> window.location.replace('customer.php');  </SCRIPT>";
                      }
                      //if customer account need to validate first via otp
                      else if($valid && $otp != ""){
                        $_SESSION['account'] = '';
                        echo "<script>$('#otpModal').modal('show');</script>";
                      }
                      //if customer password is wrong
                      else{
                        echo "<script>alert('incorrect username or password!');</script>";
                      }
                    break;
                  }
                }
                else{
                    echo "<script>alert('incorrect username or password!');</script>";
                }
            }
            else{
                echo "<script>alert('incorrect username or password!');</script>";
            }
        }
        if(isset($_POST['Verify'])){
            $username = $_SESSION["username"];
            $otp = $_POST['otp'];
            $user_id = $_SESSION['user_id'];
            $query = "select * from WEBOMS_userInfo_tb where user_id = '$user_id' && otp = '$otp' ";
            $resultSet = getQuery($query);
            if($resultSet != null){
                $updateQuery = "UPDATE WEBOMS_userInfo_tb SET otp='' WHERE otp='$otp'";
                if(Query($updateQuery)){
                    echo "<SCRIPT> window.location.replace('customer.php'); </SCRIPT>";
                    $_SESSION['username'] = $username;
                    $_SESSION['account'] = 'valid';
                    $_SESSION['accountType'] = 'customer';
                }
            }
            else
              echo  '<script>alert("Incorrect Otp!"); </script>';
        }
    ?>

        <a href="index.php" type="button" class="btn btn-lg btn-dark text-white">
            <i class="bi bi-arrow-left"></i>
            BACK
        </a>
        <div class="container">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-xl-10">
                    <div class="card mb-5" style="border-radius: 1rem;">
                        <div class="row g-0">
                            <div class="col-md-6 col-lg-5">
                                <img src="https://images.unsplash.com/photo-1611657366409-55549160be82?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mnx8Zm9vZCUyMHBvcnRyYWl0fGVufDB8fDB8fA%3D%3D&w=1000&q=80"
                                alt="login form" class="img-fluid" style="border-radius: 1rem;" />
                            </div>
                            <div class="col-md-6 col-lg-7 d-flex align-items-center">
                                <div class="card-body p-4 p-lg-5 text-black">
                                    <div class="container">
                                        <div class="row justify-content-center">
                                            <form method="post" class="form1">
                                                <h1 class="fw-normal mt-5 mb-4 text-center">Log in to your
                                                    account</h1>

                                                <div class="input-group mb-3">
                                                    <span class="input-group-text bg-dark">
                                                        <i class="bi bi-person text-white"></i>
                                                    </span>
                                                    <input class="form-control form-control-lg" type="text"
                                                        name="username" placeholder="Username" required>
                                                </div>

                                                <div class="input-group mb-3">
                                                    <span class="input-group-text bg-dark">
                                                        <i class="bi bi-lock text-white"></i>
                                                    </span>
                                                    <input class="form-control form-control-lg" type="password"
                                                        name="password" placeholder="Password" required>
                                                </div>

                                                <div class="input-group mb-3">
                                                    <a href="forgetPassword.php"
                                                        class="pass text-muted text-decoration-none">
                                                        Forgot Password?
                                                    </a>
                                                </div>

                                                <div class="input-group mb-3">
                                                    <button class="btn btn-dark btn-lg col-12" type="submit"
                                                        name="Login" value="Login">
                                                        Login
                                                    </button>
                                                </div>

                                                <div class="text-center text-muted mb-5">
                                                    Dont'have an account yet? <a href="register.php"
                                                        class="signup_link text-muted text-decoration-none">Sign up
                                                        here</a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>

</html>

