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
			echo $data;
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
		
			$data = getLatestLevl($db);
			echo $data;
		
		}
		
		else{
				
			echo json_encode(array('status' => 'bad', 'latest_series_data' => "NOT SET"));
				
		}
		
	}
	
}

else if($action_type == "history") {
	
	if (mysqli_connect_errno($db)) {
		console.log(" ** database connect error using default data ** ");
		echo json_encode(array('status' => 'database_error_update', 'latest_series_data' => 'no data available'));
	
	}
	
	else {
		
		$data_type = $_POST['dataType'];
		
		if($data_type == "temp") {
		
			$data = getTempHistory($db);
			
			echo json_encode(array('status' => 'ok', 'type' => 'temp_history', 'history_data' => $data));
		
		}
		
		else if($data_type == "flow") {
		
			$data = getFlowHistory($db);
			
			echo json_encode(array('status' => 'ok', 'type' => 'flow_history', 'history_data' => $data));
		
		}
		
		else if($data_type == "levl") {
		
			$data = getLevlHistory($db);
			
			echo json_encode(array('status' => 'ok', 'type' => 'levl_history', 'history_data' => $data));
		
		}
		
		else{
		
			echo json_encode(array('status' => 'bad', 'latest_series_data' => "NOT SET"));
		
		}
		
	}
	
}

else if($action_type == "var_init") {
	if (mysqli_connect_errno($db)) {
		console.log(" ** database connect error using default data ** ");
		echo json_encode(array('status' => 'database_error_update', 'latest_series_data' => 'no data available'));
	
	}
	
	else {
		
		$data_type = $_POST['dataType'];
		
		if($data_type == "levl") {
		
			$maxLitres = getMaxLitres($db);
				
			echo json_encode(array('status' => 'ok', 'maxLitres' => $maxLitres));
		}
	}
}

else if($action_type == "settings_read") {
	
	if (mysqli_connect_errno($db)) {
		console.log(" ** database connect error using default data ** ");
		echo json_encode(array('status' => 'database_error_update', 'latest_series_data' => 'no data available'));
	
	}
	
	else {
		
		$data_type = $_POST['dataType'];
		if($data_type == "read_settings") {
		
			$data = getSettings($db);
			echo $data;
		}
		
		else if ($data_type == "profiled_litres") {
			
			$maxLitres = getMaxLitres($db);
			echo json_encode(array('status' => 'ok', 'maxLitres' => $maxLitres));

		}
		
		else if ($data_type == "tank_stats") {
				
			$data = getTankStats($db);
			echo $data;
		
		}
		
	}	
	
}

else if($action_type == "settings_write") {

	if (mysqli_connect_errno($db)) {
		console.log(" ** database connect error using default data ** ");
		echo json_encode(array('status' => 'database_error_update', 'latest_series_data' => 'no data available'));

	}

	else {
		
		$data_type = $_POST['dataType'];
		if($data_type == "fuel_price") {
			
			$newPrice = $_POST['newData'];
			updateFuelSettings($db, $newPrice);
			
		}
		
		else if($data_type == "temp_read_interval") {
			
			$newInterval = $_POST['newData'];
			updateTempSettings($db, $newInterval);
			
		}
		
		else if($data_type == "profile_status") {
				
			$newStatus = $_POST['newData'];
			updateProfileSettings($db, $newStatus);
				
		}

	}

}

else if($action_type == "make_predictions") {
	
	if (mysqli_connect_errno($db)) {
		console.log(" ** database connect error using default data ** ");
		echo json_encode(array('status' => 'database_error_update', 'latest_series_data' => 'no data available'));
	
	}
	
	else {
	
		$data_type = $_POST['dataType'];
		if($data_type == "predictions") {
			$maxLitres = $_POST['litresMax'];
			$data = makePredictions($db, $maxLitres);
			echo $data;
		}
	}
}

?>