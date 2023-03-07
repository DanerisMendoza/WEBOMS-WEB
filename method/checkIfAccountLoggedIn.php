<?php 
session_start();
    // account is not set and account is in account pages 
    if(!isset($_SESSION['account']) && $page != 'notLogin'){
        die ("<script>window.location.replace('../general/login.php'); alert('credential invalid!');</script>");
    }
    // account is not set and account is in non account pages
    elseif(!isset($_SESSION['account']) && $page == 'notLogin'){
        return;
    }

    // account is already login
    if($page == 'notLogin' && $_SESSION['account'] == 'valid'){
        if($_SESSION['accountType'] == 'customer'){
            if(isset($isFromLogin) == true)
                die ("<script>window.location.replace('../customer/customer.php'); alert('Already Loggedin!');</script>");
            else
                die ("<script>window.location.replace('customer/customer.php'); alert('Already Loggedin!');</script>");
        }
        else if($_SESSION['accountType'] == 'admin' || $_SESSION['accountType'] == 'manager'){
            if(isset($isFromLogin) == true)
                die ("<script>window.location.replace('../nonCustomer/admin.php'); alert('Already Loggedin!');</script>");
            else
                die ("<script>window.location.replace('nonCustomer/admin.php'); alert('Already Loggedin!');</script>");
        }
        else if($_SESSION['accountType'] == 'cashier')
            if(isset($isFromLogin) == true)
                die ("<script>window.location.replace('../nonCustomer/adminPos.php'); alert('Already Loggedin!');</script>");
            else
                die ("<script>window.location.replace('nonCustomer/adminPos.php'); alert('Already Loggedin!');</script>");
    }

    //account is not customer
    if($page == 'customer' && $_SESSION['accountType'] != 'customer'){
        die ("<script>window.location.replace('../general/login.php'); alert('credential invalid!');</script>");
    }

    //account is not customer or admin or manager
    elseif($page == 'feedback' && ($_SESSION['accountType'] != 'admin' && $_SESSION['accountType'] != 'manager' && $_SESSION['accountType'] != 'customer')){
        die ("<script>window.location.replace('../general/login.php'); alert('credential invalid!');</script>");
    }

    //account is not admin or manager
    elseif($page == 'admin' && ($_SESSION['accountType'] != 'admin' && $_SESSION['accountType'] != 'manager')){
        die ("<script>window.location.replace('../general/login.php'); alert('credential invalid!');</script>");
    }

    //account is not cashier
    elseif($page == 'cashier' && ($_SESSION['accountType'] != 'admin' && $_SESSION['accountType'] != 'cashier'  && $_SESSION['accountType'] != 'manager')){
        die ("<script>window.location.replace('../general/login.php'); alert('credential invalid!');</script>");
    }

    //account is not customer or admin or manager or cashier
    elseif($page == 'receipt' && ($_SESSION['accountType'] != 'admin' && $_SESSION['accountType'] != 'manager' && $_SESSION['accountType'] != 'customer' && $_SESSION['accountType'] != 'cashier')){
        die ("<script>window.location.replace('../general/login.php'); alert('credential invalid!');</script>");
    }

?>