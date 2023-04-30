<?php 
    include('../../method/query.php');
    $feedbackId = json_decode($_POST['feedbackId']);
    $query = "update weboms_feedback_tb set feedback = 'Deleted due to inappropriate comment' WHERE id = '$feedbackId'";
    Query3($query);
?>