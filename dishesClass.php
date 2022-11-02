<?php
    class dish{
        public $feedback;
        public $ordersLinkId;
        public $userlinkId;

        function __construct($feedback,$ordersLinkId,$userlinkId){ 
            $this-> feedback = $feedback;
            $this-> ordersLinkId = $ordersLinkId;
            $this-> userlinkId = $userlinkId;
        }

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

    }
?>