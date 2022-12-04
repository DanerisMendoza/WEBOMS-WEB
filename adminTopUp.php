<?php
  $page = 'admin';
  include('method/checkIfAccountLoggedIn.php');
  include_once('method/query.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Costumer - TopUp List</title>
        
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>  
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    
</head>
<body class="bg-light">
<div class="container text-center mt-5">
    <button class="btn btn-lg btn-dark col-12 mb-4" id="back">Back</button>
          <script>
              document.getElementById("back").onclick = function () {window.location.replace('admin.php');};    
          </script> 
              <?php
              $query = "SELECT a.*,b.name FROM `WEBOMS_topUp_tb` a inner join WEBOMS_userInfo_tb b on a.user_id = b.user_id";
              $resultSet =  getQuery($query);
              ?>
              <div class="table-responsive col-lg-12">
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
                    <td><a class="btn btn-primary border-dark" href="?viewPic=<?php echo $row['proofOfPayment'];?>">View</a></td>
                        <?php if($row['status'] == 'pending') {?>
                        <td><a class="btn btn-success border-dark" href="?approve=<?php echo $row['id'].','.$row['user_id'].','.$row['amount'];?>">Approve</a></td>
                        <td><a class="btn btn-danger border-dark" href="?disapprove=<?php echo $row['id'];?>">Disapprove</a></td>
                        <?php } else {?>
                        <td colspan="3">none</td>
                        <?php } ?>
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
    </body>
</html>
<?php 
  //view pic
  if(isset($_GET['viewPic'])){
    echo "<script>$('#viewPic').modal('show');</script>";
  }
  //approve
  if(isset($_GET['approve'])){
    $arr = explode(',',$_GET['approve']);
    $id = $arr[0];
    $user_id = $arr[1];
    $amount = $arr[2];
    $query = "UPDATE WEBOMS_topUp_tb SET status='approved' WHERE id='$id' ";     
    if(Query($query)){
        $query = "UPDATE WEBOMS_userInfo_tb SET balance = (balance + '$amount') where user_id = '$user_id' ";     
        if(Query($query)){
            echo "<SCRIPT>  window.location.replace('adminTopUp.php'); alert('success!');</SCRIPT>";
        }
    }

  }
  //disapprove
  if(isset($_GET['disapprove'])){
    $query = "UPDATE WEBOMS_topUp_tb SET status='disapproved' WHERE id = '$_GET[disapprove]' ";     
    if(Query($query))
        echo "<SCRIPT>  window.location.replace('adminTopUp.php'); alert('success!');</SCRIPT>";
  }
?>