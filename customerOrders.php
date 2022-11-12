<!DOCTYPE html>
<html>
    <head>
      <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
    </head>
    <body>
    <div class="container text-center">
        <div class="col-lg-12 cont2">
          <button class="btn btn-success col-sm-4" id="orderList">Order List</button>
            <?php 
              $arr = explode(',',$_GET['idAndPic']);
              $id = $arr[0];
              $pic = $arr[1];
              include_once('class/transactionClass.php');
              include('method/Query.php');

              $order = transaction::withID( $id );
              $arr =  $order -> getAllOrderById(); 
            ?>
            <table class="table table-striped" border="10">
            <tr>	
            <!-- <th scope="col">price</th> -->
            <th scope="col">quantity</th>
            <th scope="col">name</th>
            <th scope="col">price</th>
            </tr>
              <tbody>
                <?php 
                $total = 0;
                foreach($arr as $rows){ ?>
                <tr>	   
                  <?php $price = ($rows['price']*$rows['quantity']);  $total += $price;?>
                  <td><?php echo $rows['quantity']; ?></td>
                  <td><?php echo $rows['dish']; ?></td>
                  <td><?php echo $price?></td>
                </tr>
                <?php }?>
                <tr>
                  <td colspan="2">Total Amount:</td>
                  <td><?php echo $total?></td>
                </tr>
              </tbody>
            </table>
          </div>
          <h1>Proof of Payment:</h1>
            <?php echo "<img src='payment/$pic' style=width:300px;height:500px>";?>
	    </div>
    </body>
</html>

<style>
    body{
    background-image: url(settings/bg.jpg);
    background-size: cover;
    background-attachment: fixed;
    background-repeat: no-repeat;
    background-position: center;
    color: white;
    font-family: 'Josefin Sans',sans-serif;
  }

	.cont2{
     padding: 1%;
     margin-top: 2%;
     background: gray;
   }
</style>

<script>
  document.getElementById("orderList").onclick = function () {window.location.replace('customerOrdersList.php'); };
</script> 