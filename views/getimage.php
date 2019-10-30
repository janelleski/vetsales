<?php
require('../db.php');
include("../auth.php"); 
	
  $id = $_GET['id'];
  // do some validation here to ensure id is safe

  $sql = "SELECT image FROM products WHERE productId=$id";
  $result = mysqli_query($con,$sql);
  $row = mysqli_fetch_assoc($result);
  header("Content-type: image/jpeg");
  echo $row['image'];
?>