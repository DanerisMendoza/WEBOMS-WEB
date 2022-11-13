<?php
    class dish{
        public $ordersLinkId, $userlinkId;
        public $dishes, $price, $fileNameNew, $cost, $stock;
        public $id, $pic;
        

        function __construct(){ 
            $arguments = func_get_args();
            $numberOfArguments = func_num_args();
            if (method_exists($this, $function = '__construct'.$numberOfArguments)) {
                call_user_func_array(array($this, $function), $arguments);
            }
        }

        function __construct2($id, $pic){
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
            return getQuery($query);
        }

        function generateDishTableBodyInventory($resultSet){
            if($resultSet != null)
            foreach($resultSet as $rows){?>
                <tr>	   
                <td><?php $pic = $rows['picName']; echo "<img src='dishesPic/$pic' style=width:100px;height:100px>";?></td>
                <td><?=$rows['dish']?></td>
                <td><?php echo '₱'.$rows['price']; ?></td>
                <td><?php echo '₱'.$rows['cost']; ?></td>
                <td><?php echo $rows['stock']; ?></td>
                <td><a class="btn" style="background: red; padding:2px; border: 2px black solid; color:black;" href="?idAndPicnameDelete=<?php echo $rows['orderType']." ".$rows['picName'] ?>">Delete</a></td>
                <td><a class="btn" style="background: yellow; padding:2px; border: 2px black solid; color:black;" href="adminInventoryUpdate.php?idAndPicnameUpdate=<?php echo $rows['orderType']." ".$rows['dish']." ".$rows['price']." ".$rows['picName']." ". $rows['cost']." ".$rows['stock'] ?>"  >Update</a></td>
                </tr>
                <?php } 
        }

        function generateDishTableBodyMenu($resultSet){
            if($resultSet != null)
            foreach($resultSet as $rows){ ?>
            <tr>	   
                <td><?=$rows['dish']?></td>
                <td><?php echo '₱'.$rows['price']; ?></td>
                <td><?php $pic = $rows['picName']; echo "<img src='dishesPic/$pic' style=width:100px;height:100px>";?></td>
                <td><a class="btn" style="background: white; padding:2px; border: 2px black solid; color:black;" href="?order=<?php echo $rows['dish'].",".$rows['price'].",".$rows['orderType']?>" >Add To Cart</a></td>
            </tr>
            <?php }
        }
    }
?>