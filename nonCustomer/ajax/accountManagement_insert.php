<?php 
    include('../../method/query.php');
    //update button
    $username = json_decode($_POST['username']);
    $email = json_decode($_POST['email']);
    $password = json_decode($_POST['password']);

    // hash first
    $hash = password_hash($password, PASSWORD_DEFAULT);

    //validation
    $query = "select name from weboms_userInfo_tb a INNER join weboms_user_tb b on a.user_id = b.user_id where email = '$email' and username != '$username';";
    if(getQuery3($query) != null )
        die ("email already exist");

    // // update db
    $query = "update weboms_user_tb as a inner join weboms_userInfo_tb as b on a.user_id = b.user_id SET password = '$hash', email = '$email' WHERE username='$username' ";
    Query3($query);
?>