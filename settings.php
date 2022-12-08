<?php
  $page = 'admin';
  include('method/checkIfAccountLoggedIn.php');
  include_once('method/query.php');
  $query = "select * from WEBOMS_company_tb";
  $resultSet = getQuery($query);
  if($resultSet!=null)
    foreach($resultSet as $row){
        $name = $row['name'];
        $address = $row['address'];
        $tel = $row['tel'];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <!-- for modal -->
    <link rel="stylesheet" href="css/bootstrap 5/bootstrap.min.css">
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
            <form method="post">
                <input type="text" name="name" placeholder="Enter New Company Name" class="form-control form-control-lg mb-3" value="<?php echo $name; ?>" required></textarea>
                <input type="text" name="address" placeholder="Enter New Company Address" class="form-control form-control-lg mb-3" value="<?php echo $address; ?>" required></textarea>
                <input type="number" name="tel" placeholder="Enter New Company Telephone Number" class="form-control form-control-lg mb-3" value="<?php echo $tel; ?>" required></textarea>
                <button type="submit" name="update" class="btn btn-lg btn-success col-12">update</button>
            </form>
            <script>
                document.getElementById("back").onclick = function() {
                    window.location.replace('admin.php');
                };
            </script>
        </div>
    </body>
</html>
<?php 
    // update company_tb process
    if(isset($_POST['update'])){
        $name = $_POST['name'];
        $address = $_POST['address'];
        $tel = $_POST['tel'];
        $query = "update WEBOMS_company_tb SET name = '$name', address = '$address', tel = '$tel'";
        if(Query($query)){
            echo "<script>alert('SUCCESS!'); window.location.replace('settings.php');</script>";
        }
    }
?>