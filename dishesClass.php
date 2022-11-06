<?php
    class dish{
        public $feedback;
        public $ordersLinkId;
        public $userlinkId;
        public $arr = array();

        function __construct(){ 
            $arguments = func_get_args();
            $numberOfArguments = func_num_args();
            if (method_exists($this, $function = '__construct'.$numberOfArguments)) {
                call_user_func_array(array($this, $function), $arguments);
            }
        }

        function __construct3($feedback,$ordersLinkId,$userlinkId){ 
            $this-> feedback = $feedback;
            $this-> ordersLinkId = $ordersLinkId;
            $this-> userlinkId = $userlinkId;
        }

        function getAllDishes(){
            include_once('connection.php');
            $resultSet = $conn->query("select * from dishes_tb" );
            if (mysqli_num_rows($resultSet)) {
                while($rows = mysqli_fetch_assoc($resultSet)){
                    array_push($this->arr,$rows);
                }
                return($this->arr);
            }
        }
        
        function giveFeedBackToDish(){
            include_once('connection.php');
            $query = "insert into feedback_tb(feedback, ordersLinkId, userlinkId) values('{$this->feedback}', '{$this->ordersLinkId}', '{$this->userlinkId}')";
            if($conn->query($query))
                echo "<script>alert('feedback sent thanks!'); window.location.replace('customerOrdersList.php');</script>";
            else
                echo "<script>alert('fail to send feedback!');</script>";
        }

        function checkIfAlreadyFeedback(){
            include_once('connection.php');
            $query = "";
        }

        function getAllFeedback(){
            include_once('connection.php');
            $resultSet = $conn->query("select feedback_tb.*, customer_tb.* from feedback_tb, customer_tb where user_tb.userlinkId = feedback.userlinkId;");
            if (mysqli_num_rows($resultSet)) {
                while($rows = mysqli_fetch_assoc($resultSet)){
                    array_push($this->arr,$rows);
                }
                return($this->arr);
            }
        }

    }
?>