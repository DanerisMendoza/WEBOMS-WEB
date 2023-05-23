<?php 
    if(isset($_POST['post']) && $_POST['post'] == 'webomsMobile') {
        include_once('../method/query.php');
        $query = "CHECKSUM TABLE weboms_order_tb;";
        $resultSet = getQuery2($query); 
        if($resultSet != null){
            foreach($resultSet as $row){
                $result = array('result' => $row['Checksum']);
            }
        }
        else{
            $result = array('result' => null);
        }
        echo json_encode($result);
    }
?>

