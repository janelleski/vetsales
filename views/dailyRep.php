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
$query = "SELECT p.productTitle,i.quantity, i.totalAmount FROM transactions t left join transactionitems i on t.transactionId = i.transactionId left join products p on p.productId = i.productId WHERE DATE(t.transactionDate) = CURDATE() and t.branchId = $branchId and i.transactionItemId is not null"; 
$result = mysqli_query($con, $query) or die ( mysqli_error());

$query = "SELECT r.renderId, s.serviceName,r.totalAmount FROM transactions t left join services_rendered r on t.transactionId = r.transactionId left join services s on s.serviceId = r.serviceId WHERE DATE(t.transactionDate) = CURDATE() and t.branchId = $branchId and r.renderId is not null"; 
$resultServ = mysqli_query($con, $query) or die ( mysqli_error());

$query = "SELECT e.expenseName,e.expenseCost from expenses e  WHERE DATE(e.expenseDate) = CURDATE() and e.branchId = $branchId"; 
$resultOthExp = mysqli_query($con, $query) or die ( mysqli_error());

$query = "SELECT r.renderId, r.serviceId, concat(s.serviceName,' - Commission') as expenseDetail, r.totalExpense from services_rendered r left join services s on r.serviceId = s.serviceId WHERE DATE(r.dateAdded) = CURDATE() and r.branchId = $branchId and r.totalExpense>0.00"; 
$resultServExp = mysqli_query($con, $query) or die ( mysqli_error());

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
<style>
i.icon-red {
    color: red !important;
}
strong { 
font-weight: bold; 
color: #007bff;
}
.modal-body{
    max-height: 450px;
    overflow-y: auto;
}

table {
  border: 1px solid #ddd;
  border-collapse: collapse;
  padding: 0px;
  text-align:center;
}

td, th {
  white-space: nowrap;
  border: 1px solid 	#191970;
  padding: 20px;
}

th {
  background-color: #98FB98;
}

tr td{
  padding: 0 !important;
  margin: 0 !important;
}

tr th{
  padding: 0 !important;
  margin: 0 !important;
}


.reportFont {
font-family: Verdana, Geneva, sans-serif;
font-size: 12px;
letter-spacing: 0px;
word-spacing: 0px;
color: #000000;
font-weight: 500;
text-decoration: none;
font-style: normal;
font-variant: normal;
text-transform: uppercase;

}

.totalFont {
font-family: Verdana, Geneva, sans-serif;
font-size: 16px;
letter-spacing: 0px;
word-spacing: 0px;
color: #000000;
font-weight: 500;
text-decoration: none;
font-style: normal;
font-variant: normal;
text-transform: uppercase;
font-weight: bold;
}

