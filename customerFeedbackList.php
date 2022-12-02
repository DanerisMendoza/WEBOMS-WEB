<?php
  $page = 'feedback';
  include('method/checkIfAccountLoggedIn.php');
  include_once('method/query.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Costumer - Feedback List</title>
        
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
    <link rel="stylesheet" type="text/css" href="css/style.css">
    
</head>
<body class="bg-light">
        
<div class="container text-center mt-5">
    <button class="btn btn-lg btn-dark col-12 mb-4" id="back">Back</button>
          <script>
              var accountType = "<?php echo  $_SESSION['accountType'];?>";
              document.getElementById("back").onclick = function () {
                if(accountType == 'customer')
                  window.location.replace('customerMenu.php');
                else if(accountType == 'admin' || accountType == 'manager') 
                  window.location.replace('admin.php');
              };    
          </script> 
          <?php
              $query = "select WEBOMS_userInfo_tb.*, WEBOMS_feedback_tb.*, WEBOMS_order_tb.* from WEBOMS_userInfo_tb, WEBOMS_order_tb, WEBOMS_feedback_tb where WEBOMS_userInfo_tb.user_id = WEBOMS_order_tb.user_id and WEBOMS_feedback_tb.order_id = WEBOMS_order_tb.order_id;";
              $resultSet =  getQuery($query);
              ?>
              <div class="table-responsive col-lg-12">
              <table class="table table-striped table-bordered mb-5 col-lg-12">
              <thead class="table-dark">
                  <tr>	
                  <th scope="col">NAME</th>
                  <th scope="col">FEEDBACK</th>
                  </tr>
              </thead>
                <tbody>
                  <?php
                  if($resultSet!= null)
                  foreach($resultSet as $rows){ ?>
                  <tr>	   
                    <td><?php echo $rows['name']; ?></td>
                    <td><?php echo $rows['feedback'];?></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
              <?php
          ?>
          </div>
	    </div>
    </body>
</html>