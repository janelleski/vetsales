<!--PHP login System by WEBDEVTRICK (https://webdevtrick.com) -->
<?php
require('../db.php');
include("../auth.php"); 

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
</style>
 <script type="text/javascript">	
$( document ).ready(function() {
	var user = "<?php echo $_SESSION['username']; ?>";
$('#transactionTable').DataTable({
		"bProcessing": true,
         "serverSide": true,
		 "aaSorting": [ [0,"desc" ]],
         "ajax":{
            url :"getTransactions.php", // json datasource
            type: "post",  // type of method  ,GET/POST/DELETE
			data: function(d){
       d.startDate = $('#startDate').val();
        d.endDate = $('#endDate').val();
		d.user = user;
        return d
    },
            error: function(){
              $("#transactionTable").css("display","none");
            }
          }
        });   
		
	$("#startDate").datepicker({ 
        format: 'yyyy-mm-dd'
    });
	$("#endDate").datepicker({ 
        format: 'yyyy-mm-dd'
    });


});

function checkToDelete(id){
	var user = "<?php echo $_SESSION['username']; ?>";
	var transactionId = id;
	$('#checkDeleteModal').modal('show');
	document.getElementById("idVoid").value = id;
}

function checkToPaid(id){
	var user = "<?php echo $_SESSION['username']; ?>";
	var transactionId = id;
	 $.ajax({
        url: "checkTransactionStatus.php",
        type: "POST",
		dataType: 'JSON',
        data: {transactionId:transactionId,user:user},
        success: function(result){
			
			if(result == '1'){
				$('#alreadyPaidModal').modal('show');
			}else{
				$('#checkPaidModal').modal('show');
				
			} 
	
			
        }
    });
	document.getElementById("idPaid").value = id;
}

function deleteTransaction(){
	$('#checkDeleteModal').modal('hide');
	var user = "<?php echo $_SESSION['username']; ?>";
	var transactionId = document.getElementById("idVoid").value;

	 $.ajax({
        url: "deleteTransaction.php",
        type: "POST",
		dataType: 'JSON',
        data: {transactionId:transactionId,user:user},
        success: function(result){
			
        }
    });
	
	$('#deletedModal').modal('show');
	
}

function markPaid(){
	$('#checkPaidModal').modal('hide');
	var user = "<?php echo $_SESSION['username']; ?>";
	var transactionId = document.getElementById("idPaid").value;

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

function filterTransaction()
{	
	 $('#transactionTable').DataTable().ajax.reload();
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
                <h3 class="page-title">View Transaction History</h3>
              </div>
				 <div id="blog-overview-date-range" class="input-daterange input-group input-group-sm my-auto ml-auto mr-auto ml-sm-auto mr-sm-0" style="max-width: 350px;">
                          <input type="text" class="input-sm form-control" name="startDate" id="startDate" placeholder="Start Date">
                          <input type="text" class="input-sm form-control" name="endDate" id="endDate" placeholder="End Date">
                          <span class="input-group-append">
                              <button type="button" id="filter" class="btn btn-accent" onclick="filterTransaction();">Filter</button>
                          </span>
                        </div>
            </div>
            <!-- End Page Header -->
            <div class="row">
              <div class="col-lg-12 mb-4">
                <div class="card card-small mb-4">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item px-3">
                      <div class="col">
						<table id="transactionTable" class="display" width="100%" cellspacing="0" data-page-length="100">
							<thead>
								<tr>
									<th>#</th>
									<th>Transaction Date</th>
									<th>Customer Name</th>
									<th>Total Amount</th>
									<th>Status</th>
									<th>Cashier</th>
									<th>Actions</th>
								</tr>
							</thead> 
						</table>
                       </div>
                    </li>
                  </ul>
                </div>
              </div>
			  </div>
        </main>
	  </div>
			
	</div>
	<div class='modal fade' id='checkDeleteModal' role='dialog'>
				<div class='modal-dialog modal-mb'>
				  <div class='modal-content'>
					<div class='modal-body'>
					  <p>Are you sure you want to delete this transaction?</p>
					</div>
						<div class='modal-footer'>
								<input type="hidden" value="" id ="idVoid"/>
								<button type='submit' name="void" class='btn btn-danger' data-dismiss='modal' onclick="deleteTransaction();">Yes</button>
								<button type='button' class='btn btn-default' data-dismiss='modal'>Dismiss</button>
							 
						</div>
					</div>
				</div>
	</div>
	<div class='modal fade' id='checkPaidModal' role='dialog'>
				<div class='modal-dialog modal-mb'>
				  <div class='modal-content'>
					<div class='modal-body'>
					  <p>Are you sure you want to mark this transaction as Paid?</p>
					</div>
						<div class='modal-footer'>
								<input type="hidden" value="" id ="idPaid"/>
								<button type='submit' name="paid" class='btn btn-danger' data-dismiss='modal' onclick="markPaid();">Yes</button>
								<button type='button' class='btn btn-default' data-dismiss='modal'>Dismiss</button>
							 
						</div>
					</div>
				</div>
	</div>
	<div class='modal fade' id='paidModal' role='dialog'>
				<div class='modal-dialog modal-mb'>
				  <div class='modal-content'>
					<div class='modal-body'>
					  <p>This transaction has already been marked as Paid.</p>
					</div>
						<div class='modal-footer'>
								
								<button type='button' class='btn btn-default' data-dismiss='modal'>Dismiss</button>
							 
						</div>
					</div>
				</div>
	</div>
	<div class='modal fade' id='deletedModal' role='dialog'>
				<div class='modal-dialog modal-mb'>
				  <div class='modal-content'>
					<div class='modal-body'>
					  <p>The transactions has been deleted.</p>
					</div>
						<div class='modal-footer'>
								
								<button type='button' class='btn btn-default' data-dismiss='modal' onclick='location.reload();'>Dismiss</button>
							 
						</div>
					</div>
				</div>
	</div>
	<div class='modal fade' id='alreadyVoidModal' role='dialog'>
				<div class='modal-dialog modal-mb'>
				  <div class='modal-content'>
					<div class='modal-body'>
					  <p>Transaction is already marked as void.</p>
					</div>
						<div class='modal-footer'>
								
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
	<div class='modal fade' id='alreadyPaidModal' role='dialog'>
				<div class='modal-dialog modal-mb'>
				  <div class='modal-content'>
					<div class='modal-body'>
					  <p>Transaction is already paid.</p>
					</div>
						<div class='modal-footer'>
								
								<button type='button' class='btn btn-default' data-dismiss='modal'>Dismiss</button>
							 
						</div>
					</div>
				</div>
	</div>
	<div class='modal fade' id='cannotPaidVoidModal' role='dialog'>
				<div class='modal-dialog modal-mb'>
				  <div class='modal-content'>
					<div class='modal-body'>
					  <p>Void transactions cannot be marked as paid.</p>
					</div>
						<div class='modal-footer'>
								
								<button type='button' class='btn btn-default' data-dismiss='modal'>Dismiss</button>
							 
						</div>
					</div>
				</div>
	</div>
    <?php include "scripts_min.inc"; ?>
	</div>

  </body>
</html>