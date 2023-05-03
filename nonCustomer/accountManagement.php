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
    <title>Account Management</title>

    <link rel="stylesheet" href="../css/bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <!-- modal script -->
    <script type="text/javascript" src="../js/bootstrap 5/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/jquery-3.6.1.min.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
        <!-- data tables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

</head>

<body>

    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar" class="bg-dark">
                <div class="sidebar-header bg-dark">
                    <h3 class="mt-3"><a href="admin.php"><?php echo ucwords($_SESSION['accountType']); ?></a></h3>
                </div>
                <ul class="list-unstyled components ms-3">
                    <li class="mb-2">
                        <a href="adminPos.php"><i class="bi bi-tag me-2"></i>Point of Sales</a>
                    </li>
                    <li class="mb-2">
                        <a href="adminOrders.php"><i class="bi bi-minecart me-2"></i>Orders</a>
                    </li>
                    <li class="mb-2">
                        <a href="adminOrdersQueue.php"><i class="bi bi-clock me-2"></i>Orders Queue</a>
                    </li>
                    <li class="mb-2">
                        <a href="topupRfid.php"><i class="bi bi-credit-card me-2"></i>Top-Up RFID</a>
                    </li>
                
                <?php if($_SESSION['accountType'] != 'cashier'){?>
                    <li class="mb-2">
                        <a href="adminInventory.php"><i class="bi bi-box-seam me-2"></i>Inventory</a>
                    </li>
                    <li class="mb-2">
                        <a href="adminSalesReport.php"><i class="bi bi-bar-chart me-2"></i>Sales Report</a>
                    </li>
                    <li class="mb-2 active">
                        <a href="accountManagement.php"><i class="bi bi-person-circle me-2"></i>Account Management</a>
                    </li>
                    <li class="mb-2">
                        <a href="adminFeedbackList.php"><i class="bi bi-chat-square-text me-2"></i>Customer Feedback</a>
                    </li>
                    <li class="mb-2">
                        <a href="adminTopUp.php"><i class="bi bi-cash-stack me-2"></i>Top-Up</a>
                    </li>
                    <li class="mb-1">
                        <a href="settings.php"><i class="bi bi-gear me-2"></i>Settings</a>
                    </li>
                <?php } ?>
                <li>
                    <form method="post">
                        <button class="btn btnLogout btn-dark text-danger" id="Logout" name="logout"><i class="bi bi-power me-2"></i>Logout</button>
                    </form>
                </li>
            </ul>
        </nav>

        <!-- Page Content  -->
        <div id="content">
            <nav class="navbar navbar-expand-lg bg-light">
                <div class="container-fluid bg-transparent">
                    <button type="button" id="sidebarCollapse" class="btn" style="font-size:20px;"><i class="bi bi-list"></i> Toggle</button>
                </div>
            </nav>

            <!-- content here -->
            <div class="container-fluid text-center">
                <div class="row justify-content-center">
                    
                    <div class="table-responsive col-lg-12">
                        <table  class="table table-bordered table-hover col-lg-12" id="tbl1">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">USERNAME</th>
                                    <th scope="col">NAME</th>
                                    <th scope="col">EMAIL</th>
                                    <th scope="col">ACCOUNT<br>TYPE</th>
                                    <th scope="col">RFID<br>NO.</th>
                                    <th scope="col">CUSTOMER<br>INFO</th>
                                    <th scope="col">
                                        <button id="addButton" type="button" onclick="clearField()" class="btn btn-success mb-1" data-bs-toggle="modal" data-bs-target="#addNewAccountModal"><i class="bi bi-plus"></i> ADD NEW ACCOUNT</button>
                                    </th>
                                    <th scope="col" >OPTIONS</th>
                                    <th scope="col" ></th>
                                </tr>
                            </thead>
                            <tbody id="tbody1">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- add new account -->
            <div class="modal fade" role="dialog" id="addNewAccountModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <input type="text" class="form-control form-control-lg mb-3" id="addUsername" placeholder="Enter username" required>
                            <input type="text" class="form-control form-control-lg mb-3" id="addName" placeholder="Enter name" required>
                            <input type="email" class="form-control form-control-lg mb-3" id="addEmail" placeholder="Enter email" required>
                            <input type="password" class="form-control form-control-lg mb-3" id="addPassword" placeholder="Enter password" required>
                            <select id="addAccountType" class="form-control form-control-lg col-12 mb-3">
                                <option value="manager">Manager</option>
                                <option value="cashier">Cashier</option>
                            </select>
                            <button type="submit" class="btn btn-lg btn-success col-12" onclick="insertAccount()"><i class="bi bi-save"></i> Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- passAndEmail (modal)-->
            <div class="modal fade" role="dialog" id="passAndEmail">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body ">
                            <input type="text" class="form-control form-control-lg mb-3" id="updateUsername" placeholder="Enter new username" required>
                            <input type="email" class="form-control form-control-lg mb-3" id="updateEmail" placeholder="Enter new email">
                            <input type="password" class="form-control form-control-lg mb-3" id="updatePassword" placeholder="Enter new password" required>
                            <button type="submit" class="btn btn-lg btn-warning col-12" onclick="updateDetails()"><i class="bi bi-arrow-repeat"></i> Update</button>
                        </div>
                    </div>
                </div>
            </div>
   
            <!-- RFID SCANNER (modal)-->
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
                            <br></br>
                            <br></br>
                        </div>                      
                    </div>
                </div>
            </div>           
           
            <!-- customerProfileModal (Bootstrap MODAL) -->
            <div class="modal fade" id="customerProfileModal" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content container">
                        <div class="modal-body">
                            <!-- table -->
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
            data +=     "<td><button class='btn btn-light' style='border:1px solid #cccccc;' onclick='viewCustomerInfo("+result['user_id'][i]+")'><i class='bi bi-list'></i> VIEW</button></td>";
            data +=     "<td><button class='btn btn-warning' onclick=updateUser('"+result['username'][i]+"','"+result['email'][i]+"')> <i class='bi bi-arrow-repeat'></i> UPDATE</button></td>";
            //add delete button if not admin
            if(result['accountType'][i] == 'admin'){
                data += "<td><a class='text-danger'>You Cannot Delete This Account!</a></td>";
            }
            else{
                data += "<td><button class='btn btn-danger' onclick='deleteUser("+result['user_id'][i]+")'><i class='bi bi-trash3-fill'></i> DELETE</button></td>";
            }
            // add bind button if customer
            if(result['accountType'][i] == 'customer'){
                data += "<td><button class='btn btn-primary' onclick='rfid("+result['user_id'][i]+")'><i class='bi bi-vr'></i> BIND</button></td>";
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
            data += "<tr align='center'><th><img src='../profilePic/"+res[2]+"' style='width:200px;height:200px;border:1px solid black;'> </th></tr>";
          }
            data+= "<tr align='center'><td>Name: "+res[1]+"</td></tr>";
            data+= "<tr align='center'><td>Username: "+res[3]+"</td></tr>";
            data+= "<tr align='center'><td>Gender: "+res[4]+"</td></tr>";
            data+= "<tr align='center'><td>Phone: "+res[5]+"</td></tr>";
            data+= "<tr align='center'><td>Address: "+res[6]+"</td></tr>";
            data+= "<tr align='center'><td>Balance: ₱"+res[7]+"</td></tr>";
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
    
    .scanner{
        width: 800px;
        height: 500px;
        position: absolute;
        align-items: center;
        background-color: rgba(0, 0, 0, 0.9);
        color: #fff;
        font-family: Sans-Serif;
        font-size: 30px;  
        top: 120px;           
    }
    .ocrloader { 
        position: relative;
        width: 300px;
        height: 300px;
        background: url(rfid01.png);
        background-size: 300px;    
    }
    .ocrloader:before {
        content:'';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%; 
        background: url(rfid02.png);
        background-size: 300px;
        filter: drop-shadow(0 0 3px #00FFFF) drop-shadow(0 0 7px #00FFFF);
        overflow: hidden;
        animation: animate 2s linear infinite;
    }
    @keyframes animate
    {
        0%, 50%, 100%
        {
            height: 0%;
        }
        50%
        {
            height: 70%;
        }
        75%
        {
            height: 100%;
        }
    }
    .ocrloader span {
        content:'';
        position: absolute;
        inset: 1px;
        width: calc(100% - 2px);
        height: 3px;
        background-color: #fff;
        animation: animateLine 2s linear infinite;
    }
    @keyframes animateLine{
        0%
        {
            top: 1px;
        }
        50%
        {
            top: 225px;
        }
        75%
        {
            top: 300px;
        }
    }
    *{margin: 0; padding: 0;}
    .loading span {
        position: relative;
        left: 220px;
        top: 35px;       
        width: 10px;
        height: 10px;       
        background-color: #fff;
        border-radius: 50%;
        display: inline-block;
        animation-name: dots;
        animation-duration: 2s;
        animation-iteration-count: infinite;
        animation-timing-function: ease-in-out;
        filter: drop-shadow(0 0 10px #fff) drop-shadow(0 0 20px #fff);
    }

    .loading span:nth-child(2){
        animation-delay: 0.4s;
    }
    .loading span:nth-child(3){
        animation-delay: 0.8s;
    }

    @keyframes dots{
        50%{
            opacity: 0;
            transform: scale(0.7) translateY(10px);
        }
    }
    .ocrloader > div {
        z-index: 1;
        position: absolute;
        left: 62%;
        top: 120%;
        transform: translate(-50%, -50%);
        width: 100%;
        backface-visibility: hidden;
        filter: drop-shadow(0 0 20px #fff) drop-shadow(0 0 40px #fff);
    }
    .ocrloader em:after,
    .ocrloader em:before {
        border-color: #fff;
        content: "";
        position: absolute;
        width: 19px;
        height: 16px;
        border-style: solid;
        border-width: 0px;
    }
    .ocrloader:before {
        left: 0;
        top: 0;
        border-left-width: 1px;
        border-top-width: 1px;
    }
    .ocrloader:after {
        right: 0;
        top: 0;
        border-right-width: 1px;
        border-top-width: 1px;
    }
    .ocrloader em:before {
        left: 0;
        bottom: 0;
        border-left-width: 1px;
        border-bottom-width: 1px;
    }
    .ocrloader em:after {
        right: 0;
        bottom: 0;
        border-right-width: 1px;
        border-bottom-width: 1px;
    }
    
    #rfidInput{
        opacity: 0;
    }
</style>