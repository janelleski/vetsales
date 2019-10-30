<?php
require('../db.php');
include("../auth.php"); 
$username = $_SESSION['username'];
	$sql = "SELECT branchId FROM users where username='$username'";
	   
	   if ($result = mysqli_query($con,$sql)){
		  while ($obj = mysqli_fetch_object($result)){
			 $branchId = $obj->branchId;
		  }
		  mysqli_free_result($result);
	   }
$id = null;
if(isset($_REQUEST['billId'])){	   
$id=$_REQUEST['billId'];

}
?>
<!DOCTYPE html>
<html class="no-js h-100" lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>VetSales - Dashboard</title>
    <meta name="description" content="VetSales POS and Clinic Management System">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<?php include "imports.inc"; ?>
 <script type="text/javascript">
$( document ).ready(function() {
	var user = "<?php echo $_SESSION['username']; ?>";
$('#transactionTable').DataTable({
		"bProcessing": true,
         "serverSide": true,
         "ajax":{
            url :"getTransactions.php", // json datasource
            type: "post",  // type of method  ,GET/POST/DELETE
			data: {user:user},
            error: function(){
              $("#transactionTable").css("display","none");
            }
          }
        });   
});

function checkToConfirm(id){
	$('#checkPaidModal').modal('show');
}

function markPaid(id){
	$('#checkPaidModal').modal('hide');
	var user = "<?php echo $_SESSION['username']; ?>";
	var transactionId = id;

	 $.ajax({
        url: "markpaid.php",
        type: "POST",
		dataType: 'JSON',
        data: {transactionId:transactionId,user:user},
        success: function(result){
			
        }
    });
	
	$('#markedPaidModal').modal('show');
}

function save(id)
{
	var user = "<?php echo $_SESSION['username']; ?>";
	var transactionId = id;
	
	var customerName = document.getElementById("customerName").value;
	var customerAddr = document.getElementById("customerAddr").value;
	var customerContact = document.getElementById("contactNo").value;
	
	$.ajax({
        url: "saveTransactionDetails.php",
        type: "POST",
		dataType: 'JSON',
        data: {transactionId:transactionId,user:user,customerName:customerName,customerAddr:customerAddr,customerContact:customerContact},
        success: function(result){
			
        }
    });
	
	$('#savedTransModal').modal('show');
}

</script>

