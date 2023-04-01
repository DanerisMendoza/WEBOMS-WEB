<?php 
    include('../../method/query.php');
    session_start();
    $status = json_decode($_POST['status']);
    if($status != 'all')
        $query = "select a.*, b.* from weboms_userInfo_tb a right join weboms_order_tb b on a.user_id = b.user_id where b.status = '$status'  order by b.id asc " ;
    else
        $query = "select a.*, b.* from weboms_userInfo_tb a right join weboms_order_tb b on a.user_id = b.user_id order by b.id asc " ;
    $resultSet = getQuery3($query); 
    if($resultSet != null){
        $tBody = "";
        foreach($resultSet as $row){
          $tBody .= "<tr>";
            $tBody .= "<td>$row[ID]</td>";
            //if account is deleted
            if($row['staffInCharge'] == 'online order' && $row['name'] == '' ){
                $tBody .= "<td><a class='text-danger'>Deleted Account</a></td>";
            }
            //account is from online order   
            else if($row['name'] != ''){
                $tBody .= "<td>$row[name]</td>";
            }
            //account is generated from pos
            else{
                $tBody .= "<td>(No Name)</td>";
            }
            $tBody .= "<td>$row[order_id]</td>";
            $tBody .= "<td>$row[status]</td>";
            $date = date('m/d/Y h:i a ', strtotime($row['date']));
            $tBody .= "<td>$date</td>";
            $staffInCharge = $row['staffInCharge'] == 'online order' ?    ucwords($row['staffInCharge']) : ucwords($row['staffInCharge']) .' via POS' ;
            $tBody .= "<td>$staffInCharge</td>";
            //order details button
            $tBody .= "<td><a class='btn btn-light' style='border:1px solid #cccccc;' href='adminOrder_details.php?order_id=$row[order_id]'> <i class='bi bi-list'></i> View</a></td>";
            //show customer info button when it is online order
            if($row['staffInCharge'] == 'online order'){
                $tBody .= "<td> <button type='button' class='btn btn-primary' onclick='profileModal($row[user_id])' > View <i class='bi bi-list'></i> </button></td>";                
                if($row['status'] == 'preparing'){
                    $tBody .= "<td> <button type='button' class='btn btn-success' onclick='serve($row[order_id])' > Serve <i class='bi bi-arrow-bar-left'></i> </button></td>";                
                }
                else if($row['status'] == 'serving'){
                    $tBody .= "<td> <button type='button' class='btn btn-success' onclick='complete($row[order_id])' ><i class='bi bi-check'></i>Order Complete</button></td>";                
                }
                else if($row['status'] == 'complete' || $row['status'] == 'void'){
                    $tBody .= " <td><a class='text-danger'>None</a></td>";
                }
            }
            //pos
            else{
                $tBody .= "<td><a class=text-danger>None</a></td>";
                if($row['status'] == 'preparing'){
                    $tBody .= "<td> <button type='button' class='btn btn-success' onclick='serve($row[order_id])' > Serve <i class='bi bi-arrow-bar-left'></i> </button></td>";                
                }
                else if($row['status'] == 'serving'){
                    $tBody .= "<td> <button type='button' class='btn btn-success' onclick='complete($row[order_id])' ><i class='bi bi-check'></i>Order Complete</button></td>";                
                }
                else if($row['status'] == 'complete' || $row['status'] == 'void'){
                    $tBody .= "<td><a class='text-danger'>None</a></td>";
                }
            }
            //void
            if($row['status'] != 'void' && $_SESSION['accountType'] != 'cashier'){
                $tBody .= "<td> <button type='button' class='btn btn-danger' onclick='voidOrder($row[order_id], $row[user_id], $row[totalOrder])' ><i class='bi bi-circle'></i>Void</button></td>";                
            }
            else{
                $tBody .= "<td><a class=text-danger>None</a></td>";
            }
            
          $tBody .= "</tr>";
        }
        echo $tBody;
    }
?>