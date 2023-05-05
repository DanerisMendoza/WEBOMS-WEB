<?php 
    $page = 'admin';
    include('../method/query.php');
    include('../method/checkIfAccountLoggedIn.php');

    // redefining name
    $_SESSION['name'] = getQueryOneVal2("select name from weboms_userInfo_tb where user_id = '$_SESSION[user_id]' ",'name');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>

    <link rel="stylesheet" href="../css/bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <script type="text/javascript" src="../js/bootstrap 5/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/jquery-3.6.1.min.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <!-- data tables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
</head>

<body>

    <div class="wrapper">
         <!-- Sidebar  -->
         <nav id="sidebar" class="bg-dark">
            <div class="sidebar-header bg-dark">
                <h3 class="mt-3"><a href="admin.php"><?php echo ucwords($_SESSION['accountType']); ?></a></h3>
            </div>
            <ul class="list-unstyled components ms-3">
                <li class="mb-2">
                    <a href="adminPos.php"><i class="bi bi-tag me-2"></i>Point of Sales</a>
                </li>
                <li class="mb-2">
                    <a href="adminOrders.php"><i class="bi bi-minecart me-2"></i>Orders</a>
                </li>
                <li class="mb-2">
                    <a href="adminOrdersQueue.php"><i class="bi bi-clock me-2"></i>Orders Queue</a>
                </li>
                <li class="mb-2">
                    <a href="topupRfid.php"><i class="bi bi-credit-card me-2"></i>Top-Up RFID</a>
                </li>
            
            <?php if($_SESSION['accountType'] != 'cashier'){?>
                <li class="mb-2 active">
                    <a href="adminInventory.php"><i class="bi bi-box-seam me-2"></i>Inventory</a>
                </li>
                <li class="mb-2">
                    <a href="adminSalesReport.php"><i class="bi bi-bar-chart me-2"></i>Sales Report</a>
                </li>
                <li class="mb-2">
                    <a href="accountManagement.php"><i class="bi bi-person-circle me-2"></i>Account Management</a>
                </li>
                <li class="mb-2">
                    <a href="adminFeedbackList.php"><i class="bi bi-chat-square-text me-2"></i>Customer Feedback</a>
                </li>
                <li class="mb-2">
                    <a href="adminTopUp.php"><i class="bi bi-cash-stack me-2"></i>Top-Up</a>
                </li>
                <li class="mb-1">
                    <a href="settings.php"><i class="bi bi-gear me-2"></i>Settings</a>
                </li>
            <?php } ?>
                <li>
                    <form method="post">
                        <button class="btn btnLogout btn-dark text-danger" id="Logout" name="logout"><i class="bi bi-power me-2"></i>Logout</button>
                    </form>
                </li>
            </ul>
        </nav>

        <!-- Page Content  -->
        <div id="content">
            <nav class="navbar navbar-expand-lg bg-light">
                <div class="container-fluid bg-transparent">
                    <button type="button" id="sidebarCollapse" class="btn" style="font-size:20px;"><i class="bi bi-list"></i> Toggle</button>
                </div>
            </nav>

            <!-- content here -->
            <div class="container-fluid text-center">
                <div class="row justify-content-center">

                    <!-- table -->
                    <div class="table-responsive col-lg-12 mb-5">
                        <table class="table table-bordered table-hover col-lg-12" id="tbl">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">IMAGE</th>
                                    <th scope="col">DISH</th>
                                    <th scope="col">PRICE</th>
                                    <th scope="col">STOCK</th>
                                    <th scope="col">LAST MODIFIED BY:</th>
                                    <th scope="col">
                                        <button id="addButton" type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#loginModal"><i class="bi bi-plus"></i> ADD NEW DISH</button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $query = "select * from weboms_menu_tb";
                                    $resultSet = getQuery2($query);
                                    if($resultSet != null){
                                    foreach($resultSet as $row){?>
                                <tr>
                                    <td><?php $pic = $row['picName']; echo "<img src='../dishesPic/$pic' style=width:150px; height:150px>";?></td>
                                    <td><?php echo ucwords($row['dish']);?></td>
                                    <td><?php echo '₱'. number_format($row['price'],2); ?></td>
                                    <td><?php echo $row['stock']; ?></td>
                                    <td><?php echo ucwords($row['lastModifiedBy']); ?></td>
                                    <!-- options -->
                                    <td>
                                        <a class="btn btn-warning" href="adminInventoryUpdate.php?idAndPicnameUpdate=<?php echo $row['orderType'].",".$row['dish'].",".$row['price'].",".$row['picName'].",".$row['stock']; ?>"><i class="bi bi-arrow-repeat"></i> UPDATE</a>
                                        <a class="btn btn-danger" href="?idAndPicnameDelete=<?php echo $row['orderType']." ".$row['picName']; ?>"><i class="bi bi-trash3-fill"></i> DELETE</a>
                                    </td>
                                </tr>
                                <?php } 
                                    }
			  	                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- add new dish modal -->
            <div class="modal fade" role="dialog" id="loginModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <form method="post" class="form-group" enctype="multipart/form-data">
                                <input type="text" class="form-control form-control-lg mb-3" name="dishes" placeholder="Enter dish name" required>
                                <input type="number" class="form-control form-control-lg mb-3" name="price" step="any" placeholder="Enter price" required>
                                <input type="number" class="form-control form-control-lg mb-3" name="stock" placeholder="Enter number of stock" required>
                                <input type="file" class="form-control form-control-lg mb-3" name="fileInput" required>
                                <button type="submit" class="btn btn-lg btn-success col-12" name="insert"><i class="bi bi-plus-circle"></i> Insert</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>

