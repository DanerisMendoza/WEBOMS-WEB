<?php     
  $page = 'notLogin';
  include('method/checkIfAccountLoggedIn.php'); 
?>
<?php 
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    include('method/query.php');
    // session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Forget Password</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <button class="btn btn-lg btn-dark col-12 mb-3" id="back">Back</button>
            <form method="post" class="form1 col-12">
                <input type="text" name="email" placeholder="Please Enter Your Email" required
                    class="form-control form-control-lg mb-3">
                <button type="submit" name="submit" class="btn btn-primary btn-lg col-12 mb-3">Submit</button>
            </form>
        </div>
    </div>

    <!-- Enter forgetPasswordOtp -->
    <div class="modal fade" role="dialog" id="forgetPassModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body ">
                    <form method="post" class="form-group">
                        <input type="text" class="form-control form-control-lg mb-3" name="forgetPasswordOtp"
                            placeholder="Enter Otp" required>
                        <button type="submit" class="btn btn-lg btn-success col-12"
                            name="forgetPasswordOtpSubmit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Enter New Password -->
    <div class="modal fade" role="dialog" id="newPassModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body ">
                    <form method="post" class="form-group">
                        <input type="password" class="form-control form-control-lg mb-3" name="newPass"
                            placeholder="Enter New Password" required>
                        <button type="submit" class="btn btn-lg btn-success col-12" name="newPassSubmit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
<?php 
    if(isset($_POST['submit'])){
        $_SESSION['email'] = $email = $_POST['email'];
        $selectEmail = "select * from WEBOMS_user_tb inner join WEBOMS_userInfo_tb on WEBOMS_user_tb.user_id = WEBOMS_userInfo_tb.user_id where email = '$email' ";
        //email not exist
        if(getQuery($selectEmail) == null){
            echo "<script>
            alert('email do not exist!');
            window.location.replace('forgetPassword.php');
            </script>"; 
            return;
        }
        //email exist
        else{
            $forgetPasswordOtp = uniqid();
            $queryInsertForgetPasswordOtp = "UPDATE WEBOMS_userInfo_tb SET forgetPasswordOtp = '$forgetPasswordOtp' WHERE email='$email' ";   
            if(Query($queryInsertForgetPasswordOtp)){
                echo "<script>$('#forgetPassModal').modal('show');</script>";
                //Load Composer's autoloader
                require 'vendor/autoload.php';
                //Create an instance; passing `true` enables exceptions
                $mail = new PHPMailer(true);
                //Server settings
                $mail->SMTPDebug  = SMTP::DEBUG_OFF;
                //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                    //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'weboms098@gmail.com';                  //from //SMTP username
                $mail->Password   = 'pcqezwnqunxuvzth';                     //SMTP password
                $mail->SMTPSecure =  PHPMailer::ENCRYPTION_SMTPS;           //Enable implicit TLS encryption
                $mail->Port       =  465;                                   //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom('weboms098@gmail.com', 'webBasedOrdering');
                $mail->addAddress("$email");                                //sent to
                //Content
                $mail->Subject = 'Forget Password OTP';
                $mail->Body    = $forgetPasswordOtp;
                $mail->send();
            }
        }
    }
    //submit forget pass otp
    if(isset($_POST['forgetPasswordOtpSubmit'])){
        $forgetPasswordOtp = $_POST['forgetPasswordOtp'];
        $selectForgetPassOtp = "select forgetPasswordOtp from WEBOMS_user_tb inner join WEBOMS_userInfo_tb on WEBOMS_user_tb.user_id = WEBOMS_userInfo_tb.user_id where forgetPasswordOtp = '$forgetPasswordOtp' ";
        //check if otp match
        if(getQuery($selectForgetPassOtp) == null){
            echo "<script>
            alert('Forget Password otp not match!');
            window.location.replace('forgetPassword.php');
            </script>"; 
            return;
        }
        //otp match
        else{
            echo "<script>$('#newPassModal').modal('show');</script>";
        }
    }

    // submit new pass
    if(isset($_POST['newPassSubmit'])){
        $email = $_SESSION['email'];
        $password = $_POST['newPass'];
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $queryNewPass = "update WEBOMS_user_tb as a inner join WEBOMS_userInfo_tb as b on a.user_id = b.user_id set password = '$hash' where email = '$email' ";
        if(Query($queryNewPass)){
            echo "<script>
            alert('Update Pass Sucess!');
            window.location.replace(Login.php);
            </script>";
        }
        else{
            echo "<script>
            alert('Update Pass Unsucess!');
            window.location.replace(forgetPassword.php);
            </script>";
        }
    }
?>

<script>
document.getElementById("back").onclick = function() {
    window.location.replace('Login.php');
};
</script>