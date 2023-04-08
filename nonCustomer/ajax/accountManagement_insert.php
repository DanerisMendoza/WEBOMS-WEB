<?php 
    include('../../method/query.php');
    //initialization
    $name =  json_decode($_POST['name']);
    $username = json_decode($_POST['username']);
    $email = json_decode($_POST['email']);
    $password =  json_decode($_POST['password']);
    $accountType = json_decode($_POST['accountType']);

    if($name == '' || $username == '' || $email == '' || $password == ''){
        die("Please complete Details!");
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    //get two user id from different table
    $lastUserIdOrder = getQueryOneVal3("SELECT MAX(user_id) from weboms_order_tb","MAX(user_id)");
    $lastUserIdUserInfo = getQueryOneVal3("SELECT MAX(user_id) from weboms_userInfo_tb","MAX(user_id)");
    //compare which user id is higher 
    if($lastUserIdOrder > $lastUserIdUserInfo)
        $user_id = $lastUserIdOrder;
    else
        $user_id = $lastUserIdUserInfo;   
    // increment user id
    $user_id++;

    //validation
    $query = "select * from weboms_user_tb where username = '$username'";
    if(getQueryOneVal3($query,"username") != null)
        die ("username already exist");
    $query = "select * from weboms_userInfo_tb where name = '$name'";
    if(getQueryOneVal3($query,"name") != null)
        die ("name already exist");
    $query = "select * from weboms_userInfo_tb where email = '$email'";
    if(getQueryOneVal3($query,"email") != null)
        die ("email already exist");
  
    //insert
    $query1 = "insert into weboms_user_tb(username, password, accountType, user_id) values('$username','$hash','$accountType','$user_id')";
    $query2 = "insert into weboms_userInfo_tb(name, email, otp, user_id) values('$name','$email','','$user_id')";
    if(!Query3($query1))
        echo "Failed to save to database!";
    elseif(!Query3($query2))
        echo "Failed to save to database!";
    else
        echo "Sucess!";
?>