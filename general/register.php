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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/register.css">
    <link rel="icon" href="../image/weboms.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.min.js"></script>
</head>
<body>

    <div class="container">
        <div class="card">
            <label for="" class="register">Register your account</label>
            <form action="" method="post" class="form2">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="First Name" name="firstName" value="<?php echo isset($_POST['firstName']) ? $_POST['firstName']: ''?>" required>
                    <input type="text" class="form-control" placeholder="Middle Name" name="middleName" value="<?php echo isset($_POST['middleName']) ? $_POST['middleName']: ''?>" required>
                    <input type="text" class="form-control" placeholder="Last Name" name="lastName" value="<?php echo isset($_POST['lastName']) ? $_POST['lastName']: ''?>" required>
                </div>
                <div class="input-group">
                    <input type="email" class="form-control" placeholder="Email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email']: ''?>" required>
                    <input type="number" class="form-control" placeholder="Phone Number" name="phone" id="phone" value="<?php echo isset($_POST['phone']) ? $_POST['phone']: ''?>" required>
                </div>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Username" name="username" value="<?php echo isset($_POST['username']) ? $_POST['username']: ''?>" required>
                    <input type="password" class="form-control" placeholder="Password" name="password">
                </div>
                <div class="input-group">
                    <div class="form-outline col-sm-6">
                        <select name="gender" id="" class="form-control">
                            <option value="nulll">Gender</option>
                            <option value="m">Male</option>
                            <option value="f">Female</option>
                            <option value="NA">Rather Not Say</option>
                        </select>
                    </div>
                    <input type="number" class="form-control" placeholder="Age" name="age" value="<?php echo isset($_POST['age']) ? $_POST['age']: ''?>" required>
                </div>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Address" name="address" value="<?php echo isset($_POST['address']) ? $_POST['address']: ''?>" required>
                </div>
                <button type="submit" class="btn btn-create-account col-12" name="createAccount" id="createAccount">Create Account</button>
            </form>
            <label for="" class="account">Have an account already? <a href="login.php" class="login-here">Login here.</a></label>
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
        
        $otp = mt_rand(1000, 9999);
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
            $mail->setFrom('weboms@ucc-csd-bscs.com', 'webBasedOrdering');
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