<!DOCTYPE html>
<html><head>
<title></title>
<link rel="stylesheet" type="text/css" href="css/bootstrap.css"></head>
<body>
<div class="container text-center">
<?php
    $idAndPicname = explode(' ',$_GET['idAndPicnameUpdate']);    
    $id = $idAndPicname[0];
    $dish = $idAndPicname[1];
    $price = $idAndPicname[2];
    $picName = $idAndPicname[3];
    $cost = $idAndPicname[4];
    echo "</br>dish: ".$dish."</br>price: ".$price."</br>picname: ".$picName."</br> cost: ".$cost."<br></br>";
?>
 <form method="post" class="form-group" enctype="multipart/form-data">
<input type="text" class="form-control" name="dish" placeholder="dish"></br>
<input type="number" class="form-control" name="price" placeholder="price"></br>
<input type="number" class="form-control" name="cost" placeholder="cost"></br>
<input type="file"  name="fileInput"></br>
<button type="button" class="btn-success col-sm-12" id="cancel">Cancel</button>
<button type="submit" class="btn-success col-sm-12" name="update">Update</button>
<?php
    if(isset($_POST['update'])){
        $dish = $_POST['dish'];
        $price = $_POST['price'];
        $cost = $_POST['cost'];
        if(empty($dish) || empty($price) || empty($cost) || $_FILES['fileInput']['name']==''){
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
                    cost = '$cost'
                    WHERE orderType=$id ";        
                    if(mysqli_query($conn,$updateQuery)){
                        echo '<script>alert("Sucess updating the database!");</script>';       
                        unlink("dishespic/".$picName);                                        
                    }
                    else{
                        echo '<script type="text/javascript">alert("failed to save to database");</script>';  
                    }
                    echo "<script>window.location.replace('inventory.php')</script>";                                
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
 </form>
</div>
</body>
</html>
<style>
    body{
        background-color: black;
        color: white;
    }
</style>
<script>
    document.getElementById("cancel").addEventListener("click",function(){
        window.location.replace('inventory.php');
    });
</script>