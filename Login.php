<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Log in</title>

    <link rel="stylesheet" type="text/css" href="css/bootstrap 5/bootstrap.min.css">
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="js/bootstrap 5/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>

<body class="bg-dark">

    <section class="vh-100">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-xl-10">
                    <div class="card mb-5" style="border-radius: 1rem;">
                        <div class="row g-0">
                            <div class="col-md-6 col-lg-5">
                                <!-- <div class="col-md-6 col-lg-5 d-none d-md-block"> -->
                                <img src="https://images.unsplash.com/photo-1611657366409-55549160be82?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mnx8Zm9vZCUyMHBvcnRyYWl0fGVufDB8fDB8fA%3D%3D&w=1000&q=80"
                                    alt="login form" class="img-fluid" style="border-radius: 1rem;" />
                            </div>
                            <div class="col-md-6 col-lg-7 d-flex align-items-center">
                                <div class="card-body p-4 p-lg-5 text-black">

                                    <form method="post" class="form1">
                                        <div class="container">
                                            <h2 class="fw-normal mt-4 mb-4 text-center" style="letter-spacing: 1px;">Log
                                                in to your account</h2>

                                            <div class="input-group mb-4">
                                                <span class="input-group-text">
                                                    <i class="bi bi-person"></i>
                                                </span>
                                                <!-- username -->
                                                <input class="form-control form-control-lg" type="text" name="username"
                                                    placeholder="Username" required>
                                            </div>

                                            <div class="input-group mb-4">
                                                <span class="input-group-text">
                                                    <i class="bi bi-lock"></i>
                                                </span>
                                                <!-- password -->
                                                <input class="form-control form-control-lg" type="password"
                                                    name="password" placeholder="Password" required>
                                            </div>

                                            <div class="mb-4">
                                                <!-- forgot password -->
                                                <a class="text-muted text-decoration-none ms-1" href="#!">Forgot
                                                    password?</a>
                                            </div>

                                            <div class="mb-4">
                                                <!-- login button -->
                                                <button class="btn btn-dark btn-lg col-12" type="submit" name="Login"
                                                    value="Login">Login</button>
                                            </div>

                                            <div class="mb-4 text-center">
                                                <!-- sign up -->
                                                <p class="text-muted">Don't have an account? <a href="register.php"
                                                        class="text-muted text-decoration-none">Sign up here</a></p>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- otp (Bootstrap MODAL) -->
    <div class="modal fade" id="otpModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content container">
                <div class="modal-body">
                    <form method="post" class="form-group">
                        <h3 class="fw-normal mt-3 mb-3">PLEASE ENTER YOUR OTP</h3>
                        <input type="text" class="form-control form-control-lg mb-3" placeholder="OTP" name="otp">
                        <input data-dismiss="modal" type="submit" value="CANCEL" name="Cancel"
                            class="btn btn-danger btn-lg col-12 mb-2">
                        <input type="submit" value="RESEND" name="Resend" class="btn btn-success btn-lg col-12 mb-2">
                        <input type="submit" value="VERIFY" name="Verify" class="btn btn-success btn-lg col-12 mb-3">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
        include('method/query.php');
        include_once('connection.php');
        if(isset($_POST['Login'])){
            $_SESSION["username"]  = $_POST['username'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            //user block
            $query = "select * from WEBOMS_user_tb where username = '$username'";
            $resultSet = getQuery($query);
            if(($resultSet && $resultSet->num_rows)  > 0){
                foreach($resultSet as $rows){
                    $valid = password_verify($password, $rows['password'])?true:false;
                    $accountType = $rows['accountType'];
                    $user_id = $rows['user_id'];
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

</body>

</html>

<!-- <div class="container">
        <div class="row justify-content-center">
            <form method="post" class="form1">
                <img src="settings/logo.png"><br>
                <h1 class="font-weight-normal mt-5 mb-4 text-center">Log in to your account</h1>
                <input class="form-control form-control-lg mb-3" type="text" name="username" placeholder="Username"
                    required>
                <input class="form-control form-control-lg mb-3" type="password" name="password" placeholder="Password"
                    required>
                <div class="mb-3">
                    <a href="#" class="pass text-muted">Forgot Password?</a>
                </div>
                <button class="btn btn-primary btn-lg col-12 mb-3" type="submit" name="Login"
                    value="Login">Login</button>
                <div class="text-center text-muted mb-5">
                    Dont'have an account yet? <a href="register.php" class="signup_link text-muted">Sign up</a>
                </div>
            </form>
        </div>
    </div> -->