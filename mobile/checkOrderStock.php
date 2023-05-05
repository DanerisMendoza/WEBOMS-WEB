<?php     
  $page = 'notLogin';
  include_once('../general/connection.php');
  include_once('../method/query.php');
?>

<?php
	if(isset($_POST['post']) && $_POST['post'] == 'webomsMobile') {
		$order = $_POST['order'];
		$qty = $_POST['qty'];
    $query = "SELECT stock FROM `weboms_menu_tb` WHERE dish = '$order';";
    $resultSet = getQuery2($query);
    if(($resultSet && $resultSet->num_rows)  > 0){
      foreach($resultSet as $row){
        if($row['stock'] > $qty){
          $result = array('result' => 'true');
        }
        else{
          $result = array('result' => 'false');
        }
      }
    }
    echo json_encode($result);
	} else {
		echo "unauthorized access.";
	}
?>