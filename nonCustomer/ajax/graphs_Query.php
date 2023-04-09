<?php 
    include('../../method/query.php');
    // init
    $multiArr = $dishesArr = $qantityArr = array();
    $data = [['countOfSold'],['stockLeft'],['preparing'],['serving'],['totalSold'],['todaySold'],['currentWeekSold'],['currentMonthSold'],['currentYearSold'],['multiArr'],['dailySoldMultiArr'],['weeklySoldMultiArr'],['monthlySoldMultiArr'],['yearlySoldMultiArr']];
    $countOfSold = $stockLeft = $preparing = $serving = $todaySold = 0; 
    $dailySoldMultiArr = $monthlySoldMultiArr =  $yearlySoldMultiArr = [[],[]]; 
    $weeklySoldMultiArr = [[],[],[]];

    //getting count of sold
    $countOfSoldQuery = "select dish,quantity from weboms_ordersDetail_tb a inner join weboms_menu_tb b on a.orderType = b.orderType inner join weboms_order_tb c on a.order_id = c.order_id where c.status = 'complete'";
    $resultSet = getQuery3($countOfSoldQuery); 
    if($resultSet != null){
        foreach($resultSet as $row){
            //get sold stock
            $countOfSold += $row['quantity'];
            //merge dish quantity into 1 
            if(in_array($row['dish'], $dishesArr)){
                $index = array_search($row['dish'], $dishesArr);
                $newQuantity = $qantityArr[$index] + $row['quantity'];
                $qantityArr[$index] = $newQuantity;
            }
            else{
                array_push($dishesArr,$row['dish']);
                array_push($qantityArr,$row['quantity']);
            }
        }
    }
    $data['countOfSold'] = $countOfSold;

    // getting count of stock left
    $countOfStockLeftQuery = "select * from weboms_menu_tb;";
    $resultSet = getQuery3($countOfStockLeftQuery); 
    if($resultSet != null){
        foreach($resultSet as $row){
            $stockLeft += $row['stock'];
        }
    }
    $data['stockLeft'] = $stockLeft;

    // getting count of preparing
    $countOfPreparingQuery = "select dish,quantity,status from weboms_ordersDetail_tb a inner join weboms_menu_tb b on a.orderType = b.orderType inner join weboms_order_tb c on a.order_id = c.order_id and status = 'preparing'";
    $resultSet = getQuery3($countOfPreparingQuery); 
    if($resultSet != null){
        foreach($resultSet as $row){
            $preparing += $row['quantity'];
        }
    }
    $data['preparing'] = $preparing;

    // getting count of preparing
    $countOfServingQuery = "select dish,quantity,status from weboms_ordersDetail_tb a inner join weboms_menu_tb b on a.orderType = b.orderType inner join weboms_order_tb c on a.order_id = c.order_id and status = 'serving'";
    $resultSet = getQuery3($countOfServingQuery); 
    if($resultSet != null){
        foreach($resultSet as $row){
            $serving += $row['quantity'];
        }
    }
    $data['serving'] = $serving;


    // merge multiple array into multi dimensional array
    for($i=0; $i<sizeof($dishesArr); $i++){
        $arr = array('dish' => $dishesArr[$i], 'quantity' => $qantityArr[$i]);
        array_push($multiArr,$arr);
    }

    // manual sort
    for($i=0; $i<sizeof($multiArr); $i++){
        for($j=$i+1; $j<sizeof($multiArr); $j++){
            if($multiArr[$i]['quantity'] > $multiArr[$j]['quantity']){
                $tempArr = $multiArr[$i];
                $multiArr[$i] = $multiArr[$j];
                $multiArr[$j] = $tempArr;
            }
        }                
    }
    $data['multiArr'] = $multiArr;
    
    // yearly sale | total sold
    $totalSold = $i = 0;
    $resultSet = getQuery3("SELECT totalOrder,date FROM `weboms_order_tb`WHERE status = 'complete' ");
    if($resultSet!=null){
        foreach($resultSet as $row){
            $totalSold += $row['totalOrder'];
            $year = date('Y', strtotime($row['date']));
            if(in_array($year, $yearlySoldMultiArr[0])){
                $index = array_search($year, $yearlySoldMultiArr[0]);
                $yearlySoldMultiArr[1][$index] += 1;
            }
            else{
                array_push($yearlySoldMultiArr[0],$year);
                array_push($yearlySoldMultiArr[1],1);
            }
            $i++;
        }
    }
    $data['totalSold'] = number_format($totalSold,2);
    $data['yearlySoldMultiArr'] = $yearlySoldMultiArr;

    //today sale
    $resultSet = getQuery3("SELECT totalOrder FROM `weboms_order_tb`WHERE DAY(date) = DAY(NOW()) AND status = 'complete' ");
    if($resultSet!=null){
        foreach($resultSet as $row){
            $todaySold += $row['totalOrder'] ;
        }
    }
    $data['todaySold'] = number_format($todaySold,2);

    // daily sale | current week total sold
    $currentWeekSold = $i = 0;
    $resultSet = getQuery3("SELECT totalOrder,date FROM `weboms_order_tb`WHERE WEEK(date) = WEEK(NOW()) AND status = 'complete' ");
    if($resultSet!=null){
        foreach($resultSet as $row){
            $currentWeekSold += $row['totalOrder'];
            $day = date('l', strtotime($row['date']));
            if(in_array($day, $dailySoldMultiArr[0])){
                $index = array_search($day, $dailySoldMultiArr[0]);
                $dailySoldMultiArr[1][$index] += 1;
            }
            else{
                array_push($dailySoldMultiArr[0],$day);
                array_push($dailySoldMultiArr[1],1);
            }
            $i++;
        }
    }
    $data['currentWeekSold'] = number_format($currentWeekSold,2);
    $data['dailySoldMultiArr'] = $dailySoldMultiArr;

    // weekly sale | current month total sold
    $currentMonthSold = $i = 0;
    $weekNo = 1;
    $resultSet = getQuery3("SELECT totalOrder,date FROM `weboms_order_tb`WHERE MONTH(date) = MONTH(NOW()) AND status = 'complete' ");
    if($resultSet!=null){
        foreach($resultSet as $row){
            $currentMonthSold += $row['totalOrder'];
            $week = 'Week no.'.date('W', strtotime($row['date']));
            if(in_array($week, $weeklySoldMultiArr[0])){
                $index = array_search($week, $weeklySoldMultiArr[0]);
                $weeklySoldMultiArr[1][$index] += 1;
            }
            else{
                $w = 'Week no.'.$weekNo;
                array_push($weeklySoldMultiArr[0],$week);
                array_push($weeklySoldMultiArr[1],1);
                array_push($weeklySoldMultiArr[2],$w);
                $weekNo++;
            }
        }
    }
    $data['currentMonthSold'] = number_format($currentMonthSold,2);
    $data['weeklySoldMultiArr'] = $weeklySoldMultiArr;

    // monthly sale | current year total sold
    $currentYearSold = $i = 0;
    $resultSet = getQuery3("SELECT totalOrder,date FROM `weboms_order_tb`WHERE Year(date) = Year(NOW()) AND status = 'complete' ");
    if($resultSet!=null){
        foreach($resultSet as $row){
            $currentYearSold += $row['totalOrder'];
            $month = date('M', strtotime($row['date']));
            if(in_array($month, $monthlySoldMultiArr[0])){
                $index = array_search($month, $monthlySoldMultiArr[0]);
                $monthlySoldMultiArr[1][$index] += 1;
            }
            else{
                array_push($monthlySoldMultiArr[0],$month);
                array_push($monthlySoldMultiArr[1],1);
            }
            $i++;
        }
    }
    $data['currentYearSold'] = number_format($currentYearSold,2);
    $data['monthlySoldMultiArr'] = $monthlySoldMultiArr;

    // echo everything
    echo json_encode($data);
?>

