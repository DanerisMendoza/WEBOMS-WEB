<?php
    function getQuery($query){
        include('connection.php');
        if($resultSet = $conn->query($query)){  
            if($resultSet->num_rows > 0){
                return($resultSet);
            }
            else{
                return null;
            }
        }
        else{
            return $resultSet->error;
        }
    }


    function Query($query){
        include('connection.php');
        if($conn->query($query)){
            return true;
        }
        else{
            return $conn->error;
        }
    }

?>