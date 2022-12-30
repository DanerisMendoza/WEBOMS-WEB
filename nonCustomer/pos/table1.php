<?php 
    include('../../method/query.php');
    $query = "select * from weboms_menu_tb";
    $resultSet =  getQuery3($query);
    if($resultSet != null)
        foreach($resultSet as $row){ ?>
    <tr>
        <td><?= ucwords($row['dish']);?></td>
        <td><?php echo "₱".number_format($row['price'],2); ?></td>
        <td><?php echo $row['stock']; ?></td>
        <!-- add to cart -->
        <td>
            <!-- out of stock -->
            <?php if($row['stock'] <= 0){ ?>
                <a class="text-danger">Out of Stock</a>
                <!-- not out of stock -->
                <?php } else{ ?>
                    <form method="post">
                        <input type="hidden" name="order" value="<?php echo $row['dish'].",".$row['price'].",".$row['orderType'].",".$row['stock']?>">
                        <input type="number" placeholder="Quantity" name="qty" class="form-control" value="1">
                        <button type="submit" name="addToCartSubmit" class="btn btn-light col-12" style="border:1px solid #cccccc;"><i class="bi bi-cart-plus"></i></button>
                    </form>
            <?php } ?>
        </td>
    </tr>
<?php } ?>