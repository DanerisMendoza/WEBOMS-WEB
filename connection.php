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



		$queryCreateDishesTb = "create table if not exists dishes_tb(orderType int PRIMARY KEY AUTO_INCREMENT, 
		dish varchar(255),
		price int,
		picName varchar(255),
		cost int)";
		
		$queryCreateAdminTb = "create table if not exists admin_tb(ID int PRIMARY KEY AUTO_INCREMENT, 
		admin varchar(255),
		password varchar(255))";

		$hash = password_hash('password', PASSWORD_DEFAULT);
		$query3 = "insert into admin_tb(admin, password) values('admin','$hash')";

		$queryCreateUserTb = "create table if not exists user_tb(userlinkId int PRIMARY KEY AUTO_INCREMENT,
		username varchar(255),
		name varchar(255),
		email varchar(255),
		otp varchar(255),
		password varchar(255))";

		$queryCreateOrderListTb = "create table if not exists orderList_tb(ID int PRIMARY KEY AUTO_INCREMENT, 
		proofOfPayment varchar(255), 
		userlinkId int, 
		status tinyint,
		ordersLinkId varchar(255))";

		$queryCreateOrderTb = "create table if not exists order_tb(id int PRIMARY KEY AUTO_INCREMENT, 
		ordersLinkId varchar(255), 
		quantity int,
		orderType int)";

		

		if ($conn->query($queryCreateDishesTb) === TRUE && $conn->query($queryCreateAdminTb) === TRUE && $conn->query($query3) === TRUE && $conn->query($queryCreateUserTb) === TRUE && $conn->query($queryCreateOrderListTb) === TRUE  && $conn->query($queryCreateOrderTb) === TRUE) 
			echo '<script type="text/javascript">alert("Database and Table created successfully");</script>';
		else 
			echo  '<script type="text/javascript">alert("Error creating table: ");</script>'. $conn->error;						
	}
	else 
		echo '<script type="text/javascript">alert("Error creating database:");</script>' .$conn->error;
}

?>

