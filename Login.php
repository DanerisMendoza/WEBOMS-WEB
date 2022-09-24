<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Login </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>

</head>
<body>
<?php include_once('connection.php');?>
<div class="container"><div class="row justify-content-center">

    <form  method="post" class="text-center col-6 margin_top form1" >
            <img src="settings/logo.png"><br>
            <input class="margin" type="text" name="username" placeholder="Username" ></br>
            <input class="margin" type="password" name="password" placeholder="Password" ></br>
            <button class="margin" type="submit" name="login" value="login">Login</button><br>
            <div class="pass">Forgot Password?</div>
            <div class="signup_link">
              Not a member? <a href="register.php">Signup now</a>
            </div>
    </form>

    <!-- otp (Bootstrap MODAL) -->
    <div class="modal fade" id="otpModal" role="dialog" >
        <div class="modal-dialog">
            <div class="modal-content container">
                <div class="modal-body">
                    <form method="post" class="form-group">
                        <h3>Please Enter your OTP:</h3>
                        <input type="text" class="form-control" placeholder="otp" name="otp" >          
                        <input data-dismiss="modal" type="submit" value="Cancel" name="Cancel">
                        <input type="submit" value="Resend" name="Resend">
                        <input type="submit" value="Verify" name="Verify">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
       
        if(isset($_POST['login'])){
            $_SESSION["username"]  = $_POST['username'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            if(empty($username) || empty($password)){
                echo '<script type="text/javascript">alert("Please complete details!");</script>';
                echo "<script>window.location.replace('login.php')</script>";
                return;
            }
            //admin block
            if($_POST['username'] === 'admin'){
                $readQuery = "select * from admin_tb";
                $sql = mysqli_query($conn,$readQuery);
                while($rows = mysqli_fetch_assoc($sql)){
                    $valid = password_verify($password, $rows['password']);
                }
                if($valid)
                    echo "<SCRIPT> window.location.replace('admin.php');</SCRIPT>";
                else
                    echo "<SCRIPT>  window.location.replace('login.php'); alert('incorrect username or password!');</SCRIPT>";
            }
            else{ //user block
                $readQuery = "select * from user_tb where username = '$username'";
                $result = mysqli_query($conn,$readQuery);
                if(mysqli_num_rows($result) === 1){
                    while($rows = mysqli_fetch_assoc($result)){
                        $valid = password_verify($password, $rows['password'])?true:false;
                        $otp = $rows['otp'];
                        $userlinkId = $rows['userlinkId'];
                    }
                    if($valid && $otp == ""){
                        $_SESSION['userlinkId'] = $userlinkId;
                        echo "<SCRIPT> window.location.replace('homePage.php?username=$username');  </SCRIPT>";
                    }
                    else if($valid && $otp != ""){
                        echo "<script type='text/javascript'>$('#otpModal').modal('show');</script>";
                    }
                    else
                        echo "<SCRIPT>alert('incorrect username or password!');</SCRIPT>";
                }
                else
                    echo "<SCRIPT>alert('incorrect username or password!');</SCRIPT>";
            }
        }
        if(isset($_POST['Verify'])){
            $username = $_SESSION["username"];
            $otp = $_POST['otp'];
            $readQuery = "select * from user_tb where username = '$username' && otp = '$otp' ";
            $result = mysqli_query($conn,$readQuery);
            if(mysqli_num_rows($result) === 1){
                while($rows = mysqli_fetch_assoc($result))
                    $_SESSION['userlinkId'] = $rows['userlinkId'];
                $updateQuery = "UPDATE user_tb SET otp='' WHERE otp='$otp'";
                if(mysqli_query($conn, $updateQuery))
                    echo "<SCRIPT> window.location.replace('homePage.php?username=$username'); </SCRIPT>";
            }else
            echo  '<script type="text/javascript">alert("Incorrect Otp!"); window.location.replace("login.php");</script>';
        }

    ?>


</body>
</html>

<style>
@import url('https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap');
body{
  background-image: url(settings/bg.jpg); /* #ED212D */
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-position: center;
  max-width: 100%;
  width: auto;
  height: auto;
  font-family: 'Josefin Sans', sans-serif;
}
/* Restaurant Logo */
img{
  max-width: 50%;
  height: auto;
  width: auto;
}
/* Container form1 */
.form1{
  background: gray;
  position: absolute;
  top: 48%;
  left: 50%;
  transform: translate(-50%, -50%);
  padding: 15px 50px 15px;
  height: auto;
  border-radius: 15px;
  /* box-shadow: 5px 7px black; */
}
/* Username & Password */
input[type="text"],
input[type="password"]{
  width: 100%;
  border-radius: 5px;
  border-color: transparent;
  padding: 10px 25px;
  cursor: pointer;
  font-size: 20px;
}
.margin{
  margin: 10px 0 0;
}
/* Login Button */
button{
  display: block;
  width: 100%;
  border-radius: 5px;
  border-color: transparent;
  padding: 10px 25px;
  cursor: pointer;
  font-size: 18px;
}
button:hover{
  border-color: #a6a6a6;
  transition: .2s;
}
/* Forgot Password? */
.pass{
  color: #a6a6a6;
  cursor: pointer;
  font-size: 18px;
}
.pass:hover{
  text-decoration: underline;
}
/* Signup Link */
.signup_link{
  color: white;
  font-size: 18px;
}
</style>
