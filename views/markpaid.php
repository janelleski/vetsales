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
	 

$sql = "UPDATE transactions SET status = 1, totalPaid = totalAmount, totalChange = 0 where transactionId = $transactionId and branchId=$branchId";

 mysqli_query($con,$sql);
 

$logDetails = "Transaction ".$transactionId." was marked as paid by ".$username." on ";
$sql = "INSERT INTO transactionlog (transactionId,tLogDate,branchId,tLogdetails) VALUES ($transactionId ,sysdate(),$branchId,CONCAT('$logDetails',sysdate()))";
mysqli_query($con,$sql);
			

				
mysqli_close($con);
?>