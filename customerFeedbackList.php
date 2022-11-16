<!DOCTYPE html>
<html>
<head>
    <title>Costumer - Feedback List</title>
        
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
    <link rel="stylesheet" type="text/css" href="css/style.css">
    
</head>
<body class="bg-light">
        
<div class="container text-center">
    <h1 class="font-weight-normal mt-5 mb-4 text-center">Feedback</h1>
    <button class="btn btn-lg btn-danger col-12 mb-4" id="customer">Back</button>
          <script>020
              document.getElementById("customer").onclick = function () {window.location.replace('customerMenu.php'); };    
          </script> 
          <?php
              session_start();
              include_once('class/feedbackClass.php');
              include_once('method/query.php');
              $feedback = new feedbackEmpty();  
              $resultSet =  $feedback -> getAllFeedbackSortedByUserLinkId(); 
              $feedback -> generateAllFeedbackTable($resultSet);
          ?>
          </div>
	    </div>
    </body>
</html>