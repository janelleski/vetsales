<?php
require('../db.php');

$searchProduct = $_POST['product'];
$username = $_POST['user'];

$sql = "SELECT branchId FROM users where username='$username'";
	   
	   if ($result = mysqli_query($con,$sql)){
		  while ($obj = mysqli_fetch_object($result)){
			 $branchId = $obj->branchId;
		  }
		  mysqli_free_result($result);
	   }
	 

$sql = "SELECT * FROM products where productTitle like '%$searchProduct%' and branchId=$branchId";
	
$raw_results = mysqli_query($con,$sql);
				echo "<table class='table mb-0' id='resultTbl'>
						 <thead class='bg-light'>
							<tr>
								<td>#</td>
								<td>Product Name</td>
								<td></td>
								<td>Stocks</td>
								<td>Quantity</td>
								<td>SRP</td>
								<td></td>
							</tr>
						 </thead>
						 <tbody id='tbodyid'>";

				while($results = mysqli_fetch_assoc($raw_results)){
									$productTitle = $results['productTitle'];
					echo "<tr>";
					echo "<td>" . $results['productId'] . " </td>";
					echo "<td>" . $results['productTitle'] . " </td>";
					if($results['image']!=null){
						echo "<td><img src='getimage.php?id=".$results['productId']."' height='25' /></td>";
					}
					else{
						echo "<td> </td>";
					}
					echo "<td>" . $results['quantity'] . " </td>";
					echo "<td>
         <div class='number' style='text-align:center;'>
	<button type='button' class='btn btn-white' style='width:15px;text-align:center;' onclick='minusQuantity(". $results['productId'] .");'> <i class='material-icons'>remove</i></button>
	<input id='".$results['productId']."' type='text' value='0' style='width:25px; border:0px;'/>
	<button type='button' class='btn btn-white' style='width:15px;text-align:center;' onclick='addQuantity(". $results['productId'] .",". $results['quantity'] .");'> <i class='material-icons'>add</i></button>
</div></td>"; 
					echo "<td>" . $results['srp'] . " </td>";
					echo "<td><button type='button' class='btn btn-success btn-number' onclick='addItem(". $results['productId'] .",". $results['srp'] .");'><i class='material-icons'>add</i>
								</button></td>";
										echo "</tr>";
								}
								
					echo '</tbody>
						 </table>';
								
	mysqli_close($con);
?>