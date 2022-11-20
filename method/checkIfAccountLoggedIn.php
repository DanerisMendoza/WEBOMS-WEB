<?php 
session_start();
//not valid
if(!isset($_SESSION['username']) || isset($_SESSION['account']) != 'valid'){
    die ("<script>window.location.replace('Login.php'); alert('credential invalid!');</script>");
}

//not customer
if($page == 'customer' && $_SESSION['accountType'] != 'customer'){
    die ("<script>window.location.replace('Login.php'); alert('credential invalid!');</script>");
}

//not admin or manager
elseif($page == 'admin' && ($_SESSION['accountType'] != 'admin' && $_SESSION['accountType'] != 'manager')){
    die ("<script>window.location.replace('Login.php'); alert('credential invalid!');</script>");
}

//not cashier
elseif($page == 'cashier' && ($_SESSION['accountType'] != 'admin' && $_SESSION['accountType'] != 'cashier'  && $_SESSION['accountType'] != 'manager')){
    die ("<script>window.location.replace('Login.php'); alert('credential invalid!');</script>");
}

?>