<!DOCTYPE html>
<html>
<head>
    <title>Costumer - Feedback</title>
        
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
    <link rel="stylesheet" type="text/css" href="css/style.css">
    
</head>
<body class="bg-light">
    
<div class="container text-center">
    <div class="row justify-content-center">
        <h1 class="font-weight-normal mt-5 mb-4 text-center">Feedback</h1>
        <button class="btn btn-lg btn-danger col-12 mb-3" id="orderList">Order List</button>
        <script>document.getElementById("orderList").onclick = function () {window.location.replace('customerOrdersList.php'); }; </script> 
            
        <div class="col-lg-12">
            <form method="post">
                <textarea name="feedback" placeholder="Enter your feedback" cols="30" rows="5" class="form-control form-control-lg mb-3" required></textarea>
                <button type="submit" name="submit" class="btn btn-lg btn-success col-12">Submit</button>
            </form>
        </div>
	</div>
</div>
    
</body>
</html>
<?php 
    if(isset($_POST['submit'])){
        $arr = explode(',',$_GET['ordersLinkIdAndUserLinkId']);
        $ordersLinkId = $arr[0];
        $userLinkId = $arr[1];
        $feedback = $_POST['feedback'];
        include('method/Query.php');
        $query = "insert into feedback_tb(feedback, ordersLinkId, userLinkId) values('$feedback', '$ordersLinkId', '$userLinkId')";
        if(Query($query))
            echo "<script>alert('feedback sent thanks!'); window.location.replace('customerOrdersList.php');</script>";
    }
?>