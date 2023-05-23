<?php 
	if(isset($_POST['post']) && $_POST['post'] == 'webomsMobile') {
        $page = 'notLogin';
        include_once('../general/connection.php');
        include_once('../method/query.php');
        $order_id = $_POST['order_id'];
        $orders = [['dishesArr'],['priceArr'],['dishesQuantity']];
        $query = "select weboms_menu_tb.*, weboms_ordersDetail_tb.* from weboms_menu_tb inner join weboms_ordersDetail_tb where weboms_menu_tb.orderType = weboms_ordersDetail_tb.orderType and weboms_ordersDetail_tb.order_id = '$order_id' ";
        $resultSet = getQuery2($query); 
        if($resultSet != null){
            $i = 0;
            foreach($resultSet as $row){
                $orders['dishesArr'][$i] = $row['dish'];
                $orders['priceArr'][$i] = $row['price'];
                $orders['dishesQuantity'][$i] = $row['quantity'];
                $i++;
            }
            echo json_encode($orders);
        }
        else{
            echo json_encode("null");
        }
    }
?>
