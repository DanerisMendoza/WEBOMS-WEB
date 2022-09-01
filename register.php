<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css"></head>
    </head>
    <body>
    <div class="container text-center">
    <form method="post">
        <input type="text" class="form-control" name="username" placeholder="username">
        <input type="text" class="form-control" name="name" placeholder="name">
        <input type="email" class="form-control" name="email" placeholder="email">
        <input type="password" class="form-control" name="password" placeholder="password">
        <button type="button" class="btn-success col-sm-12" id="back">Back</button>
        <button type="submit" class="btn-success col-sm-12" name="createAccount">createAccount</button>
    </form>
    </div>
    </body>
</html>
<script>
    document.getElementById("back").addEventListener("click",function(){
        window.location.replace('login.php');
    });
</script>
<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    if(isset($_POST['createAccount'])){
        $username = $_POST['username'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        if(empty($name) || empty($email) || empty($password))
        echo "<script>alert('Please complete the details!'); window.location.replace('register.php');</script>";
        include_once('connection.php');
        $otp = uniqid();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        if(mysqli_query($conn,"insert into user_tb(username, name, email, otp, password) values('$username','$name','$email','$otp','$hash')")){
            //Load Composer's autoloader
            require 'vendor/autoload.php';
            //Create an instance; passing `true` enables exceptions
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->SMTPDebug  = SMTP::DEBUG_OFF;
                //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'webBasedOrdering098@gmail.com'; //from //SMTP username
                $mail->Password   = 'cgzyificorxxdlau';                     //SMTP password
                $mail->SMTPSecure =  PHPMailer::ENCRYPTION_SMTPS;           //Enable implicit TLS encryption
                $mail->Port       =  465;                                   //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
                //Recipients
                $mail->setFrom('webBasedOrdering098@gmail.com', 'webBasedOrdering');
                $mail->addAddress("$email");                                //sent to
        
                //Content
                $mail->Subject = 'OTP';
                $mail->Body    = $otp;
        
                $mail->send();
                echo "<script>alert('OTP sent please verify your account first!'); window.location.replace('login.php');</script>";
            }catch (Exception $e) {
                echo "<script>alert('Error: $mail->ErrorInfo');</script>";
            }                          
        }
        else
        echo '<script type="text/javascript">alert("failed to save to database");window.location.replace("login.php");</script>';  
       
    }
?>
<style>
    body{
        background-color: black;
        color: white;
    }
</style>

<?php

   
?>
