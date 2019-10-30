<?php
require('../db.php');

$productId = $_POST['productId'];
$username = $_POST['user'];

$sql = "SELECT branchId FROM users where username='$username'";
	   
	   if ($result = mysqli_query($con,$sql)){
		  while ($obj = mysqli_fetch_object($result)){
			 $branchId = $obj->branchId;
		  }
		  mysqli_free_result($result);
	   }
	 

$sql = "DELETE from products where productId =$productId and branchId=$branchId";
	if ($result = mysqli_query($con,$sql)){

		 echo 'success';
	 }
	 else{
		 echo 'fail';
	 }

								
	mysqli_close($con);
?>