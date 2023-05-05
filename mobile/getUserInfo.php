<?php      
  include_once('../general/connection.php');
  include_once('../method/query.php');

	if(isset($_POST['post']) && $_POST['post'] == 'webomsMobile') {
		$user_id = $_POST['user_id'];
        $query = "select * from weboms_user_tb a RIGHT JOIN weboms_userInfo_tb b on a.user_id = b.user_id WHERE accountType = 'customer' and a.user_id = '$user_id' ;";
        $resultSet = getQuery2($query);
        $valid = false;
        if(($resultSet && $resultSet->num_rows)  > 0){
            foreach($resultSet as $row){
                $result = array('name' => $row['name'], 
                'username' => $row['username'], 
                'gender' => $row['gender'],
                'age' => $row['age'],
                'phoneNumber' => $row['phoneNumber'],
                'address' => $row['address'],
                'email' => $row['email'],  
                'picName' => $row['picName'],  
                'balance' => $row['balance']);
            }
        }
        echo json_encode($result);

	} 
    else {
		echo "unauthorized access.";
	}
?>