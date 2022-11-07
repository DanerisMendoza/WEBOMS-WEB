<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
    <div class="container text-center">
        <button class="btn btn-success col-sm-4" id="orderList">Order List</button>
        <script>document.getElementById("orderList").onclick = function () {window.location.replace('customerOrdersList.php'); }; </script> 
            <div class="col-lg-12">
                <form method="post">
                    </br>
                    <textarea  name="feedback" placeholder="Enter your feedback" cols="30" rows="5" required></textarea>
                    <button type="submit" name="submit">Submit</button>
                </form>
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
        include_once('dishesClass.php');
        $dish = new dish($feedback,$ordersLinkId,$userLinkId);
        $dish -> giveFeedBackToDish();
    }
?>