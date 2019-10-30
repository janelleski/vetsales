<?php
require('../db.php');

$transactionId = $_POST['transactionId'];
$username = $_POST['user'];

$branchId;


$sql = "SELECT branchId FROM users where username='$username'";
	   
	   if ($result = mysqli_query($con,$sql)){
		  while ($obj = mysqli_fetch_object($result)){
			 $branchId = $obj->branchId;
		  }
		  mysqli_free_result($result);
	   }
	 
$sql = "SELECT productId, quantity FROM transactionitems where transactionId = $transactionId and branchId=$branchId";

 if ($result = mysqli_query($con,$sql)){
		  while ($obj = mysqli_fetch_object($result)){
			 $productId = $obj->productId;
			 $quantity = $obj->quantity;
			 
			 $sql2 = "UPDATE products SET quantity = (quantity + $quantity) where productId = $productId and branchId=$branchId";
			 mysqli_query($con,$sql2);
		  }
		  mysqli_free_result($result);
	   }
$sql = "SELECT renderId FROM services_rendered where transactionId = $transactionId and branchId=$branchId";

 if ($result = mysqli_query($con,$sql)){
		  while ($obj = mysqli_fetch_object($result)){
			 $renderId = $obj->renderId;
		  }
		  mysqli_free_result($result);
	   }
	   
 $sql = "DELETE FROM rendered_details where renderId = $renderId and branchId=$branchId";
mysqli_query($con,$sql);
 
 
 $sql = "DELETE FROM services_rendered where transactionId = $transactionId and branchId=$branchId";
mysqli_query($con,$sql);

 $sql = "DELETE FROM transactionitems where transactionId = $transactionId and branchId=$branchId";
mysqli_query($con,$sql);

$sql = "DELETE FROM transactions where transactionId = $transactionId and branchId=$branchId";

 mysqli_query($con,$sql);
 
 $sql = "DELETE FROM transactionlog where transactionId = $transactionId and branchId=$branchId";

 mysqli_query($con,$sql);


				
mysqli_close($con);
?>