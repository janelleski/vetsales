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
$('#servicesTable').DataTable({
		"bProcessing": true,
         "serverSide": true,
         "ajax":{
            url :"getManageServices.php", // json datasource
            type: "post",  // type of method  ,GET/POST/DELETE
			data: {user:user},
            error: function(){
              $("#servicesTable").css("display","none");
            }
          }
        });   
});

function editProduct(id){
	location.href = 'editProduct.php?productId='+id;
}

function confirm(id){
	$("#confirmDelete").attr("onclick","deleteService("+id+")");
	$('#deleteModal').modal('show');
}
function editService(id){
	location.href = 'editService.php?serviceId='+id;
}


function deleteService(id)
{
	var serviceId = id;
	var user = "<?php echo $_SESSION['username']; ?>";
	$.ajax({
        url: "deleteService.php",
        type: "POST",
		dataType: 'JSON',
        data: {serviceId:serviceId,user:user},
        success: function(result){
			
        }
    });
	
	location.reload();
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
                <h3 class="page-title">Manage Services</h3>
              </div>
            </div>
            <!-- End Page Header -->
            <!-- Small Stats Blocks -->
			<!-- Default Light Table -->
			<div class="row">
              <div class="col-lg-12 mb-4">
                <div class="card card-small mb-4">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item px-3">
                      <div class="col">
						<table id="servicesTable" class="display" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th>Service ID</th>
									<th>Service Name</th>
									<th>Service Cost</th>
									<th>Service Commission</th>
									<th>Actions</th>
								</tr>
							</thead> 
						</table>
						</div>
                       </div>
                    </li>
                  </ul>
                </div>
              </div>
			  </div>
            <!-- End Default Light Table -->
            <!-- End Small Stats Blocks -->

        </main>
		
		<div class='modal fade' id='deleteModal' role='dialog'>
				<div class='modal-dialog modal-mb'>
				  <div class='modal-content'>
					<div class='modal-body'>
					  <p>Are you sure you want to delete this service?</p>
					</div>
						<div class='modal-footer'>
								<button type='submit' id='confirmDelete' name="delete" class='btn btn-danger' data-dismiss='modal'>Delete</button>
								<button type='button' class='btn btn-default' data-dismiss='modal'>Dismiss</button>
							 
						</div>
					</div>
				</div>
		</div>
		<div class='modal fade' id='deleteConfirmed' role='dialog'>
				<div class='modal-dialog modal-mb'>
				  <div class='modal-content'>
					<div class='modal-body'>
					  <p>Service deleted.</p>
					</div>
						<div class='modal-footer'>
							<button type='button' class='btn btn-default' data-dismiss='modal'>Dismiss</button>
							 
						</div>
					</div>
				</div>
		</div>
      </div>
    </div>
    <?php include "scripts_min.inc"; ?>
	</div>
	
  </body>
</html>