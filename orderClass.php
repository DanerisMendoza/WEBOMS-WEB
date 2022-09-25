<?php
    class order{

        public $dishesArr = array();
        public $priceArr = array();
        public $dishesQuantity = array();
        public $total;
     
        function __construct($ordersLinkId){ 
            include('connection.php');
            $sql = mysqli_query($conn,"select dishes_tb.*, order_tb.* from dishes_tb inner join order_tb where dishes_tb.orderType = order_tb.orderType and order_tb.ordersLinkId = '$ordersLinkId' ");  
            if (mysqli_num_rows($sql)) {  
                while($rows = mysqli_fetch_assoc($sql)){ 
                    $price = ($rows['price']*$rows['quantity']);  
                    array_push($this-> dishesArr,$rows['dish']);
                    array_push($this-> priceArr,$rows['price']);
                    array_push($this-> dishesQuantity,$rows['quantity']);
                    $this-> total += $price;
                }
            }
        }

        function makeReceipt(){
            $dishesArr = $this-> dishesArr;
            $priceArr = $this-> priceArr;
            $dishesQuantity = $this-> dishesQuantity;
            $total = $this-> total;
            date_default_timezone_set('Asia/Manila');
            $date = date("j-m-Y  h:i:s A"); 
            $content = '
            <h3>'.$date.'</h3>
            <table  text-center cellspacing="0" cellpadding="3">  
            <tr>
                <th scope="col">Quantity</th>
                <th scope="col">Dish</th>
                <th scope="col">Cost</th>
            </tr>
            ';  
            for($i=0; $i<count($dishesArr); $i++){ 
            $content .= "
            <tr>  
            <td>$dishesQuantity[$i]</td>
            <td>$dishesArr[$i]</td>
            <td>₱$priceArr[$i]</td>
            </tr>
            ";
            }
            $content .= "   
            <br><br>
            <br><br>
    
    
            <tr>
            <td></td>
            <td>Total</td>
            <td>₱$total</td>
            </tr>
    
            <style>
            h3 {text-align: center;}
            table,table td {
                border: 1px solid #cccccc;
            }
    
            td,table{
                text-align: center;
            }
            </style>
            ";
            return $content;
        }
    }
    
?>