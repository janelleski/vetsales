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
//$query = "SELECT tLogDetails from transactionlog where transactionId=".$id." and branchId = ".$branchId.";"; 
//$result = mysqli_query($con, $query) or die ( mysqli_error());
//$row = mysqli_fetch_assoc($result);
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
					<?php if($id!=null){?>
						<div class="row mb-2">
						  <div class="col border-bottom">
							<strong>Transaction #<?php echo $id; ?></strong>
						  </div>
						</div>
						<div class="row mb-2">
						  <div class="col">
							Date of Transaction: <br/>
							Customer Name: <br/>
							Customer Address: 
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
			
	</div>
	
    <?php include "scripts_min.inc"; ?>
	</div>

  </body>
</html>