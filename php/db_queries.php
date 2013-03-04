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
	$query_tme = mysqli_query($db, "SELECT * FROM (SELECT flowid, tstamp, ltrspm FROM flow ORDER BY flowid DESC LIMIT 10) holder ORDER BY flowid ASC;");
	//print_r($query_tme);

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

function getInitLevl($db){	
	$query_tme = mysqli_query($db, "SELECT * FROM (SELECT levlid, tstamp, levlcm FROM levl ORDER BY levlid DESC LIMIT 10) holder ORDER BY levlid ASC;");
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
	$query_tme = mysqli_query($db, "SELECT flowid, tstamp, ltrspm FROM flow ORDER BY flowid DESC LIMIT 1");

	while($item = mysqli_fetch_assoc($query_tme)) {

		$int_index = intval($item['flowid']);
		$xvalue  = floatval($item['tstamp']);
		$yvalue  = floatval($item['ltrspm']);

	}

	return $return_arr = json_encode(array('status' => 'ok', 'index' => $int_index, 'xvalue' => $xvalue, 'yvalue' => $yvalue));
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

?>