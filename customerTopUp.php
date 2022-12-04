<?php 
    $page = 'customer';
    include('method/checkIfAccountLoggedIn.php');
    include('method/query.php');
    $user_id = $_SESSION['user_id'];
    $query = "SELECT a.*,b.name FROM `WEBOMS_topUp_tb` a inner join WEBOMS_userInfo_tb b on a.user_id = b.user_id where a.user_id = '$user_id' order by a.id desc";
    $resultSet =  getQuery($query);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>TopUp</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>  
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
    </head>
    <body>
    <body>
        <br></br>
        <div class="container">
        <div class="container text-center mt-5">
                <button class="btn btn-lg btn-dark col-12 mb-3" id="back">Back</button>
                    <form method="post" enctype="multipart/form-data" class="col-12">   
                        <select name="amount" class="form-control form-control-lg col-12 mb-3">
                            <option value="100">₱100</option>  
                            <option value="300">₱300</option>  
                            <option value="500">₱500</option>  
                            <option value="1000">₱1000</option>  
                            <option value="3000">₱3000</option>  
                            <option value="5000">₱5000</option>  
                        </select>     
                        <label for="fileInput">PROOF OF PAYMENT:</label>
                        <input type="file" class="form-control form-control-lg mb-3" name="fileInput" required>
                        <button class="btn btn-lg btn-success col-12 mb-3" name="submit">TopUp</button>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered mb-5 col-lg-12">
                            <thead class="table-dark">
                            <tr>	
                            <th scope="col">NAME</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Proof Of Payment</th>
                            <th scope="col">Status</th>
                            <th scope="col">Date</th>
                            <th scope="col" colspan="3">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if($resultSet!= null)
                            foreach($resultSet as $row){ ?>
                            <tr>	   
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo '₱'.$row['amount'];?></td>
                                <td><?php echo $row['proofOfPayment'];?></td>
                                <td><?php echo $row['status'];?></td>
                                <td><?php echo date('m/d/Y h:i a ', strtotime($row['date']));?></td>
                                <?php if($row['status'] != 'approved'){?>
                                <td><a class="btn btn-primary border-dark" href="?viewPic=<?php echo $row['proofOfPayment'];?>">View</a></td>
                                <td><a class="btn btn-danger border-dark" href="?cancel=<?php echo $row['id'].','.$row['proofOfPayment'];?>">Cancel</a></td>
                                <?php }else{ ?>
                                <td colspan="2"><a class="btn btn-primary border-dark" href="?viewPic=<?php echo $row['proofOfPayment'];?>">View</a></td>
                                <?php }?>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- pic (Bootstrap MODAL) -->
                    <div class="modal fade" id="viewPic" role="dialog" >
                    <div class="modal-dialog">
                        <div class="modal-content container">
                            <div class="modal-body">
                                <?php  echo "<img src='payment/$_GET[viewPic]' style=width:300px;height:500px>";?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </body>
</html>
<?php 
    //view pic
    if(isset($_GET['viewPic'])){
        echo "<script>$('#viewPic').modal('show');</script>";
    }
    //top up button
    if(isset($_POST['submit'])){
        //init
        $amount = $_POST['amount'];+
        date_default_timezone_set('Asia/Manila');
        $date = new DateTime();
        $todayWithTime =  $date->format('Y-m-d H:i:s'); 
        $user_id = $_SESSION['user_id'];
        $fileName = $_FILES['fileInput']['name'];
        $fileTmpName = $_FILES['fileInput']['tmp_name'];
        $fileSize = $_FILES['fileInput']['size'];
        $fileError = $_FILES['fileInput']['error'];
        $fileType = $_FILES['fileInput']['type'];
        $fileExt = explode('.',$fileName);
        $fileActualExt = strtolower(end($fileExt));
        $allowed = array('jpg','jpeg','png');
        //process
        if(in_array($fileActualExt,$allowed)){
            if($fileError === 0){
                if($fileSize < 10000000){
                    $fileNameNew = uniqid('',true).".".$fileActualExt;
                    $fileDestination = 'payment/'.$fileNameNew;
                    move_uploaded_file($fileTmpName,$fileDestination);  
                    $query = "insert into WEBOMS_topUp_tb(user_id, amount,status, proofOfPayment, date) values('$user_id','$amount','pending','$fileNameNew','$todayWithTime')";
                    if(Query($query)){
                        echo "<script>alert('sucess place topUp'); window.location.replace('customerTopUp.php'); </script>";
                    }
                    else{
                        echo "<scrip>alert('sucess place topUp');</script>";
                    }
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
    if(isset($_GET['cancel'])){
        $arr = explode(',',$_GET['cancel']);
        $id = $arr[0];
        $pic = $arr[1];
        $query = "DELETE FROM WEBOMS_topUp_tb WHERE id='$id' ";
        if(Query($query)){
          unlink("payment/"."$pic");
          echo "<script> window.location.replace('customerTopUp.php');</script>";
        }
    }
?>
<script>
    	document.getElementById("back").onclick = function () {window.location.replace('customer.php'); };
</script>