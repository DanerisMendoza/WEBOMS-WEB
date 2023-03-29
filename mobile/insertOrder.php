<?php 
    include('../method/query.php');


    if(isset($_POST['post']) == 'webomsMobile'){
        $user_id = $_POST['user_id'];
        $dishesArr = $_POST['dishesArr'];
        $priceArr  = $_POST['priceArr'];
        $orderType = $_POST['orderType'];
        $dishesQuantity = $_POST['dishesQuantity'];
        $total = $_POST['total'];

        // $query = "INSERT INTO `try`(`user_id`, `dishesArr`, `priceArr`, `orderType`, `dishesQuantity`, `total`) 
        // VALUES ('$user_id','$dishesArr','$priceArr','$orderType','$dishesQuantity','$total')";
        // Query2($query);
        

        $dishesArr = explode(",",$dishesArr);
        $priceArr  = explode(",",$priceArr );
        $orderType = explode(",",$orderType);
        $dishesQuantity = explode(",",$dishesQuantity);

        $query = "SELECT email FROM `weboms_userInfo_tb` WHERE user_id = '$user_id' ";
        $email = getQueryOneVal2($query,'email');
        $query = "SELECT name FROM `weboms_userInfo_tb` WHERE user_id = '$user_id' ";
        $name = getQueryOneVal2($query,'name');

        $date = new DateTime();
        $today =  $date->format('Y-m-d'); 
        $todayWithTime =  $date->format('Y-m-d H:i:s'); 

        //company variables init
        $query = "select * from weboms_company_tb";
        $resultSet = getQuery2($query);
        if($resultSet!=null)
            foreach($resultSet as $row){
            $companyName = $row['name'];
            $companyAddress = $row['address'];
            $companyTel = $row['tel'];
        }

        //or number process
        $or_last = getQueryOneVal2("select or_number from weboms_order_tb WHERE id = (SELECT MAX(ID) from weboms_order_tb)","or_number");
        if($or_last == null){
            $or_last = 1;
        }
        else{
            $or_last = $or_last + 1;
        }
        $inputSize = strlen(strval($or_last));
        if($inputSize > 4)
            $str_length = $inputSize;
        else
            $str_length = 4;
        $temp = substr("0000{$or_last}", -$str_length);
        $or_number = $temp;

        //increment order id
        $lastOrderId = getQueryOneVal2("select order_id from weboms_order_tb WHERE order_id = (SELECT MAX(order_id) from weboms_order_tb)","order_id");
        if($lastOrderId == null){
            $lastOrderId = rand(1111,9999);
        }
        else{
            $lastOrderId = $lastOrderId + 1;
        }
        $order_id = $lastOrderId;

        // insert order
        $query1 = "insert into weboms_order_tb( user_id, order_id, or_number, status, date, totalOrder, payment, staffInCharge) values('$user_id', '$order_id', '$or_number', 'preparing', '$todayWithTime','$total','$total', 'online order')";
        Query2($query1);
        // insert order details
        for($i=0; $i<count($dishesArr); $i++){
            $query2 = "insert into weboms_ordersDetail_tb(order_id, quantity, orderType) values('$order_id',$dishesQuantity[$i], $orderType[$i])";
            Query2($query2);
        }
        // update balance
        $query3 = "UPDATE weboms_userInfo_tb SET balance = (balance - '$total') where user_id = '$user_id' ";     
        Query2($query3);
    }
?>