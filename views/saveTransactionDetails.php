<?php
require('../db.php');

$transactionId = $_POST['transactionId'];
$customerName = $_POST['customerName'];
$customerAddr = $_POST['customerAddr'];
$customerContact = $_POST['customerContact'];
$username = $_POST['user'];
$branchId;

$sql = "SELECT branchId FROM users where username='$username'";
	   
	   if ($result = mysqli_query($con,$sql)){
		  while ($obj = mysqli_fetch_object($result)){
			 $branchId = $obj->branchId;
		  }
		  mysqli_free_result($result);
	   }
	 
$sql = "Update transactions set customerName='$customerName', customerAddr='$customerAddr',customerContact='$customerContact'
where transactionId = $transactionId and branchId=$branchId";

mysqli_query($con,$sql); 

$arr['success'] = 'success';
			

echo json_encode($arr);
				
mysqli_close($con);
?>