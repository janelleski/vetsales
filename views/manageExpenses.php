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
$('#expensesTable').DataTable({
		"bProcessing": true,
         "serverSide": true,
		 "aaSorting": [ [0,"desc" ]],
         "ajax":{
            url :"getExpenses.php", // json datasource
            type: "post",  // type of method  ,GET/POST/DELETE
			data: function(d){
		d.user = user;
        return d
    },
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
                <span class="text-uppercase page-subtitle">Expenses</span>
                <h3 class="page-title">Manage Expenses</h3>
              </div>
				
            </div>
            <!-- End Page Header -->
            <div class="row">
              <div class="col-lg-12 mb-4">
                <div class="card card-small mb-4">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item px-3">
                      <div class="col">
						<table id="expensesTable" class="display" width="100%" cellspacing="0" data-page-length="100">
							<thead>
								<tr>
									<th>#</th>
									<th>Expense Date</th>
									<th>Expense Description</th>
									<th>Expense Amount</th>
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
    <?php include "scripts_min.inc"; ?>
	</div>

  </body>
</html>