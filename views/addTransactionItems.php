<?php
require('../db.php');

$transactionId = $_POST['transactionId'];
$productId = $_POST['productId'];
$quantity = $_POST['quantity'];
$totalAmount = $_POST['totalAmount'];
$username = $_POST['user'];
$branchId;

$sql = "SELECT branchId FROM users where username='$username'";
	   
	   if ($result = mysqli_query($con,$sql)){
		  while ($obj = mysqli_fetch_object($result)){
			 $branchId = $obj->branchId;
		  }
		  mysqli_free_result($result);
	   }
	 

$sql = "INSERT INTO transactionItems (transactionId, productId,quantity,totalAmount,status, branchId) VALUES 
($transactionId,$productId,$quantity,$totalAmount,0,$branchId)";

mysqli_query($con,$sql); 

$sql = "UPDATE products SET quantity = (quantity- $quantity) WHERE productId = $productId AND branchId = $branchId";

mysqli_query($con,$sql); 

$arr['success'] = 'success';
			

echo json_encode($arr);
				
mysqli_close($con);
?>