
<!DOCTYPE html>
<html><head><title></title></head>
<body>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
<?php include_once('connection.php');?>
<div class="container"><div class="row justify-content-center">
    <form method="post" class="text-center col-6 margin_top">
            <input class="margin" type="text" name="username" placeholder="username" ></br>
            <input class="margin" type="password" name="password" placeholder="password" ></br>
            <input class="margin" type="submit" name="login" value="login">  
    </form>
    <?php
        if(isset($_POST['login'])){
            if($_POST['username'] === 'admin'){
            $username = $_POST['username'];
            $password = $_POST['password'];
            if(empty($username) || empty($password)){ 
                echo '<script type="text/javascript">alert("Please complete details!");</script>'; 
                return;
            }
            $readQuery = "select * from admin_tb";
            $sql = mysqli_query($conn,$readQuery);  
            while($rows = mysqli_fetch_assoc($sql)){
                $valid = password_verify($password, $rows['password']);
            }
            if($valid)
                echo "<SCRIPT> window.location.replace('admin.php'); alert('Welcome Admin!'); </SCRIPT>";
            }     
            echo "<SCRIPT>  window.location.replace('login.php'); alert('incorrect username or password!');</SCRIPT>";         
        }

    ?>
</div></div>
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

