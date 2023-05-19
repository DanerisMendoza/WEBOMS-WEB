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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/login.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.min.js"></script>
</head>
<body>

    <!-- otp modal -->
    <div class="modal fade" id="otpModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <form method="post" class="form-group">
                        <label for="" class="enter-otp">PLEASE ENTER YOUR OTP</label>
                        <input type="text" name="otp" class="form-control otp-form">
                        <input type="submit" value="VERIFY" name="Verify" class="btn btn-verify">
                        <input type="submit" value="RESEND" name="Resend" class="btn btn-resend">
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
            $otp = mt_rand(1000, 9999);
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
            $mail->setFrom('weboms@ucc-csd-bscs.com', 'webBasedOrdering');
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

    <!-- login form -->
    <a href="../index.php" type="button" class="back-home"><i class="bi bi-arrow-left"></i>BACK TO HOME</a>
    <div class="container">
        <div class="card login-card">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-6">
                        <img src="../image/login.jpg" alt="Login Image" class="login-img">
                    </div>
                    <div class="col-sm-6 colum-right">
                        <div class="card login-form">
                            <label for="" class="login">Log in to your account</label>
                            <form action="" method="post" class="form1">
                                <input type="text" class="form-control username" placeholder="Username" name="username" required>
                                <input type="password" class="form-control password" placeholder="Password" name="password" required>
                                <a href="forgetPassword.php" class="forgot-password">Forgot Password?</a>
                                <button class="btn btn-login" type="submit" name="Login" value="Login">Log in</button>
                                <label for="" class="account">Don't have an account yet? <a href="register.php" class="register-here">Register here.</a></label>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>