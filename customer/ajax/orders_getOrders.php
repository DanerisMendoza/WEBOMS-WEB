<?php 
    include('../../method/query.php');
    $user_id = json_decode($_POST['user_id']);
    $orders = [['name'],['order_id'],['status'],['date'],['isAllowToFeedback'],['user_id']];
    $selectOrders = "select a.name, a.email, b.* from weboms_userInfo_tb a inner join weboms_order_tb b on a.user_id = b.user_id where a.user_id = '$user_id' order by b.id desc;";
    $resultSet = getQuery3($selectOrders); 
    if($resultSet != null){
        $i = 0;
        foreach($resultSet as $row){
            $orders['name'][$i] = $row['name'];
            $orders['order_id'][$i] = $row['order_id'];
            $orders['status'][$i] = $row['status'];
            $orders['date'][$i] = $row['date'];
            $orders['user_id'][$i] = $row['user_id'];

            $order_id = $row['order_id'];
            $user_id = $row['user_id'];

            $checkIfAlreadyFeedback = "SELECT * FROM weboms_feedback_tb WHERE order_id='$order_id' AND user_id = '$user_id' ";
            $resultSet = getQuery3($checkIfAlreadyFeedback);

            if($row['status'] == 'complete' && $resultSet == null){
                $orders['isAllowToFeedback'][$i] = 'Allowed';
            }
            else if($row['status'] == 'complete'){
                $orders['isAllowToFeedback'][$i] = 'Feedback already sent!';
            }
            else if($row['status'] == 'preparing' || $row['status'] == 'serving'){
                $orders['isAllowToFeedback'][$i] = 'Please wait until order is complete!';
            }
            else if($row['status'] == 'void'){
                $orders['isAllowToFeedback'][$i] = 'Order is void!';
            }
            $i++;
        }
        echo json_encode($orders);
    }
    else{
        echo json_encode("null");
    }
?>

