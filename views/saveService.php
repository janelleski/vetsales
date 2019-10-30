<?php
require('../db.php');

$serviceName = $_POST['serviceName'];
$serviceDesc = $_POST['serviceDesc'];
$serviceCost = $_POST['serviceCost'];
$username = $_POST['user'];
$serviceComm = $_POST['serviceComm'];
$branchId;


$sql = "SELECT branchId FROM users where username='$username'";
	   
	   if ($result = mysqli_query($con,$sql)){
		  while ($obj = mysqli_fetch_object($result)){
			 $branchId = $obj->branchId;
		  }
		  mysqli_free_result($result);
	   }

if($serviceComm==null || empty($serviceComm))
{
	$serviceComm="null";
}

if($serviceDesc!=null || !empty($serviceDesc)){
	$serviceDesc = addSlashes($serviceDesc);
}
$sql = "INSERT INTO services (dateAdded,serviceName,serviceDesc,addedBy,serviceCost,serviceCommission,branchId,status) VALUES 
(sysdate(),'$serviceName','$serviceDesc','$username',$serviceCost,$serviceComm,$branchId,1)";


mysqli_query($con,$sql); 

$serviceId = mysqli_insert_id($con);
$arr['serviceId'] =$serviceId; 


echo json_encode($arr);
				
mysqli_close($con);
?>