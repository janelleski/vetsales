<?php

require('../db.php');

$username = $_POST['user'];

$sql = "SELECT branchId FROM users where username='$username'";
	   
	   if ($result = mysqli_query($con,$sql)){
		  while ($obj = mysqli_fetch_object($result)){
			 $branchId = $obj->branchId;
		  }
		  mysqli_free_result($result);
	   }
	 

// initilize all variable
	$params = $columns = $totalRecords = $data = array();

	$params = $_REQUEST;

	//define index of column
	$columns = array( 
		0 =>'productId',
		1 =>'productTitle', 
		2 => 'productImage',
		3 => 'categoryName',
		4 => 'quantity',
		5 => 'cost',
		6 => 'srp',
		7 => 'actions'
	);

	$where = $sqlTot = $sqlRec = "";
	// check search value exist
	if( !empty($params['search']['value']) ) {   
		$where .=" AND ";
		$where .=" ( p.productTitle LIKE '%".$params['search']['value']."%' ";    
		$where .=" OR c.categoryName LIKE '%".$params['search']['value']."%' )";
	}
//'<input type=\'button\' value=\'Edit\' class=\'btn btn-success\' onclick=editProduct(',p.productId,')
	// getting total number records without any search
	$sql = "SELECT p.productId,p.productTitle,IF(p.image is not null,CONCAT('<img src=getimage.php?id=',p.productId,' height=\'25\' />'),'') as productImage, 
	c.categoryName,p.quantity,p.cost,p.srp, CONCAT('<a class=\'link\' href=# onclick=editProduct(',p.productId,')><i class=\'material-icons\'>create</i></a>',
	'<a class=\'link\' href=# onclick=confirm(',p.productId,'); style=\'margin-left: 10px;\'/><i class=\'material-icons icon-red\'>clear</i></a>'
	) as actions FROM products p left join categories c on p.category = c.categoryId where p.branchId=$branchId";
	$sqlTot .= $sql;
	$sqlRec .= $sql;
	//concatenate search sql if value exist
	if(isset($where) && $where != '') {

		$sqlTot .= $where;
		$sqlRec .= $where;
	}


 	$sqlRec .=  " ORDER BY ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";

	$queryTot = mysqli_query($con, $sqlTot) or die("database error:". mysqli_error($con));


	$totalRecords = mysqli_num_rows($queryTot);

	$queryRecords = mysqli_query($con, $sqlRec) or die("error to fetch employees data");

	//iterate on results row and create new index array of data
	while( $row = mysqli_fetch_row($queryRecords) ) { 
		$data[] = $row;
	}	
/* 	for each($data as $row){
		$row['editButtons'] ='<button type=\'button\' class=\'btn btn-accent\'>View</button>';
	} */
	$json_data = array(
			"draw"            => intval( $params['draw'] ),   
			"recordsTotal"    => intval( $totalRecords ),  
			"recordsFiltered" => intval($totalRecords),
			"data"            => $data   // total data array
			);
echo json_encode($json_data); 

?>