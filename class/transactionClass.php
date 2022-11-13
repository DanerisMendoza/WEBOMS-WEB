<?php 

     class transaction{
        public $username, $id, $date1, $date2;
        public $ordersLinkId, $userlinkId;

        //constructors

        function __construct(){ 
            $arguments = func_get_args();
            $numberOfArguments = func_num_args();
            if (method_exists($this, $function = '__construct'.$numberOfArguments)) {
                call_user_func_array(array($this, $function), $arguments);
            }
        }

        function __construct2($date1,$date2){ 
           $this-> date1 = $date1;
           $this-> date2 = $date2;
        }

        //static helper methods

        public static function withUsername( $username ) {
            $instance = new self();
            $instance->loadByUsername($username);
            return $instance;
        }

        protected function loadByUsername( $username ) {
            $this -> username = $username;
        }

        public static function withUserLinkId( $userlinkId ) {
            $instance = new self();
            $instance->loadByUserLinkId($userlinkId);
            return $instance;
        }

        protected function loadByUserLinkId( $userlinkId ) {
            $this -> userlinkId = $userlinkId;
        }
    

        public static function withUsersAndOrdersLinkId($userlinkId,$ordersLinkId) {
            $instance = new self();
            $instance->loadByOrdersAndLinkId($userlinkId,$ordersLinkId);
            return $instance;
        }

        protected function loadByOrdersAndLinkId($userlinkId,$ordersLinkId) {
            $this -> userlinkId = $userlinkId;
            $this -> ordersLinkId = $ordersLinkId;
        }

        //queries

        function getAllTransaction(){
            $query = "select customer_tb.*, orderlist_tb.* from customer_tb, orderlist_tb where customer_tb.userlinkId = orderlist_tb.userlinkId  ORDER BY orderlist_tb.id asc; ";
            return getQuery($query);
        }

        function getAllNotCompleteTransaction(){
            $query = "select customer_tb.*, orderlist_tb.* from customer_tb, orderlist_tb where customer_tb.userlinkId = orderlist_tb.userlinkId && orderlist_tb.isOrdersComplete = 0 ORDER BY orderlist_tb.id asc; ";
            return getQuery($query);
        }

        function getAllTransactionComplete(){
          $query = "select customer_tb.name, orderlist_tb.* from customer_tb, orderlist_tb where customer_tb.userlinkId = orderlist_tb.userlinkId and orderlist_tb.isOrdersComplete = 1 ORDER BY orderlist_tb.id asc; ";
          return getQuery($query);
        }

        function getAllSold(){
          $query = "select dishes_tb.*,order_tb.*,orderList_tb.isOrdersComplete from dishes_tb inner join order_tb on dishes_tb.orderType = order_tb.orderType inner join orderList_tb on orderList_tb.ordersLinkId = order_tb.OrdersLinkId where orderList_tb.isOrdersComplete = 1;";
          return getQuery($query);
        }

        function getApprovedOrderList(){
          $query = "select user_tb.name, orderlist_tb.* from user_tb, orderlist_tb where user_tb.userlinkId = orderlist_tb.userlinkId and orderlist_tb.status = 1 ORDER BY orderlist_tb.id asc; ";
          return getQuery($query);
        }

        function getPrepairingOrder(){
            $query = "select customer_tb.name, orderlist_tb.* from customer_tb, orderlist_tb where customer_tb.userlinkId = orderlist_tb.userlinkId and orderlist_tb.isOrdersComplete = 0 and orderlist_tb.status = 1 ORDER BY orderlist_tb.id asc; ";
            return getQuery($query);
        }


        function getOrderListByCustomer(){
            $query = "select customer_tb.*, orderlist_tb.* from customer_tb, orderlist_tb where customer_tb.userlinkId = orderlist_tb.userlinkId and customer_tb.username = '{$this -> username}' ORDER BY orderlist_tb.id asc; ";
            return getQuery($query);
        }

        function getOrderListByUserLinkId(){
            $query = "select customer_tb.*, orderlist_tb.* from customer_tb, orderlist_tb where customer_tb.userLinkId = orderlist_tb.userlinkId and customer_tb.userLinkId = '{$this->userlinkId}';";
            return getQuery($query);
        }

        function getAllOrderById(){
            $query = "select dishes_tb.*, order_tb.* from dishes_tb inner join order_tb where dishes_tb.orderType = order_tb.orderType and order_tb.ordersLinkId = '{$this->id}' ";
            return getQuery($query);
        }
  
        function getOrderListByDates(){
            $query = "select customer_tb.name, orderlist_tb.* from customer_tb, orderlist_tb where customer_tb.userlinkId = orderlist_tb.userlinkId and orderlist_tb.isOrdersComplete = 1 and orderlist_tb.date between '{$this->date1}' and '{$this->date2}' ORDER BY orderlist_tb.id asc; ";
            return getQuery($query);
        }
        
        function setOrderComplete(){
            $query = "UPDATE orderList_tb SET isOrdersComplete=true WHERE ordersLinkId='{$this->id}' ";     
            if(Query($query)){
                echo "<SCRIPT>  window.location.replace('adminOrdersList.php'); alert('success!');</SCRIPT>";
            }
            else{
                echo "<SCRIPT>  window.location.replace('adminOrdersList.php'); alert('unsuccess!');</SCRIPT>";
            }
        }

        //methods | functions

        function generateOrdersTable($resultSet){
          ?>
          <table class="table table-striped" border="10">
          <tr>	
            <th scope="col">name</th>
            <th scope="col">Orders ID</th>
            <th scope="col">_______</th>
            <th scope="col">_______</th>
            <th scope="col">Approve status:</th>
            <th scope="col">Order Complete Status</th>
            <th scope="col">Order status:
              <form method="post">
                <button class="btn" type="submit" name="showAll" style="font-size: 12px ;">Show/Unshow All</button>
              </form>
            </th>
              <th scope="col">Date:</th>
              <th scope="col">_______</th>
            </tr>
            <tbody>
              <?php foreach($resultSet as $rows){?>
              <tr>	   
                <td><?php echo $rows['name']; ?></td>
                <td><?php echo $rows['ordersLinkId'];?></td>
                <td colspan="2"><a class="btn" style="background: white; padding:2px; border: 2px black solid; color:black;" href="adminOrders.php?idAndPic=<?php echo $rows['ordersLinkId'].','.$rows['proofOfPayment']?>">View Order</a></td>
                <td>
                  <?php 
                    if($rows['status'] == 1){
                      echo "Already Approved";
                    }
                    else{
                      ?><a class="btn" style="background: blue; padding:2px; border: 2px black solid; color:black;" href="?status=<?php echo $rows['ordersLinkId'].','.$rows['email']; ?>">Approve</a><?php
                    }?>
                </td>
                <td>
                  <?php 
                    if($rows['status'] != 1){
                      echo "waiting for approval";
                    }
                    elseif($rows['isOrdersComplete'] == 1){
                      echo "order is complete";
                    }
                    else{
                      ?> <a class="btn"  style="background: green; padding:2px; border: 2px black solid; color:black;" href="?orderComplete=<?php echo $rows['ordersLinkId'] ?>">Order Complete</a><?php
                    }?>  
                </td>
                <td>
                <?php
                  if($rows['isOrdersComplete'] == 0 && $rows['status'] == 0){
                    echo "Pending";
                  }
                  elseif($rows['isOrdersComplete'] == 0){
                    echo "Preparing";
                  }
                  else{
                    echo "Order Complete";
                  }
                ?></td>
                <td><?php echo date('m/d/Y h:i a ', strtotime($rows['date'])); ?></td>
                <td><a class="btn" style="background: red; padding:2px; border: 2px black solid; color:black"href="method/deleteOrderMethod.php?idAndPicnameDelete=<?php echo $rows['ID'].','.$rows['proofOfPayment'].','.$rows['ordersLinkId'] ?>">Delete</a></td>
              </tr><?php } ?>
            </tbody>   
          </table><?php
        }
     }
     class transactionById extends transaction{
        function __construct($id)
        {
          $this -> id = $id;
        }
     }
     
     class transactionEmpty extends transaction{
        function __construct(){

        }
     }
?>