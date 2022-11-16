<!DOCTYPE html>
<html>
<head>
    <title>Admin Inventory - Update</title>

    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">

</head>
<body class="bg-light">

<div class="container text-center">
    <div class="row justify-content-center">
        <h1 class="font-weight-normal mt-5 mb-4 text-center">Inventory Update</h1>
        <?php
            $idAndPicname = explode(' ',$_GET['idAndPicnameUpdate']);    
            $id = $idAndPicname[0];
            $dish = $idAndPicname[1];
            $price = $idAndPicname[2];
            $picName = $idAndPicname[3];
            $cost = $idAndPicname[4];
            $stock = $idAndPicname[5];
            // echo 
            // "</br>dish: ".$dish.
            // "</br>price: ".$price.
            // "</br> cost: ".$cost.
            // "</br> stock: ".$stock.
            // "</br>picname: ".$picName.
            // "<br></br>";
        ?>

        <div class="table-responsive col-lg-12">
            <table class="table table-striped table-bordered mb-4">
                <tr>
                    <td><b>DISH:</b></td>
                    <td><?php echo $dish ?></td>
                </tr>
                <tr>
                    <td><b>PRICE:</b></td>
                    <td><?php echo $price ?></td>
                </tr>
                <tr>
                    <td><b>COST:</b></td>
                    <td><?php echo $cost ?></td>
                </tr>
                <tr>
                    <td><b>STOCK:</b></td>
                    <td><?php echo $stock ?></td>
                </tr>
                <tr>
                    <td><b>IMAGE (FILE NAME):</b></td>
                    <td><?php echo $picName ?></td>
                </tr>
            </table>
        </div>

        <form method="post" class="form-group" enctype="multipart/form-data">
            <input type="text" class="form-control form-control-lg mb-3" name="dish" placeholder="Enter New Dish Name" required>
            <input type="number" class="form-control form-control-lg mb-3" name="price" placeholder="Enter New Price" required>
            <input type="number" class="form-control form-control-lg mb-3" name="cost" placeholder="Enter New Cost" required>
            <input type="number" class="form-control form-control-lg mb-3" name="stock" placeholder="Enter New Number of Stock" required>
            <input type="file" class="form-control form-control-lg mb-3" name="fileInput" required>
            <button type="button" class="btn btn-lg btn-danger col-12 mb-3" id="cancel">Cancel</button>
            <button type="submit" class="btn btn-lg btn-success col-12" name="update">Update</button>
        </form>
    </div>
</div>

</body>
</html>

<?php
    //if update button click
    if(isset($_POST['update'])){
        $dish = $_POST['dish'];
        $price = $_POST['price'];
        $cost = $_POST['cost'];
        $stock = $_POST['stock'];
        if(empty($dish) || empty($price) || empty($cost) || empty($stock) || $_FILES['fileInput']['name']==''){
        echo "<script>alert('Please complete the details! ');</script>";
        return;    
        }
        include_once('connection.php');
        $file = $_FILES['fileInput'];
        $fileName = $_FILES['fileInput']['name'];
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
                    $fileDestination = 'dishesPic/'.$fileNameNew;
                    move_uploaded_file($fileTmpName,$fileDestination);         
                    $updateQuery = "UPDATE dishes_tb
                    SET dish='$dish', 
                    price='$price',
                    picName = '$fileNameNew',
                    cost = '$cost',
                    stock =  '$stock'
                    WHERE orderType=$id ";        
                    if($conn->query($updateQuery)){
                        echo '<script>alert("Sucess updating the database!");</script>';       
                        unlink("dishespic/".$picName);                                        
                    }
                    else{
                        echo '<script type="text/javascript">alert("failed to save to database");</script>';  
                    }
                    echo "<script>window.location.replace('adminInventory.php')</script>";                                
                }
                else
                    echo "your file is too big!";
            }
            else
                echo "there was an error uploading your file!";
        }
        else
            echo "you cannot upload files of this type";  

        $result = mysqli_query($conn, $sql);
        if ($result) {
            header("Location: ../read.php?success=successfully updated");
        }else {
            header("Location: ../update.php?id=$id&error=unknown error occurred&$user_data");
        }
    }
?>

<script>
    document.getElementById("cancel").addEventListener("click",function(){
        window.location.replace('adminInventory.php');
    });
</script>