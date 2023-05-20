<?php 
	if(isset($_POST['post']) && $_POST['post'] == 'webomsMobile') {
        $page = 'notLogin';
        include_once('../general/connection.php');
        include_once('../method/query.php');
        $user_id = $_POST['user_id'];
        $arr = ['name'=>[],'amount'=>[],'status'=>[],'date'=>[],'proofOfPayment'=>[],'id'=>[],'user_id'=>[]];
        $query = "SELECT a.*,b.name FROM `weboms_topUp_tb` a INNER JOIN weboms_userInfo_tb b ON a.user_id = b.user_id WHERE a.user_id = '$user_id'";
        $resultSet = getQuery2($query);
        if($resultSet != null){
            foreach($resultSet as $row){
                array_push($arr['name'], ucwords($row['name']));
                array_push($arr['amount'], "₱".number_format($row['amount'], 2));
                array_push($arr['status'], ucwords($row['status']));
                array_push($arr['date'], date('m/d/Y h:i a', strtotime($row['date'])));
                $proofOfPayment;
                if($row['proofOfPayment'] == ''){
                    $proofOfPayment = 'Payment Through RFID';
                }
                else{
                    $proofOfPayment = 'Online Topup Request';
                }
                array_push($arr['proofOfPayment'], $proofOfPayment);
                array_push($arr['user_id'], $row['user_id']);
            }
            echo json_encode($arr);
        }
        else{
            echo json_encode(null);
        }
    }
?>
