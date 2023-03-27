<?php     
  $page = 'notLogin';
  include('../method/checkIfAccountLoggedIn.php'); 
?>

<?php 
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    include('../method/query.php');
    // session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forgot Password</title>

    <link rel="stylesheet" type="text/css" href="../css/bootstrap 5/bootstrap.min.css">
    <script type="text/javascript" src="../js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>

<body class="bg-dark">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <button class="btn btn-lg btn-primary col-12 mb-5" id="back"><i class="bi bi-arrow-left-short"></i> Back</button>
            <form method="post" class="form1 col-12">
                <input type="text" name="email" placeholder="Enter your email" class="form-control form-control-lg mb-3" required>
                <button type="submit" name="submit" class="btn btn-success btn-lg col-12 mb-3"><i class="bi bi-file-arrow-up"></i> Submit</button>
            </form>
        </div>
    </div>

    <!-- Enter forgetPasswordOtp -->
    <div class="modal fade" role="dialog" id="forgetPassModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body ">
                    <form method="post" class="form-group">
                        <input type="text" class="form-control form-control-lg mb-3" name="forgetPasswordOtp" placeholder="Enter OTP" required>
                        <button type="submit" class="btn btn-lg btn-success col-12" name="forgetPasswordOtpSubmit"><i class="bi bi-file-arrow-up"></i> Submit</button>
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
                        <input type="password" class="form-control form-control-lg mb-3" name="newPass" placeholder="Enter new password" required>
                        <button type="submit" class="btn btn-lg btn-success col-12" name="newPassSubmit"><i class="bi bi-file-arrow-up"></i> Submit</button>
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
        $name = getQueryOneVal2("select b.name from weboms_user_tb a inner join weboms_userInfo_tb b on a.user_id = b.user_id where email = '$email';",'name');
        if($name == null){
            echo "<script>
            alert('EMAIL DO NOT EXIST!');
            window.location.replace('forgetPassword.php');
            </script>"; 
            return;
        }
        //email exist
        else{
            $forgetPasswordOtp = uniqid();
            $queryInsertForgetPasswordOtp = "UPDATE weboms_userInfo_tb SET forgetPasswordOtp = '$forgetPasswordOtp' WHERE email='$email' ";   
            if(Query2($queryInsertForgetPasswordOtp)){
                echo "<script>$('#forgetPassModal').modal('show');</script>";
                //Load Composer's autoloader
                require 'vendor/autoload.php';
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
                $mail->addAddress("$email");                                //sent to
                //Content
                $mail->Subject = 'Forget Password OTP:';
                $mail->Body    = "Good Day, $name \n \nWe would like to inform you that you're trying to change your password. \nTo confirm please use this OTP: $forgetPasswordOtp \n \nThank You!";
                $mail->send();
            }
        }
    }
    //submit forget pass otp
    if(isset($_POST['forgetPasswordOtpSubmit'])){
        $forgetPasswordOtp = $_POST['forgetPasswordOtp'];
        $selectForgetPassOtp = "select forgetPasswordOtp from weboms_user_tb inner join weboms_userInfo_tb on weboms_user_tb.user_id = weboms_userInfo_tb.user_id where forgetPasswordOtp = '$forgetPasswordOtp' ";
        //check if otp match
        if(getQuery2($selectForgetPassOtp) == null){
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
        $queryNewPass = "update weboms_user_tb as a inner join weboms_userInfo_tb as b on a.user_id = b.user_id set password = '$hash' where email = '$email' ";
        if(Query2($queryNewPass)){
            echo "<script>
            alert('Update Pass Sucess!');
            window.location.replace(../general/login.php);
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
    window.location.replace('../general/login.php');
};
</script>