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
	.table td, .table th{
		border-top:0px !important; 
	}
	</style>
<?php
	if(isset($_POST['submit'])) {
	$expenseName = $_POST['expenseName']; 
	$cost = $_POST['cost']; 
	$username = $_SESSION['username'];
	$sql = "SELECT branchId FROM users where username='$username'";
	   
	   if ($result = mysqli_query($con,$sql)){
		  while ($obj = mysqli_fetch_object($result)){
			 $branchId = $obj->branchId;
		  }
		  mysqli_free_result($result);
	   }
	 
	// Attempt insert query execution
	$sql = "INSERT INTO expenses (expenseDate, expenseName,expenseCost, branchId,status) VALUES (sysdate(), '$expenseName', $cost,$branchId,1)";
	if(mysqli_query($con, $sql)){

	 echo "<script>
	$(document).ready(function(){
	$('#myModal').modal('show');
	});
	</script>

			<div class='modal fade' id='myModal' role='dialog'>
				<div class='modal-dialog modal-mb'>
				  <div class='modal-content'>
					<div class='modal-body'>
					  <p>Expense added.</p>
					</div>
						<div class='modal-footer'>
							
								<button type='button' class='btn btn-default' data-dismiss='modal'>Dismiss</button>
							 
						</div>
					</div>
				</div>
			</div>";           
	} else{
		echo "ERROR: Could not able to execute $sql. " . mysqli_error($con);
	}

	} 
	
	

?>

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
                <span class="text-uppercase page-subtitle">Transactions</span>
                <h3 class="page-title">Add Expense</h3>
              </div>
            </div>
            <!-- End Page Header -->
            <div class="row">
              <div class="col-lg-8 mb-4">
                <div class="card card-small mb-4">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
					<form action="<?=$_SERVER['PHP_SELF'];?>" method="post">
                      <div class="form-row">
						
							<div class="form-group col-md-6">
								<label for="categoryName">Expense Name</label>
								  <input type="text" class="form-control" id="expenseName" name="expenseName" placeholder="Expense Name" required> 
							</div>
							 <div class="form-group col-md-6">
								<label for="categoryDesc">Cost</label>
								  <input type="text" class="form-control" id="cost" name="cost"  placeholder="0.00"  required> 
							</div>
                      </div>
					   <div class="form-row"><div class="col" style="text-align:right">
					  <input type="submit" value="Add Expense" name="submit" class="btn btn-accent"></div>
					  </div>
					  </form>
					  
                    </li>
                  </ul>
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