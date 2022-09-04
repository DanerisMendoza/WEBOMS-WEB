<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
</head>
<body>
  <div class="container text-center">
    <button id="addButton" type="button" class="btn" data-toggle="modal" data-target="#loginModal">Add new dish</button>
    <button class="btn" id="pos">POS</button>
    <button class="btn" id="Logout">Logout</button>
    <script>
    document.getElementById("pos").onclick = function () {window.location.replace('pos.php'); };
    document.getElementById("Logout").onclick = function () {window.location.replace('login.php'); 
    };</script>
   
		<div class="col-lg-12">
			<?php 
      include_once('connection.php');
      $sql = mysqli_query($conn,"select * from dishes_tb");  
      if (mysqli_num_rows($sql)) {  
      ?>
			<table class="table table-striped" border="10">
			  <thead>
			    <tr>	
			      <th scope="col">Dishes</th>
			      <th scope="col">cost</th>
				    <th scope="col">picture</th>
			    </tr>
			  </thead>
			  <tbody>
			  	<?php 
			  	   $i = 0;
			  	   while($rows = mysqli_fetch_assoc($sql)){
			  	   $i++;
			  	 ?>
				 
			    <tr>	   
          <td><?=$rows['dish']?></td>
          <td><?php echo '₱'.$rows['cost']; ?></td>
				  <td><?php $pic = $rows['picName']; echo "<img src='dishesPic/$pic' style=width:100px;height:100px>";?></td>
				  <td><a href="?idAndPicnameDelete=<?php echo $rows['ID']." ".$rows['picName'] ?>">Delete</a></td>
				  <td ><a href="updatePage.php?idAndPicnameUpdate=<?php echo $rows['ID']." ".$rows['dish']." ".$rows['cost']." ".$rows['picName']." update" ?>"  >Update</a></td>
			    </tr>
			    <?php } ?>
			  </tbody>
			</table>
			<?php } ?>
		</div>
  </div>
  
  <div class="modal fade" role="dialog" id="loginModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body ">
                <form method="post" class="form-group" enctype="multipart/form-data">
                    <input type="text" class="form-control" name="dishes" placeholder="dishes">
                    <input type="number" class="form-control" name="cost" placeholder="cost">
                    <input type="file"  name="fileInput">
                    <button type="submit" class="btn-success col-sm-12" name="insert">insert</button>
                </form>
                <?php
                //use $_post when using method post otherwise use $_get
                               
                //calling delete function
                if (isset($_GET['idAndPicnameDelete']))   
                  delete($_GET['idAndPicnameDelete'],$conn);

                //insert
                if(isset($_POST['insert'])){
                $dishes = $_POST['dishes'];
                $cost = $_POST['cost'];
                if(empty($dishes) || empty($cost))
                     echo "<script>alert('Please complete the details!'); window.location.replace('admin.php');</script>";
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
                            if(mysqli_query($conn,"insert into dishes_tb(dish, cost, picName) values('$dishes','$cost','$fileNameNew')")){
                                echo '<script>alert("Sucess saving to database!");</script>';                                               
                            }
                            else{
                                echo '<script type="text/javascript">alert("failed to save to database");</script>';  
                            }
                            echo "<script>window.location.replace('admin.php')</script>";                                
                        }
                        else
                            echo "your file is too big!";
                    }
                    else
                        echo "there was an error uploading your file!";
                }
                else
                    echo "you cannot upload files of this type";     
                }
                ?>
            </div>
        </div>
    </div>
  </div>
<script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
</body>
</html>

<style>
  body{
    background-color: black;
    color: white;
  }
  .btn{
    background-color: greenyellow;
  }

</style>

<?php 
  //delete
  function delete($idAndPicname,$conn){
  $idAndPicArr = explode(' ',$idAndPicname);
  echo $idAndPicArr[1];
  $sql = "DELETE FROM dishes_tb
        WHERE id=$idAndPicArr[0]";
  $result = mysqli_query($conn, $sql);
  if (!$result){
    echo "<script>alert('Delete data unsuccessfully'); window.location.replace('admin.php');</script>";  
    echo "<script> window.location.replace('admin.php');</script>";
  }
  unlink("dishespic/".$idAndPicArr[1]);
  echo "<script> window.location.replace('admin.php'); alert('Delete data successfully'); </script>";  
}
?>

