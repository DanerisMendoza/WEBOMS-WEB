<?php 
session_start();
// account not valid
if(isset($_SESSION['account']) && $_SESSION['account'] != 'valid'  && $page != 'notLogin'){
    die ("<script>window.location.replace('Login.php'); alert('credential invalid!');</script>");
}

// account is already login
if($page == 'notLogin' && $_SESSION['account'] == 'valid'){
    if($_SESSION['accountType'] == 'customer')
        die ("<script>window.location.replace('customer.php'); alert('Already Loggedin!');</script>");
    else if($_SESSION['accountType'] == 'admin' || $_SESSION['accountType'] == 'manager')
        die ("<script>window.location.replace('admin.php'); alert('Already Loggedin!');</script>");
    else if($_SESSION['accountType'] == 'cashier')
        die ("<script>window.location.replace('adminPos.php'); alert('Already Loggedin!');</script>");
}

//account is not customer
if($page == 'customer' && $_SESSION['accountType'] != 'customer'){
    die ("<script>window.location.replace('Login.php'); alert('credential invalid!');</script>");
}

//account is not customer or admin or manager
elseif($page == 'feedback' && ($_SESSION['accountType'] != 'admin' && $_SESSION['accountType'] != 'manager' && $_SESSION['accountType'] != 'customer')){
    die ("<script>window.location.replace('Login.php'); alert('credential invalid!');</script>");
}

//account is not admin or manager
elseif($page == 'admin' && ($_SESSION['accountType'] != 'admin' && $_SESSION['accountType'] != 'manager')){
    die ("<script>window.location.replace('Login.php'); alert('credential invalid!');</script>");
}

//account is not cashier
elseif($page == 'cashier' && ($_SESSION['accountType'] != 'admin' && $_SESSION['accountType'] != 'cashier'  && $_SESSION['accountType'] != 'manager')){
    die ("<script>window.location.replace('Login.php'); alert('credential invalid!');</script>");
}

?>