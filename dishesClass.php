<?php
    class dishes{
        function getAllDishes(){
            include_once('connection.php');
			$sql = mysqli_query($conn,"select * from dishes_tb");  
            if (mysqli_num_rows($sql)) {
                $arr = array();
                while($rows = mysqli_fetch_assoc($sql)){
                    array_push($arr,$rows);
                }
                return($arr);
            }
        }
    }
?>