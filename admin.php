
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
</head>
<body>
  <div class="container text-center">

    <button type="button" class="btn btn-sucess" data-toggle="modal" data-target="#loginModal">Add new dish</button>
  </div>

  <div class="modal fade" role="dialog" id="loginModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <input type="text" class="form-control" name="dishes" placeholder="dishes">
                    <input type="text" class="form-control" name="cost" placeholder="cost">
                    <input type="file" name="fileInput">
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Insert</button>
            </div>

        </div>
    </div>
  </div>
<script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
</body>
</html>

