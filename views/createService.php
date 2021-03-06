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
	.table td, .table th{
		border-top:0px !important; 
	}
	i.icon-red {
    color: red !important;
	}
	
	</style>
<script>
function saveService(){
	
	var user = "<?php echo $_SESSION['username']; ?>";
	var serviceName = document.getElementById("serviceName").value;
	var serviceDesc = document.getElementById("serviceDesc").value;
	var serviceCost = document.getElementById("serviceCost").value;
	var serviceComm = document.getElementById("serviceComm").value;
	
	if(serviceName=='' || serviceCost==''){
		$('#validation').modal('show');
	}else{
		$.ajax({
        url: "saveService.php",
        type: "POST",
		dataType: 'JSON',
        data: {user:user,serviceName:serviceName,serviceDesc:serviceDesc,serviceCost:serviceCost,serviceComm:serviceComm},
        success: function(result){
			saveDetails(result);
			
        }
    });
	
	}
	
	
}

function saveDetails(result){
	
	var table = document.getElementById("detailTbl");
	var rowNum = table.rows.length;

	var serviceId = result.serviceId;
	var user = "<?php echo $_SESSION['username']; ?>";
 
    for (var i = 1 ; i < table.rows.length; i++) {
 
		var detailName = document.getElementById(i).value;
		if(detailName!=''){
			$.ajax({
			url: "saveServiceDetails.php",
			type: "POST",
			dataType: 'JSON',
			data: {serviceId:serviceId,detailName:detailName,user:user},
			success: function(result){
			}
			});
		}
   
	}
	
	resetForm();
	$('#saveServiceModal').modal('show');
}

function addDetailRow(){
	var table = document.getElementById("detailTbl");
		var rowNum = table.rows.length;
		var row = table.insertRow(table.rows.length);
		
		var detailName = row.insertCell(0);
		var remove = row.insertCell(1);
		detailName.innerHTML = '<input id='+rowNum+' type=\'text\' class=\'form-control\'>';
		remove.innerHTML = '<a class=\'link\' style=\'cursor:pointer;\' onclick=\'deleteRow(this);\'><i class=\'material-icons icon-red\'>clear</i></a>';

}

function deleteRow(rowNum,amount){
		var row = rowNum.parentNode.parentNode;
		row.parentNode.removeChild(row);
}

function resetForm()
{
	var table = document.getElementById("detailTbl");
	var rowLength = table.rows.length;
	   
	if(rowLength>1){
		for(var i=1;i<rowLength;i++){
		   table.deleteRow(1);
		}
	}
	
	document.getElementById("serviceName").value = null;
	document.getElementById("serviceDesc").value = null;
	document.getElementById("serviceCost").value = null;
	document.getElementById("serviceComm").value = null;
}
</script>



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
            <div class="page-header row no-gutters py-4">
              <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
                <span class="text-uppercase page-subtitle">Services</span>
               <h3 class="page-title">Create Service</h3>
              </div>
            </div>
            <!-- End Page Header -->
            <div class="row">
              <div class="col-lg-10 mb-4">
                <div class="card card-small mb-4">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
					<form action="<?=$_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data">
                      <div class="form-row">
						<div class="form-group col-md-12">
							<label for="serviceName">Service Name</label>
							<input type="text" class="form-control" id="serviceName" name="serviceName" placeholder="Service Name" required> 
						</div>
                      </div>
					  <div class="form-row">
						<div class="form-group col-md-12">
							<label for="serviceDesc">Service Description</label>
							<input type="text" class="form-control" id="serviceDesc" name="serviceDesc" placeholder="Service Description"> 
						</div>
					  </div>
					  <div class="form-row">
						<div class="form-group col-md-6">
							<label for="serviceCost">Service Cost</label>
							<input type="text" class="form-control" id="serviceCost" name="serviceCost" placeholder="Service Cost" required> 
						</div>
						<div class="form-group col-md-6">
							<label for="serviceComm">Service Commission</label>
							<input type="text" class="form-control" id="serviceComm" name="serviceComm" placeholder="%"> 
						</div>
						
					  </div>
					  <div class="form-group">
										<label for="detailTbl">Service Details</label>
										<table class="table mb-0" id="detailTbl" style="border-collapse: collapse;border: 1px solid black;border-color:#e1e5eb;">
										   <thead>
											<tr>
												<td>Detail Name</td>
												<td></td>
											</tr>
										   </thead>
										  <tbody>

										  </tbody>
										</table><br/>
										<div style="text-align:right;">
										 <button type="button" class="btn btn-accent" onclick="addDetailRow();" style="margin-left:10px;">+ Add Service Detail</button>
										</div>
										</div>
					   <div class="row mb-3"><div class="col" style="text-align:right">
					   <input type="button" value="Create Service" onclick="saveService();" class="btn btn-accent">
					  </div>
					  </div>
					  
					  
                    </li>
                  </ul>
                </div>
              </div>
              
	
			</div>
			</form>

		</div>
	</div>
	</main>
	<div class='modal fade' id='saveServiceModal' role='dialog'>
				<div class='modal-dialog modal-mb'>
				  <div class='modal-content'>
					<div class='modal-body'>
					  <p>Service created.</p>
					</div>
						<div class='modal-footer'>
							
								<button type='button' class='btn btn-default' data-dismiss='modal'>Dismiss</button>
							 
						</div>
					</div>
				</div>
	</div>
	<div class='modal fade' id='validation' role='dialog'>
				<div class='modal-dialog modal-mb'>
				  <div class='modal-content'>
					<div class='modal-body'>
					  <p>Service name and cost are required.</p>
					</div>
						<div class='modal-footer'>
							
								<button type='button' class='btn btn-default' data-dismiss='modal'>Dismiss</button>
							 
						</div>
					</div>
				</div>
			</div>
    <?php include "scripts.inc"; ?>
	</div>

  </body>
</html>