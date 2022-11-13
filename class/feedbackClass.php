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
            $query = "select customer_tb.*, feedback_tb.*, orderList_tb.* from customer_tb, orderList_tb, feedback_tb where customer_tb.userlinkId = orderList_tb.userlinkId and feedback_tb.ordersLinkId = orderList_tb.ordersLinkId;";
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
            <div class="col-lg-12">
            <table class="table table-striped" border="10">
            <tr>	
            <th scope="col">name</th>
            <th scope="col">feedback</th>
            </tr>
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