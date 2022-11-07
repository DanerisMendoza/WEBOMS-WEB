<?php
    class dish{
        public $feedback, $ordersLinkId, $userlinkId;
        public $dishes, $price, $fileNameNew, $cost, $stock;
        public $id, $pic;

        function __construct(){ 
            $arguments = func_get_args();
            $numberOfArguments = func_num_args();
            if (method_exists($this, $function = '__construct'.$numberOfArguments)) {
                call_user_func_array(array($this, $function), $arguments);
            }
        }

        function __construct2($id, $pic)
        {
            $this -> id = $id;
            $this -> pic = $pic;
        }

        function __construct3($feedback,$ordersLinkId,$userlinkId){ 
            $this-> feedback = $feedback;
            $this-> ordersLinkId = $ordersLinkId;
            $this-> userlinkId = $userlinkId;
        }

        function __construct5($dishes, $price, $fileNameNew, $cost, $stock){
            $this -> dishes = $dishes;
            $this -> price = $price;
            $this -> fileNameNew = $fileNameNew;
            $this -> cost = $cost;
            $this -> stock = $stock;
            
        }

        function deleteDishOnDatabase(){
            $query = "DELETE FROM dishes_tb WHERE orderType='{$this->id}'";
            if (Query($query)){
                unlink("dishespic/"."{$this->pic}");
                echo "<script> window.location.replace('adminInventory.php'); alert('Delete data successfully'); </script>";  
            }
            else{
                echo "<script>alert('Delete data unsuccessfully');</script>";  
                echo "<script> window.location.replace('adminInventory.php');</script>";
            }

        }

        function insertNewDishToDatabase(){
            $query = "insert into dishes_tb(dish, price, picName, cost, stock) values('{$this->dishes}','{$this->price}','{$this->fileNameNew}','{$this->cost}','{$this->stock}')";
            if(Query($query))
                echo '<script>alert("Sucess saving to database!");</script>';                                               
            else
                echo '<script>alert("failed to save to database");</script>';  
        }

        function getAllDishes(){
            $query = "select * from dishes_tb";
            return QueryWithStringReturn($query);
        }
        
        function giveFeedBackToDish(){
            $query = "insert into feedback_tb(feedback, ordersLinkId, userlinkId) values('{$this->feedback}', '{$this->ordersLinkId}', '{$this->userlinkId}')";
            if(Query($query))
                echo "<script>alert('feedback sent thanks!'); window.location.replace('customerOrdersList.php');</script>";
            else
                echo "<script>alert('fail to send feedback!');</script>";
        }

        function checkIfAlreadyFeedback(){
            $query = "";
        }

        function getAllFeedback(){
            $query = "select feedback_tb.*, customer_tb.* from feedback_tb, customer_tb where user_tb.userlinkId = feedback.userlinkId;";
            return QueryWithStringReturn($query);
        }

    }

    function Query($query){
        include('connection.php');
        return $conn->query($query);
    }   
    
    function QueryWithStringReturn($query){
        include('connection.php');
        $resultSet = $conn->query($query);  
        if(mysqli_num_rows($resultSet) > 0) {
            return($resultSet);
        }
    }


?>