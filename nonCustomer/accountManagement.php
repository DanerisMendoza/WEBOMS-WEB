<?php 
    $page = 'admin';
    include('../method/checkIfAccountLoggedIn.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACCOUNT MANAGEMENT</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/rfid4.css">
    <link rel="icon" href="../image/weboms.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
</head>
<body>

<div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header">
                <a href="admin.php" class="account-type"><?php echo strtoupper($_SESSION['accountType']); ?></a>
            </div>
            <hr>
            <ul class="list-unstyled components">
                <li><a href="adminPos.php"><i class="bi-tag me-2"></i>POINT OF SALES</a></li>
                <li><a href="adminOrders.php"><i class="bi-cart me-2"></i>ORDERS</a></li>
                <li><a href="adminOrdersQueue.php"><i class="bi-clock me-2"></i>ORDERS QUEUE</a></li>
                <li><a href="topupRfid.php"><i class="bi-credit-card me-2"></i>TOP-UP RFID</a></li>

                <?php if($_SESSION['accountType'] != 'cashier'){?>
                <li><a href="adminTopUp.php"><i class="bi-wallet me-2"></i>TOP-UP</a></li>
                <li><a href="adminInventory.php"><i class="bi-box me-2"></i>INVENTORY</a></li>
                <li><a href="adminSalesReport.php"><i class="bi-bar-chart me-2"></i>SALES REPORT</a></li>
                <li><a href="adminFeedbackList.php"><i class="bi-chat-square-text me-2"></i>CUSTOMER FEEDBACK</a></li>
                <li><a href="accountManagement.php" class="active text-danger"><i class="bi-person me-2"></i>ACCOUNT MANAGEMENT</a></li>
                <li><a href="settings.php"><i class="bi-gear me-2"></i>SETTINGS</a></li>
                <?php } ?>
            </ul>
        </nav>

        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-toggle">
                        <i class="bi-list"></i>
                    </button>
                    <button class="btn btn-toggle d-inline-block d-lg-none ml-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="bi-list text-danger"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ms-auto">
                            <li>
                                <form method="post">
                                    <button class="btn text-danger" id="Logout" name="logout">LOGOUT</button>
                                </form>
                            </li>   
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="container-fluid mt-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="tbl1">
                        <thead class="table-dark">
                            <tr>
                                <th>USERNAME</th>
                                <th>NAME</th>
                                <th>EMAIL</th>
                                <th>ACCOUNT TYPE</th>
                                <th>RFID #</th>
                                <th>CUSTOMER</th>
                                <th><center><button type="button" class="btn btn-success fw-bold" id="addButton" onclick="clearField()" data-bs-toggle="modal" data-bs-target="#addNewAccountModal"><i class="bi-plus-lg me-2"></i>ADD NEW ACCOUNT</button></center></th>
                                <th>ACTION</th>
                                <th>BIND</th>
                            </tr>
                        </thead>
                        <tbody id="tbody1">

                        </tbody>
                    </table>
                </div>

                <!-- add new account (modal) -->
                <div class="modal fade" role="dialog" id="addNewAccountModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <input type="text" class="form-control" placeholder="Enter username" id="addUsername" required>
                                <input type="text" class="form-control" placeholder="Enter name" id="addName" required>
                                <input type="email" class="form-control" placeholder="Enter email" id="addEmail" required>
                                <input type="password" class="form-control" placeholder="Enter password" id="addPassword" required>
                                <select name="" id="addAccountType" class="form-control" required>
                                    <option value="manager">Manager</option>
                                    <option value="cashier">Cashier</option>
                                </select>
                                <button type="submit" class="btn btn-success w-100" onclick="insertAccount()">SAVE</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- password and email (modal) -->
                <div class="modal fade" role="dialog" id="passAndEmail">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <input type="text" class="form-control" placeholder="Enter new username" id="updateUsername">
                                <input type="email" class="form-control" placeholder="Enter new email" id="updateEmail">
                                <input type="password" class="form-control" placeholder="Enter new password" id="updatePassword">
                                <button type="submit" class="btn btn-warning w-100" onclick="updateDetails()">UPDATE</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- rfid bind (modal) -->
                <div class="modal fade" role="dialog" id="rfid">
                    <div class="modal-dialog">
                        <div class="modal-content scanner">
                            <div class="modal-body">
                                <input type="text" id="rfidInput">                            
                                <div class="ocrloader">
                                    <em></em>
                                    <div>Binding RFID</div>                                                               
                                    <span></span>
                                </div>
                                <div class="loading">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- customer profile (modal) -->
                <div class="modal fade" role="dialog" id="customerProfileModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--
                    <div class="modal fade" id="customerProfileModal" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content container">
                        <div class="modal-body">
                            <div class="table-responsive col-lg-12">
                                <table class="table table-striped table-hover col-lg-12">
                                    <tbody>
                                       
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                --> 
            </div>
        </div>
    </div>

</body>
</html>

<script>
    function clearField(){
        $("#addUsername").val('');
        $("#addName").val('');
        $("#addEmail").val('');
        $("#addPassword").val('');
    }
    
    function insertAccount(){
        let username = $("#addUsername").val();
        let name = $("#addName").val();
        let email = $("#addEmail").val();
        let password = $("#addPassword").val();
        let accountType = $("#addAccountType").val();

        $.ajax({
            url: "ajax/accountManagement_insert.php",
            method: "post",
            data: {
                'name':JSON.stringify(name),
                'username':JSON.stringify(username),
                'email':JSON.stringify(email),
                'password':JSON.stringify(password),
                'accountType':JSON.stringify(accountType)
            },
            success: function(res){
                if(res == 'Sucess!'){
                    $('#addNewAccountModal').modal('hide');
                    updateTbody();
                }
                else{
                    alert(res);
                    return;
                }
            }
        });
    }

    function updateTbody(){
        $.getJSON({
            url: "ajax/accountManagement_getUsers.php",
            method: "post",
            success: function(result){
                //username, name, email, accountType, rfid, user_id
                let data = "";
                for(let i=0; i<result['username'].length; i++){
                    data += "<tr>";
                    data +=     "<td>"+result['username'][i]+"</td>";
                    data +=     "<td>"+result['name'][i]+"</td>";
                    data +=     "<td>"+result['email'][i]+"</td>";
                    data +=     "<td>"+result['accountType'][i]+"</td>";
                    data +=     "<td>"+result['rfid'][i]+"</td>";
                    data +=     "<td><center><button class='btn btn-light' onclick='viewCustomerInfo("+result['user_id'][i]+")'><i class='bi-list'></i></button></center></td>";
                    data +=     "<td><center><button class='btn btn-warning' onclick=updateUser('"+result['username'][i]+"','"+result['email'][i]+"')><i class='bi-arrow-repeat'></i></button></center></td>";
                    //add delete button if not admin
                    if(result['accountType'][i] == 'admin'){
                        data += "<td><center><a class='text-danger'>You Cannot Delete This Account!</a></center></td>";
                    }
                    else{
                        data += "<td><center><button class='btn btn-danger' onclick='deleteUser("+result['user_id'][i]+")'><i class='bi-trash3'></i></button></center></td>";
                    }
                    // add bind button if customer
                    if(result['accountType'][i] == 'customer'){
                        data += "<td><center><button class='btn btn-primary' onclick='rfid("+result['user_id'][i]+")'><i class='bi-vr'></i></button></center></td>";
                    }
                    else{
                        data += "<td></td>";
                    }
                    data += "</tr>";
                }
                $('#tbl1').DataTable().clear().destroy();
                $('#tbody1').append(data);
                $('#tbl1').dataTable({
                "columnDefs": [
                    { "targets": [5,6,7], "orderable": false }
                ]
                });
            }
        });
    }
    updateTbody();


    function viewCustomerInfo(user_id){
        $.getJSON({
            url: "ajax/accountManagement_getUserInfo.php",
            method: "post",
            data: {'user_id':JSON.stringify(user_id)},
            success: function(res){
                //   // id, name, picName, username, phone number, address, balance, email, gender
                let data ="";
                if(res[2] != null){
                    data += "<tr align='center'><th colspan='2'><img src='../profilePic/"+res[2]+"' style='width:200px;height:200px;border:1px solid black;'> </th></tr>";
                }
                    data+= "<tr><td>Name: </td><td>"+res[1]+"</td></tr>";
                    data+= "<tr><td>Username: </td><td>"+res[3]+"</td></tr>";
                    data+= "<tr><td>Gender: </td><td>"+res[4]+"</td></tr>";
                    data+= "<tr><td>Phone: </td><td>"+res[5]+"</td></tr>";
                    data+= "<tr><td>Address: </td><td>"+res[6]+"</td></tr>";
                    data+= "<tr class='bg-success text-white fw-bold'><td>Balance: </td><td>₱"+res[7]+"</td></tr>";
                $('#customerProfileModal').find('.modal-body .table tbody tr').remove();
                $('#customerProfileModal').find('.modal-body .table tbody').append(data);
                $('#customerProfileModal').modal('show');
            }
        });
    }

    function updateUser(username,email){
        $('#passAndEmail').modal('show');
        $('#updateUsername').val(username);
        $('#updateEmail').val(email);
        $('#updatePassword').val('');
        $('#updateUsername').prop('disabled', true);
    }

    function updateDetails(){
        //validation 
        if($('#updateEmail').val() == ''){
            alert('Please enter your new email!');
            return;
        }
        if($('#updatePassword').val() == ''){
            alert('Please enter your new Password!');
            return;
        }
        $.ajax({
            url: "ajax/accountManagement_updateEmailAndPass.php",
            method: "post",
            data: {
                'username':JSON.stringify($('#updateUsername').val()), 
                'email':JSON.stringify($('#updateEmail').val()),
                'password':JSON.stringify($('#updatePassword').val()), },
            success: function(res){
                if(res == 'email already exist'){
                    alert('email already exist');
                    return;
                }
                $('#passAndEmail').modal('hide');
                updateTbody();
            }
        });
    }

    function deleteUser(user_id){
        $.ajax({
            url: "ajax/accountManagement_deleteUser.php",
            method: "post",
            data: {'user_id':JSON.stringify(user_id)},
            success: function(res){
                updateTbody();
            }
        });
    }

    // sidebar toggler
    $(document).ready(function() {
        $('#sidebarCollapse').on('click', function() {
            $('#sidebar').toggleClass('active');
        });
    });
</script>

<?php 
    // logout
    if(isset($_POST['logout'])){
        session_destroy();
        echo "<script>window.location.replace('../general/login.php');</script>";
    }
?>

<script>
    var userId_Global = null;
    let rfidBlockExecuted = false;

    $("#rfid").on('shown.bs.modal', function(){
        $(this).find('input[type="text"]').val('');
        $(this).find('input[type="text"]').focus();
    });

    $("#rfid").on('hidden.bs.modal', function(){
        rfidBlockExecuted = false;
    });

    $('#rfidInput').keyup(function(){
        if($(this).val().length == 10 && !rfidBlockExecuted){
            rfidBlockExecuted = true;
            let arr = [];
            arr.push($(this).val());
            arr.push(userId_Global);
            // check if rfid is already used
            $.ajax({
                url: "ajax/accountManagement_checkIfRfidExist.php",
                method: "post",
                data: {'rfid':JSON.stringify(arr[0])},
                success: function(currentlyUsedByOtherUser){  
                    $(this).val('');
                    //check if rfid is already used before
                    $.ajax({
                        url: "ajax/accountManagement_checkIfRfidUsedBefore.php",
                        method: "post",
                        data: {'rfid':JSON.stringify(arr[0])},
                        success: function(alreadyUsedBefore){
                            //validation
                            if(currentlyUsedByOtherUser || alreadyUsedBefore){
                                $('#rfid').modal('hide');
                                alert("RFID already used!");
                                return;
                            }
                            // update rfid card
                            $.ajax({
                                url: "ajax/accountManagement_updateRfid.php",
                                method: "post",
                                data: {'arr':JSON.stringify(arr)},
                                success: function(){  
                                    $(this).val('');
                                    $('#rfid').modal('hide');
                                    updateTbody();  
                                    // add rfid to usedRfid_tb
                                    $.ajax({
                                        url: "ajax/accountManagement_addToUsedRfid.php",
                                        method: "post",
                                        data: {'rfid':JSON.stringify(arr[0])},
                                        error: function(XMLHttpRequest, textStatus, errorThrown) { 
                                            alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                                        }     
                                    });
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                                    alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                                }     
                            });
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) { 
                            alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                        }     
                    });
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                }     
            });
        }
    });

    function rfid(user_id){
        userId_Global = user_id;
        $('#rfid').modal('show');
    }
</script>

<style>
    
</style>