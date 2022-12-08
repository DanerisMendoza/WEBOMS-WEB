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

    <link rel="stylesheet" type="text/css" href="css/bootstrap 5/bootstrap.min.css">
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="js/bootstrap 5/bootstrap.min.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>

<body class="bg-dark">

    <section class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-lg-8 col-xl-6">
                <div class="card" style="border-radius: 1rem;">
                    <div class="card-body p-4 p-md-5">

                        <form method="post" class="form2 px-md-2">

                            <h1 class="fw-normal mb-5 text-center">Register your account</h1>

                            <div class="input-group mb-4">
                                <input type="text" class="form-control form-control-lg" name="username"
                                    placeholder="Username"
                                    value="<?php echo isset($_POST['username']) ? $_POST['username']: ''?>" required>
                            </div>

                            <div class="input-group mb-4">
                                <input type="text" class="form-control form-control-lg" name="firstName"
                                    placeholder="First Name"
                                    value="<?php echo isset($_POST['firstName']) ? $_POST['firstName']: ''?>" required>
                            </div>

                            <div class="input-group mb-4">
                                <input type="text" class="form-control form-control-lg" name="middleName"
                                    placeholder="Middle Name"
                                    value="<?php echo isset($_POST['middleName']) ? $_POST['middleName']: ''?>">
                            </div>

                            <div class="input-group mb-4">
                                <input type="text" class="form-control form-control-lg" name="lastName"
                                    placeholder="Last Name"
                                    value="<?php echo isset($_POST['lastName']) ? $_POST['lastName']: ''?>" required>
                            </div>

                            <div class="row">
                                <div class="form-outline mb-4 col-6">
                                    <select name='gender' class="form-control form-control-lg">
                                        <option value="null">Gender</option>
                                        <option value="m">Male</option>
                                        <option value="f">Female</option>
                                        <option value="NA">Rather Not Say</option>
                                    </select>
                                </div>

                                <div class="form-outline mb-4 col-6">
                                    <input type="number" class="form-control form-control-lg" name="age"
                                        placeholder="Age" value="<?php echo isset($_POST['age']) ? $_POST['age']: ''?>"
                                        required>
                                </div>
                            </div>

                            <div class="input-group mb-4">
                                <input type="number" class="form-control form-control-lg" id="phone" name="phone"
                                    placeholder="Phone Number"
                                    value="<?php echo isset($_POST['phone']) ? $_POST['phone']: ''?>" required>
                            </div>

                            <div class="input-group mb-4">
                                <input type="text" class="form-control form-control-lg" name="address"
                                    placeholder="Address"
                                    value="<?php echo isset($_POST['address']) ? $_POST['address']: ''?>" required>
                            </div>

                            <div class="input-group mb-4">
                                <input type="email" class="form-control form-control-lg" name="email"
                                    placeholder="Email"
                                    value="<?php echo isset($_POST['email']) ? $_POST['email']: ''?>" required>
                            </div>

                            <div class="input-group mb-4">
                                <input type="password" class="form-control form-control-lg" name="password"
                                    placeholder="Password" required>
                            </div>

                            <div class="input-group mb-4">
                                <button type="submit" class="btn btn-dark btn-lg col-12" id="createAccount"
                                    name="createAccount">
                                    Create Account
                                </button>
                            </div>

                            <div class="text-center text-muted">
                                Have already an account? <a href="Login.php"
                                    class="login_link text-muted text-decoration-none">Log in
                                    here</a>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>

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
        include('phpMailerServerSettings.php');
        //Recipients
        $mail->setFrom('weboms098@gmail.com', 'webBasedOrdering');
        $mail->addAddress("$email");                                //sent to
        //Content
        $mail->Subject = 'OTP';
        $mail->Body    = "Good Day! "."$name"." \nWe would like to inform you that you create an account and you need to verify your account first using this OTP: ".$otp ."\nTHANK YOU!";
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
//cut phone num if excess
$("#phone").bind("change paste input", function() {
    var phone = document.forms[0].phone.value;
    if (phone.length >= 11) {
        document.forms[0].phone.value = phone.substring(0, 11);
    }
});
</script>