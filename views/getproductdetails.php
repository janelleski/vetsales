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
	 

$sql = "SELECT productTitle,srp,discount,IF(image is not null,'1','0') as image FROM products where productId ='$productId%' and branchId=$branchId";
	if ($result = mysqli_query($con,$sql)){
		  while ($obj = mysqli_fetch_object($result)){
			$arr['title'] = $obj->productTitle;
			$arr['srp'] = $obj->srp;
			$arr['discount'] = $obj->discount;
			$arr['image'] = $obj->image;
		  }

		 echo json_encode($arr);
	   }

								
	mysqli_close($con);
?>