<?php
  //delete 
  if (isset($_GET['idAndPicnameDelete'])){
    $arr = explode(' ',$_GET['idAndPicnameDelete']);
    $id = $arr[0];
    $pic = $arr[1];
    $query = "DELETE FROM weboms_menu_tb WHERE orderType='$id' ";
    if(Query2($query)){
      unlink("dishespic/"."$pic");
      echo "<script> window.location.replace('adminInventory.php');</script>";
    }
  }

  //insert
  if(isset($_POST['insert'])){
  $dishes = $_POST['dishes'];
  $price = $_POST['price'];
  $file = $_FILES['fileInput'];
  $stock = $_POST['stock'];
  $fileName = $_FILES['fileInput']['name'];
  $name = $_SESSION['name'];

  $fileTmpName = $_FILES['fileInput']['tmp_name'];
  $fileSize = $_FILES['fileInput']['size'];
  $fileError = $_FILES['fileInput']['error'];
  $fileType = $_FILES['fileInput']['type'];
  $fileExt = explode('.',$fileName);
  $fileActualExt = strtolower(end($fileExt));
  $allowed = array('jpg','jpeg','png');
  if(in_array($fileActualExt,$allowed)){
      if($fileError === 0){
          if($fileSize < 10000000){
              $fileNameNew = uniqid('',true).".".$fileActualExt;
              $fileDestination = '../dishesPic/'.$fileNameNew;
              move_uploaded_file($fileTmpName,$fileDestination);         
              $query = "insert into weboms_menu_tb(dish, price, picName, stock, lastModifiedBy) values('$dishes','$price','$fileNameNew','$stock','$name')";
              if(Query2($query));
                echo "<script>window.location.replace('adminInventory.php')</script>";                                
          }
          else
              echo "Your file is too big!";
      }
      else
          echo "There was an error uploading your file!";
  }
  else
      echo "You cannot upload files of this type!";     
  }
?>

<script>
// for data tables
$(document).ready(function() {
    $('#tbl').DataTable();
});

// sidebar toggler
$(document).ready(function() {
    $('#sidebarCollapse').on('click', function() {
        $('#sidebar').toggleClass('active');
    });
});
</script>

<?php 
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
                $updateQuery = "UPDATE weboms_menu_tb SET stock = (stock + '$dishesQuantity[$i]') WHERE dish= '$dishesArr[$i]' ";    
                Query2($updateQuery);    
            }
        }
        session_destroy();
        echo "<script>window.location.replace('../general/login.php');</script>";
    }
?>