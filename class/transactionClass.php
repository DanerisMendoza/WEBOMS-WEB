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


     

        function getAllTransactionComplete(){
          $query = "select customer_tb.name, order_tb.* from customer_tb, order_tb where customer_tb.userlinkId = order_tb.userlinkId and order_tb.isOrdersComplete = 1 ORDER BY order_tb.id asc; ";
          return getQuery($query);
        }

        function getAllSold(){
          $query = "select menu_tb.*,ordersDetail_tb.*,order_tb.isOrdersComplete from menu_tb inner join ordersDetail_tb on menu_tb.orderType = ordersDetail_tb.orderType inner join order_tb on order_tb.ordersLinkId = ordersDetail_tb.OrdersLinkId where order_tb.isOrdersComplete = 1;";
          return getQuery($query);
        }

        function getApprovedOrderList(){
          $query = "select user_tb.name, order_tb.* from user_tb, order_tb where user_tb.userlinkId = order_tb.userlinkId and order_tb.status = 1 ORDER BY order_tb.id asc; ";
          return getQuery($query);
        }

        function getPrepairingOrder(){
            $query = "select customer_tb.name, order_tb.* from customer_tb, order_tb where customer_tb.userlinkId = order_tb.userlinkId and order_tb.isOrdersComplete = 0 and order_tb.status = 1 ORDER BY order_tb.id asc; ";
            return getQuery($query);
        }


        function getOrderListByCustomer(){
            $query = "select customer_tb.*, order_tb.* from customer_tb, order_tb where customer_tb.userlinkId = order_tb.userlinkId and customer_tb.username = '{$this -> username}' ORDER BY order_tb.id asc; ";
            return getQuery($query);
        }

        function getOrderListByUserLinkId(){
            $query = "select customer_tb.*, order_tb.* from customer_tb, order_tb where customer_tb.userLinkId = order_tb.userlinkId and customer_tb.userLinkId = '{$this->userlinkId}';";
            return getQuery($query);
        }

        function getAllOrderById(){
            $query = "select menu_tb.*, ordersDetail_tb.* from menu_tb inner join ordersDetail_tb where menu_tb.orderType = ordersDetail_tb.orderType and ordersDetail_tb.ordersLinkId = '{$this->id}' ";
            return getQuery($query);
        }
  
        function getOrderListByDates(){
            $query = "select customer_tb.name, order_tb.* from customer_tb, order_tb where customer_tb.userlinkId = order_tb.userlinkId and order_tb.isOrdersComplete = 1 and order_tb.date between '{$this->date1}' and '{$this->date2}' ORDER BY order_tb.id asc; ";
            return getQuery($query);
        }
        
        function setOrderComplete(){
            $query = "UPDATE order_tb SET isOrdersComplete=true WHERE ordersLinkId='{$this->id}' ";     
            if(Query($query)){
                echo "<SCRIPT>  window.location.replace('adminOrdersList.php'); alert('success!');</SCRIPT>";
            }
            else{
                echo "<SCRIPT>  window.location.replace('adminOrdersList.php'); alert('unsuccess!');</SCRIPT>";
            }
        }

        //methods | functions

     }
  
?>