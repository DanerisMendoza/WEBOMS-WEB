<!-- serving table -->
<?php   
    include('../../method/query.php');
    $getPrepairingOrder = "select weboms_userInfo_tb.name, weboms_order_tb.* from weboms_userInfo_tb right join weboms_order_tb on weboms_userInfo_tb.user_id = weboms_order_tb.user_id  where status = 'serving' ORDER BY weboms_order_tb.id asc; ";
    $resultSet = getQuery3($getPrepairingOrder);
    if($resultSet != null)
        foreach($resultSet as $row){ 
?>
            <tr>
                <!-- orders id -->
                <td><strong style="font-size: 35px;"><?php echo $row['order_id']; ?></strong></td>
            </tr>
<?php } ?>