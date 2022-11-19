<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Sign up</title>

  <link rel="stylesheet" type="text/css" href="css/bootstrap.css"></head>
    
</head>
<body class="bg-light">

<div class="container">
  <div class="row justify-content-center">
    <form method="post" class="form2">
      <h1 class="font-weight-normal mt-5 mb-4 text-center">Register your account</h1>
      <input type="text" class="form-control form-control-lg mb-3" name="username" placeholder="Enter Username" required>
      <input type="text" class="form-control form-control-lg mb-3" name="name" placeholder="Enter Name" required>
      <input type="email" class="form-control form-control-lg mb-3" name="email" placeholder="Enter Email" required>
      <input type="password" class="form-control form-control-lg mb-3" name="password" placeholder="Enter Password" required>
      <button type="submit" class="btn btn-primary btn-lg col-12 mb-3" name="createAccount">Sign Up</button><br>
      <div class="mb-5 text-muted text-center">Have already an account? <a href="Login.php" class="login_link text-muted">Log in</a>
      </div>
    </form>
  </div>
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
        include('method/query.php');
        
        //validation
        $query = "select * from user_tb where username = '$username'";
        if(getQuery($query) != null)
          die ("<script>alert('Name Already Exist!');</script>");
        $query = "select * from userInfo_tb where name = '$name'";
        if(getQuery($query) != null)
          die ("<script>alert('Name Already Exist!');</script>");
        $query = "select * from userInfo_tb where email = '$email'";
        if(getQuery($query) != null)
          die ("<script>alert('Email Already Exist!');</script>");
        
        $otp = uniqid();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        //Load Composer's autoloader
        require 'vendor/autoload.php';
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);
        //Server settings
        $mail->SMTPDebug  = SMTP::DEBUG_OFF;
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                     //Enable verbose debug output
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

      $userLinkId = uniqid('',true);
      $query1 = "insert into user_tb(username, password, accountType, userLinkId) values('$username','$hash','customer','$userLinkId')";
      $query2 = "insert into userInfo_tb(name, email, otp, userLinkId) values('$name','$email','$otp','$userLinkId')";
      if(!Query($query1))
        echo "fail to save to database";
      elseif(!Query($query2))
        echo "fail to save to database";
      else
        echo "<script>window.location.replace('login.php'); alert('OTP sent please verify your account first!');</script>";
    }
?>