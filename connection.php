<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "ordering_db";

// Connect to MySQL but don't specify db name else terminate
$conn = mysqli_connect($dbhost,$dbuser,$dbpass);
if($conn->connect_error)
	die("Connection failed: " . $conn->connect_error);
//check db if exist create if no exist
$db_selected = mysqli_select_db($conn, $dbname);
if (!$db_selected) {
	$sql = "CREATE DATABASE ".$dbname;
	if ($conn->query($sql) === TRUE){ 
		//connect to db first then create table
		$conn = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);	

		$queryCreateMenu_tb = "create table if not exists menu_tb(orderType int PRIMARY KEY AUTO_INCREMENT, 
		dish varchar(255),
		price int,
		picName varchar(255),
		stock int)";
		

		$queryCreateUser_tb = "create table if not exists user_tb(id int PRIMARY KEY AUTO_INCREMENT,
		username varchar(255),
		password varchar(255),
		accountType varchar(255),
		userLinkId varchar(255))";

		$hash = password_hash('password', PASSWORD_DEFAULT);
		$userLinkId = uniqid('',true);

		$queryInsertAdmin = "insert into user_tb(username, password, accountType, userLinkId) values('admin','$hash','admin','$userLinkId')";
		$queryInsertAdminInfo = "insert into userInfo_tb(name, email, otp, userLinkId) values('admin','','','$userLinkId')";

		$queryCreateUserInfo_tb = "create table if not exists userInfo_tb(id int PRIMARY KEY AUTO_INCREMENT,
		name varchar(255),
		email varchar(255),
		otp varchar(255),
		userLinkId varchar(255))";
	
		$queryCreateOrder_tb = "create table if not exists order_tb(ID int PRIMARY KEY AUTO_INCREMENT, 
		proofOfPayment varchar(255), 
		userLinkId varchar(255), 
		status varchar(255),
		ordersLinkId varchar(255),
		date datetime not null,
		totalOrder int,
		staffInCharge varchar(255))";

		$queryCreateOrdersDetail_tb = "create table if not exists ordersDetail_tb(id int PRIMARY KEY AUTO_INCREMENT, 
		ordersLinkId varchar(255), 
		quantity int,
		orderType int)";

		$queryCreateFeedback_tb = "create table if not exists feedback_tb(id int PRIMARY KEY AUTO_INCREMENT, 
		feedback varchar(255), 
		ordersLinkId varchar(255), 
		userlinkId int)";

		if ($conn->query($queryCreateMenu_tb)  && $conn->query($queryCreateUser_tb) && $conn->query($queryCreateUserInfo_tb)  && $conn->query($queryInsertAdmin) && $conn->query($queryInsertAdminInfo)   && $conn->query($queryCreateOrder_tb) && $conn->query($queryCreateOrdersDetail_tb) && $conn->query($queryCreateFeedback_tb)) 
			echo '<script type="text/javascript">alert("Database and Table created successfully");</script>';
		else 
			echo  '<script type="text/javascript">alert("Error creating table: ");</script>'. $conn->error;						
	}
	else 
		echo '<script type="text/javascript">alert("Error creating database:");</script>' .$conn->error;
}

?>

