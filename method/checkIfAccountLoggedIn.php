<?php 
session_start();
if(!isset($_SESSION['username']) || isset($_SESSION['account']) != 'valid'){
    die ("<script>window.location.replace('Login.php'); alert('credential invalid!');</script>");
}

?>