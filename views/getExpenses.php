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
		0 =>'expenseId',
		1 =>'expenseDate', 
		2 => 'expenseName',
		3 => 'expenseCost'
	);

	$where = $sqlTot = $sqlRec = "";

	// check search value exist
	if( !empty($params['search']['value']) ) {   
		$where .=" AND ";
		$where .=" ( expenseId LIKE '".$params['search']['value']."%' ";    
		$where .=" OR expenseName LIKE '".$params['search']['value']."%' )";
	}
	
	// getting total number records without any search
	$sql = "SELECT expenseId,date_format(expenseDate,'%M %d, %Y') as expenseDate,expenseName,expenseCost,
	CONCAT('<a class=\'link\' href=#><i class=\'material-icons\'>remove_red_eye</i> ','<a class=\'link\' href=# onclick=checkToDelete(',expenseId,')><i class=\'material-icons icon-red\'>clear</i></a>') as editButtons FROM `expenses` where branchId=$branchId";
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