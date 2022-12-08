<?php 
    $page = 'customer';
    include('method/checkIfAccountLoggedIn.php');
    if(!isset($_SESSION["dishes"]) || !isset($_SESSION["price"]) || !isset($_SESSION["orderType"])){
        $_SESSION["dishes"] = array();
        $_SESSION["price"] = array(); 
        $_SESSION["orderType"] = array(); 
    }
    $_SESSION['multiArr'] = array();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Costumer Menu</title>

    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <script type="text/javascript" src="js/bootstrap.js"></script>
        <!-- data tables -->
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

</head>

<body class="bg-light">
    <div class="container text-center mt-5">
        <div class="row justify-content-center">
            <button type="button" class="btn btn-lg btn-dark col-12 mb-3" id="back">Back</button>
            <button class="btn btn-lg btn-success col-6 mb-4" id="customersFeedback">Customers Feedback</button>
            <button type="button" class="btn btn-lg btn-success col-6 mb-4" id="viewCart">View Cart</button>
            <div class="table-responsive col-lg-12">
                <table id="tbl" class="table table-striped table-bordered col-lg-12 mb-5">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th scope="col">DISH</th>
                            <th scope="col">PRICE</th>
                            <th scope="col">STOCK</th>
                            <th scope="col">IMAGE</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        include_once('method/query.php');
                        $query = "select * from WEBOMS_menu_tb";
                        $resultSet =  getQuery($query);
                        if($resultSet != null)
                            foreach($resultSet as $row){ ?>
                        <tr>
                            <td><?=$row['dish']?></td>
                            <td><?php echo $row['price']; ?></td>
                            <td><?php echo $row['stock']; ?></td>
                            <td><?php $pic = $row['picName']; echo "<img src='dishesPic/$pic' style=width:100px;height:100px>";?>
                            </td>
                            <td><a class="btn btn-light border-dark" <?php   if($row['stock'] <= 0) 
                                                echo "<button>Out of stock</button>";
                                            else{
                                    ?>
                                    href="?order=<?php echo $row['dish'].",".$row['price'].",".$row['orderType']?>">Add
                                    To Cart</a><?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>
<?php 
    //add to cart
	if(isset($_GET['order'])){
        $order = explode(',',$_GET['order']);  
        $dish = $order[0];
        $price = $order[1];
		$orderType = $order[2];
        array_push($_SESSION['dishes'], $dish);
        array_push($_SESSION['price'], $price);
        array_push($_SESSION['orderType'], $orderType);
        $updateQuery = "UPDATE WEBOMS_menu_tb SET stock = (stock - 1) WHERE dish= '$dish' ";    
        if(Query($updateQuery))
          echo "<script>window.location.replace('customerMenu.php');</script>";    
    }				
?>

<script>
document.getElementById("back").onclick = function() {
    window.location.replace('customer.php');
};
document.getElementById("viewCart").onclick = function() {
    window.location.replace('customerCart.php');
};
document.getElementById("customersFeedback").onclick = function() {
    window.location.replace('customerFeedbackList.php');
};
</script>

<script>
$(document).ready(function() {
    $('#tbl').DataTable();
});

// $('#tbl').dataTable( {
//       "aoColumnDefs": [
//           { 'bSortable': false, 'aTargets': [ 3 ] }
//        ]
// });

// $('#tbl').tablesorter({
//         headers: {
//             0: { sorter: false },
//             4: { sorter: false }
//         }
//     });

// $(document).ready(function() {
//     $('#tbl').dataTable( {
//         "aaSorting": [[ 1, "desc" ]]
//     } );
// } );
</script>