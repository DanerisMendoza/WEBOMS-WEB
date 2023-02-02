<?php 
    include('../../method/query.php');
    $query = "select * from weboms_menu_tb";
    $resultSet =  getQuery3($query);
    $tbody1 = "";
    if($resultSet != null){
        foreach($resultSet as $row){ 
            $dish = ucwords($row['dish']);
            $price = number_format($row['price'],2);
            $stock = $row['stock'];
            $tbody1 .= "<tr>";
            $tbody1 .= "<td>$dish</td>";
            $tbody1 .= "<td>₱$price</td>";
            $tbody1 .= "<td>$stock</td>";
            $tbody1 .= "<td>";
            if($stock <= 0)
                $tbody1 .= " <a class='text-danger'>Out of Stock</a>";
            else{
                $a = $row['dish'].",".$row['price'].",".$row['orderType'].",".$row['stock'];
                $tbody1 .= "<input type='number' placeholder='Quantity' name='qty' class='form-control' value='1' id='qty'>";
                $tbody1 .= "<button type='button' name='addToCartSubmit' onclick='AddToCart(this)' value='$a' class='btn btn-light col-12' style='border:1px solid #cccccc;'> <i class='bi bi-cart-plus'></i></button>";
            }
            $tbody1 .= "</td>";
            $tbody1 .= "</tr>";
        }
    }
    echo ($tbody1); 
?>
