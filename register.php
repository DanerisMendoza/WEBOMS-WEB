<?php     
  $page = 'notLogin';
  include('method/checkIfAccountLoggedIn.php'); 
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign up</title>

    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
</head>
<script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
</head>

<body class="bg-light">

    <div class="container">
        <div class="row justify-content-center">
            <form method="post" class="form2">
                <h1 class="font-weight-normal mt-5 mb-4 text-center">Register your account</h1>
                <input type="text" class="form-control form-control-lg mb-3" name="username"
                    placeholder="Enter Username" value="<?php echo isset($_POST['username']) ? $_POST['username']: ''?>"
                    required>
                <input type="text" class="form-control form-control-lg mb-3" name="firstName" placeholder="First Name"
                    value="<?php echo isset($_POST['firstName']) ? $_POST['firstName']: ''?>" required>
                <input type="text" class="form-control form-control-lg mb-3" name="middleName" placeholder="Middle Name"
                    value="<?php echo isset($_POST['middleName']) ? $_POST['middleName']: ''?>">
                <input type="text" class="form-control form-control-lg mb-3" name="lastName" placeholder="Last Name"
                    value="<?php echo isset($_POST['lastName']) ? $_POST['lastName']: ''?>" required>
                <select name='gender' class="form-control form-control-lg mb-3">
                    <option value="null">Gender</option>
                    <option value="m">Male</option>
                    <option value="f">Female</option>
                    <option value="NA">Rather Not Say</option>
                </select>
                <input type="number" class="form-control form-control-lg mb-3" name="age" placeholder="Age"
                    value="<?php echo isset($_POST['age']) ? $_POST['age']: ''?>" required>
                <input type="number" class="form-control form-control-lg mb-3" id="phone" name="phone"
                    placeholder="Phone Number" value="<?php echo isset($_POST['phone']) ? $_POST['phone']: ''?>"
                    required>
                <input type="text" class="form-control form-control-lg mb-3" name="address" placeholder="Address"
                    value="<?php echo isset($_POST['address']) ? $_POST['address']: ''?>" required>
                <input type="email" class="form-control form-control-lg mb-3" name="email" placeholder="Enter Email"
                    value="<?php echo isset($_POST['email']) ? $_POST['email']: ''?>" required>
                <input type="password" class="form-control form-control-lg mb-3" name="password"
                    placeholder="Enter Password" required>
                <button type="submit" class="btn btn-primary btn-lg col-12 mb-3" id="createAccount"
                    name="createAccount">Sign Up</button><br>
                <div class="mb-5 text-muted text-center">Have already an account? <a href="Login.php"
                        class="login_link text-muted">Log in</a>
                </div>
            </form>
        </div>
    </div>

</body>

</html>


<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    if(isset($_POST['createAccount'])){
        include('method/query.php');
        //init
        $username = $_POST['username'];
        $name = ucfirst($_POST['firstName'])." ".ucfirst($_POST['middleName'])." ".ucfirst($_POST['lastName']);
        $gender = $_POST['gender'];
        $age = $_POST['age'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        
     
        if($gender == "null"){
          echo "<script>alert('Please pick a gender!');</script>";
          return;
        }


        $query = "select * from WEBOMS_user_tb where username = '$username'";
        if(getQuery($query) != null)
          die ("<script>alert('Name Already Exist!');</script>");
        $query = "select * from WEBOMS_userInfo_tb where name = '$name'";
        if(getQuery($query) != null)
          die ("<script>alert('Name Already Exist!');</script>");
        $query = "select * from WEBOMS_userInfo_tb where email = '$email'";
        if(getQuery($query) != null)
          die ("<script>alert('Email Already Exist!');</script>");
        
        $otp = uniqid();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        //Load Composer's autoloader
        require 'vendor/autoload.php';
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);
        //Server settings
        $mail->SMTPDebug  = SMTP::DEBUG_OFF;                        //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host = 'mail.ucc-csd-bscs.com';		                  //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'weboms@ucc-csd-bscs.com';              //from //SMTP username
        $mail->Password   = '-Dxru8*6v]z4';                         //SMTP password
        $mail->SMTPSecure = 'ssl';                                  //Enable implicit TLS encryption
        $mail->Port       =  465;                                   //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('weboms098@gmail.com', 'webBasedOrdering');
        $mail->addAddress("$email");                                //sent to
        //Content
        $mail->Subject = 'OTP';
        $mail->Body    = "Good Day $name We would like to inform you that you create an account\nAnd you need to verify your account first using this OTP: ".$otp ."\nTHANK YOU!";
        $mail->send();

        $user_id = uniqid('',true);
        $query1 = "insert into WEBOMS_user_tb(username, password, accountType, user_id) values('$username','$hash','customer','$user_id')";
        $query2 = "insert into WEBOMS_userInfo_tb(name, gender, age, phoneNumber, address, email, otp, user_id, balance) values('$name','$gender','$age','$phone','$address','$email','$otp','$user_id',0)";
        if(!Query($query1))
          echo "fail to save to database";
        elseif(!Query($query2))
          echo "fail to save to database";
        else
          echo "<script>window.location.replace('Login.php'); alert('OTP sent please verify your account first!');</script>";
        
    }
?>
<script>
//cut phone nu
$("#phone").bind("change paste input", function() {
    var phone = document.forms[0].phone.value;
    if (phone.length >= 11) {
        document.forms[0].phone.value = phone.substring(0, 11);
    }
});
</script>