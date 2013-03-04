<?php 

require_once('db_queries.php');
require('db_config.php');


//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////


//Send default data for testing if db connection is dead
$action_type = $_POST['actionType'];

if($action_type == "init") {
	
	if (mysqli_connect_errno($db)) {
		console.log(" ** database connect error using default data ** ");
		echo json_encode(array('status' => 'database_error_init', 'series_data' => 'no data available'));
	
	} else {
		
		$data_type = $_POST['dataType'];
		
		if($data_type == "temp") {
			
			$data = getInitTemp($db);
			
			echo json_encode(array('status' => 'ok', 'series_data' => $data));
			
		}
		
		else if($data_type == "flow") {
			
			$data = getInitFlow($db);
			
			echo json_encode(array('status' => 'ok', 'series_data' => $data));
		}
		
		else if($data_type == "levl") {
		
			$data = getInitLevl($db);
		
			echo json_encode(array('status' => 'ok', 'series_data' => $data));
		}
		
		else{
			
			echo json_encode(array('status' => 'bad', 'series_data' => "NOT SET"));
			
		}
	}
}

else if($action_type == "update") {
	
	if (mysqli_connect_errno($db)) {
		console.log(" ** database connect error using default data ** ");
		echo json_encode(array('status' => 'database_error_update', 'latest_series_data' => 'no data available'));
	
	}
	
	else {
		
		$data_type = $_POST['dataType'];
		
		if($data_type == "temp") {
				
			$data = getLastTemp($db);
			echo $data;	
				
		}
		
		else if($data_type == "flow") {
		
			$data = getLastFlow($db);
			echo $data;
		
		}
		
		else if($data_type == "levl") {
		
			$data = getLastLevl($db);
			echo $data;
		
		}
		
		else{
				
			echo json_encode(array('status' => 'bad', 'latest_series_data' => "NOT SET"));
				
		}
		
		
	}
	
}

?>