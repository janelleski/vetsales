<?php
require('../db.php');
include("../auth.php");
 
// Escape user inputs for security
$categoryName = mysqli_real_escape_string($con, $_REQUEST['categoryName']);
$categoryDesc = mysqli_real_escape_string($con, $_REQUEST['categoryDesc']);
$username = $_SESSION['username'];
$sql = "SELECT branchId FROM users where username='$username'";
   
   if ($result = mysqli_query($con,$sql)){
      while ($obj = mysqli_fetch_object($result)){
         $branchId = $obj->branchId;
      }
      mysqli_free_result($result);
   }
 
// Attempt insert query execution
$sql = "INSERT INTO categories (categoryName, categoryDesc, branchId,status) VALUES ('$categoryName', '$categoryDesc', $branchId,1)";
if(mysqli_query($con, $sql)){
  // $added = 'success';
	//Header("Location: add_category.php?added=".$added);
	echo "category added";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($con);
}
 
// Close connection
mysqli_close($con);
?>