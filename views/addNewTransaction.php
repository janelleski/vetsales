<?php
require('../db.php');

$totalPaid = $_POST['totalPaid'];
$totalAmount = $_POST['totalAmount'];
$change = $_POST['change'];
$username = $_POST['user'];
$customerName = $_POST['customerName'];
$customerAddr = $_POST['customerAddr'];
$customerContact = $_POST['customerContact'];
$discount = $_POST['discount'];
$date = $_POST['date'];


$branchId;

if($change<0){
	$status = 0;
}else{
	$status=1;
}
$sql = "SELECT branchId FROM users where username='$username'";
	   
	   if ($result = mysqli_query($con,$sql)){
		  while ($obj = mysqli_fetch_object($result)){
			 $branchId = $obj->branchId;
		  }
		  mysqli_free_result($result);
	   }
if($discount==null || empty($discount))
{
	$discount="null";
}

if($date==null || empty($date)){
	$sql = "INSERT INTO transactions (transactionDate,transactionOwner,customerName,customerAddr,customerContact,totalAmount,totalPaid,totalChange,status,branchId,discount) VALUES (sysdate(),'$username','$customerName',
'$customerAddr','$customerContact', $totalAmount, $totalPaid, $change, $status,$branchId,$discount)";
}else{
	$timestamp = date('Y-m-d H:i:s', strtotime($date));  
	$sql = "INSERT INTO transactions (transactionDate,transactionOwner,customerName,customerAddr,customerContact,totalAmount,totalPaid,totalChange,status,branchId,discount) VALUES ('$timestamp','$username','$customerName',
'$customerAddr','$customerContact', $totalAmount, $totalPaid, $change, $status,$branchId,$discount)";
}



mysqli_query($con,$sql); 

$transactionId = mysqli_insert_id($con);
$arr['transactionId'] =$transactionId; 

$sql = "SELECT transactionDate FROM transactions where branchId=$branchId and transactionId=$transactionId";
	   
	   if ($result = mysqli_query($con,$sql)){
		  while ($obj = mysqli_fetch_object($result)){
			 $addedDate = $obj->transactionDate;
		  }
		  mysqli_free_result($result);
	   }
$logDetails = "Transaction ".$transactionId." was added by ".$username." on ".$addedDate." ";
$sql = "INSERT INTO transactionlog (transactionId,tLogDate,branchId,tLogdetails) VALUES ($transactionId ,sysdate(),$branchId,'$logDetails')";
mysqli_query($con,$sql);
			
echo json_encode($arr);
				
mysqli_close($con);
?>