<?php 
    $page = 'notLogin';
    $isFromLogin = true;
    include('../method/checkIfAccountLoggedIn.php');
    include('../method/query.php');
    include_once('connection.php');
    $query = "select * from weboms_company_tb";
    $resultSet = getQuery2($query);
    if($resultSet!=null){
        foreach($resultSet as $row){
            $name = $row['name'];
            $address = $row['address'];
            $tel = $row['tel'];
            $description = $row['description'];
        }
    }
?>

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ORDERS QUEUE (ONLINE)</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/orders-queue.css">
    <link rel="icon" href="../image/weboms.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
</head>
<body>

    <div class="container-fluid">
        <a href="../index.php" class="back-home text-dark"><i class="bi-arrow-left"></i>BACK TO HOME</a>
        <div class="col-sm-12">
            <div class="row">
                <label for="" class="online-order">(ONLINE ORDER)</label>

                <!-- preparing table -->
                <div class="col-sm-6 table-responsive">
                    <table class="table table-bordered" id="prepairingTable">
                        <thead class="bg-danger">
                            <tr>
                                <th scope="col">PREPARING</th>
                            </tr>
                        </thead>
                        <tbody id="tbody1" class="table-danger">
                    
                        </tbody>
                    </table>
                </div>

                <!-- serving table -->
                <div class="col-sm-6 table-responsive">
                    <table class="table table-bordered" id="tableServing">
                        <thead class="bg-success">
                            <tr>
                                <th scope="col">SERVING</th>
                            </tr>
                        </thead>
                        <tbody id="tbody2" class="table-success">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

<script>
    function updateTbody(){
        //preparing
        $.getJSON({
                url: "ajax/ordersQueueOnline_getPreparing.php",
                method: "post",
                success: function(result){
                    $('#tbody1 tr').remove();
                    if(result!=null){
                        let data = "";
                        result.forEach(function(element) {
                            data += "<tr>";
                            data +=     "<td><strong style='font-size: 35px;'>"+element+"</strong></td>";
                            data += "</tr>";
                        });
                        $('#tbody1').append(data);
                    }
                }
        });
        //serving
        $.getJSON({
            url: "ajax/ordersQueueOnline_getServing.php",
            method: "post",
            success: function(result){
                $('#tbody2 tr').remove();
                if(result!=null){
                    let data = "";
                    result.forEach(function(element) {
                        data += "<tr>";
                        data +=     "<td><strong style='font-size: 35px;'>"+element+"</strong></td>";
                        data += "</tr>";
                    });
                    $('#tbody2').append(data);
                }
            },complete: function(){
                setTimeout(updateTbody, 2000);
            }
    });
    }
    updateTbody();
</script>