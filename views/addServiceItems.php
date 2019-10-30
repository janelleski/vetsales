<?php
require('../db.php');

$transactionId = $_POST['transactionId'];
$serviceId = $_POST['serviceId'];
$totalAmount = $_POST['totalAmount'];
$totalExpense = $_POST['totalExpense'];
$username = $_POST['user'];
$branchId;

$sql = "SELECT branchId FROM users where username='$username'";
	   
	   if ($result = mysqli_query($con,$sql)){
		  while ($obj = mysqli_fetch_object($result)){
			 $branchId = $obj->branchId;
		  }
		  mysqli_free_result($result);
	   }
	 

$sql = "INSERT INTO services_rendered (transactionId, serviceId,dateAdded,totalAmount,totalExpense, branchId,status) VALUES 
($transactionId,$serviceId,sysdate(),$totalAmount,$totalExpense,$branchId,1)";

mysqli_query($con,$sql); 


$renderId = mysqli_insert_id($con);
$arr['renderId'] =$renderId; 
$arr['serviceId'] =$serviceId; 
			
echo json_encode($arr);
				
mysqli_close($con);
?>