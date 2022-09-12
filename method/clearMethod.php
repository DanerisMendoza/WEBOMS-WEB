<?php
    session_start();
    $_SESSION["dishes"] = array();
    $_SESSION["price"] = array();
    $_SESSION["orderType"] = array(); 
    // header("Refresh:1");
    // echo "<script> window.location.replace('cart.php');</script>";
    // echo '<script type="text/JavaScript"> cart.reload(); </script>';
?>