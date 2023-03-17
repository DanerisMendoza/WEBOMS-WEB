<?php 
    include_once('../general/connection.php');
    include_once('../method/query.php');
    if(isset($_POST['post']) == 'webomsMobile') {
        $dishesArr = $priceArr = $stockArr = $orderTypeArr = $aArray = $picNameArr = array();
        $query = "SELECT * FROM `weboms_menu_tb`";
        $resultSet = getQuery2($query); 
        if($resultSet != null){
            foreach($resultSet as $row){
                array_push($dishesArr,$row['dish']);
                array_push($priceArr,$row['price']);
                array_push($stockArr,$row['stock']);
                array_push($orderTypeArr,$row['orderType']);
                array_push($picNameArr,$row['picName']);
                $a = $row['dish'].",".$row['price'].",".$row['orderType'].",".$row['stock'];
                array_push($aArray,$a);
            }
            $dishesArr = implode(",",$dishesArr);
            $priceArr = implode(",",$priceArr);
            $picNameArr = implode(",",$picNameArr);
            $result = array('dishesArr' => $dishesArr,'priceArr' => $priceArr, 'picNameArr' => $picNameArr);
        }
        else{
            $result = array('result' => 'null');
        }
        echo json_encode($result);
    }
    else {
		echo "unauthorized access.";
	}

?>

