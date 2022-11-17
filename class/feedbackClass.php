<?php 
    class feedback{
        public $feedback,$ordersLinkId,$userLinkId;

        function __construct($feedback,$ordersLinkId,$userLinkId){
            $this -> feedback = $feedback;
            $this -> ordersLinkId = $ordersLinkId;
            $this -> userLinkId = $userLinkId;
        }

        //queries

        function getAllFeedbackSortedByUserLinkId(){
            $query = "select customer_tb.*, feedback_tb.*, order_tb.* from customer_tb, order_tb, feedback_tb where customer_tb.userlinkId = order_tb.userlinkId and feedback_tb.ordersLinkId = order_tb.ordersLinkId;";
            return getQuery($query);
        }

        function giveFeedBackByOrdersLinkIdAndUserLinkId(){
            $query = "insert into feedback_tb(feedback, ordersLinkId, userLinkId) values('{$this->feedback}', '{$this->ordersLinkId}', '{$this->userLinkId}')";
            $result = Query($query); 
            if($result)
                echo "<script>alert('feedback sent thanks!'); window.location.replace('customerOrdersList.php');</script>";
            else
                echo "<script>alert('fail to send feedback! $result');</script>";
        }

        function CustomerFeedback(){
            $query = "SELECT * FROM feedback_tb WHERE ordersLinkId='{$this->ordersLinkId}' AND userLinkId = '{$this->userLinkId}' ";
            return getQuery($query);
        }


        //functions
        function generateAllFeedbackTable($resultSet){
            ?>
            <div class="table-responsive col-lg-12">
            <table class="table table-striped table-bordered mb-5 col-lg-12">
            <thead class="table-dark">
                <tr>	
                <th scope="col">NAME</th>
                <th scope="col">FEEDBACK</th>
                </tr>
            </thead>
              <tbody>
                <?php
                if($resultSet!= null)
                foreach($resultSet as $rows){ ?>
                <tr>	   
                  <td><?php echo $rows['name']; ?></td>
                  <td><?php echo $rows['feedback'];?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
            <?php
        }
        
    }

    class feedbackEmpty extends feedback{
        //redefining constructor into none
        function __construct()
        {
            
        }
    }
    
 
?>