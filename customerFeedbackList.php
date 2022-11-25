<?php
  $page = 'customer';
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
    <!-- <h1 class="font-weight-normal mt-5 mb-4 text-center">Feedback</h1> -->
    <button class="btn btn-lg btn-dark col-12 mb-4" id="customer">Back</button>
          <script>020
              document.getElementById("customer").onclick = function () {window.location.replace('customerMenu.php'); };    
          </script> 
          <?php
              $query = "select WEBOMS_userInfo_tb.*, WEBOMS_feedback_tb.*, WEBOMS_order_tb.* from WEBOMS_userInfo_tb, WEBOMS_order_tb, WEBOMS_feedback_tb where WEBOMS_userInfo_tb.userlinkId = WEBOMS_order_tb.userlinkId and WEBOMS_feedback_tb.ordersLinkId = WEBOMS_order_tb.ordersLinkId;";
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