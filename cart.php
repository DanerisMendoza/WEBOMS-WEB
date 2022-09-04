<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
    </head>
    <body>
    


    


        <div class="container text-center">
            <button class="btn btn-success col-sm-4" id="pos">Pos</button>
            <div class="col-lg-12">
                <table  class="table table-striped" border="10">
                    <thead><tr>
                        <th scope="col">quantity</th>
                        <th scope="col">dish</th>
                        <th scope="col">cost</th>
                    </tr></thead>
                    <?php foreach($_SESSION["dishes"] as $d){ ?>
                    <tr>
                        <td> </td>
                        <td> <?php echo $d['dish'];?></td>
                        <td> <?php echo $d['cost'];?></td>
                    </tr>
                    <?php }?>
                </table>           
            </div>
        </div>

        
    </body>
</html>

<script>document.getElementById("pos").onclick = function () {window.location.replace('pos.php'); };</script> 