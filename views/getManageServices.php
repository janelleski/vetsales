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
		0 =>'serviceId',
		1 =>'serviceName', 
		2 => 'serviceCost',
		3 => 'serviceCommission',
		4 => 'actions'
	);

	$where = $sqlTot = $sqlRec = "";
	// check search value exist
	if( !empty($params['search']['value']) ) {   
		$where .=" AND ";
		$where .=" ( s.serviceName LIKE '".$params['search']['value']."%' ";    
	}
	// getting total number records without any search
	$sql = "SELECT s.serviceId,  s.serviceName, s.serviceCost,s.serviceCommission, CONCAT('<a class=\'link\' href=# onclick=editService(',s.serviceId,')><i class=\'material-icons\'>create</i></a>',
	'<a class=\'link\' href=# onclick=confirm(',s.serviceId,'); style=\'margin-left: 10px;\'/><i class=\'material-icons icon-red\'>clear</i></a>'
	) as actions FROM services s where s.branchId=$branchId";
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

	$json_data = array(
			"draw"            => intval( $params['draw'] ),   
			"recordsTotal"    => intval( $totalRecords ),  
			"recordsFiltered" => intval($totalRecords),
			"data"            => $data   // total data array
			);
echo json_encode($json_data); 

?>