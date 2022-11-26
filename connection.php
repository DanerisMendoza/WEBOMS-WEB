<?php
$dbhost = "localhost";
$dbuser = "ytoovumw_bscs3a";
$dbpass = "kaAGi]gz8H2*";
$dbname = "ytoovumw_bscs3a";

$conn = new mysqli($dbhost,$dbuser,$dbpass,$dbname);	

	if ($conn -> connect_error){
		die("Connection Failed: ". $conn->connect_error); 
	}
	else{
		//user
		$queryCreateUser_tb = "create table if not exists WEBOMS_user_tb(id int PRIMARY KEY AUTO_INCREMENT,
		username varchar(255),
		password varchar(255),
		accountType varchar(255),
		user_id varchar(255))";

		$queryCreateUserInfo_tb = "create table if not exists WEBOMS_userInfo_tb(id int PRIMARY KEY AUTO_INCREMENT,
		name varchar(255),
		email varchar(255),
		otp varchar(255),
		user_id varchar(255))";
		

		//menu
		$queryCreateMenu_tb = "create table if not exists WEBOMS_menu_tb(orderType int PRIMARY KEY AUTO_INCREMENT, 
		dish varchar(255),
		price int,
		picName varchar(255),
		stock int,
		lastModifiedBy varchar(255))";
		
		//orders
		$queryCreateOrder_tb = "create table if not exists WEBOMS_order_tb(ID int PRIMARY KEY AUTO_INCREMENT, 
		proofOfPayment varchar(255), 
		user_id varchar(255), 
		status varchar(255),
		order_id varchar(255),
		date datetime not null,
		totalOrder int,
		staffInCharge varchar(255))";

		$queryCreateOrdersDetail_tb = "create table if not exists WEBOMS_ordersDetail_tb(id int PRIMARY KEY AUTO_INCREMENT, 
		order_id varchar(255), 
		quantity int,
		orderType int)";

		//feedback
		$queryCreateFeedback_tb = "create table if not exists WEBOMS_feedback_tb(id int PRIMARY KEY AUTO_INCREMENT, 
		feedback varchar(255), 
		order_id varchar(255), 
		user_id varchar(255))";

		if($conn->query($queryCreateMenu_tb)  && $conn->query($queryCreateUser_tb) && $conn->query($queryCreateUserInfo_tb)  && $conn->query($queryCreateOrder_tb) && $conn->query($queryCreateOrdersDetail_tb) && $conn->query($queryCreateFeedback_tb)) {
			$checkQuery = "select * from WEBOMS_user_tb";
			if($resultSet = $conn->query($checkQuery)){  
				if($resultSet->num_rows <= 0){
					$hash = password_hash('password', PASSWORD_DEFAULT);
					$user_id = uniqid('',true);
					$queryInsertAdmin = "insert into WEBOMS_user_tb(username, password, accountType, user_id) values('admin','$hash','admin','$user_id')";
					$queryInsertAdminInfo = "insert into WEBOMS_userInfo_tb(name, email, otp, user_id) values('admin','','','$user_id')";
					if($conn->query($queryInsertAdmin))
						if($conn->query($queryInsertAdminInfo))
							echo  '<script>alert("Success creating table");</script>';						
					}
			}
		}
		else 
			echo  '<script type="text/javascript">alert("Error creating table");</script>';						
	}
	

?>

