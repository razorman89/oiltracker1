<?php

require_once('db_queries.php');
require('db_config.php');


$action_type = $_POST['actionType'];

if($action_type == "date_list") {
	
	if (mysqli_connect_errno($db)) {
		
		console.log(" ** database connect error, using default data ** ");
		echo json_encode(array('status' => 'database_error_elements', 'series_data' => 'no data available'));
	
	} 
	
	else {
	
		$data_type = $_POST['dataType'];
	
		if($data_type == "temp_dates") {
			
			$list = populate_date_fields($db);
			
			echo $list;
			
		}
		
		else {
			
			echo json_encode(array('status' => 'bad', 'list_html' => $list));
			
		}
	}
}

?>