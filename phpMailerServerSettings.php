<?php 
    //Server settings
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host = 'mail.ucc-csd-bscs.com';		                //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'weboms@ucc-csd-bscs.com';              //from //SMTP username
    $mail->Password   = '-Dxru8*6v]z4';                         //SMTP password
    $mail->SMTPSecure = 'ssl';                                  //Enable implicit TLS encryption
    $mail->Port       =  465;       
?>