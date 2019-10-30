<?php
require('../db.php');
include('../auth.php'); 

$username = $_SESSION['username'];
	$sql = "SELECT branchId FROM users where username='$username'";
	   
	   if ($result = mysqli_query($con,$sql)){
		  while ($obj = mysqli_fetch_object($result)){
			 $branchId = $obj->branchId;
		  }
		  mysqli_free_result($result);
	   }

if(isset($_REQUEST['productId'])){	   
$id=$_REQUEST['productId'];
$query = "SELECT * from products where productId=".$id." and branchId = ".$branchId.";"; 
$result = mysqli_query($con, $query) or die ( mysqli_error());
$row = mysqli_fetch_assoc($result);
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
<style>
	.table td, .table th{
		border-top:0px !important; 
	}
	</style>
	<?php

	if(isset($_POST['submit'])){
		
		//echo '<script>alert(\'here!\');</script>';
		$id = $_POST['productId'];
		
		$username = $_SESSION['username'];
		$productName = $_POST['productName']; 
		$brand = $_POST['productBrand']; 
		$productDesc = $_POST['productDesc']; 
		$category = $_POST['categoryList']; 
		$quantity = $_POST['quantity'];
		$cost = $_POST['cost']; 	
		$srp = $_POST['srp']; 
		
		
		
		if(file_exists($_FILES['image']['tmp_name'])){
			
			$imgData = addslashes(file_get_contents($_FILES['image']['tmp_name']));
			$imageProperties = getimageSize($_FILES['image']['tmp_name']);
			$sql = "Update products SET productTitle='$productName', brand='$brand',productDesc='$productDesc',
			category = $category, quantity=$quantity, cost =$cost,srp=$srp, image ='$imgData' WHERE productId=$id and branchId = $branchId";
		}
		else{
			$sql = "Update products SET productTitle='$productName', brand='$brand',productDesc='$productDesc',
			category = $category, quantity=$quantity, cost =$cost,srp=$srp WHERE productId=$id and branchId = $branchId";
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
					  <p>Saved successfully. </p>
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
		$query = "SELECT * from products where productId=".$id." and branchId = ".$branchId.";"; 
		$result = mysqli_query($con, $query) or die ( mysqli_error());
		$row = mysqli_fetch_assoc($result);
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
               <h3 class="page-title">Edit Product</h3>
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
							<input type="hidden" class="form-control" id="productId" name="productId"  value="<?php echo $row['productId'];?>"> 
							<input type="text" class="form-control" id="productName" name="productName"  value="<?php echo $row['productTitle'];?>" placeholder="Product Name" required> 
						</div>
						<div class="form-group col-md-6">
						<label for="categoryList">Category List</label>
							<?php
								$username = $_SESSION['username'];
								$sql = "SELECT c.categoryId, c.categoryName from categories c where c.branchId=(select branchId from users where username='$username')";
								$result = mysqli_query($con,$sql);

								echo "<select id='categoryList' name='categoryList' class='form-control'>";
								while($res = mysqli_fetch_array($result))
								{
									if($res['categoryId']==$row['category']){
										echo "<option value='" . $res['categoryId'] . "' selected>" . $res['categoryName'] . "</option>";
									}else{
										echo "<option value='" . $res['categoryId'] . "'>" . $res['categoryName'] . "</option>";
									}
									
								}
								echo "</select>";
								?>
						</div>
                      </div>
					  <div class="form-row">
						<div class="form-group col-md-6">
							<label for="productBrand">Product Brand</label>
							<input type="text" class="form-control" id="productBrand" name="productBrand" value="<?php echo $row['brand'];?>" placeholder="Product Brand"> 
						</div>
						<div class="form-group col-md-6">
							<label for="isbn">ISBN/Product ID/SN</label>
							<input type="text" class="form-control" id="isbn" name="isbn" value="<?php echo $row['isbn'];?>" placeholder="ISBN/Product ID/SN"> 
						</div>
					  </div>
					  <div class="form-row">
						<div class="form-group col-md-6">
							<label for="productDesc">Product Description</label>
							<input type="text" class="form-control" id="productDesc" name="productDesc" value="<?php echo $row['productDesc'];?>" placeholder="Product Description"> 
						</div>
						<div class="form-group col-md-6">
							<label for="quantity">Quantity</label>
							<input type="text" class="form-control" id="quantity" name="quantity" value="<?php echo $row['quantity'];?>"placeholder="Quantity" required> 
						</div> 
						
					  </div>
					   <div class="form-row">
						<div class="form-group col-md-6">
							<label for="cost">Cost</label>
							<div class="input-group mb-3">
							  <div class="input-group-prepend">
								<span class="input-group-text">₱</span>
							  </div>
							  <input type="text" class="form-control" placeholder="Cost" id="cost" value="<?php echo $row['cost'];?>" name="cost" required>
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
							  <input type="text" class="form-control" id="srp" placeholder="SRP" value="<?php echo $row['srp'];?>" name="srp" required>
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
					   <div class="form-row"><div class="col" style="text-align:right">
						<input value="Save Changes" name="submit" class="btn btn-accent" type="submit">
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
                    <h6 class="m-0">Upload New Image</h6>
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
                </div></form>
        </main>
      </div>
    </div>
    <?php include "scripts.inc"; ?>
	</div>

  </body>
</html>