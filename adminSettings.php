
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script> 
        <script type="text/javascript" src="js/bootstrap.js"></script>

    </head>
    <body>
        <div class="container text-center">
            <button class="btn btn-success col-sm-4" id="admin">Admin</button>
                <table class="table" border="11">
               
                    <tr>
                        <td>Background:</td>
                        <td>
                        <form method="post" class="form-group" enctype="multipart/form-data">
                        <input type="file" name="fileInput">
                        <button type="submit" name="change1">Change</button>
                        </form>
                        </td>
                    </tr>
                    <tr>
                        <td>Logo:</td>
                        <td>
                        <form method="post" class="form-group" enctype="multipart/form-data">
                        <input type="file" name="fileInput">
                        <button type="submit" name="change2">Change</button>
                        </form>
                        </td>
                    </tr>
                 
                </table>
             
        </div>
    </body>
</html>
<style>
     body{
    background-image: url(settings/bg.jpg);
    background-size: cover;
    background-attachment: fixed;
    background-repeat: no-repeat;
    background-position: center;
    color: white;
    font-family: 'Josefin Sans',sans-serif;
    }
    .container{
     padding: 1%;
     margin-top: 2%;
     background: gray;
   }
</style>
<script>
    document.getElementById("admin").onclick = function () {window.location.replace('admin.php'); };
</script>

<?php
      if(isset($_POST['change1'])){
        $file = $_FILES['fileInput'];
        if($_FILES['fileInput']['name']==''){
            echo "<script>alert('Please Insert a Pic!'); window.location.replace('settings.php');</script>";
            return;
        }
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
                    unlink("settings/bg.jpg");
                      $fileNameNew = "bg.jpg";
                      $fileDestination = 'settings/'.$fileNameNew;
                      move_uploaded_file($fileTmpName,$fileDestination);           
                      header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
                      header("Pragma: no-cache"); // HTTP 1.0.
                      header("Expires: 0");       
                    //   header("location:settings.php");
                      echo "<script>window.location.replace('settings.php')</script>"; 

                                                  
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