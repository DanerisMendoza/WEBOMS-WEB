<?php
  $page = 'customer';
  include('method/checkIfAccountLoggedIn.php');
  include_once('method/query.php');
  $query = "select a.user_id, b.name, c.feedback from WEBOMS_order_tb a inner join WEBOMS_userInfo_tb b on a.user_id = b.user_id inner join WEBOMS_feedback_tb c on c.user_id = a.user_id";
  $resultSet =  getQuery($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Feedback</title>
    <!-- for modal -->
    <link rel="stylesheet" href="css/bootstrap 5/bootstrap.min.css">
    <script type="text/javascript" src="js/bootstrap 5/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>

<body>

    <div class="container text-center mt-5">
        <!-- back button -->
        <button class="btn btn-lg btn-dark col-12 mb-4" id="back">
            <i class="bi bi-arrow-left me-1"></i>
            BACK
        </button>
        <script>
        document.getElementById("back").onclick = function() {
            window.location.replace('customerMenu.php');
        };
        </script>

        <!-- table -->
        <div class="table-responsive col-lg-12">
            <table class="table table-bordered col-lg-12">
                <thead>
                    <tr>
                        <th scope="col">NAME</th>
                        <th scope="col">FEEDBACK</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if($resultSet!= null)
                        foreach($resultSet as $row){ ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['feedback'];?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php ?>
        </div>
    </div>

</body>

</html>

<script>
// for navbar click locations
document.getElementById("pos").onclick = function() {
    window.location.replace('adminPos.php');
};
document.getElementById("orders").onclick = function() {
    window.location.replace('adminOrders.php');
};
document.getElementById("ordersQueue").onclick = function() {
    window.location.replace('adminOrdersQueue.php');
};
document.getElementById("inventory").onclick = function() {
    window.location.replace('adminInventory.php');
};
document.getElementById("salesReport").onclick = function() {
    window.location.replace('adminSalesReport.php');
};
document.getElementById("accountManagement").onclick = function() {
    window.location.replace('accountManagement.php');
};
document.getElementById("adminTopUp").onclick = function() {
    window.location.replace('adminTopUp.php');
};
</script>

<script>
// sidebar toggler
$(document).ready(function() {
    $('#sidebarCollapse').on('click', function() {
        $('#sidebar').toggleClass('active');
    });
});
</script>

<?php 
// logout
    if(isset($_POST['logout'])){
        $dishesArr = array();
        $dishesQuantity = array();
        if(isset($_SESSION['dishes'])){
            for($i=0; $i<count($_SESSION['dishes']); $i++){
                if(in_array( $_SESSION['dishes'][$i],$dishesArr)){
                    $index = array_search($_SESSION['dishes'][$i], $dishesArr);
                }
                else{
                    array_push($dishesArr,$_SESSION['dishes'][$i]);
                }
            }
            foreach(array_count_values($_SESSION['dishes']) as $count){
                array_push($dishesQuantity,$count);
            }
            for($i=0; $i<count($dishesArr); $i++){ 
                $updateQuery = "UPDATE WEBOMS_menu_tb SET stock = (stock + '$dishesQuantity[$i]') WHERE dish= '$dishesArr[$i]' ";    
                Query($updateQuery);    
            }
        }
        session_destroy();
        echo "<script>window.location.replace('Login.php');</script>";
    }
?>