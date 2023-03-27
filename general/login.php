<?php     
  $page = 'notLogin';
  $isFromLogin = true;
  include('../method/checkIfAccountLoggedIn.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Log in</title>

    <link rel="stylesheet" type="text/css" href="../css/bootstrap 5/bootstrap.min.css">
    <script type="text/javascript" src="../js/jquery-3.6.1.min.js"></script>  
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>

<body class="bg-dark">

        <!-- otp (Bootstrap MODAL) -->
        <div class="modal fade" id="otpModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content container">
                    <div class="modal-body">
                        <form method="post" class="form-group">
                            <h3 class="fw-normal mt-4 mb-4 ms-1">PLEASE ENTER YOUR OTP</h3>
                            <input type="text" class="form-control form-control-lg mb-4" placeholder="OTP" name="otp">
                            <input type="submit" value="Verify" name="Verify" class="btn btn-success btn-lg col-12 mb-2">
                            <input type="submit" value="Resend" name="Resend" class="btn btn-primary btn-lg col-12 mb-2">
                            <input data-dismiss="modal" type="submit" value="Cancel" name="Cancel" class="btn btn-danger btn-lg col-12 mb-4">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <?php
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\SMTP;
        use PHPMailer\PHPMailer\Exception;
        include_once('connection.php');
        include('../method/query.php');
        if(isset($_POST['Login'])){
            $_SESSION["username"]  = $_POST['username'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            //user block
            $query = "select * from weboms_user_tb where username = '$username'";
            $resultSet = getQuery2($query);
            if(($resultSet && $resultSet->num_rows)  > 0){
                foreach($resultSet as $row){
                    $valid = password_verify($password, $row['password'])?true:false;
                    $accountType = $row['accountType'];
                    $user_id = $row['user_id'];
                }
                if($valid){
                  //setting credential if valid
                  $query = "select * from weboms_userInfo_tb where user_id = '$user_id'";
                  $resultSet = getQuery2($query);
                  foreach($resultSet as $row){
                    $_SESSION['name'] = $row['name'];
                    $otp = $row['otp'];
                    $email = $row['email'];
                  }
                  $_SESSION['email'] = $email;
                  $_SESSION['user_id'] = $user_id;
                  $_SESSION['accountType'] = $accountType;
                  $_SESSION['username'] = $username;
                  $_SESSION['account'] = 'valid';
                  switch($accountType){
                    case ('admin'):
                      echo "<script>  window.location.replace('../nonCustomer/admin.php');</script>";
                    break;

                    case ('manager'):
                      echo "<script>  window.location.replace('../nonCustomer/admin.php');</script>";
                    break;

                    case 'cashier';
                      echo "<script> window.location.replace('../nonCustomer/adminPos.php');</script>";
                    break;

                    case 'customer':
                      //if customer account is valid
                      if($valid && $otp == ''){
                        echo "<SCRIPT> window.location.replace('../customer/customer.php');  </SCRIPT>";
                      }
                      //if customer account need to validate first via otp
                      else if($valid && $otp != ""){
                        $_SESSION['account'] = '';
                        echo "<script>$('#otpModal').modal('show');</script>";
                      }
                      //if customer password is wrong
                      else{
                        echo "<script>alert('Incorrect username or password!');</script>";
                      }
                    break;
                  }
                }
                else{
                    echo "<script>alert('Incorrect username or password!');</script>";
                }
            }
            else{
                echo "<script>alert('Incorrect username or password!');</script>";
            }
        }
        // verifiy otp
        if(isset($_POST['Verify'])){
            $username = $_SESSION["username"];
            $otp = $_POST['otp'];
            $user_id = $_SESSION['user_id'];
            $query = "select * from weboms_userInfo_tb where user_id = '$user_id' && otp = '$otp' ";
            $resultSet = getQuery2($query);
            if($resultSet != null){
                $updateQuery = "UPDATE weboms_userInfo_tb SET otp='' WHERE otp='$otp'";
                if(Query2($updateQuery)){
                    echo "<SCRIPT> window.location.replace('../customer/customer.php'); </SCRIPT>";
                    $_SESSION['username'] = $username;
                    $_SESSION['account'] = 'valid';
                    $_SESSION['accountType'] = 'customer';
                }
            }
            else
              echo  '<script>alert("Incorrect OTP!"); </script>';
        }
        // resent otp
        if(isset($_POST['Resend'])){
            $otp = uniqid();
            // email proccess
            //Load Composer's autoloader
            require '../vendor/autoload.php';
            //Create an instance; passing `true` enables exceptions
            $mail = new PHPMailer(true);
            //Server settings
            $mail->SMTPDebug  = SMTP::DEBUG_OFF;                        //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            include_once('mailerConfig.php');
            $mail->SMTPSecure = 'ssl';                                  //Enable implicit TLS encryption
            $mail->Port       =  465;       
            //Recipients
            $mail->setFrom('weboms098@gmail.com', 'webBasedOrdering');
            $mail->addAddress("$_SESSION[email]");                                //sent to
            //Content
            $mail->Subject = 'OTP';
            $mail->Body    = "Good Day, ".$_SESSION['name']." \n \nWe would like to inform you that you have created an account and you need to verify your account first using this OTP: ". $otp ."\n \nThank You!.";
            $mail->send();
            // query
            $updateOtp = "update weboms_userInfo_tb as a inner join weboms_user_tb as b on a.user_id = b.user_id set otp = '$otp' where b.username = '$_SESSION[username]' ";
            if(Query2($updateOtp)){
                echo "<script>
                alert('OTP sent to your Gmail!');
                window.location.replace('../general/login.php');
                </script>";  
            }
        }
    ?>

        <!-- home button -->
        <a href="../index.php" type="button" class="btn btn-lg btn-dark text-white"> <i class="bi bi-arrow-left-short"></i> Home</a>
        
        <!-- login form -->
        <div class="container mt-5">
            <div class="row g-5 justify-content-center h-100">
                <div class="col col-xl-10">
                    <div class="card mb-5" style="border-radius: 1rem;">
                        <div class="row g-0">
                            <!-- image -->
                            <div class="col-md-6 col-lg-5">
                                <img src="https://thumbs.dreamstime.com/b/dark-plate-italian-spaghetti-dark-tasty-appetizing-classic-italian-spaghetti-pasta-tomato-sauce-cheese-parmesan-119870253.jpg" alt="login form" class="img-fluid" style="border-radius: 1rem; width:auto; height:auto;" />
                            </div>
                            <div class="col-md-6 col-lg-7 d-flex align-items-center">
                                <div class="card-body p-4 p-lg-5 text-black">
                                    <div class="container">
                                        <div class="row justify-content-center">
                                            <form method="post" class="form1">
                                                <h1 class="fw-normal text-center mb-5 mt-3">Log in to your account</h1>
                                                <!-- username -->
                                                <div class="input-group mb-4">
                                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                                    <input class="form-control form-control-lg" type="text" name="username" placeholder="Username" required>
                                                </div>
                                                <!-- password -->
                                                <div class="input-group mb-4">
                                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                                    <input class="form-control form-control-lg" type="password" name="password" placeholder="Password" required>
                                                </div>
                                                <!-- forgot password -->
                                                <div class="input-group mb-4">
                                                    <a href="forgetPassword.php" class="pass text-muted text-decoration-none">Forgot Password?</a>
                                                </div>
                                                <!-- login button -->
                                                <div class="input-group mb-4">
                                                    <button class="btn btn-dark btn-lg col-12" type="submit"name="Login" value="Login">Login</button>
                                                </div>
                                                <!-- sign up here -->
                                                <div class="text-center text-muted mb-4">
                                                    Dont'have an account yet? <a href="register.php" class="signup_link text-muted text-decoration-none">Sign up here</a>
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