.totalAmount {
font-family: Verdana, Geneva, sans-serif;
font-size: 16px;
letter-spacing: 0px;
word-spacing: 0px;
color: red;
font-weight: 500;
text-decoration: none;
font-style: normal;
font-variant: normal;
text-transform: uppercase;
font-weight: bold;
}
</style>
<script>

	
</script>
<style></style>
  </head>
  <body class="h-100"><div class="form">
    <div class="container-fluid">
      <div class="row">
        <!-- Main Sidebar -->
        <?php include "menu.inc"; ?>
        <!-- End Main Sidebar -->
        <main class="main-content col-lg-10 col-md-9 col-sm-12 p-0 offset-lg-2 offset-md-3 reportFont">
          <div class="main-navbar sticky-top bg-white">
            <!-- Main Navbar -->
            <?php include "mainnavbar.inc"; ?>
          <!-- / .main-navbar -->
          <div class="main-content-container container-fluid px-4">
            <!-- Page Header -->
            <div class="page-header row no-gutters py-4">
              <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
                <span class="text-uppercase page-subtitle">Reports</span>
                <h3 class="page-title">Daily Report</h3>
              </div>
            </div>
            <!-- End Page Header -->
			  <div class="row">
              <div class="col-lg-12 mb-4">
                <!-- Sliders & Progress Bars -->
                <div class="card card-small mb-4">
                  <div class="card-header border-bottom">
				   <div class="form-row">
						  <div class="form-group col-md-6">
                    <h6 class="m-0">Transaction Summary as of 
					<?php 
					$now = new DateTime();
					echo $now->format("F j, Y, g:i a"); 
				?></h6></div>
				<div class="form-group col-md-6" style="text-align:right;">
				<!--<h6 class="m-0">Beginning Balance: ₱ </h6>-->
				</div>
                  </div>
				  </div>
                    <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                      <!-- Progress Bars -->
                      <div class="form-row">
						  <div class="form-group col-md-12">
						   
							  <table style="width:100%;"id="retailTbl">
							  <thead>
								<tr>
								  <th width="50%">Product</th>
								  <th width="15%">Quantity</th>
								  <th width="10%">Total Amount</th>
								  <th width="10%">Action</th>
								</tr>
							  </thead>
							  <tbody>
								<?php
								$productTotal =0;
								while($output = mysqli_fetch_assoc($result))
								{
									echo "<tr>";
									echo "<td>".$output['productTitle']."</td>";
									echo "<td>".$output['quantity']."</td>";
									echo "<td>".$output['totalAmount']."</td>";
									echo "<td> </td>";
									echo "</tr>";
									$productTotal+=$output['totalAmount'];
								}
								 
									echo "<tr>";
									echo "<td></td>";
									echo "<td style='text-align:right'><strong>Total: </strong></td>";
									echo "<td><strong>".sprintf('%0.2f',(float)$productTotal)."</strong></td>";
									
									echo "<td> </td>";
									echo "</tr>";
								?>
								
								</tbody>
							</table>
						  </div>
					</div>
					<div class="form-row">
						   <div class="form-group col-md-12">
						   
							  <table style="width:100%;" id="serviceTbl">
							  <thead>
								<tr>
								  <th width="50%">Service</th>
								  <th width="15%">Details</th>
								  <th width="10%">Total Amount</th>
								  <th width="10%">Action</th>
								</tr>
							  </thead>
							  <tbody>
								<?php
								$serviceTotal =0;
								while($outputService = mysqli_fetch_assoc($resultServ))
								{
									$renderId = $outputService['renderId'];
									echo "<tr>";
									echo "<td>".$outputService['serviceName']."</td>";
									echo "<td>";
									$query2 = "SELECT sd.fieldName, d.fieldValue from services_rendered r left join rendered_details d on d.renderId=r.renderId left join service_details sd on sd.serviceDetailId = d.serviceDetailId where r.renderId = '$renderId' and r.branchId = $branchId"; 
									$details = mysqli_query($con, $query2);
									while($output2 = mysqli_fetch_assoc($details))
									{
										echo $output2['fieldName']." : ".$output2['fieldValue'];
										echo "<br/>";
									}
									echo "</td>";
									echo "<td>".$outputService['totalAmount']."</td>";
									echo "<td> </td>";
									echo "</tr>";
									
									$serviceTotal+=$outputService['totalAmount'];
								}
								
								echo "<tr>";
									echo "<td></td>";
									echo "<td style='text-align:right'><strong>Total: </strong></td>";
									echo "<td><strong>".sprintf('%0.2f',(float)$serviceTotal)."</strong></td>";
									
									echo "<td> </td>";
									echo "</tr>";
								?>
								</tbody>
							</table>
						  </div>
					</div>
					<div class="form-row">
						   <div class="form-group col-md-12">
						   
							   <table style="width:100%;">
							  <thead>
								<tr>
								  <th width="50%">Expense</th>
								  <th width="15%">Expense Detail</th>
								  <th width="10%">Total Amount</th>
								  <th width="10%">Action</th>
								</tr>
							  </thead>
							  <tbody>
								<?php
								$expenseTotal =0;
								while($outputServExp = mysqli_fetch_assoc($resultServExp))
								{
									$renderId = $outputServExp['renderId'];
									echo "<tr>";
									echo "<td>".$outputServExp['expenseDetail']."</td>";
									echo "<td>";
									$query2 = "SELECT sd.fieldName, d.fieldValue from services_rendered r left join rendered_details d on d.renderId=r.renderId left join service_details sd on sd.serviceDetailId = d.serviceDetailId where r.renderId = '$renderId' and r.branchId = $branchId"; 
									$details = mysqli_query($con, $query2);
									while($output2 = mysqli_fetch_assoc($details))
									{
										echo $output2['fieldName']." : ".$output2['fieldValue'];
										echo "<br/>";
									}
									echo "</td>";
									echo "<td>".$outputServExp['totalExpense']."</td>";
									echo "<td> </td>";
									echo "</tr>";
									
									$expenseTotal+=$outputServExp['totalExpense'];
								}
								
								while($outputOthExp = mysqli_fetch_assoc($resultOthExp))
								{
									echo "<tr>";
									echo "<td>".$outputOthExp['expenseName']."</td>";
									echo "<td></td>";
									echo "<td>".$outputOthExp['expenseCost']."</td>";
									echo "<td> </td>";
									echo "</tr>";
									
									$expenseTotal+=$outputOthExp['expenseCost'];
								}
								
									echo "<tr>";
									echo "<td></td>";
									echo "<td style='text-align:right'><strong>Total: </strong></td>";
									echo "<td><strong>".sprintf('%0.2f',(float)$expenseTotal)."</strong></td>";
									
									echo "<td> </td>";
									echo "</tr>";
								?>
								</tbody>
							</table>
						  </div>
					</div>
					<div style="text-align:right" class="totalFont">
					Total Income: <?php 
					$totalIncome = ($productTotal+$serviceTotal)  - $expenseTotal;
					echo "₱".sprintf('%0.2f',(float)$totalIncome);?><br/>
					<!--Ending Balance: -->
                    </div>
					</li>
                  </ul>
				  
				   </div>
                </div>
				</div>
				  
				 </div>
        </main>
	  </div>

			</div>
	
    <?php include "scripts.inc"; ?>
	</div>

  </body>
</html>