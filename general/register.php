<?php     
  $page = 'notLogin';
  include('../method/checkIfAccountLoggedIn.php'); 
  include_once('connection.php');
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign up</title>

    <link rel="stylesheet" type="text/css" href="../css/bootstrap 5/bootstrap.min.css">
    <script type="text/javascript" src="../js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap 5/bootstrap.min.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>

<body class="bg-dark">

    <div class="container mt-4">
        <div class="row g-5 justify-content-center h-100">
            <div class="col-lg-12 col-xl-8">
                <div class="card" style="border-radius: 1rem;">
                    <div class="card-body p-4 p-md-5">
                        <form method="post" class="form2 px-md-2">
                            <h1 class="fw-normal text-center mb-4">Register your account</h1>
                            <!-- first name -->
                            <div class="input-group mb-4">
                                <input type="text" class="form-control form-control-lg" name="firstName" placeholder="First Name" value="<?php echo isset($_POST['firstName']) ? $_POST['firstName']: ''?>" required>
                            </div>
                            <!-- middle name -->
                            <div class="input-group mb-4">
                                <input type="text" class="form-control form-control-lg" name="middleName" placeholder="Middle Name" value="<?php echo isset($_POST['middleName']) ? $_POST['middleName']: ''?>">
                            </div>
                            <!-- last name --> 
                            <div class="input-group mb-4">
                                <input type="text" class="form-control form-control-lg" name="lastName" placeholder="Last Name" value="<?php echo isset($_POST['lastName']) ? $_POST['lastName']: ''?>" required>
                            </div>
                            <!-- email -->
                            <div class="input-group mb-4">
                                <input type="email" class="form-control form-control-lg" name="email" placeholder="Email" value="<?php echo isset($_POST['email']) ? $_POST['email']: ''?>" required>
                            </div>
                            <!-- username -->
                            <div class="input-group mb-4">
                                <input type="text" class="form-control form-control-lg" name="username" placeholder="Username" value="<?php echo isset($_POST['username']) ? $_POST['username']: ''?>" required>
                            </div>
                            <!-- password -->
                            <div class="input-group mb-4">
                                <input type="password" class="form-control form-control-lg" name="password" placeholder="Password" required>
                            </div>
                            <div class="row mb-4">
                                <!-- gender -->
                                <div class="form-outline col-6">
                                    <select name='gender' class="form-control form-control-lg">
                                        <option value="null">Gender</option>
                                        <option value="m">Male</option>
                                        <option value="f">Female</option>
                                        <option value="NA">Rather Not Say</option>
                                    </select>
                                </div>
                                <!-- age -->
                                <div class="form-outline col-6">
                                    <input type="number" class="form-control form-control-lg" name="age" placeholder="Age" value="<?php echo isset($_POST['age']) ? $_POST['age']: ''?>" required>
                                </div>
                            </div>
                            <!-- address -->
                            <div class="input-group mb-4">
                                <input type="text" class="form-control form-control-lg" name="address" placeholder="Address" value="<?php echo isset($_POST['address']) ? $_POST['address']: ''?>" required>
                            </div>
                            <!-- phone number -->
                            <div class="input-group mb-4">
                                <input type="number" class="form-control form-control-lg" id="phone" name="phone" placeholder="Phone Number" value="<?php echo isset($_POST['phone']) ? $_POST['phone']: ''?>" required>
                            </div>
                            <!-- create account button -->
                            <div class="input-group mb-4">
                                <button type="submit" class="btn btn-dark btn-lg col-12" id="createAccount" name="createAccount">
                                    Create Account
                                </button>
                            </div>
                            <!-- login here -->
                            <div class="text-center text-muted">
                                Have already an account? <a href="../general/login.php" class="login_link text-muted text-decoration-none">Log in here</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>


<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    if(isset($_POST['createAccount'])){
        include('../method/query.php');
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

     

        $query = "select * from weboms_user_tb where username = '$username'";
        if(getQuery2($query) != null)
          die ("<script>alert('Name already exist!');</script>");
        $query = "select * from weboms_userInfo_tb where name = '$name'";
        if(getQuery2($query) != null)
          die ("<script>alert('Name already exist!');</script>");
        $query = "select * from weboms_userInfo_tb where email = '$email'";
        if(getQuery2($query) != null)
          die ("<script>alert('Email already exist!');</script>");
        
        $otp = uniqid();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        //get two user id from different table
        $lastUserIdOrder = getQueryOneVal2("SELECT MAX(user_id) from weboms_order_tb","MAX(user_id)");
        $lastUserIdUserInfo = getQueryOneVal2("SELECT MAX(user_id) from weboms_userInfo_tb","MAX(user_id)");
        //compare which user id is higher 
        if($lastUserIdOrder > $lastUserIdUserInfo)
            $user_id = $lastUserIdOrder;
        else
            $user_id = $lastUserIdUserInfo;   
        // increment user id
        $user_id++;

        $query1 = "insert into weboms_user_tb(username, password, accountType, user_id) values('$username','$hash','customer','$user_id')";
        $query2 = "insert into weboms_userInfo_tb(name, gender, age, phoneNumber, address, email, otp, user_id, balance) values('$name','$gender','$age','$phone','$address','$email','$otp','$user_id',0)";
        if(!Query2($query1))
          echo "Failed to save to database!";
        elseif(!Query2($query2))
          echo "Failed to save to database!";
        else{
            echo "<script>window.location.replace('../general/login.php'); alert('OTP sent! Please verify your account first!');</script>";
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
            $mail->addAddress("$email");                                //sent to
            //Content
            $mail->Subject = 'OTP';
            $mail->Body    = "Good Day, ".$name." \n \nWe would like to inform you that you have created an account and you need to verify your account first using this OTP: ". $otp ."\n \nThank You!";
            $mail->send();
        }
        
    }
?>
<script>
//cut phone num if excess
$("#phone").bind("change paste input", function() {
    var phone = ''+document.forms[0].phone.value;
    if (phone.length >= 11 || !isNaN(phone) ) {
        document.forms[0].phone.value = phone.substring(0, 11);
    }
});
</script>