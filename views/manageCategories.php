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
	$categoryName = $_POST['categoryName']; 
	$categoryDesc = $_POST['categoryDesc']; 
	$username = $_SESSION['username'];
	$sql = "SELECT branchId FROM users where username='$username'";
	   
	   if ($result = mysqli_query($con,$sql)){
		  while ($obj = mysqli_fetch_object($result)){
			 $branchId = $obj->branchId;
		  }
		  mysqli_free_result($result);
	   }
	 
	// Attempt insert query execution
	$sql = "INSERT INTO categories (categoryName, categoryDesc, branchId,status) VALUES ('$categoryName', '$categoryDesc', $branchId,1)";
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
					  <p>Category added.</p>
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
	
	if(isset($_POST['edit'])) {
	$categoryName = $_POST['editCategoryName']; 
	$categoryDesc = $_POST['editCategoryDesc']; 
	$categoryId = $_SESSION['categoryId'];
	 
	// Attempt insert query execution
	$sql = "Update categories SET categoryName='$categoryName', categoryDesc='$categoryDesc' WHERE categoryId='$categoryId'";
	if(mysqli_query($con, $sql)){
		header("Location: manageCategories.php");
	}
		
	} 
  
	if(isset($_REQUEST['del'])){
		$id = $_REQUEST['del'];
		$sql= "DELETE FROM categories WHERE categoryId = $id";
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
					  <p>Category deleted.</p>
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
	
	if(isset($_REQUEST['check'])){
		$id = $_REQUEST['check'];
		$catName;
		$catDesc;
		$sql= "Select c.categoryName, c.categoryDesc FROM categories c WHERE c.categoryId = $id";
		 if ($result = mysqli_query($con,$sql)){
		  while ($obj = mysqli_fetch_object($result)){
			 $catName = $obj->categoryName;
			 $catDesc = $obj->categoryDesc;
		  }
		  mysqli_free_result($result);
		  $_SESSION['categoryId'] = $id;
	   }
	   echo "<script>
	$(document).ready(function(){
	$('#editModal').modal('show');
	$('#editModal #editCategoryName').val('$catName');
	$('#editModal #editCategoryDesc').val('$catDesc');
	});
	</script>
	
	  <div class='modal fade' id='editModal' role='dialog'>
				<div class='modal-dialog modal-lg'>
				  <div class='modal-content'>
				  <div class='modal-header'>
				  Edit Category Details
				  </div>
					<div class='modal-body'><div class='container-fluid'>
					  <form action='' method='post'>
                      <div class='form-row'>
							<div class='form-group col-md-12'>
							<label for='categoryName'>Category Name</label>
								  <input type='text' class='form-control' id='editCategoryName' name='editCategoryName' placeholder='Category Name' required> 		
							</div>
                      </div>
					  <div class='form-row'>
						<div class='form-group col-md-12'>
							<label for='categoryDesc'>Category Description</label>
								  <input type='text' class='form-control' id='editCategoryDesc' name='editCategoryDesc' placeholder='Category Description'> 
							</div>
					  </div>
					   <div class='row mb-3'><div class='col' style='text-align:right'>
					  <input type='submit' value='Submit' name='edit' class='btn btn-accent'>
					  <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
					  </div>
					  </div>
					  </form>
					</div>
					</div></div>
				</div>
			</div>
	
	";
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
                <span class="text-uppercase page-subtitle">Products</span>
                <h3 class="page-title">Manage Categories</h3>
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
						
							<div class="form-group col-md-12">
								<label for="categoryName">Category Name</label>
								  <input type="text" class="form-control" id="categoryName" name="categoryName" placeholder="Category Name" required> 
									 <div class="invalid-feedback">Category Name is required</div>
							</div>
							 
                             
						
                      </div>
					  <div class="form-row">
						<div class="form-group col-md-12">
								<label for="categoryDesc">Category Description</label>
								  <input type="text" class="form-control" id="categoryDesc" name="categoryDesc" placeholder="Category Description"> 
							</div>
					  </div>
					   <div class="form-row"><div class="col" style="text-align:right">
					  <input type="submit" value="Add Category" name="submit" class="btn btn-accent"></div>
					  </div>
					  </form>
					  
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-lg-4 mb-4">
                <!-- Sliders & Progress Bars -->
                <div class="card card-small mb-4">
                  <div class="card-header border-bottom">
                    <h6 class="m-0">Categories</h6>
                  </div>
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item px-3">
                      <!-- Progress Bars -->
                      <div class="mb-2">
                         <table class="table">
						  <?php
							$username = $_SESSION['username'];
							$result = mysqli_query($con,"SELECT c.categoryId, c.categoryName as categoryName FROM categories c where c.branchId = (SELECT branchId from users where username='$username')");

								while($row = mysqli_fetch_array($result))
								{
									echo "<tr>";
									echo "<td> ". $row['categoryId'] ." " . $row['categoryName'] . " <a class='link' href=manageCategories.php?check=".$row['categoryId']."><i class='material-icons'>create</i></a>
									 <a class='link' href=manageCategories.php?del=".$row['categoryId']."><i class='material-icons'>delete</i></a></td>";
									echo "</tr>";
								}

						mysqli_close($con);
						?>
						 </table>
                      </div>
                      <!-- / Progress Bars -->
                    </li>
                  </ul>
                </div>
        </main>
      </div>
	
    </div>
    <?php include "scripts.inc"; ?>
	</div>

  </body>
</html>