<style></style>
  </head>
  <body class="h-100"><div class="form">
    <div class="container-fluid">
      <div class="row">
        <!-- Main Sidebar -->
        <?php include "menu.inc"; ?>
        <!-- End Main Sidebar -->
        <main class="main-content col-lg-10 col-md-9 col-sm-12 p-0 offset-lg-2 offset-md-3">
          <div class="main-navbar sticky-top bg-white">
            <!-- Main Navbar -->
            <?php include "mainnavbar.inc"; ?>
          <!-- / .main-navbar -->
          <div class="main-content-container container-fluid px-4">
            <!-- Page Header -->
             <!-- Page Header -->
            <div class="page-header row no-gutters py-4">
              <div class="col-12 col-sm-4 text-center text-sm-left mb-4 mb-sm-0">
                <span class="text-uppercase page-subtitle">Transaction</span>
                <h3 class="page-title">View Transaction Details</h3>
              </div>
             
            </div>
            <!-- End Page Header -->
            <div class="row">
              <div class="col-lg-8 mb-4">
                <div class="card card-small mb-4">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item px-3">
					<?php if($id!=null){ 
						$query = "SELECT * from transactions where transactionId=".$id." and branchId = (SELECT branchId from users where username='$username')"; 
						$result = mysqli_query($con, $query) or die ( mysqli_error());
						$detail = mysqli_fetch_assoc($result);
					
					?>
						<div class="row mb-2">
						  <div class="col border-bottom">
							<strong>Transaction #<?php echo $id; ?></strong>
						  </div>
						  <div class="col border-bottom" style="text-align:right;">
							Status: 
							<?php 
							
							$status = $detail['status'];
							if($status==0){
							?>
							<strong class="text-warning">Pending</strong>
							<?php }else if($status==1){ ?>
							<strong class="text-success">Paid</strong>
							<?php } else{ ?>
							<strong class="text-danger">Void</strong>
							<?php }?>
						  </div>
						</div>
						<div class="row mb-2">
						  <div class="col">
							Date of Transaction: <?php echo $detail['transactionDate'];?>
						  </div>
						</div>
						<div class="row mb-2">
							<div class="col">
							Customer Name: 
							<input type="hidden" class="form-control" id="transactionId" name="transactionId"  value="<?php echo $detail['transactionId'];?>"> 
							<input type="text" class="form-control" id="customerName" name="customerName"  value="<?php echo $detail['customerName'];?>" placeholder="Customer Name"> 
					
							</div>
						</div>
						<div class="row mb-2">
							<div class="col">
								Customer Address: 
								<input type="text" class="form-control" id="customerAddr" name="customerAddr"  value="<?php echo $detail['customerAddr'];?>" placeholder="Customer Address"> 
						
							</div>
							<div class="col">
								Contact No. : 
								<input type="text" class="form-control" id="contactNo" name="contactNo"  value="<?php echo $detail['customerContact'];?>" placeholder="Contact No.">
							</div>
						</div>
						<div class="row mb-2">
							<div class="col">
								<table class="table mb-0" id="billSummaryTbl" style="margin-top:20px;">
									<thead>
										<tr>
										<td>Product Id</td>
										<td>Product Name</td>
										<td>Quantity</td>
										<td>SRP</td>
										<td>Discount/Commission</td>
										<td>Total</td>
										</tr>
									</thead>
									<tbody>
									<?php 
									
										$username = $_SESSION['username'];
										$result = mysqli_query($con,"select p.productId, p.productTitle, i.quantity,p.srp,p.discount, i.totalAmount from transactionitems i left join products p on i.productId = p.productId left join transactions t on t.transactionId=i.transactionId where i.transactionId = $id and t.branchId = (SELECT branchId from users where username='$username')");

										while($row = mysqli_fetch_array($result))
										{
											echo "<tr>";
											echo "<td> ". $row['productId'] ."</td>";
											echo "<td> ". $row['productTitle'] ."</td>";
											echo "<td> ". $row['quantity'] ."</td>";
											echo "<td> ". $row['srp'] ."</td>";
											echo "<td> ". $row['discount'] ."</td>";
											echo "<td> ". $row['totalAmount'] ."</td>";
											echo "</tr>";
										}

									?>
									<?php 
									
										$username = $_SESSION['username'];
										$result = mysqli_query($con,
										"select r.renderId, s.serviceId, s.serviceName,r.totalAmount, r.totalExpense from services_rendered r left join
services s on s.serviceId = r.serviceId where r.transactionId = $id and r.branchId = (SELECT branchId from users where username='$username')");

										while($row = mysqli_fetch_array($result))
										{
											echo "<tr>";
											echo "<td> ". $row['serviceId'] ."</td>";
											echo "<td> ". $row['serviceName'] ."</td>";
											echo "<td> 1 </td>";
											echo "<td> ". $row['totalAmount'] ."</td>";
											echo "<td> ". $row['totalExpense'] ."</td>";
											echo "<td> ". $row['totalAmount'] ."</td>";
											echo "</tr>";
											
											$renderId = $row['renderId'];
											
											$resultDetails = mysqli_query($con,
											"select d.fieldValue,s.fieldName from rendered_details d left join service_details s 
											on s.serviceDetailId = d.serviceDetailId where d.renderId = $renderId and d.branchId = 
											(SELECT branchId from users where username='inahmauricar')");
										
											while($row2 = mysqli_fetch_array($resultDetails))
											{
												if(!empty($row2['fieldValue'])){
												echo "<tr>";
												echo "<td> </td>";
												echo "<td> ". $row2['fieldName'] .": ". $row2['fieldValue'] ."</td>";
												echo "<td></td>";
												echo "<td> </td>";
												echo "<td> </td>";
												echo "<td> </td>";
												echo "</tr>";}
											}
										
										}

									?>
									</tbody>
								</table>
						  </div>
						</div>
						<div class="row mb-2">
							<div class="col">
								<h6 class="text-fiord-blue">Total Amount Due: <strong class="text-success">₱ <?php echo $detail['totalAmount'];?></strong> </h6>
								<h6 class="text-fiord-blue">Total Discount: <strong class="text-success">₱<?php
								if($detail['discount']==null){
									$discount = sprintf('%0.2f',0);
								}else{
									$discount = $detail['discount'];
								}
								echo $discount;?></strong> </h6>
								<h6 class="text-fiord-blue">Total Amount Paid: <strong class="text-success">₱ <?php echo sprintf("%.2f",$detail['totalPaid']-$detail['totalChange']); ?></strong> </h6>
						</div>
						</div>
						
						
						<div class="row mb-2">
							<div class="col" style="text-align:right;">
									<input value="Save Details" name="save" class="btn btn-accent" type="button" onclick="save(<?php echo $id;?>);">
							<?php if($status!=1 && $status!=2) { ?>
								<input value="Mark as Paid" name="submit" class="btn btn-accent" type="button" onclick="checkToConfirm(<?php echo $id;?>);">
							<?php } ?>
							</div>
						</div>
						
					<?php	} ?>
                    </li>
                  </ul>
                </div>
              </div>
			  <div class="col-lg-4 mb-4">
                <!-- Sliders & Progress Bars -->
                <div class="card card-small mb-4">
                  <div class="card-header border-bottom">
                    <h6 class="m-0">Update History</h6>
                  </div>
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item px-3">
                      <!-- Progress Bars -->
                      <div class="row mb-2">
                         <div class="col">
							<?php 
							if($id!=null){
								
								
								$query = "SELECT * from transactionlog where transactionId=$id and branchId = $branchId"; 
								$result = mysqli_query($con,$query);
								
								while($row = mysqli_fetch_array($result))
								{
									echo $row['tLogDetails'];
									echo "<br/><br/>";
								}
								
							}
							?>
						 </div>
                      </div>
                      <!-- / Progress Bars -->
                    </li>
                  </ul>
                </div>
				</div>
			</div>
        </main>
	  </div>
			<div class='modal fade' id='checkPaidModal' role='dialog'>
				<div class='modal-dialog modal-mb'>
				  <div class='modal-content'>
					<div class='modal-body'>
					  <p>Are you sure you want to mark this transaction as Paid?</p>
					</div>
						<div class='modal-footer'>
								<input type="hidden" value="" id ="idPaid"/>
								<button type='submit' name="paid" class='btn btn-danger' data-dismiss='modal' onclick="markPaid(<?php echo $id; ?>);">Yes</button>
								<button type='button' class='btn btn-default' data-dismiss='modal'>Dismiss</button>
							 
						</div>
					</div>
				</div>
	</div>
	<div class='modal fade' id='markedPaidModal' role='dialog'>
				<div class='modal-dialog modal-mb'>
				  <div class='modal-content'>
					<div class='modal-body'>
					  <p>The transactions has been marked as paid.</p>
					</div>
						<div class='modal-footer'>
								
								<button type='button' class='btn btn-default' data-dismiss='modal' onclick='location.reload();'>Dismiss</button>
							 
						</div>
					</div>
				</div>
	</div>
	<div class='modal fade' id='savedTransModal' role='dialog'>
				<div class='modal-dialog modal-mb'>
				  <div class='modal-content'>
					<div class='modal-body'>
					  <p>Transaction details updated successfully.</p>
					</div>
						<div class='modal-footer'>
								
								<button type='button' class='btn btn-default' data-dismiss='modal' onclick='location.reload();'>Dismiss</button>
							 
						</div>
					</div>
				</div>
	</div>
	</div>
	
    <?php include "scripts_min.inc"; ?>
	</div>

  </body>
</html>