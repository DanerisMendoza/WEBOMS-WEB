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
    
 
  
    <!-- DELETE POP UP FORM (Bootstrap MODAL) -->
    <div class="modal fade" id="deletemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Please enter your otp ?</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>

                <form action="deletecode.php" method="POST">

                    <div class="modal-body">
                       
                        <input type="hidden" name="delete_id" id="delete_id">
                        <input type="text" name="otp" id="otp">
                        

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"> Cancel </button>
                        <button type="submit" name="deletedata" class="btn btn-primary"> Submit </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <?php
        if(isset($_POST['login'])){
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
                $readQuery = "select * from user_tb where name = '$username'";
                $result = mysqli_query($conn,$readQuery);  
                if(mysqli_num_rows($result) === 1){
                    while($rows = mysqli_fetch_assoc($result)){
                        $valid = password_verify($password, $rows['password']);
                        $otp = $rows['otp'];
                    }
                    if($otp != ""){
                        //verify otp block
                        // echo "<SCRIPT>  alert('Please verify your account first!');</SCRIPT>"; 
                        echo "<script type='text/javascript'>
                        $('#deletemodal').modal('show');
                        </script>";
                    }
                    if($valid && $otp === ""){               
                        echo "<SCRIPT> window.location.replace('homePage.php?username=$username');  </SCRIPT>";
                    }
                }
                else
                    echo "<SCRIPT>alert('incorrect username or password!');</SCRIPT>"; 
            }
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

                $('#deletemodal').modal('show');

            });
        });
</script>
