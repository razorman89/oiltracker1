<?php

function getInitTemp($db){
	$query_tme = mysqli_query($db, "SELECT * FROM (SELECT tempid, tstamp, tempdc FROM temp ORDER BY tempid DESC LIMIT 10) holder ORDER BY tempid ASC;");
	//print_r($query_tme);

	$return_arr = array();

	while($item = mysqli_fetch_assoc($query_tme)) {


		$t = array();

		$float_dat = floatval($item['tstamp']);
		$float_val = floatval($item['tempdc']);

		$t[] = $float_dat;
		$t[] = $float_val;

		$return_arr[] = $t;
	}

	return $return_arr;
}

function getInitFlow($db){
	$query_tme = mysqli_query($db, "SELECT * FROM (SELECT flowid, tstamp, ltrspm, costpm FROM flow ORDER BY flowid DESC LIMIT 10) holder ORDER BY flowid ASC;");

	$return_arr_ltrs = array();
	$return_arr_cost = array();

	while($item = mysqli_fetch_assoc($query_tme)) {


		$ret_ltrs = array();
		$ret_cost = array();

		$float_dat = floatval($item['tstamp']);
		$float_ltr = floatval($item['ltrspm']);
		$float_cst = floatval($item['costpm']);

		$ret_ltrs[] = $float_dat;
		$ret_ltrs[] = $float_ltr;
		
		$ret_cost[] = $float_dat;
		$ret_cost[] = $float_cst;

		$return_arr_ltrs[] = $ret_ltrs;
		$return_arr_cost[] = $ret_cost;
	}
	
	return json_encode(array('status' => 'ok', 'series_ltrs_data' => $return_arr_ltrs, 'series_cost_data' => $return_arr_cost));

	//return $return_arr;
}

function getInitLevl($db){	
	$query_tme = mysqli_query($db, "SELECT * FROM (SELECT levlid, tstamp, levlcm FROM levl ORDER BY levlid DESC LIMIT 1) holder ORDER BY levlid ASC;");
	//print_r($query_tme);
	
	$return_arr = array();
	
	while($item = mysqli_fetch_assoc($query_tme)) {
	
	
		$t = array();
	
		$float_dat = floatval($item['tstamp']);
		$float_val = floatval($item['levlcm']);
	
		$t[] = $float_dat;
		$t[] = $float_val;
	
		$return_arr[] = $t;
	}
	
	return $return_arr;
	
}

function getLastTemp($db){
	$query_tme = mysqli_query($db, "SELECT tempid, tstamp, tempdc FROM temp ORDER BY tempid DESC LIMIT 1");
	
	while($item = mysqli_fetch_assoc($query_tme)) {
		
		$int_index = intval($item['tempid']);
		$xvalue  = floatval($item['tstamp']);
		$yvalue  = floatval($item['tempdc']);
		
	}
	
	return $return_arr = json_encode(array('status' => 'ok', 'index' => $int_index, 'xvalue' => $xvalue, 'yvalue' => $yvalue));	
}

function getLastFlow($db){
	$query_tme = mysqli_query($db, "SELECT flowid, tstamp, ltrspm, costpm FROM flow ORDER BY flowid DESC LIMIT 1");

	while($item = mysqli_fetch_assoc($query_tme)) {

		$int_index = intval($item['flowid']);
		$xvalue  = floatval($item['tstamp']);
		$yvalue  = floatval($item['ltrspm']);
		$costvalue  = floatval($item['costpm']);

	}

	return $return_arr = json_encode(array('status' => 'ok', 'index' => $int_index, 'xvalue' => $xvalue, 'yvalue' => $yvalue, 'costvalue' => $costvalue));
}

function getLastLevl($db){
	$query_tme = mysqli_query($db, "SELECT levlid, tstamp, levlcm FROM levl ORDER BY levlid DESC LIMIT 1");

	while($item = mysqli_fetch_assoc($query_tme)) {

		$int_index = intval($item['levlid']);
		$xvalue  = floatval($item['tstamp']);
		$yvalue  = floatval($item['levlcm']);

	}

	return $return_arr = json_encode(array('status' => 'ok', 'index' => $int_index, 'xvalue' => $xvalue, 'yvalue' => $yvalue));
}

function getTempHistory($db){
	
	$query_tme = mysqli_query($db, "SELECT tstamp, tempdc FROM temp ORDER BY tempid ASC");
	
	$return_arr = array();
	
	while($item = mysqli_fetch_assoc($query_tme)) {
	
	
		$t = array();
	
		$float_dat = floatval($item['tstamp']);
		$float_val = floatval($item['tempdc']);
	
		$t[] = $float_dat;
		$t[] = $float_val;
	
		$return_arr[] = $t;
	}
	
	return $return_arr;
	
}

function getFlowHistory($db){

	$query_tme = mysqli_query($db, "SELECT tstamp, ltrspm FROM flow ORDER BY flowid ASC");

	$return_arr = array();

	while($item = mysqli_fetch_assoc($query_tme)) {


		$t = array();

		$float_dat = floatval($item['tstamp']);
		$float_val = floatval($item['ltrspm']);

		$t[] = $float_dat;
		$t[] = $float_val;

		$return_arr[] = $t;
	}

	return $return_arr;

}

function getlevlHistory($db){

	$query_tme = mysqli_query($db, "SELECT tstamp, levlcm FROM levl ORDER BY levlid ASC");

	$return_arr = array();

	while($item = mysqli_fetch_assoc($query_tme)) {


		$t = array();

		$float_dat = floatval($item['tstamp']);
		$float_val = floatval($item['levlcm']);

		$t[] = $float_dat;
		$t[] = $float_val;

		$return_arr[] = $t;
	}

	return $return_arr;

}

// function populate_date_fields($db) {
	
// 	$options = '';
// 	$query_tme = mysqli_query($db, "SELECT tempid, tstamp FROM temp ORDER BY tempid ASC");
	
// 	while($row = mysqli_fetch_assoc($query_tme)) {
// 		$mili = $row['tstamp'];
// 		$seconds = $mili / 1000;
// 		$options .= "<option>" . date('D-M-y H:i', $seconds) . "</option>";
// 	}
	
// 	$menu="<form id = 'temp_start_date' name = 'start_date' method = 'post' action=''>
//     <select name = 'filter' id = 'filter'>" . $options . "</select>";
	
// 	echo $menu;
// }


?>