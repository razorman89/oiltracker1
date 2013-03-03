<?php 

require_once('db_queries.php');
require('db_config.php');


//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////


//Send default data for testing if db connection is dead
if (mysqli_connect_errno($db))
{
	echo json_encode(array('name' => 'Temperature', 'data' => array(8, 7, 4)));
	

} else {
	$data_type = $_POST['data'];
	
	if($data_type == "temp") {
		
		$data = getTemp($db);
		
		echo json_encode(array('status' => 'ok', 'series_data' => $data));
	}
}

?>