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
	 

$sql = "SELECT status from transactions where transactionId = $transactionId and branchId=$branchId";

if ($result = mysqli_query($con,$sql)){
		  while ($obj = mysqli_fetch_object($result)){
			 $status = $obj->status;
		  }
		  mysqli_free_result($result);
	   }

echo $status;
				
mysqli_close($con);
?>