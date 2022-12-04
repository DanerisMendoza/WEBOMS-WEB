<?php 
    $page = 'admin';
    include('method/checkIfAccountLoggedIn.php');
    $_SESSION['query'] = 'all';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin</title>

    <link rel="stylesheet" href="css/bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" href="css/admin.css">
    <script type="text/javascript" src="js/bootstrap 5/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>

<body id="body-pd">

    <header class="header" id="header">
        <div class="header_toggle">
            <i class="bi bi-list" id="header-toggle"></i>
            Dashboard
        </div>
    </header>
    <div class="l-navbar bg-dark" id="nav-bar">
        <nav class="nav">
            <div class="text-white">
                <a href="#" class="nav_logo">
                    <!-- logo dito -->
                    <i class="bi bi-bootstrap text-white"></i>
                    <span class="nav_logo-name">ADMIN</span>
                </a>
                <div class="nav_list">
                    <a href="adminPos.php" class="nav_link">
                        <i class="bi bi-tag"></i>
                        <span class="nav_name" id="pos">POINT OF SALES</span>
                    </a>
                    <a href="adminOrders.php" class="nav_link">
                        <i class="bi bi-minecart"></i>
                        <span class="nav_name" id="orders">ORDERS</span>
                    </a>
                    <a href="adminOrdersQueue.php" class="nav_link">
                        <i class="bi bi-clock"></i>
                        <span class="nav_name" id="ordersQueue">ORDERS QUEUE</span>
                    </a>
                    <a href="adminInventory.php" class="nav_link">
                        <i class="bi bi-box-seam"></i>
                        <span class="nav_name" id="inventory">INVENTORY</span>
                    </a>
                    <a href="adminSalesReport.php" class="nav_link">
                        <i class="bi bi-bar-chart"></i>
                        <span class="nav_name" id="salesReport">SALES REPORT</span>
                    </a>
                    <a href="accountManagement.php" class="nav_link">
                        <i class="bi bi-person-circle"></i>
                        <span class="nav_name" id="accountManagement">ACCOUNT <br> MANAGEMENT</span>
                    </a>
                    <a href="customerFeedbackList.php" class="nav_link">
                        <i class="bi bi-chat-square-text"></i>
                        <span class="nav_name" id="customerFeedback">CUSTOMER <br> FEEDBACK</span>
                    </a>
                    <a href="adminTopUp.php" class="nav_link">
                        <i class="bi bi-cash-stack"></i>
                        <span class="nav_name" id="adminTopUp">TOP UP</span>
                    </a>
                </div>
            </div>
            <!-- <form method="POST">
                <a href="" class="nav_link text-danger" id="Logout" name="logout">
                    <i class="bi bi-power"></i>
                    <span class="nav_name">LOGOUT</span>
                </a>
            </form> -->
            <form method="post">
                <button class="btn btn-dark col-12 mb-3 nav_link text-danger" id="Logout" name="logout">
                    <i class="bi bi-power"></i>
                    <span class="nav_name">LOGOUT</span>
                </button>
            </form>
        </nav>
    </div>

    <!-- Container Main start -->
    <div class="height-100 text-center">
        <h1 class="fw-normal"
            style="margin:0; position:absolute; top:40%; left:50%; transform:translate(-50%, -50%); letter-spacing:5px;">
            Welcome <br>
            <?php echo $_SESSION['name'].'('.$_SESSION['accountType'].')';?>
        </h1>
    </div>

</body>

</html>

<?php 
    include('method/query.php');
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

<script>
document.addEventListener("DOMContentLoaded", function(event) {

    const showNavbar = (toggleId, navId, bodyId, headerId) => {
        const toggle = document.getElementById(toggleId),
            nav = document.getElementById(navId),
            bodypd = document.getElementById(bodyId),
            headerpd = document.getElementById(headerId)

        // Validate that all variables exist
        if (toggle && nav && bodypd && headerpd) {
            toggle.addEventListener('click', () => {
                // show navbar
                nav.classList.toggle('show')
                // change icon
                toggle.classList.toggle('bx-x')
                // add padding to body
                bodypd.classList.toggle('body-pd')
                // add padding to header
                headerpd.classList.toggle('body-pd')
            })
        }
    }

    showNavbar('header-toggle', 'nav-bar', 'body-pd', 'header')

    /*===== LINK ACTIVE =====*/
    const linkColor = document.querySelectorAll('.nav_link')

    function colorLink() {
        if (linkColor) {
            linkColor.forEach(l => l.classList.remove('active'))
            this.classList.add('active')
        }
    }
    linkColor.forEach(l => l.addEventListener('click', colorLink))

    // Your code to run since DOM is loaded and ready
});
</script>

<script>
document.getElementById("pos").onclick = function() {
    window.location.replace('adminPos.php');
};
document.getElementById("orders").onclick = function() {
    window.location.replace('adminOrders.php');
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
document.getElementById("customerFeedback").onclick = function() {
    window.location.replace('customerFeedbackList.php');
};
document.getElementById("adminTopUp").onclick = function() {
    window.location.replace('adminTopUp.php');
};
</script>