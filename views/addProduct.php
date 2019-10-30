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
	<script>

</script>
<?php

	$msg = "";
	if (isset($_POST['submit'])) {
		$username = $_SESSION['username'];
		$productName = $_POST['productName']; 
		$productBrand = $_POST['productBrand']; 
		$productDesc = $_POST['productDesc']; 
		$category = $_POST['categoryList']; 
		$quantity = $_POST['quantity'];
		$cost = $_POST['cost']; 	
		$srp = $_POST['srp']; 
		$sql = "SELECT branchId FROM users where username='$username'";
	   
	   if ($result = mysqli_query($con,$sql)){
		  while ($obj = mysqli_fetch_object($result)){
			 $branchId = $obj->branchId;
		  }
		  mysqli_free_result($result);
	   }
	   if(file_exists($_FILES['image']['tmp_name'])){
	    $imgData = addslashes(file_get_contents($_FILES['image']['tmp_name']));
        $imageProperties = getimageSize($_FILES['image']['tmp_name']);
    
		
		$sql = "INSERT INTO products (image,dateAdded,brand,productDesc, productTitle,quantity,cost,srp,branchId,status,category) VALUES 
		('$imgData',sysdate(),'$productBrand','$productDesc', '$productName',$quantity,$cost,$srp,$branchId,1,$category)";
	   }
	   else{
		 $sql = "INSERT INTO products (image,dateAdded,brand,productDesc, productTitle,quantity,cost,srp,branchId,status,category) VALUES 
		(null,sysdate(),'$productBrand','$productDesc', '$productName',$quantity,$cost,$srp,$branchId,1,$category)";
		   
	   }
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
					  <p>Product added.</p>
					</div>
						<div class='modal-footer'>
							
								<button type='button' class='btn btn-default' data-dismiss='modal'>Dismiss</button>
							 
						</div>
					</div>
				</div>
			</div>";         
		}else{
			echo "<div>".mysqli_error($con)."</div>";
		}

	}
	
	//batch upload
	
	if(isset($_POST["import"])){
		
    $username = $_SESSION['username'];
	$sql = "SELECT branchId FROM users where username='$username'";
	if ($result = mysqli_query($con,$sql)){
		  while ($obj = mysqli_fetch_object($result)){
			 $branchId = $obj->branchId;
		  }
		  mysqli_free_result($result);
	   }
	   
	   
    $filename=$_FILES["file"]["tmp_name"];  
	$isUploaded = true;
     if($_FILES["file"]["size"] > 0)
     {
        $file = fopen($filename, "r");
          while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
           {
             $sql = "INSERT into products (dateAdded,productTitle,quantity,cost,srp,category,status,branchId) 
                   values (sysdate(),".$getData[0].",".$getData[1].",".$getData[2].",".$getData[3].",".$getData[4].",1,$branchId)";
                   $result = mysqli_query($con, $sql);
				if(!isset($result))
				{
				 $isUploaded = false;
				}
       
           }
		   if($isUploaded){
			   echo "<script type=\"text/javascript\">
            alert(\"CSV File has been successfully Imported.\");
          </script>";
		   }
		   
      
           fclose($file);  
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
                <span class="text-uppercase page-subtitle">Products</span>
               <h3 class="page-title">Add New Product</h3>
              </div>
            </div>
            <!-- End Page Header -->
            <div class="row">
              <div class="col-lg-8 mb-4">
                <div class="card card-small mb-4">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
					<form action="<?=$_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data">
                      <div class="form-row">
						<div class="form-group col-md-6">
							<label for="productName">Product Name</label>
							<input type="text" class="form-control" id="productName" name="productName" placeholder="Product Name" required> 
						</div>
						<div class="form-group col-md-6">
						<label for="categoryList">Category List</label>
							<?php
								$username = $_SESSION['username'];
								$sql = "SELECT c.categoryId, c.categoryName from categories c where c.branchId=(select branchId from users where username='$username')";
								$result = mysqli_query($con,$sql);

								echo "<select id='categoryList' name='categoryList' class='form-control' required>";
								while($row = mysqli_fetch_array($result))
								{
									echo "<option value='" . $row['categoryId'] . "'>" . $row['categoryName'] . "</option>";
								}
								echo "</select>";
								?>
						</div>
                      </div>
					  <div class="form-row">
						<div class="form-group col-md-6">
							<label for="productBrand">Product Brand</label>
							<input type="text" class="form-control" id="productBrand" name="productBrand" placeholder="Product Brand"> 
						</div>
						<div class="form-group col-md-6">
							<label for="isbn">ISBN/Product ID/SN</label>
							<input type="text" class="form-control" id="isbn" name="isbn" placeholder="ISBN/Product ID/SN"> 
						</div>
					  </div>
					  <div class="form-row">
						<div class="form-group col-md-6">
							<label for="productDesc">Product Description</label>
							<input type="text" class="form-control" id="productDesc" name="productDesc" placeholder="Product Description"> 
						</div>
						<div class="form-group col-md-6">
							<label for="quantity">Quantity</label>
							<input type="text" class="form-control" id="quantity" name="quantity" placeholder="Quantity" required> 
						</div>
						
					  </div>
					   <div class="form-row">
						<div class="form-group col-md-6">
						<label for="cost">Cost</label>
							<div class="input-group mb-3">
							  <div class="input-group-prepend">
								<span class="input-group-text">₱</span>
							  </div>
							  <input type="text" class="form-control" placeholder="Cost" name="cost" id="cost" required>
							  <div class="input-group-append">
								<span class="input-group-text">.00</span>
							  </div>
							</div>
						</div>
						<div class="form-group col-md-6">
							<label for="srp">SRP</label>
							<div class="input-group mb-3">
							  <div class="input-group-prepend">
								<span class="input-group-text">₱</span>
							  </div>
							  <input type="text" class="form-control" placeholder="SRP" name="srp" id="srp" required>
							  <div class="input-group-append">
								<span class="input-group-text">.00</span>
							  </div>
							</div>
						</div>
						<div class="form-group col-md-6">
						<label for="discount">Discount</label>
							<div class="input-group mb-3">
							  <div class="input-group-prepend">
								<span class="input-group-text">₱</span>
							  </div>
							  <input type="text" class="form-control" placeholder="Discount Price (Optional)" name="discount" id="discount">
							  <div class="input-group-append">
								<span class="input-group-text">.00</span>
							  </div>
							</div>
						</div>
					  </div>
					   <div class="row mb-3"><div class="col" style="text-align:right">
					   <input type="submit" value="Add Product" name="submit" class="btn btn-accent">
					  </div>
					  </div>
					  
					  
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-lg-4 mb-4">
                <!-- Sliders & Progress Bars -->
                <div class="card card-small mb-4">
                  <div class="card-header border-bottom">
                    <h6 class="m-0">Upload Image</h6>
                  </div>
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item px-3">
                      <!-- Progress Bars -->
                      <div class="row mb-2">
                         <div class="col">
							<div class="form-group">
								<label for="image">Valid formats: jpg, png, gif</label>
								<input type="file" class="form-control-file" id="image" name="image">
							</div>
						 </div>
                      </div>
                      <!-- / Progress Bars -->
                    </li>
                  </ul>
                </div>
				
				
        
				</div>
	
			</div>
			</form>
			<form action="<?=$_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data">
			<div class="row" style="margin-bottom:20px;">
			
              <div class="page-header col-12 col-sm-4 text-center text-sm-left mb-0">
                <span class="text-uppercase page-subtitle">Add New Product</span>
               <h3 class="page-title">Batch Upload</h3>
              </div>
			 </div>
			<div class="row">
				<div class="col-lg-8 mb-4">
					<div class="card card-small mb-4">
						<ul class="list-group list-group-flush">
							<li class="list-group-item p-3">
                                <label for="filebutton">Select File: </label> 
								<input type="file" name="file" id="file" class="input-large">
                        <!-- Button -->
                        <div class="form-group">
                            <div style="text-align:right">
                                <button type="submit" id="import" name="import" class="btn btn-primary button-loading" data-loading-text="Loading...">Upload Products</button>
                            </div>
                        </div>
                    </fieldset>
					  
					  
							</li>
						</ul>
					</div>
				</div>
				 <div class="col-lg-4 mb-4">
                <!-- Sliders & Progress Bars -->
                <div class="card card-small mb-4">
                  <div class="card-header border-bottom">
                    <h6 class="m-0">Batch Upload Guidelines</h6>
                  </div>
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item px-3">
                      <!-- Progress Bars -->
                      <div class="row mb-2">
                         <div class="col">
							1: Create a csv file in excel with the following basic information:<br/><br/>
							Product Title, Quantity, Cost, SRP, Category<br/><br/>For example:<br/><br/>
							'Pet Slicker Brush',5,68,120,120 (Category number refer to 'Manage Categories')<br/><br/>
							2: Click 'Choose File' and select the csv file containing the product list <br/>
						 </div>
                      </div>
                      <!-- / Progress Bars -->
                    </li>
                  </ul>
                </div>
        
				</div>
			</div>
			
		</div>
	</div>
	</main></form>
    <?php include "scripts.inc"; ?>
	</div>

  </body>
</html>