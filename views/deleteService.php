<?php
require('../db.php');

$serviceId = $_POST['serviceId'];
$username = $_POST['user'];

$sql = "SELECT branchId FROM users where username='$username'";
	   
	   if ($result = mysqli_query($con,$sql)){
		  while ($obj = mysqli_fetch_object($result)){
			 $branchId = $obj->branchId;
		  }
		  mysqli_free_result($result);
	   }
	 

$sql = "DELETE from services where serviceId =$serviceId and branchId=$branchId";
	if ($result = mysqli_query($con,$sql)){

		 echo 'success';
	 }
	 else{
		 echo 'fail';
	 }

$sql = "DELETE from service_details where serviceId =$serviceId and branchId=$branchId";
	if ($result = mysqli_query($con,$sql)){

		 echo 'success';
	 }
	 else{
		 echo 'fail';
	 }
								
	mysqli_close($con);
?>