<?php 

    $dishesArr = array();
    $priceArr = array();
    $dishesQuantity = array();
    $orderType = array();

    //merge repeating order into 1 
    for($i=0; $i<count($_SESSION['dishes']); $i++){
        if(in_array( $_SESSION['dishes'][$i],$dishesArr)){
            $index = array_search($_SESSION['dishes'][$i], $dishesArr);
            $newCost = $priceArr[$index] + $_SESSION['price'][$i];
                        $priceArr[$index] = $newCost;
        }
        else{
            array_push($dishesArr,$_SESSION['dishes'][$i]);
            array_push($priceArr,$_SESSION['price'][$i]);
            array_push($orderType,$_SESSION['orderType'][$i]);
        }
    }
    //push order quantity into arrray
    foreach(array_count_values($_SESSION['dishes']) as $count){
        array_push($dishesQuantity,$count);
    }
    
    //merge 3 array into 1 multi dimensional
    for($i=0; $i<count($dishesArr); $i++){ 
        $arr = array('dish'=> $dishesArr[$i], 'price' => $priceArr[$i], 'quantity' => $dishesQuantity[$i], 'orderType' => $orderType[$i]);
        array_push($_SESSION['multiArr'],$arr);
    }
    //sort multi dimensional
    sort($_SESSION['multiArr']);
    $total = 0;
    for($i=0; $i<count($priceArr); $i++){
        $total += $priceArr[$i];
    }

    //populate table using the multi dimensional array
    foreach($_SESSION['multiArr'] as $arr){ ?>
    <tr>
        <td><?php echo ucwords($arr['dish']);?></td>
        <td><?php echo $arr['quantity'];?></td>
        <td>
            <!-- check stock -->
            <?php if(getQueryOneVal3("select stock from weboms_menu_tb where dish = '$arr[dish]' ",'stock') > 0) { ?>
            <!-- quantity plus -->
            <a class="btn btn-success" href="?add=<?php echo $arr['dish'].','.($arr['price']/$arr['quantity']).','.$arr['orderType']; ?>"><i class="bi bi-plus"></i></a>
            <?php }else{ ?>
            <a class="text-danger me-2">Out of Stock</a>
            <?php } ?>
            <!-- quantity minus -->
            <a class="btn btn-danger" href="?minus=<?php echo $arr['dish'].','.($arr['price']/$arr['quantity']).','.$arr['orderType']; ?>"><i class="bi bi-dash"></i></a>
        </td>
        <td><?php echo '₱'.number_format($arr['price'],2);?></td>
    </tr>
        <?php }?>
    <tr>
        <td colspan="3"><b>Total Amount:</b></td>
        <td><b>₱<?php echo number_format($total,2); ?></b></td>
    </tr>