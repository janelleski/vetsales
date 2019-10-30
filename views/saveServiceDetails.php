<?php
require('../db.php');

$serviceId = $_POST['serviceId'];
$detailName = $_POST['detailName'];
$username = $_POST['user'];
$branchId;

$sql = "SELECT branchId FROM users where username='$username'";
	   
	   if ($result = mysqli_query($con,$sql)){
		  while ($obj = mysqli_fetch_object($result)){
			 $branchId = $obj->branchId;
		  }
		  mysqli_free_result($result);
	   }
	 
$detail = addSlashes($detailName);
$sql = "INSERT INTO service_details (serviceId,fieldName,branchId) VALUES 
($serviceId,'$detail',$branchId)";

mysqli_query($con,$sql); 

$arr['success'] = 'success';
			

echo json_encode($arr);
				
mysqli_close($con);
?>