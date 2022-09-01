<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> PHP CRUD with Bootstrap Modal </title>
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
    <form method="post" class="text-center col-6 margin_top">
            <input class="margin" type="text" name="username" placeholder="username" ></br>
            <input class="margin" type="password" name="password" placeholder="password" ></br>
            <input class="margin" type="submit" name="login" value="login">  
            <input class="margin" type="button" id="register" value="Register">  
    </form>
    
 
  
    <!-- otp (Bootstrap MODAL) -->
    <div class="modal fade" id="otpModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog">
            <div class="modal-content container">
                <div class="modal-body">
                    <form method="post">
                        <h3>Please Enter your OTP:</h3>
                        <input type="text" placeholder="otp" name="otp">          
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
                    echo "<SCRIPT> window.location.replace('admin.php'); alert('Welcome Admin!'); </SCRIPT>";
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
                    }
                    if($valid && $valid && $otp === ""){               
                        echo "<SCRIPT> window.location.replace('homePage.php?username=$username');  </SCRIPT>";
                    }
                    else if($valid && $otp != ""){
                        echo "<script type='text/javascript'>$('#otpModal').modal('show');</script>";
                        // echo "<script type='text/javascript'>$('#otpModal').data-toggle('modal');</script>";
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
    .margin{
        margin: 2px;
    }
    .margin_top{
        margin-top: 10px;
    }
    body{
    background-color: black;
    color: white;
    }
</style>

<script>
        document.getElementById("register").addEventListener("click",function(){
            window.location.replace('register.php');
        });
        $(document).ready(function () {

            $('.deletebtn').on('click', function () {

                $('#otpModal').modal('show');

            });
        });
</script>
