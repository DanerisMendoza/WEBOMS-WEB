<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "ytoovumw_bscs3a";


$conn = new mysqli($dbhost,$dbuser,$dbpass,$dbname);	

	if ($conn -> connect_error){
		die("Connection Failed: ". $conn->connect_error); 
	}
	else{
		//user
		$queryCreateUser_tb = "create table if not exists weboms_user_tb(id int PRIMARY KEY AUTO_INCREMENT,
		user_id int,
		username varchar(255),
		password varchar(255),
		accountType varchar(255))";

		$queryCreateUserInfo_tb = "create table if not exists weboms_userInfo_tb(id int PRIMARY KEY AUTO_INCREMENT,
		user_id int,
		name varchar(255),
		picName varchar(255),
		gender varchar(255),
		age int,
		phoneNumber varchar(255),
		address varchar(255),
		email varchar(255),
		otp varchar(255),
		forgetPasswordOtp varchar(255),
		balance int,
		rfid varchar(255))";

		//menu
		$queryCreateMenu_tb = "create table if not exists weboms_menu_tb(orderType int PRIMARY KEY AUTO_INCREMENT, 
		dish varchar(255),
		price float,
		picName varchar(255),
		stock int,
		lastModifiedBy varchar(255))";
		
		//orders
		$queryCreateOrder_tb = "create table if not exists weboms_order_tb(ID int PRIMARY KEY AUTO_INCREMENT, 
		user_id int, 
		order_id int,
		or_number int,
		status varchar(255),
		date datetime not null,
		totalOrder int,
		payment int,
		staffInCharge varchar(255))";

		$queryCreateOrdersDetail_tb = "create table if not exists weboms_ordersDetail_tb(id int PRIMARY KEY AUTO_INCREMENT, 
		order_id int(255), 
		quantity int,
		orderType int)";

		//feedback
		$queryCreateFeedback_tb = "create table if not exists weboms_feedback_tb(id int PRIMARY KEY AUTO_INCREMENT, 
		user_id int, 
		order_id int, 
		feedback varchar(255))";

		//topup
		$queryTopUp_tb = "create table if not exists weboms_topUp_tb(id int PRIMARY KEY AUTO_INCREMENT,
		user_id int,
		amount int,
		status varchar(255),
		proofOfPayment varchar(255),
		date datetime)";

		//company settings
		$queryCreateCompany_tb = "create table if not exists weboms_company_tb(id int PRIMARY KEY AUTO_INCREMENT, 
		name varchar(255), 
		address varchar(255), 
		tel varchar(255),
		description TEXT)";

		//cart
		$queryCreateCart_tb = "create table if not exists weboms_cart_tb(id int PRIMARY KEY AUTO_INCREMENT,
		user_id int,
		orderType int,
		qty int)";

		//rfid
		$queryCreateUsedRfid_tb = "create table if not exists weboms_usedRfid_tb(id int PRIMARY KEY AUTO_INCREMENT,
		rfid varchar(255))";

		if($conn->query($queryCreateMenu_tb)  && $conn->query($queryCreateUser_tb) && $conn->query($queryCreateUserInfo_tb)  && $conn->query($queryCreateOrder_tb) && $conn->query($queryCreateOrdersDetail_tb) && $conn->query($queryCreateFeedback_tb) && $conn->query($queryTopUp_tb) && $conn->query($queryCreateCompany_tb) && $conn->query($queryCreateCart_tb) && $conn->query($queryCreateUsedRfid_tb)) {
			$checkQuery = "select * from weboms_user_tb";
			if($resultSet = $conn->query($checkQuery)){  
				if($resultSet->num_rows <= 0){
					$hash = password_hash('password', PASSWORD_DEFAULT);

					$query = "select user_id from weboms_user_tb WHERE user_id = (SELECT MAX(user_id) from weboms_user_tb)";
					$lastUserId = null;
					if($resultSet = $conn->query($query)){  
						if($resultSet->num_rows > 0){
							foreach($resultSet as $row){
								$lastUserId = $row['user_id'];
							}
						}
					}
					else{
						die ($conn -> error);
					}

					$user_id = 1;
					$queryInsertAdmin = "insert into weboms_user_tb(username, password, accountType, user_id) values('admin','$hash','admin','$user_id')";
					$queryInsertAdminInfo = "insert into weboms_userInfo_tb(name, user_id) values('admin', '$user_id')";
					$queryInsertCompanyInfo = "insert into weboms_company_tb(name, address, tel, description) values('companyName', 'address', '0000', 'description')";
					if($conn->query($queryInsertAdmin) && $conn->query($queryInsertAdminInfo) && $conn->query($queryInsertCompanyInfo))
						echo  '<script> alert("Success creating table"); </script>';						
					}
			}
		}
		else 
			echo  '<script type="text/javascript">alert("Error creating table");</script>';						
	}
?>