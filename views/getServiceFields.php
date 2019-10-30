<?php
require('../db.php');

$serviceId = $_POST['serviceId'];
$username = $_POST['user'];
$_SESSION['serviceId'] = $serviceId;

$sql = "SELECT branchId FROM users where username='$username'";
	   
	   if ($result = mysqli_query($con,$sql)){
		  while ($obj = mysqli_fetch_object($result)){
			 $branchId = $obj->branchId;
		  }
		  mysqli_free_result($result);
	   }
	 

$sql = "SELECT fieldName,serviceDetailId from service_details where serviceId = $serviceId";
	
$raw_results = mysqli_query($con,$sql);
	$ctr=100;			
	while($results = mysqli_fetch_assoc($raw_results)){
			$fieldName = $results['fieldName'];
			$detailId = $results['serviceDetailId'];
			echo "<div class='form-row'>";
			echo "<label id=".$detailId.">".$fieldName."</label>";
			echo "<input type='text' class='form-control' id=".$ctr." name='".$detailId."' />";	
			echo "</div>";
			
			$ctr++;
	}
	

$sql = "SELECT serviceId,serviceName,serviceDesc,serviceCost,((serviceCommission/100) * serviceCost) as commission  from services where serviceId = $serviceId";								
		if ($result = mysqli_query($con,$sql)){
		  while ($obj = mysqli_fetch_object($result)){
			 $serviceName = $obj->serviceName;
			 $serviceDesc = $obj->serviceDesc;
			 $serviceCost = $obj->serviceCost;
			 $serviceId = $obj->serviceId;
			 $commission =sprintf("%.2f",$obj->commission);
			 echo "<div class='form-row'>";
			 echo "<label>Service Cost</label>";
			 echo "<input type='text' class='form-control' id='serviceCost' value='".$serviceCost."' disabled='true'/>";
			 echo "<input type='hidden' class='form-control' id='serviceName' value='".$serviceName."'/>";		
			 echo "<input type='hidden' class='form-control' id='serviceId' value='".$serviceId."'/>";				 
			 echo "</div>";
			 echo "<div class='form-row'>";
			 echo "<label>Service Commission</label>";
			 echo "<input type='text' class='form-control' id='serviceComm' value='".$commission."' disabled='true'/>";	
			 echo "</div><br/>";
			 echo "<div style='text-align:right;'><button class='btn btn-accent' type='button' data-dismiss='modal' onclick='addService();'>Add</button></div>";
		  }
		  mysqli_free_result($result);
		 
	   }			
								
	mysqli_close($con);
?>