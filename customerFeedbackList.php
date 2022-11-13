<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
        <div class="container text-center">
          <button class="btn btn-success col-sm-4" id="customer">Back</button>
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