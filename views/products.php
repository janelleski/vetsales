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
$('#productsTable').DataTable({
		"bProcessing": true,
         "serverSide": true,
         "ajax":{
            url :"productList.php", // json datasource
            type: "post",  // type of method  ,GET/POST/DELETE
			data: {user:user},
            error: function(){
              $("#productsTable").css("display","none");
            }
          }
        });   
});

function editProduct(id){
	location.href = 'editProduct.php?productId='+id;
}

function confirm(id){
	$("#confirmDelete").attr("onclick","deleteProduct("+id+")");
	$('#deleteModal').modal('show');
}

function deleteProduct(id)
{
	var productId = id;
	var user = "<?php echo $_SESSION['username']; ?>";
	$.ajax({
        url: "deleteProduct.php",
        type: "POST",
		dataType: 'JSON',
        data: {productId:productId,user:user},
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
                <span class="text-uppercase page-subtitle">Products</span>
                <h3 class="page-title">Manage Products</h3>
              </div>
            </div>
            <!-- End Page Header -->
            <!-- Small Stats Blocks -->
            <div class="row">
				<div class="col-12 col-md-9 text-center text-sm-left mb-0">
					<div class="input-group mb-3">
						<button onclick="location.href='addProduct.php'" class="btn btn-accent" style="margin-left:10px">Add Product</button>
						<button onclick="location.href='manageCategories.php'" class="btn btn-accent" style="margin-left:10px">Add Category</button>
					</div>
				</div>
            </div>
			<!-- Default Light Table -->
			<div class="row">
              <div class="col-lg-12 mb-4">
                <div class="card card-small mb-4">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item px-3">
                      <div class="col">
						<table id="productsTable" class="display" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th>Product ID</th>
									<th>Product Title</th>
									<th></th>
									<th>Category</th>
									<th>Stock</th>
									<th>Cost</th>
									<th>SRP</th>
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
					  <p>Are you sure you want to delete this product?</p>
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
					  <p>Product deleted.</p>
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