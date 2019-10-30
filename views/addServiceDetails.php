<?php
require('../db.php');

$renderId = $_POST['renderId'];
$serviceId = $_POST['serviceId'];
$fieldArr = $_POST['fieldArr'];
$username = $_POST['user'];
$branchId;

$sql = "SELECT branchId FROM users where username='$username'";
	   
	   if ($result = mysqli_query($con,$sql)){
		  while ($obj = mysqli_fetch_object($result)){
			 $branchId = $obj->branchId;
		  }
		  mysqli_free_result($result);
	   }
	   
$serviceDetailId = trim($fieldArr[0]);
$fieldValue = $fieldArr[1];

/* $sql = "SELECT serviceDetailId FROM service_details where fieldName='$fieldName'";
	   
	   if ($result = mysqli_query($con,$sql)){
		  while ($obj = mysqli_fetch_object($result)){
			 $serviceDetailId = $obj->serviceDetailId;
		  }
		  mysqli_free_result($result);
	   } */

$sql = "INSERT INTO rendered_details (renderId,serviceDetailId,fieldValue,branchId,status) VALUES 
($renderId,$serviceDetailId,'$fieldValue',$branchId,1)";

mysqli_query($con,$sql); 

				
mysqli_close($con);
?>