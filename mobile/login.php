<?php     
  $page = 'notLogin';
  include_once('../general/connection.php');
  include_once('../method/query.php');
?>

<?php
	if(isset($_POST['post']) && $_POST['post'] == 'webomsMobile') {
		$username = $_POST['username'];
		$password = $_POST['password'];

        $query = "select * from weboms_user_tb a RIGHT JOIN weboms_userInfo_tb b on a.user_id = b.user_id WHERE accountType = 'customer' and username = '$username' ;";
        $resultSet = getQuery2($query);
        $valid = false;

        if(($resultSet && $resultSet->num_rows)  > 0){
            foreach($resultSet as $row){
                $valid = password_verify($password, $row['password'])?true:false;
                $accountType = $row['accountType'];
                $user_id = $row['user_id'];
            }
        }
        if($valid){
            $result = array('result' => 'valid', 'user_id' => $user_id);
        }
        else{
            $result = array('result' => 'invalid');
        }

        echo json_encode($result);

	} else {
		echo "unauthorized access.";
	}
?>